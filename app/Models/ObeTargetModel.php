<?php

namespace App\Models;

use CodeIgniter\Model;

class ObeTargetModel extends BaseModel
{
    protected $table            = 'obe_targets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['org_id', 'target_type', 'entity_id', 'target_percentage', 'level_1_threshold', 'level_2_threshold', 'level_3_threshold'];

    protected $useTimestamps = true;

    public function getGlobalTargets($org_id)
    {
        $targets = $this->where('org_id', $org_id)
                        ->where('entity_id', null)
                        ->findAll();
        
        $result = [];
        foreach($targets as $t) {
            $result[$t['target_type']] = $t;
        }
        return $result;
    }
}
