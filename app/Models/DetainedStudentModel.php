<?php
namespace App\Models;
use CodeIgniter\Model;

class DetainedStudentModel extends BaseModel
{
    protected $table = 'detained_students';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'date_detained', 'reason', 'status', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
