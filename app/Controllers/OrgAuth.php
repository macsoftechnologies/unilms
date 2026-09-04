<?php
namespace App\Controllers;

use App\Models\OrgUserModel;
use App\Models\OrganizationModel;

class OrgAuth extends BaseController
{
    public function login()
    {
        if (session()->get('org_user_id')) {
            return redirect()->to(base_url('org/dashboard'));
        }
        return view('org/login');
    }
    
    public function authenticate()
    {
        $employeeCode = trim((string)($this->request->getPost('employee_code') ?: $this->request->getPost('login_id')));
        $password = $this->request->getPost('password');
        
        if (empty($employeeCode) || empty($password)) {
            return redirect()->back()->with('error', 'Please enter your Employee ID and password.');
        }

        $userModel = new OrgUserModel();
        $orgModel = new OrganizationModel();
        $db = \Config\Database::connect();
        
        // 1. Direct match on org_users.employee_code or email
        $user = $userModel->groupStart()
            ->where('employee_code', $employeeCode)
            ->orWhere('email', $employeeCode)
            ->groupEnd()
            ->first();

        // 2. Fallback match on hr_employees.employee_code linked to org_users
        if (!$user) {
            $emp = $db->table('hr_employees')
                ->where('employee_code', $employeeCode)
                ->get()
                ->getRowArray();
            if ($emp && !empty($emp['user_id'])) {
                $user = $userModel->find($emp['user_id']);
            }
        }
        
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return redirect()->back()->with('error', 'Invalid Employee ID or password.');
        }
        
        $org = $orgModel->find($user['org_id']);
        
        if (!$org) {
            return redirect()->back()->with('error', 'Organization not found.');
        }
        
        if ($org['status'] !== 'active') {
            return redirect()->back()->with('error', 'Your organization has been suspended. Contact the platform admin.');
        }
        
        if (strtotime($org['subscription_end_date']) < time()) {
            return redirect()->back()->with('error', 'Your organization subscription has expired. Contact the platform admin.');
        }
        
        // Strictly block student accounts from Staff / Organization Portal
        if ($user['user_type'] === 'student' || ($user['role'] ?? '') === 'student') {
            return redirect()->back()->with('error', 'Access denied. Student accounts cannot log in to the Staff Portal. Please log in via the Student LMS Portal.');
        }

        // Set session for Staff / Admin
        session()->set([
            'org_user_id'   => $user['id'],
            'org_id'        => $user['org_id'],
            'org_user_email'=> $user['email'],
            'org_user_name' => $user['full_name'] ?? $user['email'],
            'is_org_admin'  => (bool)$user['is_org_admin'],
            'org_name'      => $org['name'],
            'cms_enabled'   => (bool)$org['cms_enabled'],
            'lms_enabled'   => (bool)$org['lms_enabled'],
        ]);
        
        // Pre-load permissions for non-admin users
        if (!$user['is_org_admin']) {
            $permModel = new \App\Models\PermissionModel();
            $permissions = $permModel->getUserPermissionKeys($user['id']);
            session()->set('user_permissions', $permissions);
        }
        
        return redirect()->to(base_url('org/dashboard'));
    }
    
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('org/login'));
    }
}
