<?php
namespace App\Models;
use CodeIgniter\Model;

class ExamScheduleModel extends BaseModel
{
    protected $table = 'exam_schedules';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'exam_id', 'program_id', 'semester_id', 'subject_id', 'exam_date', 'start_time', 'end_time', 'max_marks', 'passing_marks', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
