<?php
namespace App\Models;
use CodeIgniter\Model;

class StudentFeeLedgerModel extends BaseModel
{
    protected $table = 'student_fee_ledger';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'fee_structure_id', 'amount_due', 'amount_paid', 'balance', 'status', 'created_at', 'updated_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
