<?php
namespace App\Controllers;

use App\Models\DepartmentModel;
use App\Models\ProgramModel;
use App\Models\AcademicYearModel;
use App\Models\SemesterModel;
use App\Models\CohortModel;
use App\Models\SubjectModel;
use App\Models\RegulationModel;
use App\Models\CohortSectionModel;

class OrgAcademics extends BaseController
{
    public function index()
    {
        $data = [
            'total_depts' => (new DepartmentModel())->where('org_id', session('org_id'))->countAllResults(),
            'total_programs' => (new ProgramModel())->where('org_id', session('org_id'))->countAllResults(),
            'total_cohorts' => (new CohortModel())->where('org_id', session('org_id'))->countAllResults(),
            'total_subjects' => (new SubjectModel())->where('org_id', session('org_id'))->countAllResults(),
        ];
        return view('org/academics/dashboard', $data);
    }

    // --- Departments ---
    public function departments()
    {
        $model = new DepartmentModel();
        $data['departments'] = $model->where('org_id', session('org_id'))->findAll();
        return view('org/academics/departments', $data);
    }

    public function save_department()
    {
        $model = new DepartmentModel();
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => session('org_id'),
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code')
        ];
        
        try {
            if ($id) {
                $model->update($id, $data);
            } else {
                $model->insert($data);
            }
            return redirect()->to('org/academics/departments')->with('success', 'Department saved.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error saving department. Code may already exist.');
        }
    }

    public function delete_department($id)
    {
        $model = new DepartmentModel();
        try {
            $model->where('org_id', session('org_id'))->delete($id);
            return redirect()->to('org/academics/departments')->with('success', 'Department deleted.');
        } catch (\Exception $e) {
            return redirect()->to('org/academics/departments')->with('error', 'Cannot delete department. It may be in use by programs.');
        }
    }

    // --- Programs ---
    public function programs()
    {
        $model = new ProgramModel();
        $deptModel = new DepartmentModel();
        $db = \Config\Database::connect();
        $orgId = session('org_id');
        
        $programs = $model->select('programs.*, departments.name as dept_name')
            ->join('departments', 'departments.id = programs.dept_id')
            ->where('programs.org_id', $orgId)
            ->findAll();

        foreach ($programs as &$prog) {
            $prog['pos'] = $db->table('po_definitions')
                              ->where('org_id', $orgId)
                              ->where('program_id', $prog['id'])
                              ->orderBy('code', 'ASC')
                              ->get()->getResultArray();
            $prog['po_count'] = count($prog['pos']);
        }
            
        $data['programs'] = $programs;
        $data['departments'] = $deptModel->where('org_id', $orgId)->findAll();
        return view('org/academics/programs', $data);
    }

    public function save_program()
    {
        $model = new ProgramModel();
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => session('org_id'),
            'dept_id' => $this->request->getPost('dept_id'),
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'duration_years' => $this->request->getPost('duration_years')
        ];
        
        try {
            if ($id) {
                $model->update($id, $data);
            } else {
                $newProgId = $model->insert($data);
                if ($newProgId) {
                    try {
                        $templateKey = \App\Services\ObeTemplateService::detectTemplateKey($data['name'], $data['code'] ?? '');
                        $obeService = new \App\Services\ObeTemplateService();
                        $obeService->applyTemplateToProgram((int)session('org_id'), (int)$newProgId, $templateKey);
                    } catch (\Throwable $t) {
                        // Safe fallback - keep program creation smooth
                    }
                }
            }
            return redirect()->to('org/academics/programs')->with('success', 'Program saved with standard NAAC/NBA outcomes initialized.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error saving program. Code may already exist.');
        }
    }

    public function delete_program($id)
    {
        $model = new ProgramModel();
        try {
            $model->where('org_id', session('org_id'))->delete($id);
            return redirect()->to('org/academics/programs')->with('success', 'Program deleted.');
        } catch (\Exception $e) {
            return redirect()->to('org/academics/programs')->with('error', 'Cannot delete program. It is in use.');
        }
    }

    // --- Academic Years ---
    public function academic_years()
    {
        $model = new AcademicYearModel();
        $data['academic_years'] = $model->where('org_id', session('org_id'))->findAll();
        return view('org/academics/academic_years', $data);
    }

    public function save_academic_year()
    {
        $model = new AcademicYearModel();
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => session('org_id'),
            'name' => $this->request->getPost('name'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'status' => $this->request->getPost('status')
        ];
        
        try {
            if ($id) {
                $model->update($id, $data);
            } else {
                $model->insert($data);
            }
            return redirect()->to('org/academics/academic_years')->with('success', 'Academic Year saved.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error saving academic year. Name may already exist.');
        }
    }

    public function delete_academic_year($id)
    {
        $model = new AcademicYearModel();
        try {
            $model->where('org_id', session('org_id'))->delete($id);
            return redirect()->to('org/academics/academic_years')->with('success', 'Academic Year deleted.');
        } catch (\Exception $e) {
            return redirect()->to('org/academics/academic_years')->with('error', 'Cannot delete academic year. It is in use.');
        }
    }

    // --- Semesters ---
    public function semesters()
    {
        $model = new SemesterModel();
        $data['semesters'] = $model->where('org_id', session('org_id'))->orderBy('sequence', 'ASC')->findAll();
        return view('org/academics/semesters', $data);
    }

    public function save_semester()
    {
        $model = new SemesterModel();
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => session('org_id'),
            'name' => $this->request->getPost('name'),
            'sequence' => $this->request->getPost('sequence')
        ];
        
        if ($id) {
            $model->update($id, $data);
        } else {
            $model->insert($data);
        }
        return redirect()->to('org/academics/semesters')->with('success', 'Semester saved.');
    }

    public function delete_semester($id)
    {
        $model = new SemesterModel();
        try {
            $model->where('org_id', session('org_id'))->delete($id);
            return redirect()->to('org/academics/semesters')->with('success', 'Semester deleted.');
        } catch (\Exception $e) {
            return redirect()->to('org/academics/semesters')->with('error', 'Cannot delete semester. It is in use.');
        }
    }

    // --- Cohorts ---
    public function cohorts()
    {
        $model = new CohortModel();
        $progModel = new ProgramModel();
        $ayModel = new AcademicYearModel();
        $semModel = new SemesterModel();
        
        $cohorts = $model->select('cohorts.*, programs.name as program_name, academic_years.name as ay_name, semesters.name as semester_name')
            ->join('programs', 'programs.id = cohorts.program_id')
            ->join('academic_years', 'academic_years.id = cohorts.academic_year_id')
            ->join('semesters', 'semesters.id = cohorts.current_semester_id', 'left')
            ->where('cohorts.org_id', session('org_id'))
            ->findAll();

        $sectionModel = new CohortSectionModel();
        $sections = $sectionModel->where('org_id', session('org_id'))->findAll();
        $cohortSections = [];
        foreach ($sections as $sec) {
            $cohortSections[$sec['cohort_id']][] = $sec;
        }

        foreach ($cohorts as &$c) {
            $c['sections'] = $cohortSections[$c['id']] ?? [];
        }
            
        $data['cohorts'] = $cohorts;
        $data['programs'] = $progModel->where('org_id', session('org_id'))->findAll();
        $data['academic_years'] = $ayModel->where('org_id', session('org_id'))->findAll();
        $data['semesters'] = $semModel->where('org_id', session('org_id'))->orderBy('sequence', 'ASC')->findAll();
        
        return view('org/academics/cohorts', $data);
    }

    public function save_cohort()
    {
        $model = new CohortModel();
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => session('org_id'),
            'program_id' => $this->request->getPost('program_id'),
            'academic_year_id' => $this->request->getPost('academic_year_id'),
            'current_semester_id' => $this->request->getPost('current_semester_id') ?: null,
            'name' => $this->request->getPost('name')
        ];
        
        try {
            if ($id) {
                $model->update($id, $data);
            } else {
                $model->insert($data);
            }
            return redirect()->to('org/academics/cohorts')->with('success', 'Cohort saved.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error saving cohort.');
        }
    }

    public function delete_cohort($id)
    {
        $model = new CohortModel();
        try {
            $model->where('org_id', session('org_id'))->delete($id);
            return redirect()->to('org/academics/cohorts')->with('success', 'Cohort deleted.');
        } catch (\Exception $e) {
            return redirect()->to('org/academics/cohorts')->with('error', 'Cannot delete cohort. It is in use.');
        }
    }

    // --- Subjects ---
    public function subjects()
    {
        $org_id = session('org_id');
        $model = new SubjectModel();
        $progModel = new ProgramModel();
        $semModel = new SemesterModel();
        $deptModel = new DepartmentModel();

        $selected_program_id = $this->request->getGet('program_id');
        
        $programs = $progModel->select('programs.*, departments.name as department_name, departments.code as department_code')
            ->join('departments', 'departments.id = programs.dept_id', 'left')
            ->where('programs.org_id', $org_id)
            ->findAll();

        // Calculate subject counts and total credits per program
        foreach ($programs as &$prog) {
            $stats = $model->select('COUNT(id) as total_subjects, COALESCE(SUM(credits), 0) as total_credits')
                ->where('org_id', $org_id)
                ->where('program_id', $prog['id'])
                ->first();
            $prog['total_subjects'] = (int)($stats['total_subjects'] ?? 0);
            $prog['total_credits'] = (float)($stats['total_credits'] ?? 0);
        }

        $subjectQuery = $model->select('subjects.*, programs.name as program_name, programs.code as program_code, semesters.name as semester_name, semesters.sequence as semester_sequence')
            ->join('programs', 'programs.id = subjects.program_id')
            ->join('semesters', 'semesters.id = subjects.semester_id')
            ->where('subjects.org_id', $org_id);

        if (!empty($selected_program_id)) {
            $subjectQuery->where('subjects.program_id', $selected_program_id);
        }

        $data['subjects'] = $subjectQuery->orderBy('semesters.sequence', 'ASC')->orderBy('subjects.display_order', 'ASC')->findAll();
        $data['programs'] = $programs;
        $data['semesters'] = $semModel->where('org_id', $org_id)->orderBy('sequence', 'ASC')->findAll();
        $data['selected_program_id'] = $selected_program_id;
        
        return view('org/academics/subjects', $data);
    }

    public function save_subject()
    {
        $model = new SubjectModel();
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => session('org_id'),
            'program_id' => $this->request->getPost('program_id'),
            'semester_id' => $this->request->getPost('semester_id'),
            'name' => $this->request->getPost('name'),
            'short_name' => $this->request->getPost('short_name'),
            'code' => $this->request->getPost('code'),
            'credits' => $this->request->getPost('credits'),
            'subject_type' => $this->request->getPost('subject_type') ?: 'Theory',
            'no_of_sessions' => $this->request->getPost('no_of_sessions') ?: 0,
            'no_of_units' => $this->request->getPost('no_of_units') ?: 0,
            'no_of_outcomes' => $this->request->getPost('no_of_outcomes') ?: 0,
            'internal_max_marks' => $this->request->getPost('internal_max_marks') ?: 0,
            'internal_pass_marks' => $this->request->getPost('internal_pass_marks') ?: 0,
            'external_max_marks' => $this->request->getPost('external_max_marks') ?: 0,
            'external_pass_marks' => $this->request->getPost('external_pass_marks') ?: 0,
            'total_pass_marks' => $this->request->getPost('total_pass_marks') ?: 0,
            'display_order' => $this->request->getPost('display_order') ?: 1
        ];
        
        try {
            if ($id) {
                $model->update($id, $data);
            } else {
                $model->insert($data);
            }
            return redirect()->to('org/academics/subjects')->with('success', 'Subject saved.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error saving subject. Code may already exist for this program and semester.');
        }
    }

    public function delete_subject($id)
    {
        $model = new SubjectModel();
        try {
            $model->where('org_id', session('org_id'))->delete($id);
            return redirect()->to('org/academics/subjects')->with('success', 'Subject deleted.');
        } catch (\Exception $e) {
            return redirect()->to('org/academics/subjects')->with('error', 'Cannot delete subject. It is in use.');
        }
    }

    // --- Academic Regulations ---
    public function regulations()
    {
        $orgId = session('org_id');
        $model = new RegulationModel();
        $data = [
            'activeModule' => 'academics',
            'regulations' => $model->where('org_id', $orgId)->orderBy('start_year', 'DESC')->findAll()
        ];
        return view('org/academics/regulations', $data);
    }

    public function save_regulation()
    {
        $orgId = session('org_id');
        $model = new RegulationModel();

        $id = $this->request->getPost('id');
        $data = [
            'org_id' => $orgId,
            'name' => trim($this->request->getPost('name')),
            'description' => trim($this->request->getPost('description')),
            'start_year' => $this->request->getPost('start_year') ?: date('Y'),
            'total_credits' => (int)$this->request->getPost('total_credits'),
            'pass_percentage' => (float)$this->request->getPost('pass_percentage'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        if ($id) {
            $model->where('org_id', $orgId)->update($id, $data);
            return redirect()->to('org/academics/regulations')->with('success', 'Academic regulation updated.');
        } else {
            $model->insert($data);
            return redirect()->to('org/academics/regulations')->with('success', 'Academic regulation added.');
        }
    }

    public function delete_regulation($id)
    {
        $orgId = session('org_id');
        (new RegulationModel())->where('org_id', $orgId)->delete($id);
        return redirect()->to('org/academics/regulations')->with('success', 'Academic regulation deleted.');
    }

    // --- Cohort Sections ---
    public function sections()
    {
        $orgId = session('org_id');
        $db = \Config\Database::connect();

        $sections = $db->table('cohort_sections cs')
            ->select('cs.*, c.name as cohort_name, p.name as program_name')
            ->join('cohorts c', 'c.id = cs.cohort_id', 'left')
            ->join('programs p', 'p.id = c.program_id', 'left')
            ->where('cs.org_id', $orgId)
            ->orderBy('cs.id', 'DESC')
            ->get()->getResultArray();

        $cohorts = (new CohortModel())->where('org_id', $orgId)->findAll();

        $data = [
            'activeModule' => 'academics',
            'sections' => $sections,
            'cohorts' => $cohorts
        ];
        return view('org/academics/sections', $data);
    }

    public function save_section()
    {
        $orgId = session('org_id');
        $model = new CohortSectionModel();

        $id = $this->request->getPost('id');
        $data = [
            'org_id' => $orgId,
            'cohort_id' => $this->request->getPost('cohort_id'),
            'name' => trim($this->request->getPost('name')),
            'max_students' => (int)$this->request->getPost('max_students'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        if ($id) {
            $model->where('org_id', $orgId)->update($id, $data);
            return redirect()->to('org/academics/sections')->with('success', 'Cohort section updated.');
        } else {
            $model->insert($data);
            return redirect()->to('org/academics/sections')->with('success', 'Cohort section created.');
        }
    }

    public function delete_section($id)
    {
        $orgId = session('org_id');
        (new CohortSectionModel())->where('org_id', $orgId)->delete($id);
        return redirect()->to('org/academics/sections')->with('success', 'Cohort section deleted.');
    }
}
