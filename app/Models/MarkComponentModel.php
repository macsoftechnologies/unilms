<?php

namespace App\Models;

use CodeIgniter\Model;

class MarkComponentModel extends BaseModel
{
    protected $table            = 'mark_components';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['org_id', 'program_id', 'semester_id', 'subject_id', 'name', 'max_marks'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getComponentsBySubject($org_id, $subject_id)
    {
        return $this->where('org_id', $org_id)
                    ->where('subject_id', $subject_id)
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }
}
