<?php
namespace App\Controllers;

use App\Models\OrgUserModel;
use App\Models\AccessGroupModel;
use App\Models\PermissionModel;
use App\Models\OrganizationModel;

class OrgSystems extends BaseController
{
    // =============================================
    // USERS MANAGEMENT
    // =============================================
    public function users()
    {
        $userModel = new OrgUserModel();
        $orgId = session()->get('org_id');
        $db = \Config\Database::connect();
        $type = $this->request->getGet('type') ?: 'all';
        
        $fields = $db->getFieldNames('org_users');
        if (in_array('created_by', $fields)) {
            $userModel->select('org_users.*, c.full_name as created_by_name, u.full_name as updated_by_name')
                      ->join('org_users c', 'c.id = org_users.created_by', 'left')
                      ->join('org_users u', 'u.id = org_users.updated_by', 'left');
        } else {
            $userModel->select('org_users.*');
        }

        $userModel->where('org_users.org_id', $orgId);

        if (!session()->get('is_org_admin')) {
            $userModel->where('org_users.is_org_admin', 0);
        }

        if ($type === 'staff') {
            $userModel->groupStart()
                ->where('org_users.user_type', 'staff')
                ->orWhere('org_users.user_type', null)
                ->orWhere('org_users.user_type', '')
            ->groupEnd();
        } elseif ($type === 'student') {
            $userModel->where('org_users.user_type', 'student');
        } elseif ($type === 'parent') {
            $userModel->where('org_users.user_type', 'parent');
        }
                  
        $data['users'] = $userModel->orderBy('org_users.id', 'ASC')->findAll();
        $data['current_type'] = $type;

        // Compute counts
        $data['counts'] = [
            'all'     => $db->table('org_users')->where('org_id', $orgId)->countAllResults(),
            'staff'   => $db->table('org_users')->where('org_id', $orgId)->groupStart()->where('user_type', 'staff')->orWhere('user_type', null)->orWhere('user_type', '')->groupEnd()->countAllResults(),
            'student' => $db->table('org_users')->where('org_id', $orgId)->where('user_type', 'student')->countAllResults(),
            'parent'  => $db->table('org_users')->where('org_id', $orgId)->where('user_type', 'parent')->countAllResults(),
        ];
        
        // Get access groups for the assignment dropdown
        $groupModel = new AccessGroupModel();
        $data['access_groups'] = $groupModel->where('org_id', $orgId)->findAll();
        
        // Get each user's assigned groups
        $db = \Config\Database::connect();
        $data['user_groups'] = [];
        foreach ($data['users'] as $user) {
            $groups = $db->table('org_user_groups oug')
                ->select('ag.name')
                ->join('access_groups ag', 'ag.id = oug.group_id')
                ->where('oug.user_id', $user['id'])
                ->get()->getResultArray();
            $data['user_groups'][$user['id']] = array_column($groups, 'name');
        }
        
        return view('org/systems/users', $data);
    }
    
