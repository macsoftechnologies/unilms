<?php
namespace App\Models;

use CodeIgniter\Model;

class AttendanceRecordModel extends BaseModel
{
    protected $table = 'attendance_records';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'session_id', 'student_id', 'status'];
    protected $useTimestamps = false; // Because table doesn't have timestamps

    public function getStudentAttendanceStats($org_id, $cohort_id = null)
    {
        // Get percentage of Present + Late vs Total sessions for each student
        $builder = $this->db->table('attendance_records ar')
                            ->select('ar.student_id, s.first_name, s.last_name, s.roll_number')
                            ->select('COUNT(ar.id) as total_sessions')
                            ->select('SUM(CASE WHEN ar.status IN ("Present", "Late") THEN 1 ELSE 0 END) as attended_sessions')
                            ->join('students s', 's.id = ar.student_id')
                            ->where('ar.org_id', $org_id);
                            
        if ($cohort_id) {
            $builder->where('s.cohort_id', $cohort_id);
        }
        
        $builder->groupBy('ar.student_id');
        return $builder->get()->getResultArray();
    }
}

