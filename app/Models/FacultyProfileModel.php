<?php

namespace App\Models;

use CodeIgniter\Model;

class FacultyProfileModel extends BaseModel
{
    protected $table            = 'faculty_profiles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'org_id',
        'user_id',
        'department_id',
        'qualification',
        'experience_years',
        'joining_date',
        'specialization'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get a faculty profile along with their user details
     */
    public function getProfileWithUser($orgId, $userId)
    {
        return $this->select('faculty_profiles.*, org_users.full_name, org_users.email, org_users.phone, org_users.designation, departments.name as department_name')
                    ->join('org_users', 'org_users.id = faculty_profiles.user_id')
                    ->join('departments', 'departments.id = faculty_profiles.department_id', 'left')
                    ->where('faculty_profiles.org_id', $orgId)
                    ->where('faculty_profiles.user_id', $userId)
                    ->first();
    }

    /**
     * Get all faculty profiles for an organization
     */
    public function getAllProfiles($orgId)
    {
        return $this->select('faculty_profiles.*, org_users.full_name, org_users.email, org_users.phone, org_users.designation, departments.name as department_name')
                    ->join('org_users', 'org_users.id = faculty_profiles.user_id')
                    ->join('departments', 'departments.id = faculty_profiles.department_id', 'left')
                    ->where('faculty_profiles.org_id', $orgId)
                    ->findAll();
    }
}
