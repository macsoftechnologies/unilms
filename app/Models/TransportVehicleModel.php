<?php

namespace App\Models;

use CodeIgniter\Model;

class TransportVehicleModel extends BaseModel
{
    protected $table            = 'transport_vehicles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'org_id', 'vehicle_no', 'type', 'capacity', 'model_year', 'fuel_type', 'owner_status', 'created_at'
    ];

    // Enable Timestamps but map them appropriately if we only have created_at
    protected $useTimestamps = false; 
}
