<?php

namespace App\Models;

class InternshipTaskModel extends BaseModel
{
    protected $table            = 'internship_tasks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'milestone_id', 'task_title', 'description', 'expected_output',
        'estimated_hours', 'submission_type', 'is_company_added', 'is_faculty_approved', 'sort_order'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
