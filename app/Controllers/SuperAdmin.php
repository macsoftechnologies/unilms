<?php
namespace App\Controllers;

use App\Models\OrganizationModel;
use App\Models\PaymentModel;
use App\Models\PlanModel;

class SuperAdmin extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        // Ensure user is logged in
        if (!session()->get('is_superadmin')) {
            header('Location: ' . base_url('superadmin/login'));
            exit;
        }
    }

    public function index()
    {
        $orgModel = new OrganizationModel();
        $paymentModel = new PaymentModel();
        
        $data = [
            'total_orgs' => $orgModel->countAll(),
            'active_orgs' => $orgModel->where('status', 'active')->countAllResults(),
            'total_revenue' => $paymentModel->selectSum('amount')->first()['amount'] ?? 0,
            'recent_orgs' => $orgModel->select('organizations.*, plans.name as plan_name')
                                      ->join('plans', 'plans.id = organizations.plan_id')
                                      ->orderBy('organizations.created_at', 'DESC')
                                      ->limit(5)
                                      ->find()
        ];
        
        return view('super_admin/dashboard', $data);
    }

    public function organizations()
    {
        $this->checkSuperAdminPermission('organizations');
        $orgModel = new OrganizationModel();
        $planModel = new PlanModel();

        $data = [
            'organizations' => $orgModel->select('organizations.*, plans.name as plan_name')
                                        ->join('plans', 'plans.id = organizations.plan_id')
                                        ->orderBy('organizations.created_at', 'DESC')
                                        ->paginate(10),
            'pager' => $orgModel->pager,
            'plans' => $planModel->findAll()
        ];
        
        return view('super_admin/organizations', $data);
    }
    
    public function expiring()
    {
        $orgModel = new OrganizationModel();
        
        // Find orgs expiring within 20 days
        $targetDate = date('Y-m-d', strtotime('+20 days'));
        
        $data = [
            'expiring_orgs' => $orgModel->select('organizations.*, plans.name as plan_name')
                                        ->join('plans', 'plans.id = organizations.plan_id')
                                        ->where('subscription_end_date <=', $targetDate)
                                        ->where('subscription_end_date >=', date('Y-m-d'))
                                        ->orderBy('subscription_end_date', 'ASC')
                                        ->paginate(10),
            'pager' => $orgModel->pager
        ];
        
        return view('super_admin/expiring', $data);
    }

    public function plans()
    {
        $this->checkSuperAdminPermission('plans');
        $planModel = new PlanModel();
        
        $data = [
            'plans' => $planModel->findAll()
        ];
        
        return view('super_admin/plans', $data);
    }

    public function payments()
    {
        $this->checkSuperAdminPermission('payments');
        $paymentModel = new PaymentModel();
        
        $data = [
            'payments' => $paymentModel->select('payments.*, organizations.name as org_name')
                                       ->join('organizations', 'organizations.id = payments.org_id')
                                       ->orderBy('payments.payment_date', 'DESC')
                                       ->paginate(15),
            'pager' => $paymentModel->pager
        ];
        
        return view('super_admin/payments', $data);
    }
    
    public function create_org_view()
    {
        $planModel = new PlanModel();
        $data['plans'] = $planModel->findAll();
        $data['org'] = null; // null means it's a new org
        return view('super_admin/org_form', $data);
    }
    
    public function view_organization($id)
    {
        if (!is_uuid($id)) {
            return redirect()->to('/superadmin/organizations')->with('error', 'Invalid organization identifier.');
        }

        $orgModel = new OrganizationModel();
        $orgUserModel = new \App\Models\OrgUserModel();
        
        $data['org'] = $orgModel->select('organizations.*, plans.name as plan_name')
            ->join('plans', 'plans.id = organizations.plan_id', 'left')
            ->where('organizations.uuid', $id)
            ->first();
        
        if (!$data['org']) {
            return redirect()->to('/superadmin/organizations')->with('error', 'Organization not found.');
        }
        
        $orgId = $data['org']['id'];
        $data['users'] = $orgUserModel->where('org_id', $orgId)->orderBy('created_at', 'DESC')->findAll();
        
        return view('super_admin/view_organization', $data);
    }
    
    public function edit_org_view($id)
    {
        if (!is_uuid($id)) {
            return redirect()->to('/superadmin/organizations')->with('error', 'Invalid organization identifier.');
        }

        $orgModel = new OrganizationModel();
        $planModel = new PlanModel();
        
        $data['plans'] = $planModel->findAll();
        $data['org'] = $orgModel->findByUuid($id);
        
        if (!$data['org']) {
            return redirect()->to('/superadmin/organizations')->with('error', 'Organization not found.');
        }
        
        return view('super_admin/org_form', $data);
    }
    
    public function save_organization()
    {
        $orgModel = new OrganizationModel();
        $planModel = new PlanModel();
        $paymentModel = new PaymentModel();
        $orgUserModel = new \App\Models\OrgUserModel();
        
        $orgId = $this->request->getPost('org_id'); // If this is set, it's an edit
        
        $planId = $this->request->getPost('plan_id');
        $plan = $planModel->find($planId);
        
        $orgCode = strtoupper(trim((string)$this->request->getPost('code')));
        if (empty($orgCode)) {
            $words = preg_split('/\s+/', trim((string)$this->request->getPost('name')));
            $orgCode = '';
            foreach ($words as $w) {
                if (!empty($w)) $orgCode .= strtoupper($w[0]);
            }
            $orgCode = substr($orgCode ?: 'ORG', 0, 5);
        }

        // 1. Enforce Institution Code Uniqueness
        $codeCheck = $orgModel->where('code', $orgCode);
        if (!empty($orgId)) {
            $codeCheck->where('id !=', $orgId);
        }
        if ($codeCheck->first()) {
            return redirect()->back()->withInput()->with('error', "Institution Code '{$orgCode}' is already in use by another organization. Please provide a unique code (e.g. {$orgCode}-2 or {$orgCode}-BLR).");
        }

        // 2. Enforce Admin Email Uniqueness
        $adminEmail = trim((string)$this->request->getPost('admin_email'));
        $emailCheck = $orgUserModel->where('email', $adminEmail);
        if (!empty($orgId)) {
            $emailCheck->where('org_id !=', $orgId);
        }
        if ($emailCheck->first()) {
            return redirect()->back()->withInput()->with('error', "Admin Email '{$adminEmail}' is already registered with another organization.");
        }

        $orgData = [
            'name' => $this->request->getPost('name'),
            'code' => $orgCode,
            'admin_email' => $adminEmail,
            'plan_id' => $planId,
            'cms_enabled' => $this->request->getPost('cms_enabled') ? 1 : 0,
            'lms_enabled' => $this->request->getPost('lms_enabled') ? 1 : 0,
        ];
        
        if (empty($orgId)) {
            // Creation Mode
            $durationMonths = $plan['duration_months'] ?? 1;
            $orgData['subscription_end_date'] = date('Y-m-d', strtotime("+$durationMonths months"));
            
            $orgModel->insert($orgData);
            $newOrgId = $orgModel->getInsertID();
            
            $adminEmpCode = $orgCode . '-ADM-1001';

            // Create Admin User with Unique Prefix Employee ID
            $orgUserModel->insert([
                'org_id' => $newOrgId,
                'employee_code' => $adminEmpCode,
                'email' => $this->request->getPost('admin_email'),
                'full_name' => $this->request->getPost('name') . ' Administrator',
                'password_hash' => password_hash($this->request->getPost('admin_password'), PASSWORD_DEFAULT),
                'role' => 'admin',
                'is_org_admin' => 1
            ]);

            // Auto-Seed 9 Standard University Access Groups with Permissions for this Organization
            \App\Libraries\RbacService::seedDefaultAccessGroupsForOrg($newOrgId);
            
            // Log Payment Confirmation
            if ($this->request->getPost('confirm_payment')) {
                $paymentModel->insert([
                    'invoice_id' => 'INV-' . date('Y') . '-' . rand(1000, 9999),
                    'org_id' => $newOrgId,
                    'amount' => $plan['price'],
                    'payment_date' => date('Y-m-d')
                ]);
            }
            
            return redirect()->to('/superadmin/organizations')->with('success', 'Organization created! Admin Login ID: ' . $adminEmpCode);
            
        } else {
            // Edit Mode
            $orgModel->update($orgId, $orgData);
            
            // Update Admin Email in org_users if changed
            $orgUser = $orgUserModel->where('org_id', $orgId)->first();
            if ($orgUser && $orgUser['email'] !== $orgData['admin_email']) {
                $orgUserModel->update($orgUser['id'], ['email' => $orgData['admin_email']]);
            }
            
            // Update Password if provided (optional on edit)
            if (!empty($this->request->getPost('admin_password'))) {
                $orgUserModel->update($orgUser['id'], [
                    'password_hash' => password_hash($this->request->getPost('admin_password'), PASSWORD_DEFAULT)
                ]);
            }
            
            return redirect()->to('/superadmin/organizations')->with('success', 'Organization updated successfully.');
        }
    }
    
    public function toggle_status()
    {
        $orgModel = new OrganizationModel();
        $orgId = $this->request->getPost('org_id');
        $status = $this->request->getPost('status');
        
        if (in_array($status, ['active', 'suspended'])) {
            $orgModel->update($orgId, ['status' => $status]);
            return $this->response->setJSON(['success' => true]);
        }
        return $this->response->setJSON(['success' => false]);
    }
    
    public function renew_organization()
    {
        $orgModel = new OrganizationModel();
        $paymentModel = new PaymentModel();
        $planModel = new PlanModel();
        
        $orgId = $this->request->getPost('org_id');
        $planId = $this->request->getPost('plan_id'); // Capture selected plan
        $amount = $this->request->getPost('amount');
        
        $paymentModel->insert([
            'invoice_id' => 'INV-' . date('Y') . '-' . rand(1000, 9999),
            'org_id' => $orgId,
            'amount' => $amount,
            'payment_date' => date('Y-m-d')
        ]);
        
        $org = $orgModel->find($orgId);
        
        if (!empty($planId)) {
            $plan = $planModel->find($planId);
            $orgModel->update($orgId, ['plan_id' => $planId]);
        } else {
            $plan = $planModel->find($org['plan_id']);
        }
        
        $durationMonths = $plan['duration_months'] ?? 1;
        
        $newEndDate = date('Y-m-d', strtotime($org['subscription_end_date'] . " +$durationMonths months"));
        $orgModel->update($orgId, ['subscription_end_date' => $newEndDate]);
        
        return redirect()->back()->with('success', 'Payment recorded and subscription renewed.');
    }
    
    public function toggle_module()
    {
        $orgModel = new OrganizationModel();
        $orgId = $this->request->getPost('org_id');
        $module = $this->request->getPost('module'); // 'cms_enabled' or 'lms_enabled'
        $status = $this->request->getPost('status') == '1' ? 1 : 0;
        
        if (in_array($module, ['cms_enabled', 'lms_enabled'])) {
            $orgModel->update($orgId, [$module => $status]);
        }
        
        return $this->response->setJSON(['success' => true]);
    }
    
    public function add_plan()
    {
        $planModel = new PlanModel();
        
        $planModel->insert([
            'name' => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
            'features' => $this->request->getPost('features'),
            'duration_months' => $this->request->getPost('duration_months') ?? 1
        ]);
        
        return redirect()->back()->with('success', 'Plan created successfully.');
    }
    
    public function edit_plan()
    {
        $planModel = new PlanModel();
        $planId = $this->request->getPost('plan_id');
        
        $planModel->update($planId, [
            'name' => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
            'features' => $this->request->getPost('features'),
            'duration_months' => $this->request->getPost('duration_months') ?? 1
        ]);
        
        return redirect()->back()->with('success', 'Plan updated successfully.');
    }
    
    public function delete_plan()
    {
        $planModel = new PlanModel();
        $orgModel = new OrganizationModel();
        $planId = $this->request->getPost('plan_id');
        
        // Prevent deletion if orgs are using it
        $count = $orgModel->where('plan_id', $planId)->countAllResults();
        if ($count > 0) {
            return redirect()->back()->with('error', 'Cannot delete this plan because it is currently assigned to one or more organizations.');
        }
        
        $planModel->delete($planId);
        return redirect()->back()->with('success', 'Plan deleted successfully.');
    }
    public function export_organizations()
    {
        $orgModel = new OrganizationModel();
        $orgs = $orgModel->select('organizations.*, plans.name as plan_name')
                         ->join('plans', 'plans.id = organizations.plan_id', 'left')
                         ->findAll();
                         
        $filename = 'organizations_export_' . date('Ymd') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Name', 'Plan', 'Status', 'Admin Email', 'Subscription End Date', 'Created At']);
        
        foreach ($orgs as $org) {
            fputcsv($output, [
                $org['id'],
                $org['name'],
                $org['plan_name'] ?? 'N/A',
                $org['status'],
                $org['admin_email'],
                $org['subscription_end_date'],
                $org['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    public function export_payments()
    {
        $paymentModel = new PaymentModel();
        $payments = $paymentModel->select('payments.*, organizations.name as org_name')
                                 ->join('organizations', 'organizations.id = payments.org_id', 'left')
                                 ->findAll();
                                 
        $filename = 'payments_export_' . date('Ymd') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Payment ID', 'Invoice ID', 'Organization', 'Amount', 'Payment Date']);
        
        foreach ($payments as $payment) {
            fputcsv($output, [
                $payment['id'],
                $payment['invoice_id'],
                $payment['org_name'] ?? 'Unknown',
                $payment['amount'],
                $payment['payment_date']
            ]);
        }
        
        fclose($output);
        exit;
    }
}
