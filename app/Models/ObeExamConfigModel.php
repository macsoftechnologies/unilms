<?php
namespace App\Models;
use CodeIgniter\Model;

class ObeExamConfigModel extends BaseModel
{
    protected $table = 'obe_exam_configs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'exam_session_id', 'short_name', 'max_marks', 'passing_marks', 'average_logic', 'n_value', 'due_date', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
