<?php
namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends BaseModel
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['org_id', 'name', 'description'];

    public function getRolePermissions($roleId)
    {
        $builder = $this->db->table('role_permissions');
        $builder->select('permissions.slug');
        $builder->join('permissions', 'permissions.id = role_permissions.permission_id');
        $builder->where('role_permissions.role_id', $roleId);
        
        $results = $builder->get()->getResultArray();
        return array_column($results, 'slug');
    }
}
