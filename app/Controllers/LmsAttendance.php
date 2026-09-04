<?php

namespace App\Controllers;

use App\Models\SubjectModel;

class LmsAttendance extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        $student_id = session('student_id') ?: (session('lms_student_id') ?: 3);
        $orgId = $this->org_id ?: (session('org_id') ?: 5);
        
        // Overall attendance
        $overall_counts = $db->query("
            SELECT r.status, COUNT(*) as cnt 
            FROM attendance_records r 
            JOIN attendance_sessions s ON s.id = r.session_id
            WHERE r.student_id = ? AND r.org_id = ?
            GROUP BY r.status
        ", [$student_id, $orgId])->getResultArray();
        
        $present = 0; $absent = 0; $late = 0;
        foreach($overall_counts as $c) {
            if ($c['status'] == 'Present') $present = $c['cnt'];
            if ($c['status'] == 'Absent') $absent = $c['cnt'];
            if ($c['status'] == 'Late') $late = $c['cnt'];
        }
        
        $total = $present + $absent + $late;
        $percentage = $total > 0 ? (($present + ($late * 0.5)) / $total) * 100 : 0;
        
        $overall = [
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'total' => $total,
            'percentage' => round($percentage, 2)
        ];
        
        // Subject-wise attendance
        $subject_stats = [];
        $subjects = (new SubjectModel())->where('org_id', $this->org_id)->findAll();
        
        foreach($subjects as $subj) {
            $counts = $db->query("
                SELECT r.status, COUNT(*) as cnt 
                FROM attendance_records r 
                JOIN attendance_sessions s ON s.id = r.session_id
                WHERE r.student_id = ? AND s.subject_id = ?
                GROUP BY r.status
            ", [$student_id, $subj['id']])->getResultArray();
            
            $sp = 0; $sa = 0; $sl = 0;
            foreach($counts as $c) {
                if ($c['status'] == 'Present') $sp = $c['cnt'];
                if ($c['status'] == 'Absent') $sa = $c['cnt'];
                if ($c['status'] == 'Late') $sl = $c['cnt'];
            }
            
            $st = $sp + $sa + $sl;
            if ($st > 0) {
                $pct = (($sp + ($sl * 0.5)) / $st) * 100;
                $subject_stats[] = [
                    'subject' => $subj,
                    'present' => $sp,
                    'absent' => $sa,
                    'late' => $sl,
                    'total' => $st,
                    'percentage' => round($pct, 2)
                ];
            }
        }
        
        // Recent history
        $recent_history = $db->query("
            SELECT r.status, s.session_date, s.topic_taught, sub.name as subject_name 
            FROM attendance_records r 
            JOIN attendance_sessions s ON s.id = r.session_id
            JOIN subjects sub ON sub.id = s.subject_id
            WHERE r.student_id = ?
            ORDER BY s.session_date DESC
            LIMIT 10
        ", [$student_id])->getResultArray();

        return view('lms/attendance/index', [
            'overall' => $overall,
            'subject_stats' => $subject_stats,
            'recent_history' => $recent_history
        ]);
    }
}

