<?php
namespace App\Models;
use CodeIgniter\Model;

class InappNotificationModel extends BaseModel
{
    protected $table = 'inapp_notifications';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'user_id', 'title', 'message', 'is_read', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
