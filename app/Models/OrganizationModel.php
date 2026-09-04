<?php
namespace App\Models;
use CodeIgniter\Model;

class OrganizationModel extends BaseModel
{
    protected $table = 'organizations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'code', 'admin_email', 'plan_id', 'cms_enabled', 'lms_enabled', 'status', 'subscription_end_date', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
