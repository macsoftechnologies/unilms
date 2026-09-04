<?php

namespace App\Models;

use CodeIgniter\Model;

class PoDefinitionModel extends BaseModel
{
    protected $table            = 'po_definitions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['org_id', 'program_id', 'code', 'description', 'type'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getPosByProgram($org_id, $program_id)
    {
        $results = $this->where('org_id', $org_id)
                        ->where('program_id', $program_id)
                        ->findAll();

        usort($results, function($a, $b) {
            // First sort by Type (PO before PSO)
            if ($a['type'] !== $b['type']) {
                return strcmp($a['type'], $b['type']);
            }
            // Natural sort by Code (PO1, PO2 ... PO9, PO10, PO11, PO12)
            return strnatcasecmp($a['code'], $b['code']);
        });

        return $results;
    }
}
