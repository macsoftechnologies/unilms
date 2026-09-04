<?php
namespace App\Models;
use CodeIgniter\Model;

class StaffDutyModel extends BaseModel
{
    protected $table = 'staff_duties';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'staff_id', 'task_name', 'assigned_by', 'due_date', 'remarks', 'status', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
