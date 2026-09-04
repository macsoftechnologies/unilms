<?php
namespace App\Models;
use CodeIgniter\Model;

class PlacementCompanyModel extends BaseModel
{
    protected $table = 'placement_companies';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'company_name', 'industry', 'hr_contact_name', 'hr_email', 'hr_phone', 'mou_signed', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
