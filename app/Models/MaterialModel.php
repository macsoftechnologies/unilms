<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialModel extends BaseModel
{
    protected $table            = 'lms_materials';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['org_id', 'subject_id', 'cohort_id', 'faculty_user_id', 'title', 'description', 'type', 'file_path', 'external_url', 'is_active'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getMaterialsByFaculty($org_id, $faculty_user_id)
    {
        return $this->select('lms_materials.*, subjects.name as subject_name, subjects.code as subject_code, cohorts.name as cohort_name')
                    ->join('subjects', 'subjects.id = lms_materials.subject_id')
                    ->join('cohorts', 'cohorts.id = lms_materials.cohort_id')
                    ->where('lms_materials.org_id', $org_id)
                    ->where('lms_materials.faculty_user_id', $faculty_user_id)
                    ->orderBy('lms_materials.created_at', 'DESC')
                    ->findAll();
    }

    public function getMaterialsByOrg($org_id)
    {
        return $this->select('lms_materials.*, subjects.name as subject_name, subjects.code as subject_code, cohorts.name as cohort_name, org_users.full_name')
                    ->join('subjects', 'subjects.id = lms_materials.subject_id')
                    ->join('cohorts', 'cohorts.id = lms_materials.cohort_id')
                    ->join('org_users', 'org_users.id = lms_materials.faculty_user_id')
                    ->where('lms_materials.org_id', $org_id)
                    ->orderBy('lms_materials.created_at', 'DESC')
                    ->findAll();
    }
}
