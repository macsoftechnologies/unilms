<?php
namespace App\Models;
use CodeIgniter\Model;

class GovtReceiptModel extends BaseModel
{
    protected $table = 'govt_receipts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'scheme_name', 'application_no', 'amount', 'status', 'remarks', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
