<?php
namespace App\Models;
use CodeIgniter\Model;

class PlanModel extends BaseModel
{
    protected $table = 'plans';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'price', 'features', 'duration_months', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
