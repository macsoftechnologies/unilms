<?php
namespace App\Models;
use CodeIgniter\Model;

class SubjectModel extends BaseModel
{
    protected $table = 'subjects';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'program_id', 'semester_id', 'name', 'short_name', 'code', 'credits', 'subject_type', 'no_of_sessions', 'no_of_units', 'no_of_outcomes', 'internal_max_marks', 'internal_pass_marks', 'external_max_marks', 'external_pass_marks', 'total_pass_marks', 'display_order', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
