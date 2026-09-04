<?php
namespace App\Models;
use CodeIgniter\Model;

class ApplicationChecklistModel extends BaseModel
{
    protected $table = 'application_checklists';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'program_id', 'item_name', 'is_mandatory', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
