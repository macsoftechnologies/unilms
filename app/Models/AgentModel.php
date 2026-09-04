<?php
namespace App\Models;
use CodeIgniter\Model;

class AgentModel extends BaseModel
{
    protected $table = 'agents';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'name', 'phone', 'email', 'commission_rate', 'status', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
