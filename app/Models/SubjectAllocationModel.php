<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectAllocationModel extends BaseModel
{
    protected $table            = 'subject_allocations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'org_id',
        'faculty_user_id',
        'subject_id',
        'cohort_id',
        'semester_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get all allocations for a specific organization
     */
    public function getAllocations($orgId)
    {
        return $this->select('subject_allocations.*, org_users.full_name as faculty_name, subjects.name as subject_name, subjects.code as subject_code, cohorts.name as cohort_name, semesters.name as semester_name')
                    ->join('org_users', 'org_users.id = subject_allocations.faculty_user_id')
                    ->join('subjects', 'subjects.id = subject_allocations.subject_id')
                    ->join('cohorts', 'cohorts.id = subject_allocations.cohort_id')
                    ->join('semesters', 'semesters.id = subject_allocations.semester_id', 'left')
                    ->where('subject_allocations.org_id', $orgId)
                    ->findAll();
    }
}
