<?php

namespace App\Controllers;

use App\Models\HrEmployeeModel;
use App\Models\HrDepartmentModel;
use App\Models\HrDesignationModel;
use App\Models\HrEmploymentTypeModel;
use App\Models\OrgUserModel;

class OrgEmployees extends BaseController
{
    public function index()
    {
        $employeeModel = new HrEmployeeModel();
        $deptModel = new HrDepartmentModel();
        $desigModel = new HrDesignationModel();
        $typeModel = new HrEmploymentTypeModel();
        $userModel = new OrgUserModel();

        $org_id = session()->get('org_id');

        // Fetch employees with join
        $employees = $employeeModel->select('hr_employees.*, org_users.full_name, org_users.email, hr_departments.name as department_name, hr_designations.name as designation_name')
            ->join('org_users', 'org_users.id = hr_employees.org_user_id')
            ->join('hr_departments', 'hr_departments.id = hr_employees.department_id')
            ->join('hr_designations', 'hr_designations.id = hr_employees.designation_id')
            ->where('hr_employees.org_id', $org_id)
            ->findAll();

        $data = [
            'activeModule' => 'hr',
            'employees' => $employees,
            'departments' => $deptModel->where('org_id', $org_id)->findAll(),
            'designations' => $desigModel->where('org_id', $org_id)->findAll(),
            'employment_types' => $typeModel->where('org_id', $org_id)->findAll(),
            // Fetch users that are staff but not yet mapped in hr_employees
            'unmapped_users' => $userModel->where('org_id', $org_id)->where('role', 'faculty')->findAll() // Assuming faculty/staff
        ];

        return view('org/hr/employees/index', $data);
    }

    public function save()
    {
        $model = new HrEmployeeModel();
        $data = [
            'org_id' => session()->get('org_id'),
            'org_user_id' => $this->request->getPost('org_user_id'),
            'department_id' => $this->request->getPost('department_id'),
            'designation_id' => $this->request->getPost('designation_id'),
            'employment_type_id' => $this->request->getPost('employment_type_id'),
            'employee_code' => $this->request->getPost('employee_code'),
            'joining_date' => $this->request->getPost('joining_date'),
            'base_salary' => $this->request->getPost('base_salary') ?: 0,
            'bank_account' => $this->request->getPost('bank_account'),
            'bank_name' => $this->request->getPost('bank_name'),
            'ifsc_code' => $this->request->getPost('ifsc_code')
        ];
        
        $id = $this->request->getPost('id');
        if ($id) {
            $model->update($id, $data);
            $msg = 'Employee updated.';
        } else {
            $model->insert($data);
            $msg = 'Employee onboarded.';
        }
        
        return redirect()->to('org/hr/employees')->with('success', $msg);
    }

    public function delete($id)
    {
        $model = new HrEmployeeModel();
        $model->where('org_id', session()->get('org_id'))->delete($id);
        return redirect()->to('org/hr/employees')->with('success', 'Employee deleted.');
    }
}
