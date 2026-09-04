<?php
namespace App\Models;
use CodeIgniter\Model;

class StudentComplaintModel extends BaseModel
{
    protected $table = 'student_complaints';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'category', 'subject', 'description', 'attachment', 'status', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
