<?php
namespace App\Models;
use CodeIgniter\Model;

class FestivalGreetingModel extends BaseModel
{
    protected $table = 'festival_greetings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'festival_name', 'send_date', 'template_id', 'audience', 'status', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
