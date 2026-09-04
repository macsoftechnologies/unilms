<?php

namespace App\Models;

use CodeIgniter\Model;

class InternalMarkModel extends BaseModel
{
    protected $table            = 'internal_marks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['org_id', 'component_id', 'student_id', 'score', 'faculty_user_id', 'is_locked'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getMarksByComponent($org_id, $component_id)
    {
        $records = $this->where('org_id', $org_id)
                        ->where('component_id', $component_id)
                        ->findAll();
        
        $mapped = [];
        foreach($records as $r) {
            $mapped[$r['student_id']] = $r;
        }
        return $mapped;
    }

    public function lockMarks($org_id, $component_id)
    {
        return $this->where('org_id', $org_id)
                    ->where('component_id', $component_id)
                    ->set(['is_locked' => 1])
                    ->update();
    }
}
