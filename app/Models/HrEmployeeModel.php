<?php

namespace App\Models;

use CodeIgniter\Model;

class HrEmployeeModel extends BaseModel
{
    protected $table            = 'hr_employees';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'org_id', 'org_user_id', 'department_id', 'designation_id',
        'employment_type_id', 'employee_code', 'joining_date',
        'base_salary', 'bank_account', 'bank_name', 'ifsc_code'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
