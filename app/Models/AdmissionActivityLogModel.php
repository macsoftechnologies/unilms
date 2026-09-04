<?php

namespace App\Models;

use CodeIgniter\Model;

class AdmissionActivityLogModel extends BaseModel
{
    protected $table = 'admission_activity_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'lead_id', 'application_id', 'action', 'description', 'performed_by'];
    protected $useTimestamps = false; // We use database default timestamp for performed_at

    public function getLogsForLead($orgId, $leadId) {
        return $this->select('admission_activity_logs.*, org_users.full_name as performer_name')
                    ->join('org_users', 'org_users.id = admission_activity_logs.performed_by', 'left')
                    ->where('admission_activity_logs.org_id', $orgId)
                    ->where('admission_activity_logs.lead_id', $leadId)
                    ->orderBy('admission_activity_logs.performed_at', 'DESC')
                    ->findAll();
    }
}
