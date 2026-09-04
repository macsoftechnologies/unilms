<?php
namespace App\Models;
use CodeIgniter\Model;

class StaffChecklistModel extends BaseModel
{
    protected $table = 'staff_checklists';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'staff_id', 'checklist_type', 'item_name', 'is_completed', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
