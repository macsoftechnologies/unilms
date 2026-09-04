<?php
namespace App\Controllers;

use App\Models\AdminUserModel;
use App\Models\SuperadminActivityLogModel;

class SuperAdminUsers extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        if (!session()->get('is_superadmin')) {
            header('Location: ' . base_url('superadmin/login'));
            exit;
        }
        if (session()->get('admin_is_root') != 1) {
            echo view('super_admin/layout', ['title' => 'Access Denied']) . '<div style="padding:40px; text-align:center;"><h3>Access Denied</h3><p>Only the Root Admin can manage users.</p></div>';
            exit;
        }
    }

    public function index()
    {
        $adminModel = new AdminUserModel();
        
        $data = [
            'title' => 'Super Admins',
            'admins' => $adminModel->orderBy('created_at', 'DESC')->findAll()
        ];
        
        return view('super_admin/users', $data);
    }
    
    public function save()
    {
        $adminModel = new AdminUserModel();
        $logModel = new SuperadminActivityLogModel();
        
        $id = $this->request->getPost('admin_id');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $permissions = $this->request->getPost('permissions') ?? [];
        
        $data = [
            'email' => $email,
            'permissions' => json_encode($permissions)
        ];
        
        if (!empty($password)) {
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }
        
        $currentAdmin = $adminModel->where('email', session()->get('admin_email'))->first();
        
        if (empty($id)) {
            // Check if email already exists
            if ($adminModel->where('email', $email)->first()) {
                return redirect()->back()->with('error', 'Email already in use.');
            }
            
            $data['created_at'] = date('Y-m-d H:i:s');
            $adminModel->insert($data);
            
            if ($currentAdmin) {
                $logModel->logAction($currentAdmin['id'], 'Created Super Admin', "Email: $email");
            }
            
            return redirect()->back()->with('success', 'Admin user created successfully.');
        } else {
            // Edit
            $adminModel->update($id, $data);
            
            if ($currentAdmin) {
                $logModel->logAction($currentAdmin['id'], 'Updated Super Admin', "Admin ID: $id");
            }
            
            // If they updated their own email, update session
            if ($currentAdmin && $id == $currentAdmin['id'] && $email !== $currentAdmin['email']) {
                session()->set('admin_email', $email);
            }
            
            return redirect()->back()->with('success', 'Admin user updated successfully.');
        }
    }
    
    public function delete()
    {
        $adminModel = new AdminUserModel();
        $logModel = new SuperadminActivityLogModel();
        
        $id = $this->request->getPost('admin_id');
        $currentAdmin = $adminModel->where('email', session()->get('admin_email'))->first();
        
        if ($currentAdmin && $id == $currentAdmin['id']) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }
        
        $adminModel->delete($id);
        
        if ($currentAdmin) {
            $logModel->logAction($currentAdmin['id'], 'Deleted Super Admin', "Admin ID: $id");
        }
        
        return redirect()->back()->with('success', 'Admin user deleted successfully.');
    }
}
