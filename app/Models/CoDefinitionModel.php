<?php

namespace App\Models;

use CodeIgniter\Model;

class CoDefinitionModel extends BaseModel
{
    protected $table            = 'co_definitions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['org_id', 'subject_id', 'code', 'description'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getCosBySubject($org_id, $subject_id)
    {
        $results = $this->where('org_id', $org_id)
                        ->where('subject_id', $subject_id)
                        ->findAll();

        usort($results, function($a, $b) {
            return strnatcasecmp($a['code'], $b['code']);
        });

        return $results;
    }
}
