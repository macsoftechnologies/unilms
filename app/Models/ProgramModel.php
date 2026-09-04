<?php
namespace App\Models;
use CodeIgniter\Model;

class ProgramModel extends BaseModel
{
    protected $table = 'programs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'dept_id', 'name', 'code', 'duration_years', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
