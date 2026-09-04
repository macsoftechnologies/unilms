<?php
namespace App\Models;
use CodeIgniter\Model;

class MessageTemplateModel extends BaseModel
{
    protected $table = 'message_templates';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'name', 'type', 'body_text', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
