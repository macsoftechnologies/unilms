<?php
namespace App\Models;
use CodeIgniter\Model;

class BacklogModel extends BaseModel
{
    protected $table = 'backlogs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'subject_id', 'exam_schedule_id', 'status', 'cleared_in_exam_id', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
