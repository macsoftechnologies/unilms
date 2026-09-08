<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ParentModel;
use App\Models\StudentModel;

class ParentApiController extends BaseController
{
    protected function getParentContext(): ?array
    {
        $payload = $this->request->api_user ?? null;
        if (!$payload || ($payload['user_type'] ?? '') !== 'parent') return null;

        $parent = (new ParentModel())->where('id', $payload['parent_id'])->first();
        return $parent;
    }

    protected function verifyParentWardAccess(int $parentId, int $studentId): bool
    {
        $db = \Config\Database::connect();
        $map = $db->table('parent_student_map')
            ->where('parent_id', $parentId)
            ->where('student_id', $studentId)
            ->get()->getRowArray();

        return !empty($map);
    }

    public function dashboard()
    {
        $parent = $this->getParentContext();
        if (!$parent) {
            return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Parent record not found.']);
        }

        $db = \Config\Database::connect();
        $parentId = $parent['id'];
        $orgId = $parent['org_id'];

        $wards = $db->table('parent_student_map psm')
            ->select('s.id, s.roll_number, s.first_name, s.last_name, s.cohort_id, psm.relationship, c.name as cohort_name, pr.name as program_name')
            ->join('students s', 's.id = psm.student_id')
            ->join('cohorts c', 'c.id = s.cohort_id', 'left')
            ->join('programs pr', 'pr.id = c.program_id', 'left')
            ->where('psm.parent_id', $parentId)
            ->get()->getResultArray();

        // Calculate aggregate stats for first child or all
        $wardSummaries = [];
        foreach ($wards as $w) {
            $sId = $w['id'];
            $totalSessions = $db->table('attendance_records')->where('student_id', $sId)->where('org_id', $orgId)->countAllResults();
            $presentSessions = $db->table('attendance_records')->where('student_id', $sId)->where('org_id', $orgId)->where('status', 'Present')->countAllResults();
            $attPct = $totalSessions > 0 ? round(($presentSessions / $totalSessions) * 100, 1) : 100;

            $feeRow = $db->table('student_fee_ledger')
                ->selectSum('balance')
                ->where('student_id', $sId)
                ->where('org_id', $orgId)
                ->get()->getRowArray();

            $wardSummaries[] = [
                'student' => $w,
                'attendance_pct' => $attPct,
                'fee_balance' => (float)($feeRow['balance'] ?? 0)
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'parent' => [
                'id' => $parent['id'],
                'name' => trim(($parent['first_name'] ?? '') . ' ' . ($parent['last_name'] ?? '')),
                'email' => $parent['email'],
                'phone' => $parent['phone']
            ],
            'total_wards' => count($wards),
            'wards' => $wardSummaries
        ]);
    }

