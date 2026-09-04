<?php
namespace App\Models;
use CodeIgniter\Model;

class OrgUserModel extends BaseModel
{
    protected $table = 'org_users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'employee_code', 'email', 'full_name', 'phone', 'designation', 'is_org_admin', 'password_hash', 'role', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
