<?php
namespace App\Models;
use CodeIgniter\Model;

class HostelOutingModel extends BaseModel
{
    protected $table = 'hostel_outings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'date_out', 'expected_return', 'actual_return', 'destination', 'purpose', 'approved_by', 'status', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
