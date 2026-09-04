<?php
namespace App\Models;
use CodeIgniter\Model;

class ScholarshipModel extends BaseModel
{
    protected $table = 'scholarships';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'scholarship_name', 'donor_name', 'amount', 'academic_year_id', 'status', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
