<?php
namespace App\Models;
use CodeIgniter\Model;

class DiaryModel extends BaseModel
{
    protected $table = 'diary';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'title', 'content', 'target_audience', 'publish_date', 'expiry_date', 'created_by', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
