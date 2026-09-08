<?php
namespace App\Models;

class SalaryComponentModel extends BaseModel
{
    protected $table = 'salary_components';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'name', 'code', 'type', 'component_type', 'calculation_type',
        'percentage_of', 'default_amount', 'default_value', 'description',
        'is_taxable', 'is_active'
    ];
    protected $useTimestamps = true;
}
