<?php
namespace App\Models;
use CodeIgniter\Model;

class PlacementPerformanceModel extends BaseModel
{
    protected $table = 'placement_performance';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'company_id', 'aptitude_score', 'technical_cleared', 'hr_cleared', 'remarks', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
