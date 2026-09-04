<?php

namespace App\Controllers;

use App\Models\SalaryComponentModel;
use App\Models\SalaryStructureModel;
use App\Models\HrEmployeeModel;
use App\Models\HrDesignationModel;

class OrgPayroll extends BaseController
{
    public function index()
    {
        return redirect()->to('org/payroll/structures');
    }

    // Salary Components (Earnings & Deductions)
    public function components()
    {
        $orgId = session('org_id');
        $model = new SalaryComponentModel();

        $data = [
            'activeModule' => 'hr',
            'components' => $model->where('org_id', $orgId)->orderBy('type', 'ASC')->findAll()
        ];

        return view('org/hr/payroll/components', $data);
    }

    public function save_component()
    {
        $orgId = session('org_id');
        $model = new SalaryComponentModel();

        $id = $this->request->getPost('id');
        $data = [
            'org_id' => $orgId,
            'name' => trim($this->request->getPost('name')),
            'code' => strtoupper(trim($this->request->getPost('code'))),
            'type' => $this->request->getPost('type'), // Earning or Deduction
            'calculation_type' => $this->request->getPost('calculation_type'), // Fixed or Percentage
            'percentage_of' => $this->request->getPost('percentage_of') ?: null,
            'default_amount' => (float)$this->request->getPost('default_amount'),
            'is_taxable' => $this->request->getPost('is_taxable') ? 1 : 0
        ];

        if ($id) {
            $model->where('org_id', $orgId)->update($id, $data);
            return redirect()->to('org/payroll/components')->with('success', 'Salary component updated.');
        } else {
            $model->insert($data);
            return redirect()->to('org/payroll/components')->with('success', 'Salary component added.');
        }
    }

    public function delete_component($id)
    {
        $orgId = session('org_id');
        (new SalaryComponentModel())->where('org_id', $orgId)->delete($id);
        return redirect()->to('org/payroll/components')->with('success', 'Salary component removed.');
    }

    // Salary Structures
    public function structures()
    {
        $orgId = session('org_id');
        $db = \Config\Database::connect();

        $structures = $db->table('salary_structures ss')
            ->select('ss.*, hd.name as designation_name')
            ->join('hr_designations hd', 'hd.id = ss.designation_id', 'left')
            ->where('ss.org_id', $orgId)
            ->orderBy('ss.id', 'DESC')
            ->get()->getResultArray();

        $designations = (new HrDesignationModel())->where('org_id', $orgId)->findAll();
        $components = (new SalaryComponentModel())->where('org_id', $orgId)->findAll();

        $data = [
            'activeModule' => 'hr',
            'structures' => $structures,
            'designations' => $designations,
            'components' => $components
        ];

        return view('org/hr/payroll/structures', $data);
    }

    public function save_structure()
    {
        $orgId = session('org_id');
        $model = new SalaryStructureModel();

        $id = $this->request->getPost('id');
        $componentsJson = $this->request->getPost('components') ? json_encode($this->request->getPost('components')) : null;

        $data = [
            'org_id' => $orgId,
            'name' => trim($this->request->getPost('name')),
            'designation_id' => $this->request->getPost('designation_id') ?: null,
            'basic_salary' => (float)$this->request->getPost('basic_salary'),
            'components_json' => $componentsJson,
            'total_earnings' => (float)$this->request->getPost('total_earnings'),
            'total_deductions' => (float)$this->request->getPost('total_deductions'),
            'net_salary' => (float)$this->request->getPost('net_salary'),
            'status' => 'Active'
        ];

        if ($id) {
            $model->where('org_id', $orgId)->update($id, $data);
            return redirect()->to('org/payroll/structures')->with('success', 'Salary structure updated.');
        } else {
            $model->insert($data);
            return redirect()->to('org/payroll/structures')->with('success', 'Salary structure created.');
        }
    }

    public function delete_structure($id)
    {
        $orgId = session('org_id');
        (new SalaryStructureModel())->where('org_id', $orgId)->delete($id);
        return redirect()->to('org/payroll/structures')->with('success', 'Salary structure deleted.');
    }

    // Payslips Directory
    public function payslips()
    {
        $orgId = session('org_id');
        $db = \Config\Database::connect();

        $month = $this->request->getGet('month') ?: date('Y-m');

        $employees = $db->table('hr_employees he')
            ->select('he.*, u.full_name, u.email, hd.name as department_name, hdes.name as designation_name')
            ->join('org_users u', 'u.id = he.org_user_id', 'left')
            ->join('hr_departments hd', 'hd.id = he.department_id', 'left')
            ->join('hr_designations hdes', 'hdes.id = he.designation_id', 'left')
            ->where('he.org_id', $orgId)
            ->get()->getResultArray();

        $data = [
            'activeModule' => 'hr',
            'month' => $month,
            'employees' => $employees
        ];

        return view('org/hr/payroll/payslips', $data);
    }
}
