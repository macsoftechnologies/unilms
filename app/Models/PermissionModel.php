<?php
namespace App\Models;
use CodeIgniter\Model;

class PermissionModel extends BaseModel
{
    protected $table = 'permissions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['module', 'module_area', 'feature', 'action', 'permission_key', 'label'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
    
    /**
     * Get all permissions for the modules an org has enabled.
     */
    public function getPermissionsForOrg($org)
    {
        $modules = [];
        if (!empty($org['cms_enabled'])) $modules[] = 'cms';
        if (!empty($org['lms_enabled'])) $modules[] = 'lms';
        
        if (empty($modules)) return [];
        
        return $this->whereIn('module', $modules)->orderBy('module_area')->orderBy('feature')->orderBy('action')->findAll();
    }
    
    /**
     * Get all permission IDs assigned to a user through their access groups.
     */
    public function getUserPermissionKeys($userId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('access_group_permissions agp');
        $builder->select('p.permission_key');
        $builder->join('permissions p', 'p.id = agp.permission_id');
        $builder->join('org_user_groups oug', 'oug.group_id = agp.group_id');
        $builder->where('oug.user_id', $userId);
        
        $results = $builder->get()->getResultArray();
        return array_column($results, 'permission_key');
    }
}
