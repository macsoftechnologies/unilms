<?php
namespace App\Models;
use CodeIgniter\Model;

class HostelRequestModel extends BaseModel
{
    protected $table = 'hostel_requests';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'preferred_room_type', 'joining_date', 'status', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
