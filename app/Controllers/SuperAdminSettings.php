<?php
namespace App\Controllers;

use App\Models\SystemSettingModel;
use App\Models\SuperadminActivityLogModel;
use App\Models\AdminUserModel;

class SuperAdminSettings extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        if (!session()->get('is_superadmin')) {
            header('Location: ' . base_url('superadmin/login'));
            exit;
        }
        $this->checkSuperAdminPermission('settings');
    }

    public function index()
    {
        $settingModel = new SystemSettingModel();
        
        $data = [
            'title' => 'System Settings',
            'settings' => $settingModel->findAll()
        ];
        
        // Convert to key-value array for easy view usage
        $data['settings_kv'] = [];
        foreach ($data['settings'] as $s) {
            $data['settings_kv'][$s['setting_key']] = $s['setting_value'];
        }
        
        return view('super_admin/settings', $data);
    }
    
    public function save()
    {
        $settingModel = new SystemSettingModel();
        $logModel = new SuperadminActivityLogModel();
        
        $postData = $this->request->getPost();
        
        foreach ($postData as $key => $value) {
            // Update only if it exists in settings
            if ($settingModel->find($key)) {
                $settingModel->update($key, ['setting_value' => $value]);
            }
        }
        
        // Log action
        $adminModel = new AdminUserModel();
        $admin = $adminModel->where('email', session()->get('admin_email'))->first();
        if ($admin) {
            $logModel->logAction($admin['id'], 'Updated System Settings', json_encode($postData));
        }
        
        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
    
    public function logs()
    {
        $logModel = new SuperadminActivityLogModel();
        
        $data = [
            'title' => 'Activity Logs',
            'logs' => $logModel->select('superadmin_activity_logs.*, admin_users.email as admin_email')
                               ->join('admin_users', 'admin_users.id = superadmin_activity_logs.admin_id', 'left')
                               ->orderBy('created_at', 'DESC')
                               ->paginate(20),
            'pager' => $logModel->pager
        ];
        
        return view('super_admin/activity_logs', $data);
    }
}
