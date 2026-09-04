<?php
namespace App\Models;
use CodeIgniter\Model;

class ExamMarksExternalModel extends BaseModel
{
    protected $table = 'exam_marks_external';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'exam_schedule_id', 'marks_obtained', 'status', 'entered_by', 'created_at', 'updated_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
