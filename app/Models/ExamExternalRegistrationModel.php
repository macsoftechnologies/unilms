<?php
namespace App\Models;
use CodeIgniter\Model;

class ExamExternalRegistrationModel extends BaseModel
{
    protected $table = 'exam_external_registrations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'exam_name', 'registration_no', 'fee_paid', 'registration_date', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
