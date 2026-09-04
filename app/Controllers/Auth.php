<?php
namespace App\Controllers;

use App\Models\AdminUserModel;

class Auth extends BaseController
{
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('is_superadmin')) {
            return redirect()->to('/superadmin');
        }
        
        return view('super_admin/login');
    }
    
    public function authenticate()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $adminModel = new AdminUserModel();
        $admin = $adminModel->where('email', $email)->first();
        
        if ($admin && password_verify($password, $admin['password_hash'])) {
            session()->set('is_superadmin', true);
            session()->set('admin_email', $admin['email']);
            session()->set('admin_is_root', $admin['is_root'] ?? 0);
            
            $permissions = [];
            if (!empty($admin['permissions'])) {
                $permissions = json_decode($admin['permissions'], true) ?? [];
            }
            session()->set('admin_permissions', $permissions);
            return redirect()->to('/superadmin');
        } else {
            return redirect()->back()->with('error', 'Invalid email or password.');
        }
    }
    
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/superadmin/login');
    }
}
