<?php

namespace App\Models;

use CodeIgniter\Model;

class AssignmentModel extends BaseModel
{
    protected $table            = 'lms_assignments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['org_id', 'subject_id', 'cohort_id', 'faculty_user_id', 'title', 'description', 'due_date', 'max_marks', 'assessment_type', 'meta_data', 'file_path', 'is_active'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAssignmentsByFaculty($org_id, $faculty_user_id)
    {
        return $this->select('lms_assignments.*, subjects.name as subject_name, subjects.code as subject_code, cohorts.name as cohort_name')
                    ->join('subjects', 'subjects.id = lms_assignments.subject_id')
                    ->join('cohorts', 'cohorts.id = lms_assignments.cohort_id')
                    ->where('lms_assignments.org_id', $org_id)
                    ->where('lms_assignments.faculty_user_id', $faculty_user_id)
                    ->orderBy('lms_assignments.created_at', 'DESC')
                    ->findAll();
    }

    public function getAssignmentsByOrg($org_id)
    {
        return $this->select('lms_assignments.*, subjects.name as subject_name, subjects.code as subject_code, cohorts.name as cohort_name, org_users.full_name')
                    ->join('subjects', 'subjects.id = lms_assignments.subject_id')
                    ->join('cohorts', 'cohorts.id = lms_assignments.cohort_id')
                    ->join('org_users', 'org_users.id = lms_assignments.faculty_user_id')
                    ->where('lms_assignments.org_id', $org_id)
                    ->orderBy('lms_assignments.created_at', 'DESC')
                    ->findAll();
    }
}
