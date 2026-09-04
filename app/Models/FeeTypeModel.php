<?php
namespace App\Models;
use CodeIgniter\Model;

class FeeTypeModel extends BaseModel
{
    protected $table = 'fee_types';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'name', 'description', 'applicable_to', 'gl_head', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
