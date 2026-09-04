<?php

namespace App\Models;

use CodeIgniter\Model;

class TransportRouteModel extends BaseModel
{
    protected $table            = 'transport_routes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'org_id', 'name', 'start_point', 'end_point', 'created_at'
    ];
    protected $useTimestamps = false; 
}
