<?php
namespace App\Models;

class SalaryComponentModel extends BaseModel
{
    protected $table = 'salary_components';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'name', 'component_type', 'calculation_type', 
        'default_value', 'description', 'is_active'
    ];
    protected $useTimestamps = true;
}
