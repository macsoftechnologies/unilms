<?php

namespace App\Models;

use CodeIgniter\Model;

class TransportLogbookModel extends BaseModel
{
    protected $table            = 'transport_logbook';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'org_id', 'vehicle_id', 'expense_head', 'amount', 'expense_date', 'description', 'created_at'
    ];
    protected $useTimestamps = false; 
}
