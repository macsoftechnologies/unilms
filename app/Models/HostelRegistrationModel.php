<?php
namespace App\Models;
use CodeIgniter\Model;

class HostelRegistrationModel extends BaseModel
{
    protected $table = 'hostel_registrations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'hostel_id', 'room_id', 'bed_no', 'joining_date', 'academic_year_id', 'emergency_contact', 'status', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
