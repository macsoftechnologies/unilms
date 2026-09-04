<?php
namespace App\Models;
use CodeIgniter\Model;

class AccountsHeadModel extends BaseModel
{
    protected $table = 'accounts_heads';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'head_name', 'head_type', 'parent_head_id', 'gl_code', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
