<?php

namespace App\Models;

use CodeIgniter\Model;

class AssessmentCoMappingModel extends BaseModel
{
    protected $table            = 'assessment_co_mapping';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['org_id', 'component_id', 'co_id'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    public function getMappingsForComponent($org_id, $component_id)
    {
        $records = $this->where('org_id', $org_id)
                        ->where('component_id', $component_id)
                        ->findAll();
        return array_column($records, 'co_id');
    }
}
