<?php
namespace App\Models;
use CodeIgniter\Model;

class SystemSettingModel extends BaseModel
{
    protected $table = 'system_settings';
    protected $primaryKey = 'setting_key';
    protected $allowedFields = ['setting_key', 'setting_value', 'description'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
