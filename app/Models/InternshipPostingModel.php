<?php

namespace App\Models;

class InternshipPostingModel extends BaseModel
{
    protected $table            = 'internship_postings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'org_id', 'company_name', 'role_title', 'description', 'required_skills',
        'work_mode', 'location', 'stipend_amount', 'total_seats', 'available_seats',
        'min_cgpa', 'target_department_id', 'application_deadline', 'start_date',
        'end_date', 'allow_company_tasks', 'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
