<?php
namespace App\Models;
use CodeIgniter\Model;

class AccessGroupModel extends BaseModel
{
    protected $table = 'access_groups';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'name', 'description', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
