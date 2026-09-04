<?php

namespace App\Controllers;

use App\Models\HrLeaveRequestModel;
use App\Models\HrLeavePolicyModel;
use App\Models\HrEmployeeModel;

class OrgLeaves extends BaseController
{
    public function index()
    {
        $leaveModel = new HrLeaveRequestModel();
        $policyModel = new HrLeavePolicyModel();
        $empModel = new HrEmployeeModel();

        $org_id = session()->get('org_id');
        $user_id = session()->get('org_user_id');
        $is_admin = session()->get('is_org_admin');

        // Get current employee record
        $current_employee = $empModel->where('org_id', $org_id)->where('org_user_id', $user_id)->first();

        $query = $leaveModel->select('hr_leave_requests.*, hr_leave_policies.leave_type, org_users.full_name')
            ->join('hr_leave_policies', 'hr_leave_policies.id = hr_leave_requests.leave_policy_id')
            ->join('hr_employees', 'hr_employees.id = hr_leave_requests.employee_id')
            ->join('org_users', 'org_users.id = hr_employees.org_user_id')
            ->where('hr_leave_requests.org_id', $org_id);

        if (!$is_admin && $current_employee) {
            $query->where('hr_leave_requests.employee_id', $current_employee['id']);
        }

        $leaves = $query->orderBy('created_at', 'DESC')->findAll();

        $policies = [];
        if ($current_employee) {
            $policies = $policyModel->where('org_id', $org_id)
                ->where('employment_type_id', $current_employee['employment_type_id'])
                ->findAll();
        }

        $data = [
            'activeModule' => 'hr',
            'leaves' => $leaves,
            'policies' => $policies,
            'current_employee' => $current_employee,
            'is_admin' => $is_admin
        ];

        return view('org/hr/leaves/index', $data);
    }

    public function apply()
    {
        $model = new HrLeaveRequestModel();
        $empModel = new HrEmployeeModel();
        
        $org_id = session()->get('org_id');
        $user_id = session()->get('org_user_id');
        $current_employee = $empModel->where('org_id', $org_id)->where('org_user_id', $user_id)->first();

        if (!$current_employee) {
            return redirect()->to('org/hr/leaves')->with('error', 'You are not mapped as an employee yet.');
        }

        $data = [
            'org_id' => $org_id,
            'employee_id' => $current_employee['id'],
            'leave_policy_id' => $this->request->getPost('leave_policy_id'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'reason' => $this->request->getPost('reason'),
            'status' => 'Pending'
        ];
        
        $model->insert($data);
        return redirect()->to('org/hr/leaves')->with('success', 'Leave application submitted.');
    }

    public function updateStatus($id)
    {
        if (!session()->get('is_org_admin')) {
            return redirect()->to('org/hr/leaves')->with('error', 'Unauthorized.');
        }

        $model = new HrLeaveRequestModel();
        
        $data = [
            'status' => $this->request->getPost('status'),
            'hr_remarks' => $this->request->getPost('hr_remarks')
        ];
        
        $model->where('org_id', session()->get('org_id'))->update($id, $data);
        return redirect()->to('org/hr/leaves')->with('success', 'Leave status updated.');
    }
}
