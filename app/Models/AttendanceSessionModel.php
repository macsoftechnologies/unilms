<?php
namespace App\Models;

use CodeIgniter\Model;

class AttendanceSessionModel extends BaseModel
{
    protected $table = 'attendance_sessions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'cohort_id', 'subject_id', 'faculty_user_id', 'session_date', 'period_id', 'topic_taught'];
    protected $useTimestamps = true;

    // Optional: get sessions with subject and cohort details
    public function getSessionsByFaculty($org_id, $faculty_user_id)
    {
        return $this->select('attendance_sessions.*, cohorts.name as cohort_name, subjects.name as subject_name, subjects.code as subject_code')
                    ->join('cohorts', 'cohorts.id = attendance_sessions.cohort_id')
                    ->join('subjects', 'subjects.id = attendance_sessions.subject_id')
                    ->where('attendance_sessions.org_id', $org_id)
                    ->where('attendance_sessions.faculty_user_id', $faculty_user_id)
                    ->orderBy('attendance_sessions.session_date', 'DESC')
                    ->findAll();
    }
}

