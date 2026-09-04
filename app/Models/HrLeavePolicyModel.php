<?php

namespace App\Models;

use CodeIgniter\Model;

class HrLeavePolicyModel extends BaseModel
{
    protected $table            = 'hr_leave_policies';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'org_id', 'employment_type_id', 'leave_type', 'annual_quota'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
