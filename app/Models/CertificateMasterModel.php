<?php
namespace App\Models;
use CodeIgniter\Model;

class CertificateMasterModel extends BaseModel
{
    protected $table = 'certificates_master';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'certificate_name', 'template', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
