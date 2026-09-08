<?php
namespace App\Controllers;

use App\Models\ParentStudentMapModel;
use App\Models\StudentModel;

class ParentDashboard extends BaseController
{
    private function getStudentContext()
    {
        $db = \Config\Database::connect();
        $parentId = session('parent_id');
        $orgId = session('org_id');

        $mapModel = new ParentStudentMapModel();
        $linked = $mapModel->where('parent_id', $parentId)->where('org_id', $orgId)->findAll();
        if (empty($linked)) {
            return ['students' => [], 'selected_student' => null];
        }

        $studentIds = array_column($linked, 'student_id');
        $studentModel = new StudentModel();
        $students = $studentModel->whereIn('id', $studentIds)->where('org_id', $orgId)->findAll();

        $selectedStudentId = session('selected_student_id');
        if (!$selectedStudentId || !in_array($selectedStudentId, $studentIds)) {
            $selectedStudentId = $studentIds[0];
            session()->set('selected_student_id', $selectedStudentId);
        }

        $selectedStudent = $studentModel->find($selectedStudentId);

        return [
            'students' => $students,
            'selected_student' => $selectedStudent,
            'selected_student_id' => $selectedStudentId
        ];
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $ctx = $this->getStudentContext();
        if (!$ctx['selected_student']) {
            return view('parent/dashboard', $ctx);
        }

        $studentId = $ctx['selected_student_id'];
        $orgId = session('org_id');

        // External Marks
        $ctx['marks'] = $db->table('exam_marks_external em')
            ->select('em.*, es.exam_date, sub.name as subject_name, e.name as exam_name')
            ->join('exam_schedules es', 'es.id = em.exam_schedule_id')
            ->join('subjects sub', 'sub.id = es.subject_id')
            ->join('exam_names e', 'e.id = es.exam_id')
            ->where('em.student_id', $studentId)
            ->get()->getResultArray();

        // Attendance
        $ctx['attendance'] = $db->table('attendance_records')
            ->select('COUNT(id) as total_sessions, SUM(CASE WHEN status="Present" THEN 1 ELSE 0 END) as present_count')
            ->where('student_id', $studentId)
            ->get()->getRowArray();

        // Notices
        $ctx['notices'] = $db->table('diary')
            ->where('org_id', $orgId)
            ->whereIn('target_audience', ['All', 'Students'])
            ->where('publish_date <=', date('Y-m-d'))
            ->orderBy('publish_date', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // Fee Dues
        $fee_row = $db->table('student_fee_ledger')
            ->select('SUM(amount_due - amount_paid) as balance, SUM(amount_due) as total_due, SUM(amount_paid) as total_paid')
            ->where('org_id', $orgId)
            ->where('student_id', $studentId)
            ->get()->getRowArray();

        $ctx['fee_dues'] = ($fee_row && $fee_row['balance']) ? (float)$fee_row['balance'] : 0;
        $ctx['fee_summary'] = $fee_row;

        // Recent Assignments
        $ctx['recent_assignments'] = $db->table('lms_assignments a')
            ->select('a.*, sub.name as subject_name')
            ->join('subjects sub', 'sub.id = a.subject_id', 'left')
            ->where('a.cohort_id', $ctx['selected_student']['cohort_id'])
            ->orderBy('a.due_date', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        return view('parent/dashboard', $ctx);
    }

    public function select_student($studentId)
    {
        $mapModel = new ParentStudentMapModel();
        $check = $mapModel->where('parent_id', session('parent_id'))->where('student_id', $studentId)->first();
        if ($check) {
            session()->set('selected_student_id', $studentId);
        }
        return redirect()->back();
    }

    public function attendance()
    {
        $db = \Config\Database::connect();
        $ctx = $this->getStudentContext();
        if (!$ctx['selected_student']) return view('parent/dashboard', $ctx);

        $studentId = $ctx['selected_student_id'];

        $records = $db->table('attendance_records ar')
            ->select('ar.*, ast.session_date, ast.period_id, sub.name as subject_name, sub.code as subject_code')
            ->join('attendance_sessions ast', 'ast.id = ar.session_id', 'left')
            ->join('subjects sub', 'sub.id = ast.subject_id', 'left')
            ->where('ar.student_id', $studentId)
            ->orderBy('ast.session_date', 'DESC')
            ->get()->getResultArray();

        $total = count($records);
        $present = count(array_filter($records, function($r) { return $r['status'] === 'Present'; }));
        $absent = count(array_filter($records, function($r) { return $r['status'] === 'Absent'; }));
        $pct = $total > 0 ? round(($present / $total) * 100, 1) : 0;

        $ctx['records'] = $records;
        $ctx['total'] = $total;
        $ctx['present'] = $present;
        $ctx['absent'] = $absent;
        $ctx['percentage'] = $pct;

        return view('parent/attendance', $ctx);
    }

    public function fees()
    {
        $db = \Config\Database::connect();
        $ctx = $this->getStudentContext();
        if (!$ctx['selected_student']) return view('parent/dashboard', $ctx);

        $studentId = $ctx['selected_student_id'];
        $orgId = session('org_id');

        $ctx['ledger'] = $db->table('student_fee_ledger fl')
            ->select('fl.*, COALESCE(ft.name, "Tuition & Academic Fees") as structure_name')
            ->join('fee_structures fs', 'fs.id = fl.fee_structure_id', 'left')
            ->join('fee_types ft', 'ft.id = fs.fee_type_id', 'left')
            ->where('fl.student_id', $studentId)
            ->where('fl.org_id', $orgId)
            ->get()->getResultArray();

        $ctx['payments'] = $db->table('fee_receipts')
            ->select('id, receipt_no as transaction_reference, mode as payment_method, amount as amount_paid, created_at')
            ->where('student_id', $studentId)
            ->orderBy('id', 'DESC')
            ->get()->getResultArray();

        return view('parent/fees', $ctx);
    }

    public function marks()
    {
        $db = \Config\Database::connect();
        $ctx = $this->getStudentContext();
        if (!$ctx['selected_student']) return view('parent/dashboard', $ctx);

        $studentId = $ctx['selected_student_id'];

        // Internal continuous assessments
        $ctx['internal_marks'] = $db->table('internal_marks im')
            ->select('im.*, mc.name as component_name, mc.max_marks, sub.name as subject_name, sub.code as subject_code')
            ->join('mark_components mc', 'mc.id = im.component_id', 'left')
            ->join('subjects sub', 'sub.id = mc.subject_id', 'left')
            ->where('im.student_id', $studentId)
            ->orderBy('sub.name', 'ASC')
            ->get()->getResultArray();

        // Semester external exams
        $ctx['external_marks'] = $db->table('exam_marks_external em')
            ->select('em.*, es.exam_date, sub.name as subject_name, e.name as exam_name')
            ->join('exam_schedules es', 'es.id = em.exam_schedule_id', 'left')
            ->join('subjects sub', 'sub.id = es.subject_id', 'left')
            ->join('exam_names e', 'e.id = es.exam_id', 'left')
            ->where('em.student_id', $studentId)
            ->get()->getResultArray();

        return view('parent/marks', $ctx);
    }

    public function assignments()
    {
        $db = \Config\Database::connect();
        $ctx = $this->getStudentContext();
        if (!$ctx['selected_student']) return view('parent/dashboard', $ctx);

        $studentId = $ctx['selected_student_id'];
        $cohortId = $ctx['selected_student']['cohort_id'];

        $ctx['assignments'] = $db->table('lms_assignments a')
            ->select('a.*, sub.name as subject_name, sub.code as subject_code, sub_mit.status as submission_status, sub_mit.marks_obtained as score, sub_mit.feedback')
            ->join('subjects sub', 'sub.id = a.subject_id', 'left')
            ->join('lms_assignment_submissions sub_mit', 'sub_mit.assignment_id = a.id AND sub_mit.student_id = ' . (int)$studentId, 'left')
            ->where('a.cohort_id', $cohortId)
            ->orderBy('a.due_date', 'DESC')
            ->get()->getResultArray();

        return view('parent/assignments', $ctx);
    }

    public function timetable()
    {
        $db = \Config\Database::connect();
        $ctx = $this->getStudentContext();
        if (!$ctx['selected_student']) return view('parent/dashboard', $ctx);

        $cohortId = $ctx['selected_student']['cohort_id'];

        $schedules = [];
        if (!empty($cohortId)) {
            $schedules = $db->table('timetable_entries te')
                ->select('te.*, tp.period_name as period_name, tp.start_time, tp.end_time, sub.name as subject_name, sub.code as subject_code, u.full_name as teacher_name')
                ->join('timetable_schedules ts', 'ts.id = te.schedule_id')
                ->join('timetable_periods tp', 'tp.id = te.period_id', 'left')
                ->join('subjects sub', 'sub.id = te.subject_id', 'left')
                ->join('org_users u', 'u.id = te.faculty_user_id', 'left')
                ->where('ts.cohort_id', $cohortId)
                ->orderBy('tp.start_time', 'ASC')
                ->get()->getResultArray();
        }

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $grid = [];
        foreach ($days as $day) {
            $grid[$day] = [];
        }
        foreach ($schedules as $s) {
            $grid[$s['day_of_week']][] = $s;
        }

        $ctx['timetable_grid'] = $grid;
        $ctx['days'] = $days;

        return view('parent/timetable', $ctx);
    }
}
