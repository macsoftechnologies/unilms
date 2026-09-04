<?php
namespace App\Models;
use CodeIgniter\Model;

class StaffCertificateModel extends BaseModel
{
    protected $table = 'staff_certificates';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'staff_id', 'certificate_type', 'issue_date', 'signatory_id', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
