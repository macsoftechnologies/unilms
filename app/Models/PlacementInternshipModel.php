<?php
namespace App\Models;
use CodeIgniter\Model;

class PlacementInternshipModel extends BaseModel
{
    protected $table = 'placement_internships';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'company_id', 'role', 'start_date', 'end_date', 'stipend', 'status', 'certificate_submitted', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
