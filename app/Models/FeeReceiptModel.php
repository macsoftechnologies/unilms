<?php
namespace App\Models;
use CodeIgniter\Model;

class FeeReceiptModel extends BaseModel
{
    protected $table = 'fee_receipts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'receipt_no', 'student_id', 'amount', 'mode', 'bank_ref', 'date', 'collected_by', 'remarks', 'cancelled', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
