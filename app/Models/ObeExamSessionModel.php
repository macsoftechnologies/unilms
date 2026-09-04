<?php
namespace App\Models;
use CodeIgniter\Model;

class ObeExamSessionModel extends BaseModel
{
    protected $table = 'obe_exam_sessions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'session_name', 'academic_year_id', 'semester_id', 'type', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
