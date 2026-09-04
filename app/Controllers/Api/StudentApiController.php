<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\StudentBioModel;
use App\Models\PlacementDriveModel;
use App\Models\PlacementApplicationModel;

class StudentApiController extends BaseController
{
    protected function getStudentContext(): ?array
    {
        $payload = $this->request->api_user ?? null;
        if (!$payload || ($payload['user_type'] ?? '') !== 'student') return null;

        $db = \Config\Database::connect();
        $student = $db->table('students s')
            ->select('s.*, c.name as cohort_name, p.name as program_name, p.code as program_code, d.name as department_name')
            ->join('cohorts c', 'c.id = s.cohort_id', 'left')
            ->join('programs p', 'p.id = c.program_id', 'left')
            ->join('departments d', 'd.id = p.dept_id', 'left')
            ->where('s.id', $payload['student_id'])
            ->where('s.org_id', $payload['org_id'])
            ->get()->getRowArray();

        return $student;
    }

    public function dashboard()
    {
        $student = $this->getStudentContext();
        if (!$student) {
            return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Student record not found.']);
        }

        $db = \Config\Database::connect();
        $studentId = $student['id'];
        $orgId = $student['org_id'];

        // 1. Attendance stats
        $totalSessions = $db->table('attendance_records ar')
            ->join('attendance_sessions asess', 'asess.id = ar.session_id')
            ->where('ar.student_id', $studentId)
            ->where('ar.org_id', $orgId)
            ->countAllResults();

        $presentSessions = $db->table('attendance_records ar')
            ->join('attendance_sessions asess', 'asess.id = ar.session_id')
            ->where('ar.student_id', $studentId)
            ->where('ar.org_id', $orgId)
            ->where('ar.status', 'Present')
            ->countAllResults();

        $attendancePct = $totalSessions > 0 ? round(($presentSessions / $totalSessions) * 100, 1) : 100;

        // 2. Fee Dues
        $feeRow = $db->table('student_fee_ledger')
            ->selectSum('amount_due')
            ->selectSum('amount_paid')
            ->selectSum('balance')
            ->where('student_id', $studentId)
            ->where('org_id', $orgId)
            ->get()->getRowArray();

        // 3. Today's Classes
        $todayClasses = [];
        if (!empty($student['cohort_id'])) {
            $dayOfWeek = date('N'); // 1 (Mon) through 7 (Sun)
            $todayClasses = $db->table('timetable_entries te')
                ->select('te.*, tp.start_time, tp.end_time, s.name as subject_name, s.code as subject_code')
                ->join('timetable_schedules ts', 'ts.id = te.schedule_id')
                ->join('timetable_periods tp', 'tp.id = te.period_id', 'left')
                ->join('subjects s', 's.id = te.subject_id', 'left')
                ->where('ts.cohort_id', $student['cohort_id'])
                ->where('te.day_of_week', $dayOfWeek)
                ->orderBy('tp.start_time', 'ASC')
                ->get()->getResultArray();
        }

        // 4. Open Placements
        $openDrives = (new PlacementDriveModel())
            ->where('org_id', $orgId)
            ->where('status', 'Active')
            ->countAllResults();

        return $this->response->setJSON([
            'status' => 'success',
            'student' => [
                'id' => $student['id'],
                'roll_number' => $student['roll_number'],
                'name' => $student['first_name'] . ' ' . $student['last_name'],
                'program' => $student['program_name'],
                'cohort' => $student['cohort_name'],
            ],
            'metrics' => [
                'attendance_percentage' => $attendancePct,
                'total_sessions' => $totalSessions,
                'present_sessions' => $presentSessions,
                'fee_total' => (float)($feeRow['amount_due'] ?? 0),
                'fee_paid' => (float)($feeRow['amount_paid'] ?? 0),
                'fee_balance' => (float)($feeRow['balance'] ?? 0),
                'open_placement_drives' => $openDrives,
                'today_classes_count' => count($todayClasses)
            ],
            'today_schedule' => $todayClasses
        ]);
    }

    public function profile()
    {
        $student = $this->getStudentContext();
        if (!$student) return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Student not found']);

        $bio = (new StudentBioModel())->where('student_id', $student['id'])->first();

        return $this->response->setJSON([
            'status' => 'success',
            'student' => $student,
            'bio' => $bio
        ]);
    }

