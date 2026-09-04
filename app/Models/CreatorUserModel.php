<?php

namespace App\Models;

use CodeIgniter\Model;

class CreatorUserModel extends Model
{
    protected $table            = 'creator_users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['org_id', 'name', 'email', 'password_hash', 'avatar', 'status'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function findByEmail($email)
    {
        return $this->where('email', $email)->where('status', 'active')->first();
    }
}
