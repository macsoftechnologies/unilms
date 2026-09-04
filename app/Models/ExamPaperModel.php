<?php
namespace App\Models;
use CodeIgniter\Model;

class ExamPaperModel extends BaseModel
{
    protected $table = 'exam_papers';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'exam_id', 'subject_id', 'faculty_id', 'paper_type', 'deadline', 'status', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
