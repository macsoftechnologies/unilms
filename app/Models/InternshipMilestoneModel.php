<?php

namespace App\Models;

class InternshipMilestoneModel extends BaseModel
{
    protected $table            = 'internship_milestones';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'posting_id', 'milestone_number', 'title', 'description', 'is_midpoint_gate'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
