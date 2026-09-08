<?php

namespace App\Controllers;

use App\Models\AttendanceSessionModel;
use App\Models\AttendanceRecordModel;
use App\Models\CohortModel;
use App\Models\SubjectModel;
use App\Models\StudentModel;
use App\Models\TimetablePeriodModel;

class OrgAttendance extends BaseController
{
    public function index()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $cohortModel = new CohortModel();
        $cohorts = $cohortModel->where('org_id', $this->org_id)->findAll();
        
        $subjectModel = new SubjectModel();
        $subjects = $subjectModel->where('org_id', $this->org_id)->findAll();
        
        // Find recent sessions created by this faculty
        $sessionModel = new AttendanceSessionModel();
        $recent_sessions = [];
        
        if (!session('is_org_admin')) {
            $recent_sessions = $sessionModel->select('attendance_sessions.*, cohorts.name as cohort_name, subjects.name as subject_name')
                                            ->join('cohorts', 'cohorts.id = attendance_sessions.cohort_id')
                                            ->join('subjects', 'subjects.id = attendance_sessions.subject_id')
                                            ->where('attendance_sessions.org_id', $this->org_id)
                                            ->where('attendance_sessions.faculty_user_id', $this->org_user_id)
                                            ->orderBy('session_date', 'DESC')
                                            ->limit(10)
                                            ->findAll();
        }

        return view('org/attendance/index', [
            'cohorts' => $cohorts,
            'subjects' => $subjects,
            'recent_sessions' => $recent_sessions
        ]);
    }

    public function createSession()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        
        $cohort_id = $this->request->getPost('cohort_id');
        $subject_id = $this->request->getPost('subject_id');
        $session_date = $this->request->getPost('session_date');
        
        $sessionModel = new AttendanceSessionModel();
        
        // Check if session exists
        $existing = $sessionModel->where('org_id', $this->org_id)
                                 ->where('cohort_id', $cohort_id)
                                 ->where('subject_id', $subject_id)
                                 ->where('session_date', $session_date)
                                 ->first();
                                 
        if ($existing) {
            return redirect()->to('org/attendance/take/' . ($existing['uuid'] ?? $existing['id']));
        }
        
        $session_id = $sessionModel->insert([
            'org_id' => $this->org_id,
            'cohort_id' => $cohort_id,
            'subject_id' => $subject_id,
            'faculty_user_id' => $this->org_user_id,
            'session_date' => $session_date
        ]);
        
        // Initialize records for all students in cohort
        $studentModel = new StudentModel();
        $students = $studentModel->where('org_id', $this->org_id)
                                 ->where('cohort_id', $cohort_id)
                                 ->findAll();
                                 
        if (!empty($students)) {
            $recordModel = new AttendanceRecordModel();
            $inserts = [];
            foreach($students as $s) {
                $inserts[] = [
                    'org_id' => $this->org_id,
                    'session_id' => $session_id,
                    'student_id' => $s['id'],
                    'status' => 'Present' // Default to present
                ];
            }
            $recordModel->insertBatch($inserts);
        }
        
        $newSession = $sessionModel->find($session_id);
        $targetUuid = $newSession['uuid'] ?? $session_id;
        return redirect()->to('org/attendance/take/' . $targetUuid);
    }

    public function take($session_id)
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        
        $sessionModel = new AttendanceSessionModel();
        $session = $sessionModel->select('attendance_sessions.*, cohorts.name as cohort_name, subjects.name as subject_name')
                                ->join('cohorts', 'cohorts.id = attendance_sessions.cohort_id')
                                ->join('subjects', 'subjects.id = attendance_sessions.subject_id')
                                ->where('attendance_sessions.org_id', $this->org_id)
                                ->findByIdOrUuid($session_id);
                                
        if (!$session) return redirect()->to('org/attendance');
        $realSessionId = $session['id'];

        $db = \Config\Database::connect();
        $students = $db->table('attendance_records r')
                       ->select('r.id as record_id, r.status, s.id as student_id, s.first_name, s.last_name, s.roll_number')
                       ->join('students s', 's.id = r.student_id')
                       ->where('r.session_id', $realSessionId)
                       ->orderBy('s.roll_number', 'ASC')
                       ->get()->getResultArray();
                        
        return view('org/attendance/take', [
            'session' => $session,
            'students' => $students
        ]);
    }

    public function save()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        
        $session_id = $this->request->getPost('session_id');
        $sessionModel = new AttendanceSessionModel();
        $sess = $sessionModel->findByIdOrUuid($session_id);
        $realSessionId = $sess ? $sess['id'] : (int)$session_id;

        $attendance_data = $this->request->getPost('attendance'); // array of record_id => status
        $topic_taught = $this->request->getPost('topic_taught');
        
        if (is_array($attendance_data)) {
            $recordModel = new AttendanceRecordModel();
            foreach($attendance_data as $record_id => $status) {
                $recordModel->update($record_id, ['status' => $status]);
            }
        }
        
        if ($topic_taught) {
            $sessionModel->update($realSessionId, ['topic_taught' => $topic_taught]);
        }
        
        return redirect()->to('org/attendance')->with('success', 'Attendance saved successfully.');
    }

    public function report()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        
        $cohortModel = new CohortModel();
        $cohorts = $cohortModel->where('org_id', $this->org_id)->findAll();
        
        $selected_cohort_id = $this->request->getGet('cohort_id');
        $report_data = [];
        
        if ($selected_cohort_id) {
            $db = \Config\Database::connect();
            $students = $db->table('students')
                           ->select('id, first_name, last_name, roll_number')
                           ->where('org_id', $this->org_id)
                           ->where('cohort_id', $selected_cohort_id)
                           ->orderBy('roll_number', 'ASC')
                           ->get()->getResultArray();
                           
            foreach($students as $s) {
                $counts = $db->query("
                    SELECT r.status, COUNT(*) as cnt 
                    FROM attendance_records r 
                    JOIN attendance_sessions s ON s.id = r.session_id
                    WHERE r.student_id = ? AND s.cohort_id = ?
                    GROUP BY r.status
                ", [$s['id'], $selected_cohort_id])->getResultArray();
                
                $present = 0;
                $absent = 0;
                $late = 0;
                
                foreach($counts as $c) {
                    if ($c['status'] == 'Present') $present = $c['cnt'];
                    if ($c['status'] == 'Absent') $absent = $c['cnt'];
                    if ($c['status'] == 'Late') $late = $c['cnt'];
                }
                
                $total = $present + $absent + $late;
                // Treat Late as 50% attendance for calculation
                $percentage = $total > 0 ? (($present + ($late * 0.5)) / $total) * 100 : 0;
                
                $report_data[] = [
                    'student' => $s,
                    'present' => $present,
                    'absent' => $absent,
                    'late' => $late,
                    'total' => $total,
                    'percentage' => round($percentage, 2)
                ];
            }
        }
        
        return view('org/attendance/report', [
            'cohorts' => $cohorts,
            'selected_cohort_id' => $selected_cohort_id,
            'report_data' => $report_data
        ]);
    }

    public function classTeacherMonitor()
    {
        return (new OrgFaculty())->classTeacherMonitor();
    }
}


