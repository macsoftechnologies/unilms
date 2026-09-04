<?php
namespace App\Models;
use CodeIgniter\Model;

class AdmissionPaymentModel extends BaseModel
{
    protected $table = 'admission_payments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'offer_id', 'amount', 'payment_status', 'payment_mode', 
        'gateway', 'gateway_reference', 'webhook_payload', 'receipt_number'
    ];
    protected $useTimestamps = false; // We use created_at automatically via DB default
    
    public function getPayments($orgId) {
        return $this->select('admission_payments.*, applications.adm_number, applications.full_name')
                    ->join('admission_offers', 'admission_offers.id = admission_payments.offer_id', 'left')
                    ->join('applications', 'applications.id = admission_offers.application_id', 'left')
                    ->where('admission_payments.org_id', $orgId)
                    ->orderBy('admission_payments.created_at', 'DESC')
                    ->findAll();
    }
}
