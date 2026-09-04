<?php

namespace App\Controllers;

use App\Models\AssessmentModel;
use App\Models\AssessmentQuestionModel;
use App\Models\AssessmentSubmissionModel;
use App\Models\SubjectModel;
use App\Models\CohortModel;
use App\Models\CoDefinitionModel;
use App\Models\StudentModel;

class OrgAssessments extends BaseController
{
    public function index()
    {
        $assessmentModel = new AssessmentModel();
        $db = \Config\Database::connect();

        $builder = $db->table('lms_assessments a');
        $builder->select('a.*, s.name as subject_name, s.code as subject_code, c.name as cohort_name');
        $builder->join('subjects s', 's.id = a.subject_id', 'left');
        $builder->join('cohorts c', 'c.id = a.cohort_id', 'left');
        $builder->where('a.org_id', $this->org_id);
        
        if (!session('is_org_admin') && !$this->hasPermission('manage_academics_global')) {
            $builder->where('a.faculty_user_id', $this->org_user_id);
        }
        $builder->orderBy('a.created_at', 'DESC');
        $assessments = $builder->get()->getResultArray();

        // Attach submission counts
        foreach ($assessments as &$item) {
            $item['submission_count'] = $db->table('lms_assessment_submissions')
                ->where('assessment_id', $item['id'])
                ->countAllResults();
        }

        return view('org/assessments/index', [
            'assessments' => $assessments
        ]);
    }

    public function create()
    {
        $subjectModel = new SubjectModel();
        $cohortModel = new CohortModel();
        $coModel = new CoDefinitionModel();

        $subjects = $subjectModel->where('org_id', $this->org_id)->findAll();
        $cohorts = $cohortModel->where('org_id', $this->org_id)->findAll();
        $cos = $coModel->where('org_id', $this->org_id)->findAll();

        return view('org/assessments/create', [
            'subjects' => $subjects,
            'cohorts' => $cohorts,
            'cos' => $cos,
            'assessment' => null,
            'questions' => []
        ]);
    }

    public function edit($id)
    {
        $assessmentModel = new AssessmentModel();
        $questionModel = new AssessmentQuestionModel();
        $subjectModel = new SubjectModel();
        $cohortModel = new CohortModel();
        $coModel = new CoDefinitionModel();

        $assessment = $assessmentModel->where('org_id', $this->org_id)->find($id);
        if (!$assessment) {
            return redirect()->to('org/assessments')->with('error', 'Assessment not found.');
        }

        $subjects = $subjectModel->where('org_id', $this->org_id)->findAll();
        $cohorts = $cohortModel->where('org_id', $this->org_id)->findAll();
        $cos = $coModel->where('org_id', $this->org_id)->where('subject_id', $assessment['subject_id'])->findAll();
        $questions = $questionModel->where('assessment_id', $id)->orderBy('sort_order', 'ASC')->findAll();

        return view('org/assessments/create', [
            'subjects' => $subjects,
            'cohorts' => $cohorts,
            'cos' => $cos,
            'assessment' => $assessment,
            'questions' => $questions
        ]);
    }

