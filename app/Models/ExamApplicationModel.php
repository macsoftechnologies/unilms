<?php
namespace App\Models;
use CodeIgniter\Model;

class ExamApplicationModel extends BaseModel
{
    protected $table = 'exam_applications';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'exam_id', 'status', 'fee_paid', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
