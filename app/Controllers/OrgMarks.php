<?php

namespace App\Controllers;

use App\Models\MarkComponentModel;
use App\Models\InternalMarkModel;
use App\Models\ProgramModel;
use App\Models\SemesterModel;
use App\Models\SubjectModel;
use App\Models\CohortModel;
use App\Models\StudentModel;
use App\Models\CoDefinitionModel;
use App\Models\AssessmentCoMappingModel;
use App\Models\ExamMarksAuditModel;

class OrgMarks extends BaseController
{
    // ADMIN: Setup Mark Components
    public function componentsSetup()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $programModel = new ProgramModel();
        $semesterModel = new SemesterModel();
        $subjectModel = new SubjectModel();
        $componentModel = new MarkComponentModel();
        $coModel = new CoDefinitionModel();
        $assessmentMappingModel = new AssessmentCoMappingModel();

        $programs = $programModel->where('org_id', $this->org_id)->findAll();
        
        $selected_program_id = $this->request->getGet('program_id') ?? ($programs[0]['id'] ?? null);
        $semesters = [];
        $selected_semester_id = $this->request->getGet('semester_id');
        
        $subjects = [];
        $selected_subject_id = $this->request->getGet('subject_id');

        $components = [];
        $cos = [];
        $mappings = [];

        $semesters = $semesterModel->where('org_id', $this->org_id)->orderBy('sequence', 'ASC')->findAll();
        $selected_semester_id = $selected_semester_id ?? ($semesters[0]['id'] ?? null);

        if ($selected_program_id && $selected_semester_id) {
            $subjects = $subjectModel->where('org_id', $this->org_id)
                                     ->where('program_id', $selected_program_id)
                                     ->where('semester_id', $selected_semester_id)
                                     ->findAll();
            $selected_subject_id = $selected_subject_id ?? ($subjects[0]['id'] ?? null);

            if ($selected_subject_id) {
                $components = $componentModel->getComponentsBySubject($this->org_id, $selected_subject_id);
                $cos = $coModel->getCosBySubject($this->org_id, $selected_subject_id);
                
                foreach($components as $comp) {
                    $mappings[$comp['id']] = $assessmentMappingModel->getMappingsForComponent($this->org_id, $comp['id']);
                }
            }
        }

