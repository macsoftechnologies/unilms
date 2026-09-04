<?php
namespace App\Models;
use CodeIgniter\Model;

class CohortModel extends BaseModel
{
    protected $table = 'cohorts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'program_id', 'academic_year_id', 'current_semester_id', 'name', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
