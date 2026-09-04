<?php
namespace App\Models;
use CodeIgniter\Model;

class AcademicYearModel extends BaseModel
{
    protected $table = 'academic_years';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'name', 'start_date', 'end_date', 'status', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
