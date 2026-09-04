<?php
namespace App\Models;
use CodeIgniter\Model;

class TransferCertificateModel extends BaseModel
{
    protected $table = 'transfer_certificates';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'application_date', 'issue_date', 'tc_number', 'reason', 'status', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
