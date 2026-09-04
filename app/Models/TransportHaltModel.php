<?php

namespace App\Models;

use CodeIgniter\Model;

class TransportHaltModel extends BaseModel
{
    protected $table            = 'transport_halts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'org_id', 'route_id', 'name', 'distance_km', 'annual_fee', 'created_at'
    ];
    protected $useTimestamps = false; 
}
