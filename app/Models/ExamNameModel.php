<?php
namespace App\Models;
use CodeIgniter\Model;

class ExamNameModel extends BaseModel
{
    protected $table = 'exam_names';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'name', 'type', 'description', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
