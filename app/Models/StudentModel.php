<?php
namespace App\Models;
use CodeIgniter\Model;

class StudentModel extends BaseModel
{
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'user_id', 'application_id', 'roll_number', 
        'first_name', 'last_name', 'parent_name', 'parent_phone', 
        'cohort_id', 'status'
    ];
    protected $useTimestamps = true;
}
