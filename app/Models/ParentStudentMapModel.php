<?php
namespace App\Models;
use CodeIgniter\Model;

class ParentStudentMapModel extends BaseModel
{
    protected $table = 'parent_student_map';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'parent_id', 'student_id', 'relationship', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
