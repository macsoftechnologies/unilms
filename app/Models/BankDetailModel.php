<?php
namespace App\Models;
use CodeIgniter\Model;

class BankDetailModel extends BaseModel
{
    protected $table = 'bank_details';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'bank_name', 'account_name', 'account_number', 'ifsc_code', 'branch_name', 'is_primary', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
