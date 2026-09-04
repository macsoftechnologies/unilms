<?php
namespace App\Models;
use CodeIgniter\Model;

class DepartmentModel extends BaseModel
{
    protected $table = 'departments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'name', 'code', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
