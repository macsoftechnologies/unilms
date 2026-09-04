<?php

namespace App\Models;

use CodeIgniter\Model;

class CoPoMappingModel extends BaseModel
{
    protected $table            = 'co_po_mapping';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['org_id', 'co_id', 'po_id', 'strength'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getMappingsForSubjectCos($org_id, $co_ids)
    {
        if(empty($co_ids)) return [];
        return $this->where('org_id', $org_id)
                    ->whereIn('co_id', $co_ids)
                    ->findAll();
    }
}
