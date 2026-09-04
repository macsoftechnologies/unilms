<?php

namespace App\Models;

use CodeIgniter\Model;

class TimetablePeriodModel extends BaseModel
{
    protected $table            = 'timetable_periods';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'org_id',
        'template_id',
        'period_name',
        'start_time',
        'end_time',
        'is_break'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}

