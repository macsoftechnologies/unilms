<?php
namespace App\Models;
use CodeIgniter\Model;

class AdmissionOfferModel extends BaseModel
{
    protected $table = 'admission_offers';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'application_id', 'version', 'fee_amount', 
        'due_date', 'expires_at', 'status'
    ];
    protected $useTimestamps = true;

    public function getOffers($orgId) {
        return $this->select('admission_offers.*, applications.adm_number, applications.full_name, applications.program_name')
                    ->join('applications', 'applications.id = admission_offers.application_id', 'left')
                    ->where('admission_offers.org_id', $orgId)
                    ->orderBy('admission_offers.created_at', 'DESC')
                    ->findAll();
    }
}
