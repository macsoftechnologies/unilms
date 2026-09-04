<?php
namespace App\Models;
use CodeIgniter\Model;

class CertificateRequestModel extends BaseModel
{
    protected $table = 'certificate_requests';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'certificate_type', 'reason', 'payment_status', 'status', 'request_date'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
