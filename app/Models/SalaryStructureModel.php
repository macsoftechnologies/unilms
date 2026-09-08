<?php
namespace App\Models;

class SalaryStructureModel extends BaseModel
{
    protected $table = 'salary_structures';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'name', 'designation_id', 'base_salary', 'basic_salary',
        'total_earnings', 'total_deductions', 'net_salary', 'status',
        'description', 'components_json'
    ];
    protected $useTimestamps = true;
}
