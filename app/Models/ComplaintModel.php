<?php
namespace App\Models;
use CodeIgniter\Model;

class ComplaintModel extends BaseModel
{
    protected $table = 'complaints';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'user_id', 'subject', 'description', 'status', 'created_at', 'updated_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