    public function wards()
    {
        $parent = $this->getParentContext();
        if (!$parent) return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Parent not found']);

        $db = \Config\Database::connect();
        $wards = $db->table('parent_student_map psm')
            ->select('s.*, c.name as cohort_name, pr.name as program_name, psm.relationship')
            ->join('students s', 's.id = psm.student_id')
            ->join('cohorts c', 'c.id = s.cohort_id', 'left')
            ->join('programs pr', 'pr.id = c.program_id', 'left')
            ->where('psm.parent_id', $parent['id'])
            ->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'wards' => $wards
        ]);
    }

    public function ward_attendance($studentId)
    {
        $parent = $this->getParentContext();
        if (!$parent || !$this->verifyParentWardAccess($parent['id'], $studentId)) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Access denied to this student record.']);
        }

        $db = \Config\Database::connect();
        $student = (new StudentModel())->find($studentId);

        $logs = $db->table('attendance_records ar')
            ->select('ar.*, asess.session_date, sub.name as subject_name, sub.code as subject_code')
            ->join('attendance_sessions asess', 'asess.id = ar.session_id')
            ->join('subjects sub', 'sub.id = asess.subject_id', 'left')
            ->where('ar.student_id', $studentId)
            ->where('ar.org_id', $parent['org_id'])
            ->orderBy('asess.session_date', 'DESC')
            ->limit(50)
            ->get()->getResultArray();

        $total = count($logs);
        $present = count(array_filter($logs, fn($l) => $l['status'] === 'Present'));

        return $this->response->setJSON([
            'status' => 'success',
            'student_name' => $student['first_name'] . ' ' . $student['last_name'],
            'roll_number' => $student['roll_number'],
            'summary' => [
                'total_recorded' => $total,
                'present' => $present,
                'absent' => $total - $present,
                'percentage' => $total > 0 ? round(($present / $total) * 100, 1) : 100
            ],
            'records' => $logs
        ]);
    }

    public function ward_fees($studentId)
    {
        $parent = $this->getParentContext();
        if (!$parent || !$this->verifyParentWardAccess($parent['id'], $studentId)) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Access denied to this student record.']);
        }

        $db = \Config\Database::connect();
        $student = (new StudentModel())->find($studentId);

        $dues = $db->table('student_fee_ledger sfl')
            ->select('sfl.*, fs.name as structure_name')
            ->join('fee_structures fs', 'fs.id = sfl.fee_structure_id', 'left')
            ->where('sfl.student_id', $studentId)
            ->where('sfl.org_id', $parent['org_id'])
            ->get()->getResultArray();

        $receipts = $db->table('fee_receipts')
            ->where('student_id', $studentId)
            ->where('org_id', $parent['org_id'])
            ->orderBy('id', 'DESC')
            ->get()->getResultArray();

        $totalDue = array_sum(array_column($dues, 'amount_due'));
        $totalPaid = array_sum(array_column($dues, 'amount_paid'));
        $balance = $totalDue - $totalPaid;

        return $this->response->setJSON([
            'status' => 'success',
            'student_name' => $student['first_name'] . ' ' . $student['last_name'],
            'roll_number' => $student['roll_number'],
            'summary' => [
                'total_due' => (float)$totalDue,
                'total_paid' => (float)$totalPaid,
                'balance' => (float)$balance
            ],
            'ledger_dues' => $dues,
            'receipts' => $receipts
        ]);
    }

    public function ward_marks($studentId)
    {
        $parent = $this->getParentContext();
        if (!$parent || !$this->verifyParentWardAccess($parent['id'], $studentId)) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Access denied to this student record.']);
        }

        $db = \Config\Database::connect();
        $student = (new StudentModel())->find($studentId);

        $marks = $db->table('internal_marks im')
            ->select('im.*, s.name as subject_name, s.code as subject_code')
            ->join('subjects s', 's.id = im.subject_id', 'left')
            ->where('im.student_id', $studentId)
            ->where('im.org_id', $parent['org_id'])
            ->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'student_name' => $student['first_name'] . ' ' . $student['last_name'],
            'roll_number' => $student['roll_number'],
            'marks' => $marks
        ]);
    }

    public function ward_timetable($studentId)
    {
        $parent = $this->getParentContext();
        if (!$parent || !$this->verifyParentWardAccess($parent['id'], $studentId)) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Access denied to this student record.']);
        }

        $db = \Config\Database::connect();
        $student = (new StudentModel())->find($studentId);

        $entries = $db->table('timetable_entries te')
            ->select('te.*, tp.start_time, tp.end_time, s.name as subject_name, s.code as subject_code')
            ->join('timetable_schedules ts', 'ts.id = te.schedule_id')
            ->join('timetable_periods tp', 'tp.id = te.period_id', 'left')
            ->join('subjects s', 's.id = te.subject_id', 'left')
            ->where('ts.cohort_id', $student['cohort_id'])
            ->orderBy('te.day_of_week', 'ASC')
            ->orderBy('tp.start_time', 'ASC')
            ->get()->getResultArray();

        $byDay = [];
        foreach ($entries as $e) {
            $byDay[$e['day_of_week']][] = $e;
        }

        return $this->response->setJSON([
            'status' => 'success',
            'student_name' => $student['first_name'] . ' ' . $student['last_name'],
            'schedule' => $byDay
        ]);
    }
}
