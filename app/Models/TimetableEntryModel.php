<?php

namespace App\Models;

use CodeIgniter\Model;

class TimetableEntryModel extends BaseModel
{
    protected $table            = 'timetable_entries';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'org_id',
        'schedule_id',
        'day_of_week',
        'period_id',
        'subject_id',
        'faculty_user_id',
        'room_number'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    public function getEntriesForSchedule($orgId, $scheduleId)
    {
        return $this->select('timetable_entries.*, subjects.name as subject_name, subjects.code as subject_code, org_users.full_name as faculty_name')
                    ->join('subjects', 'subjects.id = timetable_entries.subject_id', 'left')
                    ->join('org_users', 'org_users.id = timetable_entries.faculty_user_id', 'left')
                    ->where('timetable_entries.org_id', $orgId)
                    ->where('timetable_entries.schedule_id', $scheduleId)
                    ->findAll();
    }
}

