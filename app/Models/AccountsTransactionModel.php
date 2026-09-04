<?php
namespace App\Models;
use CodeIgniter\Model;

class AccountsTransactionModel extends BaseModel
{
    protected $table = 'accounts_transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'transaction_date', 'type', 'head_id', 'bank_account_id', 'amount', 'transaction_type', 'reference_no', 'description', 'party_name', 'created_by', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
