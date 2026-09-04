<?php
namespace App\Models;
use CodeIgniter\Model;

class FacultyFeedbackModel extends BaseModel
{
    protected $table = 'faculty_feedback';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'faculty_id', 'subject_id', 'rating', 'comments', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
