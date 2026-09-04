<?php
namespace App\Models;
use CodeIgniter\Model;

class SuperadminActivityLogModel extends BaseModel
{
    protected $table = 'superadmin_activity_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['admin_id', 'action', 'details', 'ip_address', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    public function logAction($adminId, $action, $details = '')
    {
        $request = \Config\Services::request();
        $ip = $request->getIPAddress();
        
        return $this->insert([
            'admin_id' => $adminId,
            'action' => $action,
            'details' => $details,
            'ip_address' => $ip,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
