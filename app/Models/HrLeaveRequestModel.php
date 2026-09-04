<?php

namespace App\Models;

use CodeIgniter\Model;

class HrLeaveRequestModel extends BaseModel
{
    protected $table            = 'hr_leave_requests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'org_id', 'employee_id', 'leave_policy_id', 'start_date', 'end_date',
        'reason', 'status', 'hr_remarks'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
