<?php

namespace App\Models;

use CodeIgniter\Model;

class HrPayrollModel extends BaseModel
{
    protected $table            = 'hr_payroll';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'org_id', 'employee_id', 'month', 'year', 'base_salary',
        'allowances', 'deductions', 'net_salary', 'status', 'payment_date'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
