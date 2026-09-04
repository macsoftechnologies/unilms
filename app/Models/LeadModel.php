<?php

namespace App\Models;

use CodeIgniter\Model;

class LeadModel extends BaseModel
{
    protected $table = 'leads';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'full_name', 'phone', 'email', 'program_id', 'source', 'referred_by', 'city', 'assigned_to', 'status', 'follow_up_date'];
    protected $useTimestamps = true;

    public function getLeads($orgId) {
        return $this->select('leads.*, programs.name as program_name, org_users.full_name as assigned_name')
                    ->join('programs', 'programs.id = leads.program_id', 'left')
                    ->join('org_users', 'org_users.id = leads.assigned_to', 'left')
                    ->where('leads.org_id', $orgId)
                    ->orderBy('leads.created_at', 'DESC')
                    ->findAll();
    }
}
