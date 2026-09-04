<?php
namespace App\Models;
use CodeIgniter\Model;

class ExamInvigilationModel extends BaseModel
{
    protected $table = 'exam_invigilation';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'exam_schedule_id', 'invigilator_id', 'charge_per_session', 'total_charge', 'paid', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
