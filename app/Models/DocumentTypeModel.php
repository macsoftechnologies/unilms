<?php
namespace App\Models;
use CodeIgniter\Model;

class DocumentTypeModel extends BaseModel
{
    protected $table = 'document_types';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'program_id', 'admission_category', 'name', 'is_mandatory'];
    protected $useTimestamps = false;
}
