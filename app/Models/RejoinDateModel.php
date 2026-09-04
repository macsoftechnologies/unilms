<?php
namespace App\Models;
use CodeIgniter\Model;

class RejoinDateModel extends BaseModel
{
    protected $table = 'rejoin_dates';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'expected_rejoin_date', 'actual_rejoin_date', 'remarks', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
