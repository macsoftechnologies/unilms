<?php
namespace App\Models;
use CodeIgniter\Model;

class ExamGrantModel extends BaseModel
{
    protected $table = 'exam_grants';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'grant_type', 'amount', 'source', 'received_date', 'purpose', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
