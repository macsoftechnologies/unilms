<?php

namespace App\Models;

use CodeIgniter\Model;

class TimetableScheduleModel extends BaseModel
{
    protected $table            = 'timetable_schedules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'org_id',
        'cohort_id',
        'semester_id',
        'template_id',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getSchedules($orgId)
    {
        return $this->select('timetable_schedules.*, cohorts.name as cohort_name, semesters.name as semester_name, timetable_templates.name as template_name')
                    ->join('cohorts', 'cohorts.id = timetable_schedules.cohort_id')
                    ->join('semesters', 'semesters.id = timetable_schedules.semester_id', 'left')
                    ->join('timetable_templates', 'timetable_templates.id = timetable_schedules.template_id')
                    ->where('timetable_schedules.org_id', $orgId)
                    ->findAll();
    }
}

