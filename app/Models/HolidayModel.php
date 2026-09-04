<?php
namespace App\Models;
use CodeIgniter\Model;

class HolidayModel extends BaseModel
{
    protected $table = 'holidays';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'title', 'start_date', 'end_date', 'type', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
