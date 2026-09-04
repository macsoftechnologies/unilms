<?php
namespace App\Models;
use CodeIgniter\Model;

class FeeStructureModel extends BaseModel
{
    protected $table = 'fee_structures';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'program_id', 'semester_id', 'academic_year_id', 'fee_type_id', 'amount', 'due_date', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
