<?php

namespace App\Controllers;

use App\Models\HrDepartmentModel;
use App\Models\HrDesignationModel;
use App\Models\HrEmploymentTypeModel;
use App\Models\HrLeavePolicyModel;

class OrgHrSettings extends BaseController
{
    public function index()
    {
        $deptModel = new HrDepartmentModel();
        $desigModel = new HrDesignationModel();
        $empTypeModel = new HrEmploymentTypeModel();
        $leavePolicyModel = new HrLeavePolicyModel();

        $org_id = session()->get('org_id');

        $data = [
            'activeModule' => 'hr',
            'departments' => $deptModel->where('org_id', $org_id)->findAll(),
            'designations' => $desigModel->where('org_id', $org_id)->findAll(),
            'employment_types' => $empTypeModel->where('org_id', $org_id)->findAll(),
            'leave_policies' => $leavePolicyModel->where('org_id', $org_id)->findAll()
        ];

        return view('org/hr/settings/index', $data);
    }

    public function saveDepartment()
    {
        $model = new HrDepartmentModel();
        $data = [
            'org_id' => session()->get('org_id'),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description')
        ];
        
        $id = $this->request->getPost('id');
        if ($id) {
            $model->update($id, $data);
        } else {
            $model->insert($data);
        }
        
        return redirect()->to('org/hr/settings')->with('success', 'Department saved.');
    }

    public function deleteDepartment($id)
    {
        $model = new HrDepartmentModel();
        $model->where('org_id', session()->get('org_id'))->delete($id);
        return redirect()->to('org/hr/settings')->with('success', 'Department deleted.');
    }

    public function saveDesignation()
    {
        $model = new HrDesignationModel();
        $data = [
            'org_id' => session()->get('org_id'),
            'name' => $this->request->getPost('name')
        ];
        
        $id = $this->request->getPost('id');
        if ($id) {
            $model->update($id, $data);
        } else {
            $model->insert($data);
        }
        
        return redirect()->to('org/hr/settings')->with('success', 'Designation saved.');
    }

    public function deleteDesignation($id)
    {
        $model = new HrDesignationModel();
        $model->where('org_id', session()->get('org_id'))->delete($id);
        return redirect()->to('org/hr/settings')->with('success', 'Designation deleted.');
    }

    public function saveEmploymentType()
    {
        $model = new HrEmploymentTypeModel();
        $data = [
            'org_id' => session()->get('org_id'),
            'name' => $this->request->getPost('name')
        ];
        
        $id = $this->request->getPost('id');
        if ($id) {
            $model->update($id, $data);
        } else {
            $model->insert($data);
        }
        
        return redirect()->to('org/hr/settings')->with('success', 'Employment type saved.');
    }

    public function deleteEmploymentType($id)
    {
        $model = new HrEmploymentTypeModel();
        $model->where('org_id', session()->get('org_id'))->delete($id);
        return redirect()->to('org/hr/settings')->with('success', 'Employment type deleted.');
    }

    public function saveLeavePolicy()
    {
        $model = new HrLeavePolicyModel();
        $data = [
            'org_id' => session()->get('org_id'),
            'employment_type_id' => $this->request->getPost('employment_type_id'),
            'leave_type' => $this->request->getPost('leave_type'),
            'annual_quota' => $this->request->getPost('annual_quota')
        ];
        
        $id = $this->request->getPost('id');
        if ($id) {
            $model->update($id, $data);
        } else {
            $model->insert($data);
        }
        
        return redirect()->to('org/hr/settings')->with('success', 'Leave policy saved.');
    }

    public function deleteLeavePolicy($id)
    {
        $model = new HrLeavePolicyModel();
        $model->where('org_id', session()->get('org_id'))->delete($id);
        return redirect()->to('org/hr/settings')->with('success', 'Leave policy deleted.');
    }
}