    public function attendance()
    {
        $student = $this->getStudentContext();
        if (!$student) return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Student not found']);

        $db = \Config\Database::connect();
        $logs = $db->table('attendance_records ar')
            ->select('ar.*, asess.session_date, sub.name as subject_name, sub.code as subject_code')
            ->join('attendance_sessions asess', 'asess.id = ar.session_id')
            ->join('subjects sub', 'sub.id = asess.subject_id', 'left')
            ->where('ar.student_id', $student['id'])
            ->where('ar.org_id', $student['org_id'])
            ->orderBy('asess.session_date', 'DESC')
            ->limit(50)
            ->get()->getResultArray();

        $total = count($logs);
        $present = count(array_filter($logs, fn($l) => $l['status'] === 'Present'));

        return $this->response->setJSON([
            'status' => 'success',
            'summary' => [
                'total_recorded' => $total,
                'present' => $present,
                'absent' => $total - $present,
                'percentage' => $total > 0 ? round(($present / $total) * 100, 1) : 100
            ],
            'records' => $logs
        ]);
    }

    public function timetable()
    {
        $student = $this->getStudentContext();
        if (!$student) return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Student not found']);

        $db = \Config\Database::connect();
        $entries = [];
        if (!empty($student['cohort_id'])) {
            $entries = $db->table('timetable_entries te')
                ->select('te.*, tp.start_time, tp.end_time, s.name as subject_name, s.code as subject_code')
                ->join('timetable_schedules ts', 'ts.id = te.schedule_id')
                ->join('timetable_periods tp', 'tp.id = te.period_id', 'left')
                ->join('subjects s', 's.id = te.subject_id', 'left')
                ->where('ts.cohort_id', $student['cohort_id'])
                ->orderBy('te.day_of_week', 'ASC')
                ->orderBy('tp.start_time', 'ASC')
                ->get()->getResultArray();
        }

        $byDay = [];
        foreach ($entries as $e) {
            $byDay[$e['day_of_week']][] = $e;
        }

        return $this->response->setJSON([
            'status' => 'success',
            'cohort_name' => $student['cohort_name'],
            'schedule' => $byDay
        ]);
    }

    public function marks()
    {
        $student = $this->getStudentContext();
        if (!$student) return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Student not found']);

        $db = \Config\Database::connect();
        $marks = $db->table('internal_marks im')
            ->select('im.*, s.name as subject_name, s.code as subject_code')
            ->join('subjects s', 's.id = im.subject_id', 'left')
            ->where('im.student_id', $student['id'])
            ->where('im.org_id', $student['org_id'])
            ->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'marks' => $marks
        ]);
    }

    public function fees()
    {
        $student = $this->getStudentContext();
        if (!$student) return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Student not found']);

        $db = \Config\Database::connect();
        $dues = $db->table('student_fee_ledger sfl')
            ->select('sfl.*, fs.name as structure_name')
            ->join('fee_structures fs', 'fs.id = sfl.fee_structure_id', 'left')
            ->where('sfl.student_id', $student['id'])
            ->where('sfl.org_id', $student['org_id'])
            ->get()->getResultArray();

        $receipts = $db->table('fee_receipts')
            ->where('student_id', $student['id'])
            ->where('org_id', $student['org_id'])
            ->orderBy('id', 'DESC')
            ->get()->getResultArray();

        $totalDue = array_sum(array_column($dues, 'amount_due'));
        $totalPaid = array_sum(array_column($dues, 'amount_paid'));
        $balance = $totalDue - $totalPaid;

        return $this->response->setJSON([
            'status' => 'success',
            'summary' => [
                'total_due' => (float)$totalDue,
                'total_paid' => (float)$totalPaid,
                'balance' => (float)$balance
            ],
            'ledger_dues' => $dues,
            'receipts' => $receipts
        ]);
    }

    public function placements()
    {
        $student = $this->getStudentContext();
        if (!$student) return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Student not found']);

        $db = \Config\Database::connect();
        $drives = $db->table('placement_drives pd')
            ->select('pd.*, pc.company_name')
            ->join('placement_companies pc', 'pc.id = pd.company_id', 'left')
            ->where('pd.org_id', $student['org_id'])
            ->where('pd.status', 'Active')
            ->orderBy('pd.id', 'DESC')
            ->get()->getResultArray();

        $myApplications = (new PlacementApplicationModel())
            ->where('student_id', $student['id'])
            ->findAll();

        $appliedDriveIds = array_column($myApplications, 'drive_id');

        foreach ($drives as &$d) {
            $d['has_applied'] = in_array($d['id'], $appliedDriveIds);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'drives' => $drives,
            'my_applications' => $myApplications
        ]);
    }
}