    public function saveUser()
    {
        $userModel = new OrgUserModel();
        $orgId = session()->get('org_id');
        $userId = $this->request->getPost('user_id');
        $email = trim((string)$this->request->getPost('email'));
        $employeeCode = trim((string)$this->request->getPost('employee_code'));

        if (empty($email)) {
            return redirect()->back()->withInput()->with('error', 'Email address is required.');
        }

        // 1. Check duplicate email in this organization
        $existingEmail = $userModel->where('org_id', $orgId)->where('email', $email);
        if (!empty($userId)) {
            $existingEmail->where('id !=', $userId);
        }
        if ($existingEmail->first()) {
            return redirect()->back()->withInput()->with('error', "The email '{$email}' is already in use by another user in your organization.");
        }

        // 2. Generate or validate unique employee code
        if (empty($employeeCode) && empty($userId)) {
            $isAdmin = $this->request->getPost('is_org_admin') ? 1 : 0;
            $prefix = $isAdmin ? 'ADM-' : 'EMP-';
            $employeeCode = $prefix . date('y') . '-' . str_pad((string)($userModel->where('org_id', $orgId)->countAllResults() + 1), 3, '0', STR_PAD_LEFT);
        }

        if (!empty($employeeCode)) {
            $existingCode = $userModel->where('org_id', $orgId)->where('employee_code', $employeeCode);
            if (!empty($userId)) {
                $existingCode->where('id !=', $userId);
            }
            if ($existingCode->first()) {
                return redirect()->back()->withInput()->with('error', "The Employee ID / Roll Number '{$employeeCode}' is already assigned to another user in this organization.");
            }
        }
        
        $userData = [
            'org_id' => $orgId,
            'email' => $email,
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone'),
            'designation' => $this->request->getPost('designation'),
            'user_type' => 'staff',
            'role' => $this->request->getPost('role') ?: 'faculty',
        ];

        if (!empty($employeeCode)) {
            $userData['employee_code'] = $employeeCode;
        }
        
        if (session()->get('is_org_admin')) {
            $userData['is_org_admin'] = $this->request->getPost('is_org_admin') ? 1 : 0;
        } elseif (empty($userId)) {
            $userData['is_org_admin'] = 0; // force 0 for new users created by non-admins
        }
        
        if (empty($userId)) {
            // New user
            $userData['password_hash'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            $userModel->insert($userData);
            $userId = $userModel->getInsertID();
        } else {
            // Edit user
            $userModel->update($userId, $userData);
            if (!empty($this->request->getPost('password'))) {
                $userModel->update($userId, [
                    'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
                ]);
            }
        }
        
        // Assign access groups
        $db = \Config\Database::connect();
        $db->table('org_user_groups')->where('user_id', $userId)->delete();
        
        $groupIds = $this->request->getPost('group_ids') ?? [];
        foreach ($groupIds as $groupId) {
            $db->table('org_user_groups')->insert([
                'user_id' => $userId,
                'group_id' => $groupId,
            ]);
        }
        
        // Clear cached permissions for this user
        if (session()->get('org_user_id') == $userId) {
            session()->remove('user_permissions');
        }
        
        return redirect()->to(base_url('org/systems/users'))->with('success', 'User saved successfully.');
    }
    
    public function deleteUser()
    {
        $userModel = new OrgUserModel();
        $userId = $this->request->getPost('user_id');
        
        // Prevent deleting yourself
        if ($userId == session()->get('org_user_id')) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }
        
        $userModel->delete($userId);
        return redirect()->back()->with('success', 'User deleted successfully.');
    }
    
    // =============================================
    // ACCESS GROUPS MANAGEMENT
    // =============================================
    public function accessGroups()
    {
        $groupModel = new AccessGroupModel();
        $orgId = session()->get('org_id');
        $db = \Config\Database::connect();
        
        $fields = $db->getFieldNames('access_groups');
        if (in_array('created_by', $fields)) {
            $data['groups'] = $groupModel->select('access_groups.*, c.full_name as created_by_name, u.full_name as updated_by_name')
                ->join('org_users c', 'c.id = access_groups.created_by', 'left')
                ->join('org_users u', 'u.id = access_groups.updated_by', 'left')
                ->where('access_groups.org_id', $orgId)
                ->findAll();
        } else {
            $data['groups'] = $groupModel->where('access_groups.org_id', $orgId)->findAll();
        }
        
        // Count permissions and users per group
        $db = \Config\Database::connect();
        $data['group_stats'] = [];
        foreach ($data['groups'] as $group) {
            $permCount = $db->table('access_group_permissions')->where('group_id', $group['id'])->countAllResults();
            $userCount = $db->table('org_user_groups')->where('group_id', $group['id'])->countAllResults();
            $data['group_stats'][$group['id']] = ['perms' => $permCount, 'users' => $userCount];
        }
        
        return view('org/systems/access_groups', $data);
    }
    
    public function createGroup()
    {
        $permModel = new PermissionModel();
        $orgModel = new OrganizationModel();
        
        $org = $orgModel->find(session()->get('org_id'));
        $data['permissions'] = $permModel->getPermissionsForOrg($org);
        $data['group'] = null;
        $data['assigned_perms'] = [];
        
        $groupModel = new AccessGroupModel();
        $all_groups = $groupModel->where('org_id', session()->get('org_id'))->findAll();
        
        $group_permissions_map = [];
        $db = \Config\Database::connect();
        if (!empty($all_groups)) {
            $group_ids = array_column($all_groups, 'id');
            $perms = $db->table('access_group_permissions')->whereIn('group_id', $group_ids)->get()->getResultArray();
            foreach ($perms as $p) {
                $group_permissions_map[$p['group_id']][] = $p['permission_id'];
            }
        }
        $data['all_groups'] = $all_groups;
        $data['group_permissions_map'] = json_encode($group_permissions_map);
        
        return view('org/systems/access_group_form', $data);
    }
    
