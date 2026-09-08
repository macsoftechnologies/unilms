<?php

namespace App\Controllers;

use App\Models\HrAppraisalModel;
use App\Models\HrEmployeeModel;
use App\Models\AcademicYearModel;

class OrgHrAppraisals extends BaseController
{
    public function index()
    {
        $orgId = session('org_id');
        $db = \Config\Database::connect();

        $appraisals = $db->table('hr_appraisals ha')
            ->select('ha.*, u.full_name as employee_name, hd.name as department_name, hdes.name as designation_name, rv.full_name as reviewer_name')
            ->join('hr_employees he', 'he.id = ha.employee_id', 'left')
            ->join('org_users u', 'u.id = he.org_user_id', 'left')
            ->join('hr_departments hd', 'hd.id = he.department_id', 'left')
            ->join('hr_designations hdes', 'hdes.id = he.designation_id', 'left')
            ->join('org_users rv', 'rv.id = ha.reviewed_by', 'left')
            ->where('ha.org_id', $orgId)
            ->orderBy('ha.id', 'DESC')
            ->get()->getResultArray();

        $employees = $db->table('hr_employees he')
            ->select('he.id, u.full_name, he.employee_code, hd.name as department_name')
            ->join('org_users u', 'u.id = he.org_user_id', 'left')
            ->join('hr_departments hd', 'hd.id = he.department_id', 'left')
            ->where('he.org_id', $orgId)
            ->get()->getResultArray();

        $academicYears = (new AcademicYearModel())->where('org_id', $orgId)->findAll();

        $data = [
            'activeModule' => 'hr',
            'appraisals' => $appraisals,
            'employees' => $employees,
            'academic_years' => $academicYears
        ];

        return view('org/hr/appraisals/index', $data);
    }

    public function save()
    {
        $orgId = session('org_id');
        $model = new HrAppraisalModel();

        $id = $this->request->getPost('id');
        $selfRating = (float)$this->request->getPost('self_rating');
        $hodRating = (float)$this->request->getPost('hod_rating');
        $reviewedBy = session('org_user_id');
        $data = [
            'org_id' => $orgId,
            'employee_id' => $this->request->getPost('employee_id'),
            'appraisal_period' => $this->request->getPost('appraisal_period') ?: date('Y') . '-' . (date('Y') + 1),
            'academic_year_id' => $this->request->getPost('academic_year_id') ?: null,
            'self_rating' => $selfRating,
            'self_score' => $selfRating,
            'hod_rating' => $hodRating,
            'reviewer_score' => $hodRating,
            'final_score' => (float)$this->request->getPost('final_score'),
            'reviewed_by' => $reviewedBy,
            'evaluator_id' => $reviewedBy,
            'review_date' => date('Y-m-d'),
            'strengths' => trim($this->request->getPost('strengths')),
            'areas_for_improvement' => trim($this->request->getPost('areas_for_improvement')),
            'recommendation' => $this->request->getPost('recommendation') ?: 'Increment',
            'status' => $this->request->getPost('status') ?: 'Submitted'
        ];

        if ($id) {
            $model->where('org_id', $orgId)->update($id, $data);
            return redirect()->to('org/hr/appraisals')->with('success', 'Performance appraisal updated successfully.');
        } else {
            $model->insert($data);
            return redirect()->to('org/hr/appraisals')->with('success', 'Performance appraisal submitted.');
        }
    }

    public function delete($id)
    {
        $orgId = session('org_id');
        (new HrAppraisalModel())->where('org_id', $orgId)->delete($id);
        return redirect()->to('org/hr/appraisals')->with('success', 'Appraisal record removed.');
    }
}