        return view('org/obe/admin_components_setup', [
            'programs' => $programs,
            'semesters' => $semesters,
            'subjects' => $subjects,
            'selected_program_id' => $selected_program_id,
            'selected_semester_id' => $selected_semester_id,
            'selected_subject_id' => $selected_subject_id,
            'components' => $components,
            'cos' => $cos,
            'mappings' => $mappings
        ]);
    }

    public function saveComponent()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $componentModel = new MarkComponentModel();
        $assessmentMappingModel = new AssessmentCoMappingModel();
        
        $program_id = $this->request->getPost('program_id');
        $semester_id = $this->request->getPost('semester_id');
        $subject_id = $this->request->getPost('subject_id');

        $data = [
            'org_id' => $this->org_id,
            'program_id' => $program_id,
            'semester_id' => $semester_id,
            'subject_id' => $subject_id,
            'name' => $this->request->getPost('name'),
            'max_marks' => $this->request->getPost('max_marks')
        ];

        if ($compParam = $this->request->getPost('component_id')) {
            $existing = $componentModel->where('org_id', $this->org_id)->findByIdOrUuid($compParam);
            $id = $existing ? $existing['id'] : $compParam;
            $componentModel->update($id, $data);
            $msg = 'Component updated.';
        } else {
            $id = $componentModel->insert($data);
            $msg = 'Component added.';
        }

        // Save Assessment CO Mappings
        $mapped_cos = $this->request->getPost('co_ids') ?? [];
        $assessmentMappingModel->where('org_id', $this->org_id)->where('component_id', $id)->delete();
        
        $inserts = [];
        foreach ($mapped_cos as $co_id) {
            $inserts[] = [
                'org_id' => $this->org_id,
                'component_id' => $id,
                'co_id' => $co_id
            ];
        }
        if (!empty($inserts)) {
            $assessmentMappingModel->insertBatch($inserts);
        }

        return redirect()->to("org/marks/components?program_id=$program_id&semester_id=$semester_id&subject_id=$subject_id")->with('success', $msg);
    }
    
    public function deleteComponent($id)
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        $componentModel = new MarkComponentModel();
        $comp = $componentModel->where('org_id', $this->org_id)->findByIdOrUuid($id);
        if ($comp) {
            $componentModel->delete($comp['id']);
        }
        return redirect()->back()->with('success', 'Component deleted.');
    }

    // FACULTY: Internal Marks Entry
    public function marksEntry()
    {
        $subjectModel = new SubjectModel();
        $cohortModel = new CohortModel();
        $componentModel = new MarkComponentModel();
        $markModel = new InternalMarkModel();

        $subjects = $subjectModel->where('org_id', $this->org_id)->findAll();
        $selected_subject_id = $this->request->getGet('subject_id') ?? ($subjects[0]['id'] ?? null);

        $cohorts = [];
        $selected_cohort_id = $this->request->getGet('cohort_id');

        $components = [];
        $selected_component_id = $this->request->getGet('component_id');

        $selected_exam_type = $this->request->getGet('exam_type') ?? 'internal1';

        $students = [];
        $marks_data = [];
        $max_marks = 50;

        if ($selected_subject_id) {
            $subject = $subjectModel->find($selected_subject_id);
            if ($subject) {
                $cohorts = $cohortModel->where('org_id', $this->org_id)
                                       ->where('program_id', $subject['program_id'])
                                       ->findAll();
                                       
                $selected_cohort_id = $selected_cohort_id ?? ($cohorts[0]['id'] ?? null);
                $components = $componentModel->getComponentsBySubject($this->org_id, $selected_subject_id);
                $selected_component_id = $selected_component_id ?? ($components[0]['id'] ?? null);

                if ($selected_component_id) {
                    $comp = $componentModel->find($selected_component_id);
                    if ($comp) $max_marks = $comp['max_marks'];
                }
            }
        }

        if ($selected_cohort_id && $selected_component_id) {
            $db = \Config\Database::connect();
            $students = $db->table('students')
                           ->select('students.*')
                           ->where('students.org_id', $this->org_id)
                           ->where('students.cohort_id', $selected_cohort_id)
                           ->orderBy('students.roll_number', 'ASC')
                           ->get()->getResultArray();
                           
            $marks_data = $markModel->getMarksByComponent($this->org_id, $selected_component_id);
        }

        $is_locked = false;
        if (!empty($marks_data)) {
            $first_mark = reset($marks_data);
            if ($first_mark['is_locked'] == 1) {
                $is_locked = true;
            }
        }

        $examTypes = [
            'internal1' => 'Internal Assessment 1',
            'internal2' => 'Internal Assessment 2',
            'internal3' => 'Internal Assessment 3',
            'midterm'   => 'Mid-Term Exam',
            'final'     => 'Semester End Final Exam',
            'practical' => 'Lab / Practical Assessment'
        ];

        return view('org/marks/faculty_marks_entry', [
            'subjects'              => $subjects,
            'cohorts'               => $cohorts,
            'components'            => $components,
            'selected_subject_id'   => $selected_subject_id,
            'selected_cohort_id'    => $selected_cohort_id,
            'selected_component_id' => $selected_component_id,
            'selected_exam_type'    => $selected_exam_type,
            'examTypes'             => $examTypes,
            'max_marks'             => $max_marks,
            'students'              => $students,
            'marks_data'            => $marks_data,
            'is_locked'             => $is_locked
        ]);
    }

    public function saveMarks()
    {
        $markModel = new InternalMarkModel();
        $auditModel = new ExamMarksAuditModel();
        
        $subject_id = $this->request->getPost('subject_id');
        $cohort_id = $this->request->getPost('cohort_id');
        $component_id = $this->request->getPost('component_id');
        $exam_type = $this->request->getPost('exam_type') ?: 'internal1';
        $scores = $this->request->getPost('scores'); // array student_id => score
        $finalize = $this->request->getPost('finalize_submit') ? 1 : 0;

        // Check lock status
        $existing = $markModel->getMarksByComponent($this->org_id, $component_id);
        $is_locked = (!empty($existing) && reset($existing)['is_locked'] == 1);
        
        if ($is_locked) {
            return redirect()->back()->with('error', 'Marks for this component are locked by HOD/Admin. An unlock request is required to make modifications.');
        }

        // Validate max marks
        $compModel = new MarkComponentModel();
        $comp = $compModel->find($component_id);
        $maxAllowed = $comp ? (float)$comp['max_marks'] : 100;

        if (is_array($scores)) {
            foreach ($scores as $student_id => $score) {
                if ($score !== '' && $score !== null) {
                    if ((float)$score > $maxAllowed) {
                        return redirect()->back()->with('error', "Marks entered ({$score}) cannot exceed maximum allowed marks ({$maxAllowed}).");
                    }
                } else {
                    $score = null;
                }
                
                // Upsert
                $record = $markModel->where('org_id', $this->org_id)
                                    ->where('component_id', $component_id)
                                    ->where('student_id', $student_id)
                                    ->first();
                if ($record) {
                    $markModel->update($record['id'], [
                        'score' => $score,
                        'faculty_user_id' => $this->org_user_id,
                        'is_locked' => $finalize
                    ]);
                } else {
                    $markModel->insert([
                        'org_id' => $this->org_id,
                        'component_id' => $component_id,
                        'student_id' => $student_id,
                        'score' => $score,
                        'faculty_user_id' => $this->org_user_id,
                        'is_locked' => $finalize
                    ]);
                }
            }
        }

        // Audit submission action
        $auditModel->insert([
            'org_id'               => $this->org_id,
            'subject_id'           => $subject_id,
            'cohort_id'            => $cohort_id,
            'component_id'         => $component_id,
            'exam_type'            => $exam_type,
            'action'               => $finalize ? 'locked' : 'submitted',
            'performed_by_user_id' => $this->org_user_id ?? 1,
            'performed_by_name'    => session('user_name') ?: 'Faculty Member',
            'unlock_reason'        => $finalize ? 'Marks finalized and locked by teacher.' : 'Draft marks saved.'
        ]);

        return redirect()->to("org/marks/entry?subject_id=$subject_id&cohort_id=$cohort_id&component_id=$component_id&exam_type=$exam_type")
            ->with('success', $finalize ? 'Marks submitted and locked successfully!' : 'Marks draft saved successfully.');
    }

    // ADMIN/HOD: Lock Marks
    public function lockMarks()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        
        $component_id = $this->request->getPost('component_id');
        $subject_id = $this->request->getPost('subject_id');
        $cohort_id = $this->request->getPost('cohort_id');
        $exam_type = $this->request->getPost('exam_type') ?: 'internal1';

        $markModel = new InternalMarkModel();
        $auditModel = new ExamMarksAuditModel();

        $markModel->lockMarks($this->org_id, $component_id);

        $auditModel->insert([
            'org_id'               => $this->org_id,
            'subject_id'           => $subject_id,
            'cohort_id'            => $cohort_id,
            'component_id'         => $component_id,
            'exam_type'            => $exam_type,
            'action'               => 'locked',
            'performed_by_user_id' => $this->org_user_id ?? 1,
            'performed_by_name'    => session('user_name') ?: 'HOD / Admin',
            'unlock_reason'        => 'Administrative freeze of marks entry.'
        ]);
        
        return redirect()->back()->with('success', 'Marks have been locked.');
    }

    // ADMIN/HOD: Unlock Marks with Audit Reason
    public function unlockMarks()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $component_id = $this->request->getPost('component_id');
        $subject_id = $this->request->getPost('subject_id');
        $cohort_id = $this->request->getPost('cohort_id');
        $exam_type = $this->request->getPost('exam_type') ?: 'internal1';
        $reason = $this->request->getPost('unlock_reason');

        if (empty(trim($reason))) {
            return redirect()->back()->with('error', 'A mandatory unlock reason is required for compliance audit logs.');
        }

        $markModel = new InternalMarkModel();
        $auditModel = new ExamMarksAuditModel();

        $markModel->where('org_id', $this->org_id)
                  ->where('component_id', $component_id)
                  ->set(['is_locked' => 0])
                  ->update();

        $auditModel->insert([
            'org_id'               => $this->org_id,
            'subject_id'           => $subject_id,
            'cohort_id'            => $cohort_id,
            'component_id'         => $component_id,
            'exam_type'            => $exam_type,
            'action'               => 'unlocked',
            'performed_by_user_id' => $this->org_user_id ?? 1,
            'performed_by_name'    => session('user_name') ?: 'HOD / Admin',
            'unlock_reason'        => $reason
        ]);

        return redirect()->back()->with('success', 'Marks unlocked. Faculty member can now revise entries. Audit record created.');
    }

    public function auditTrail()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $db = \Config\Database::connect();
        $logs = $db->table('org_exam_marks_audit a')
            ->select('a.*, s.name as subject_name, c.name as cohort_name, mc.name as component_name')
            ->join('subjects s', 's.id = a.subject_id', 'left')
            ->join('cohorts c', 'c.id = a.cohort_id', 'left')
            ->join('mark_components mc', 'mc.id = a.component_id', 'left')
            ->where('a.org_id', $this->org_id)
            ->orderBy('a.created_at', 'DESC')
            ->get()->getResultArray();

        return view('org/marks/audit_trail', [
            'logs' => $logs
        ]);
    }
}
