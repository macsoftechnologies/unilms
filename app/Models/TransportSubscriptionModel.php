<?php

namespace App\Models;

use CodeIgniter\Model;

class TransportSubscriptionModel extends BaseModel
{
    protected $table            = 'transport_subscriptions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'org_id', 'student_id', 'route_id', 'halt_id', 'start_date', 'status', 'created_at'
    ];
    protected $useTimestamps = false; 
}
