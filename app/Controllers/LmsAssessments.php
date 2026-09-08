<?php

namespace App\Controllers;

use App\Models\AssessmentModel;
use App\Models\AssessmentQuestionModel;
use App\Models\AssessmentSubmissionModel;
use App\Models\StudentModel;

class LmsAssessments extends BaseController
{
    protected $student_id;
    protected $cohort_id;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->student_id = session('student_id') ?: (session('lms_student_id') ?: 3);
        $this->cohort_id = session('cohort_id') ?: (session('lms_cohort_id') ?: (session('student_cohort_id') ?: 1));
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $orgId = session('org_id') ?: (session('lms_org_id') ?: 5);
        $cohortId = session('cohort_id') ?: (session('lms_cohort_id') ?: (session('student_cohort_id') ?: 1));
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);

        // Fetch from lms_assessments
        $builder = $db->table('lms_assessments a');
        $builder->select('a.*, s.name as subject_name, s.code as subject_code');
        $builder->join('subjects s', 's.id = a.subject_id', 'left');
        $builder->where('a.org_id', $orgId);
        $builder->where('a.is_published', 1);

        if ($cohortId) {
            $builder->groupStart()
                    ->where('a.cohort_id', $cohortId)
                    ->orWhere('a.cohort_id', null)
                    ->orWhere('a.cohort_id', 0)
                    ->groupEnd();
        }

        $assessments = $builder->orderBy('a.due_date', 'ASC')->get()->getResultArray();

        // Attach submissions
        $subQuery = $db->table('lms_assessment_submissions')
                       ->where('student_id', $studentId)
                       ->get()->getResultArray();
        $subMap = [];
        foreach ($subQuery as $s) {
            $subMap[$s['assessment_id']] = $s;
        }

        foreach ($assessments as &$ass) {
            $ass['submission'] = $subMap[$ass['id']] ?? null;
            $ass['assessment_type'] = $ass['assessment_type'] ?? 'file_upload';
        }

        return view('lms/assessments/index', [
            'assessments' => $assessments
        ]);
    }

    public function view($id)
    {
        $assessmentModel = new AssessmentModel();
        $questionModel = new AssessmentQuestionModel();
        $submissionModel = new AssessmentSubmissionModel();

        $assessment = $assessmentModel->where('is_published', 1)->findByIdOrUuid($id);
        if (!$assessment) {
            return redirect()->to('lms/assessments')->with('error', 'Assessment not found or not published.');
        }
        $realId = $assessment['id'];

        // Get past submission if any
        $submission = $submissionModel->where('assessment_id', $realId)
            ->where('student_id', $this->student_id)
            ->first();

        // Questions for CBT or Video
        $questions = [];
        if (in_array($assessment['assessment_type'], ['cbt_quiz', 'interactive_video'])) {
            $qQuery = $questionModel->where('assessment_id', $realId);
            if ($assessment['randomize_questions'] && $assessment['assessment_type'] === 'cbt_quiz') {
                $qQuery->orderBy('RAND()');
            } else {
                $qQuery->orderBy('sort_order', 'ASC');
            }
            $questions = $qQuery->findAll();
        }

        switch ($assessment['assessment_type']) {
            case 'cbt_quiz':
                return view('lms/assessments/cbt_player', [
                    'assessment' => $assessment,
                    'questions' => $questions,
                    'submission' => $submission
                ]);
            case 'interactive_video':
                return view('lms/assessments/video_player', [
                    'assessment' => $assessment,
                    'questions' => $questions,
                    'submission' => $submission
                ]);
            case 'essay':
                return view('lms/assessments/essay_editor', [
                    'assessment' => $assessment,
                    'submission' => $submission
                ]);
            case 'file_upload':
            default:
                return view('lms/assessments/file_submit', [
                    'assessment' => $assessment,
                    'submission' => $submission
                ]);
        }
    }

    public function submit($id)
    {
        $assessmentModel = new AssessmentModel();
        $questionModel = new AssessmentQuestionModel();
        $submissionModel = new AssessmentSubmissionModel();

        $assessment = $assessmentModel->findByIdOrUuid($id);
        if (!$assessment) {
            return redirect()->to('lms/assessments')->with('error', 'Assessment not found.');
        }
        $realId = $assessment['id'];

        $isLate = (!empty($assessment['due_date']) && strtotime($assessment['due_date']) < time()) ? 1 : 0;
        $type = $assessment['assessment_type'];

        $submissionData = [
            'assessment_id' => $realId,
            'student_id'    => $this->student_id,
            'submitted_at'  => date('Y-m-d H:i:s'),
            'is_late'       => $isLate,
            'status'        => 'submitted'
        ];

        if ($type === 'file_upload') {
            $file = $this->request->getFile('submitted_file');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                if (!$this->validate([
                    'submitted_file' => [
                        'label' => 'Submission File',
                        'rules' => 'uploaded[submitted_file]|ext_in[submitted_file,pdf,doc,docx,zip,rar,txt,jpg,jpeg,png,webp]|max_size[submitted_file,25600]'
                    ]
                ])) {
                    return redirect()->back()->withInput()->with('error', $this->validator->getError('submitted_file') ?: 'Invalid file type or file exceeds 25MB limit.');
                }
                $newName = $file->getRandomName();
                $targetDir = FCPATH . 'uploads/submissions/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                $file->move($targetDir, $newName);
                $submissionData['submitted_file'] = 'uploads/submissions/' . $newName;
            }
            $submissionData['student_comments'] = $this->request->getPost('student_comments');
        } elseif ($type === 'essay') {
            $essayText = $this->request->getPost('submitted_essay');
            $wordCount = str_word_count(strip_tags($essayText));
            $submissionData['submitted_essay'] = $essayText;
            $submissionData['essay_word_count'] = $wordCount;
        } elseif (in_array($type, ['cbt_quiz', 'interactive_video'])) {
            $rawAnswers = $this->request->getPost('answers') ?? [];
            $questions = $questionModel->where('assessment_id', $id)->findAll();
            
            $totalPoints = 0;
            $earnedPoints = 0;

            foreach ($questions as $q) {
                $qId = $q['id'];
                $totalPoints += (float)$q['points'];
                $userAns = $rawAnswers[$qId] ?? null;

                if ($userAns !== null && (string)$userAns === (string)$q['correct_option']) {
                    $earnedPoints += (float)$q['points'];
                }
            }

            // Scale to max marks
            $scaledScore = ($totalPoints > 0) ? ($earnedPoints / $totalPoints) * $assessment['max_marks'] : 0;
            $submissionData['answers_payload'] = json_encode($rawAnswers);
            $submissionData['auto_score'] = $scaledScore;
            $submissionData['final_marks'] = $scaledScore;
            $submissionData['status'] = 'graded';
            $submissionData['graded_at'] = date('Y-m-d H:i:s');
        }

        // Check if existing record
        $existing = $submissionModel->where('assessment_id', $id)
            ->where('student_id', $this->student_id)
            ->first();

        if ($existing) {
            $submissionModel->update($existing['id'], $submissionData);
        } else {
            $submissionData['started_at'] = date('Y-m-d H:i:s');
            $submissionModel->insert($submissionData);
        }

        return redirect()->to('lms/assessments/view/' . $id)->with('success', 'Assessment submitted successfully!');
    }
}
