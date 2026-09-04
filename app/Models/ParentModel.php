<?php
namespace App\Models;
use CodeIgniter\Model;

class ParentModel extends BaseModel
{
    protected $table = 'parents';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'first_name', 'last_name', 'phone', 'email', 'password_hash', 'status', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
