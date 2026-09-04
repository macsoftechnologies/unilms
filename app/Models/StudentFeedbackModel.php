<?php
namespace App\Models;
use CodeIgniter\Model;

class StudentFeedbackModel extends BaseModel
{
    protected $table = 'student_feedbacks';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'subject_id', 'faculty_id', 'rating', 'comments', 'submitted_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
