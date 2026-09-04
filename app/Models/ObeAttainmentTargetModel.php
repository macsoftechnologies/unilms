<?php
namespace App\Models;
use CodeIgniter\Model;

class ObeAttainmentTargetModel extends BaseModel
{
    protected $table = 'obe_attainment_targets';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'department_id', 'target_type', 'target_value', 'target_level', 'academic_year_id', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
