<?php
namespace App\Models;
use CodeIgniter\Model;

class AdmissionCategoryModel extends BaseModel
{
    protected $table = 'admission_categories';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'name', 'description', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