    public function editGroup($id)
    {
        $groupModel = new AccessGroupModel();
        $permModel = new PermissionModel();
        $orgModel = new OrganizationModel();
        
        $group = $groupModel->findByIdOrUuid($id);
        if (!$group || $group['org_id'] != session()->get('org_id')) {
            return redirect()->to(base_url('org/systems/access-groups'))->with('error', 'Access Group not found.');
        }
        
        $org = $orgModel->find(session()->get('org_id'));
        $data['permissions'] = $permModel->getPermissionsForOrg($org);
        $data['group'] = $group;
        
        // Get assigned permission IDs
        $db = \Config\Database::connect();
        $assigned = $db->table('access_group_permissions')
            ->select('permission_id')
            ->where('group_id', $group['id'])
            ->get()->getResultArray();
        $data['assigned_perms'] = array_column($assigned, 'permission_id');
        
        return view('org/systems/access_group_form', $data);
    }
    
    public function saveGroup()
    {
        $groupModel = new AccessGroupModel();
        $orgId = session()->get('org_id');
        $groupParam = $this->request->getPost('group_id');
        
        $groupData = [
            'org_id' => $orgId,
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ];
        
        if (empty($groupParam)) {
            $groupModel->insert($groupData);
            $groupId = $groupModel->getInsertID();
        } else {
            $existing = $groupModel->findByIdOrUuid($groupParam);
            $groupId = $existing ? $existing['id'] : $groupParam;
            $groupModel->update($groupId, $groupData);
        }
        
        // Sync permissions
        $db = \Config\Database::connect();
        $db->table('access_group_permissions')->where('group_id', $groupId)->delete();
        
        $permIds = $this->request->getPost('permission_ids') ?? [];
        foreach ($permIds as $permId) {
            $db->table('access_group_permissions')->insert([
                'org_id' => $orgId,
                'group_id' => $groupId,
                'permission_id' => $permId,
            ]);
        }
        
        // Clear cached permissions for all users in this group
        session()->remove('user_permissions');
        
        return redirect()->to(base_url('org/systems/access-groups'))->with('success', 'Access Group saved with ' . count($permIds) . ' permissions.');
    }
    
    public function deleteGroup()
    {
        $groupModel = new AccessGroupModel();
        $groupId = $this->request->getPost('group_id');
        $existing = $groupModel->findByIdOrUuid($groupId);
        if ($existing) {
            $groupModel->delete($existing['id']);
        }
        return redirect()->back()->with('success', 'Access Group deleted.');
    }
    
    // =============================================
    // GLOBAL SETTINGS
    // =============================================
    public function settings()
    {
        if (!session()->get('is_org_admin')) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
        
        $orgId = session()->get('org_id');
        $orgModel = new OrganizationModel();
        
        $data['org'] = $orgModel->find($orgId);
        
        $db = \Config\Database::connect();
        $settings = $db->table('org_settings')->where('org_id', $orgId)->get()->getResultArray();
        
        $data['settings'] = [];
        foreach ($settings as $setting) {
            $data['settings'][$setting['setting_key']] = $setting['setting_value'];
        }
        
        return view('org/systems/settings', $data);
    }
    
    public function saveSettings()
    {
        if (!session()->get('is_org_admin')) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
        
        $orgId = session()->get('org_id');
        $orgModel = new OrganizationModel();
        $db = \Config\Database::connect();
        
        // Update org table (name and email)
        $orgModel->update($orgId, [
            'name' => $this->request->getPost('name'),
            'admin_email' => $this->request->getPost('admin_email'),
        ]);
        
        // Settings to upsert
        $keysToSave = ['org_phone', 'org_address'];
        
        foreach ($keysToSave as $key) {
            $val = $this->request->getPost($key);
            
            // Check if exists
            $exists = $db->table('org_settings')
                         ->where('org_id', $orgId)
                         ->where('setting_key', $key)
                         ->countAllResults() > 0;
                         
            if ($exists) {
                $db->table('org_settings')
                   ->where('org_id', $orgId)
                   ->where('setting_key', $key)
                   ->update(['setting_value' => $val]);
            } else {
                $db->table('org_settings')->insert([
                    'org_id' => $orgId,
                    'setting_key' => $key,
                    'setting_value' => $val,
                    'created_by' => session()->get('org_user_id')
                ]);
            }
        }
        
        return redirect()->back()->with('success', 'Organization Profile updated successfully.');
    }
}
