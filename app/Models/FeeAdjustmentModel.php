<?php
namespace App\Models;
use CodeIgniter\Model;

class FeeAdjustmentModel extends BaseModel
{
    protected $table = 'fee_adjustments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'type', 'amount', 'reason', 'approved_by', 'date', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
