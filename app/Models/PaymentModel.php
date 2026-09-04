<?php
namespace App\Models;
use CodeIgniter\Model;

class PaymentModel extends BaseModel
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['invoice_id', 'org_id', 'amount', 'payment_date', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
