<?php
namespace App\Models;
use CodeIgniter\Model;

class AdmissionCycleModel extends BaseModel
{
    protected $table = 'admission_cycles';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'name', 'academic_year_id', 'application_start', 'application_end', 'status', 'is_default'];
    protected $useTimestamps = false;
}