    public function save()
    {
        $assessmentModel = new AssessmentModel();
        $questionModel = new AssessmentQuestionModel();
        $id = $this->request->getPost('id');
        $type = $this->request->getPost('assessment_type');

        // Handle reference file upload
        $referenceFile = $this->request->getFile('reference_attachment');
        $referencePath = null;
        if ($referenceFile && $referenceFile->isValid() && !$referenceFile->hasMoved()) {
            if (!$this->validate([
                'reference_attachment' => [
                    'label' => 'Reference File',
                    'rules' => 'uploaded[reference_attachment]|ext_in[reference_attachment,pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar,jpg,jpeg,png,webp]|max_size[reference_attachment,51200]'
                ]
            ])) {
                return redirect()->back()->withInput()->with('error', $this->validator->getError('reference_attachment') ?: 'Invalid reference file type or exceeds 50MB limit.');
            }
            $newName = $referenceFile->getRandomName();
            $targetDir = FCPATH . 'uploads/assessments/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
            $referenceFile->move($targetDir, $newName);
            $referencePath = 'uploads/assessments/' . $newName;
        }

        $data = [
            'org_id'                 => $this->org_id,
            'subject_id'             => $this->request->getPost('subject_id'),
            'cohort_id'              => $this->request->getPost('cohort_id'),
            'faculty_user_id'        => $this->org_user_id ?? 1,
            'co_id'                  => $this->request->getPost('co_id') ?: null,
            'title'                  => $this->request->getPost('title'),
            'description'            => $this->request->getPost('description'),
            'assessment_type'        => $type,
            'max_marks'              => $this->request->getPost('max_marks') ?: 100.00,
            'due_date'               => $this->request->getPost('due_date'),
            'is_published'           => $this->request->getPost('is_published') ? 1 : 0,
            'allowed_extensions'     => $this->request->getPost('allowed_extensions'),
            'max_file_size_mb'       => $this->request->getPost('max_file_size_mb') ?: 10,
            'time_limit_mins'        => $this->request->getPost('time_limit_mins') ?: 0,
            'max_attempts'           => $this->request->getPost('max_attempts') ?: 1,
            'randomize_questions'    => $this->request->getPost('randomize_questions') ? 1 : 0,
            'randomize_options'      => $this->request->getPost('randomize_options') ? 1 : 0,
            'score_visibility'       => $this->request->getPost('score_visibility') ?: 'immediate',
            'video_source_type'      => $this->request->getPost('video_source_type') ?: 'youtube',
            'video_url'              => $this->request->getPost('video_url'),
            'video_duration_seconds' => $this->request->getPost('video_duration_seconds') ?: 0,
            'min_word_count'         => $this->request->getPost('min_word_count') ?: 0,
            'max_word_count'         => $this->request->getPost('max_word_count') ?: 0,
            'submission_mode'        => $this->request->getPost('submission_mode') ?: 'rich_text',
            'allow_late_submissions' => $this->request->getPost('allow_late_submissions') ? 1 : 0,
            'rubric_criteria'        => $this->request->getPost('rubric_criteria') ? json_encode($this->request->getPost('rubric_criteria')) : null,
        ];

        if ($referencePath) {
            $data['reference_attachment'] = $referencePath;
        }

        if (empty($id)) {
            $assessmentId = $assessmentModel->insert($data);
        } else {
            $assessmentModel->update($id, $data);
            $assessmentId = $id;
        }

        // Save inline questions for CBT Quiz & Interactive Video
        if (in_array($type, ['cbt_quiz', 'interactive_video'])) {
            $questionsData = $this->request->getPost('questions') ?? [];
            $questionModel->where('assessment_id', $assessmentId)->delete();

            foreach ($questionsData as $idx => $q) {
                if (empty(trim($q['question_text'] ?? ''))) continue;

                $optionsArray = [];
                if (($q['question_type'] ?? 'mcq') === 'mcq') {
                    $opts = $q['options'] ?? [];
                    foreach ($opts as $optKey => $optVal) {
                        if (trim($optVal) !== '') {
                            $optionsArray[] = ['key' => $optKey, 'text' => trim($optVal)];
                        }
                    }
                } else {
                    $optionsArray = [
                        ['key' => 'true', 'text' => 'True'],
                        ['key' => 'false', 'text' => 'False']
                    ];
                }

                $questionModel->insert([
                    'assessment_id'     => $assessmentId,
                    'co_id'             => !empty($q['co_id']) ? $q['co_id'] : null,
                    'timestamp_seconds' => !empty($q['timestamp_seconds']) ? intval($q['timestamp_seconds']) : null,
                    'question_type'     => $q['question_type'] ?? 'mcq',
                    'question_text'     => $q['question_text'],
                    'points'            => $q['points'] ?? 1.00,
                    'options'           => json_encode($optionsArray),
                    'correct_option'    => $q['correct_option'] ?? '0',
                    'explanation'       => $q['explanation'] ?? null,
                    'sort_order'        => $idx
                ]);
            }
        }

        return redirect()->to('org/assessments')->with('success', 'Assessment saved successfully.');
    }

    public function delete($id)
    {
        $assessmentModel = new AssessmentModel();
        $assessment = $assessmentModel->where('org_id', $this->org_id)->find($id);
        if ($assessment) {
            $assessmentModel->delete($id);
        }
        return redirect()->to('org/assessments')->with('success', 'Assessment deleted.');
    }

    public function submissions($id)
    {
        $assessmentModel = new AssessmentModel();
        $assessment = $assessmentModel->where('org_id', $this->org_id)->find($id);
        if (!$assessment) {
            return redirect()->to('org/assessments')->with('error', 'Assessment not found.');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('lms_assessment_submissions sub');
        $builder->select('sub.*, s.first_name, s.last_name, s.roll_number, s.email');
        $builder->join('students s', 's.id = sub.student_id');
        $builder->where('sub.assessment_id', $id);
        $builder->orderBy('sub.submitted_at', 'DESC');
        $submissions = $builder->get()->getResultArray();

        return view('org/assessments/submissions', [
            'assessment' => $assessment,
            'submissions' => $submissions
        ]);
    }

    public function grade($submissionId)
    {
        $submissionModel = new AssessmentSubmissionModel();
        $assessmentModel = new AssessmentModel();
        $studentModel = new StudentModel();

        $submission = $submissionModel->find($submissionId);
        if (!$submission) {
            return redirect()->to('org/assessments')->with('error', 'Submission not found.');
        }

        $assessment = $assessmentModel->where('org_id', $this->org_id)->find($submission['assessment_id']);
        $student = $studentModel->find($submission['student_id']);

        return view('org/assessments/grade', [
            'assessment' => $assessment,
            'submission' => $submission,
            'student' => $student
        ]);
    }

    public function saveGrade()
    {
        $submissionModel = new AssessmentSubmissionModel();
        $submissionId = $this->request->getPost('submission_id');

        $submission = $submissionModel->find($submissionId);
        if (!$submission) {
            return redirect()->to('org/assessments')->with('error', 'Submission not found.');
        }

        $finalMarks = $this->request->getPost('final_marks');
        $feedback = $this->request->getPost('faculty_feedback');
        $rubricBreakdown = $this->request->getPost('rubric_scores');

        $submissionModel->update($submissionId, [
            'final_marks'       => $finalMarks,
            'faculty_feedback'  => $feedback,
            'rubric_breakdown'  => $rubricBreakdown ? json_encode($rubricBreakdown) : null,
            'graded_by_user_id' => $this->org_user_id ?? 1,
            'graded_at'         => date('Y-m-d H:i:s'),
            'status'            => 'graded'
        ]);

        return redirect()->to('org/assessments/submissions/' . $submission['assessment_id'])->with('success', 'Grade and feedback recorded successfully.');
    }
}
