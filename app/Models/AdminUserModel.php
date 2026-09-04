<?php
namespace App\Models;
use CodeIgniter\Model;

class AdminUserModel extends BaseModel
{
    protected $table = 'admin_users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['email', 'password_hash', 'is_root', 'permissions', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
