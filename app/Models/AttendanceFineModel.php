<?php
namespace App\Models;
use CodeIgniter\Model;

class AttendanceFineModel extends BaseModel
{
    protected $table = 'attendance_fines';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'date', 'period_no', 'fine_amount', 'status', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
