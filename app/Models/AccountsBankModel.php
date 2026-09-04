<?php
namespace App\Models;
use CodeIgniter\Model;

class AccountsBankModel extends BaseModel
{
    protected $table = 'accounts_banks';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'account_name', 'account_no', 'ifsc_code', 'type', 'opening_balance', 'current_balance', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
