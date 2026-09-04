<?php
namespace App\Models;
use CodeIgniter\Model;

class StudentChecklistModel extends BaseModel
{
    protected $table = 'student_checklists';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'checklist_id', 'status', 'remarks', 'updated_by', 'updated_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
