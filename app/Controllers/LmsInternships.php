<?php

namespace App\Controllers;

use App\Models\InternshipPostingModel;
use App\Models\InternshipEnrollmentModel;
use App\Models\InternshipSubmissionModel;
use App\Models\InternshipCommentModel;

class LmsInternships extends BaseController
{
    protected $student_id;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->student_id = session('student_id');
    }

    public function index()
    {
        $db = \Config\Database::connect();
        
        // Active student enrollments
        $myEnrollments = $db->table('internship_enrollments e')
            ->select('e.*, p.company_name, p.role_title, p.work_mode, p.location, p.stipend_amount')
            ->join('internship_postings p', 'p.id = e.posting_id')
            ->where('e.student_id', $this->student_id)
            ->orderBy('e.created_at', 'DESC')
            ->get()->getResultArray();

        // Available postings
        $openPostings = $db->table('internship_postings p')
            ->where('p.status', 'published')
            ->where('p.application_deadline >=', date('Y-m-d'))
            ->orderBy('p.created_at', 'DESC')
            ->get()->getResultArray();

        return view('lms/internships/index', [
            'enrollments'  => $myEnrollments,
            'openPostings' => $openPostings
        ]);
    }

    public function apply($postingId)
    {
        $postingModel = new InternshipPostingModel();
        $posting = $postingModel->where('status', 'published')->findByIdOrUuid($postingId);

        if (!$posting) {
            return redirect()->to('lms/internships')->with('error', 'Posting not found.');
        }

        return view('lms/internships/apply', [
            'posting' => $posting
        ]);
    }

    public function submitApplication()
    {
        $enrollmentModel = new InternshipEnrollmentModel();
        $postingModel = new InternshipPostingModel();
        $postingParam = $this->request->getPost('posting_id');
        $posting = $postingModel->findByIdOrUuid($postingParam);
        $postingId = $posting ? $posting['id'] : $postingParam;

        // Check if already applied
        $existing = $enrollmentModel->where('posting_id', $postingId)
            ->where('student_id', $this->student_id)
            ->first();

        if ($existing) {
            return redirect()->to('lms/internships')->with('error', 'You have already applied for this position.');
        }

        $resumePath = null;
        $resumeFile = $this->request->getFile('resume_file');
        if ($resumeFile && $resumeFile->isValid() && !$resumeFile->hasMoved()) {
            if (!$this->validate([
                'resume_file' => [
                    'label' => 'Resume Document',
                    'rules' => 'uploaded[resume_file]|ext_in[resume_file,pdf,doc,docx]|max_size[resume_file,10240]'
                ]
            ])) {
                return redirect()->back()->withInput()->with('error', $this->validator->getError('resume_file') ?: 'Invalid resume format (PDF/DOCX allowed) or exceeds 10MB.');
            }
            $newName = $resumeFile->getRandomName();
            $targetDir = FCPATH . 'uploads/resumes/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
            $resumeFile->move($targetDir, $newName);
            $resumePath = 'uploads/resumes/' . $newName;
        }

        $enrollmentModel->insert([
            'posting_id'   => $postingId,
            'student_id'   => $this->student_id,
            'resume_file'  => $resumePath,
            'cover_letter' => $this->request->getPost('cover_letter'),
            'status'       => 'applied'
        ]);

        return redirect()->to('lms/internships')->with('success', 'Your internship application has been submitted successfully!');
    }

    public function respondOffer()
    {
        $enrollmentModel = new InternshipEnrollmentModel();
        $enrollmentParam = $this->request->getPost('enrollment_id');
        $decision = $this->request->getPost('decision'); // accept or decline

        $enrollment = $enrollmentModel->where('student_id', $this->student_id)->findByIdOrUuid($enrollmentParam);
        if (!$enrollment) {
            return redirect()->back()->with('error', 'Offer record not found.');
        }
        $enrollmentId = $enrollment['id'];

        if ($decision === 'accept') {
            $enrollmentModel->update($enrollmentId, ['status' => 'in_progress']);
            return redirect()->to('lms/internships/workspace/' . ($enrollment['uuid'] ?? $enrollmentId))->with('success', 'Congratulations! Offer accepted. Welcome to your internship workspace.');
        } else {
            $enrollmentModel->update($enrollmentId, ['status' => 'cancelled', 'rejection_reason' => 'Declined by student']);
            return redirect()->to('lms/internships')->with('success', 'Offer declined.');
        }
    }

    public function workspace($enrollmentId)
    {
        $db = \Config\Database::connect();
        $enrollmentModel = new InternshipEnrollmentModel();
        $enrollmentRec = $enrollmentModel->where('student_id', $this->student_id)->findByIdOrUuid($enrollmentId);
        if (!$enrollmentRec) {
            return redirect()->to('lms/internships')->with('error', 'Workspace not found.');
        }
        $realEnrollmentId = $enrollmentRec['id'];

        $enrollment = $db->table('internship_enrollments e')
            ->select('e.*, p.company_name, p.role_title, p.start_date, p.end_date')
            ->join('internship_postings p', 'p.id = e.posting_id')
            ->where('e.id', $realEnrollmentId)
            ->where('e.student_id', $this->student_id)
            ->get()->getRowArray();

        if (!$enrollment) {
            return redirect()->to('lms/internships')->with('error', 'Workspace not found.');
        }

        $milestones = $db->table('internship_milestones')
            ->where('posting_id', $enrollment['posting_id'])
            ->orderBy('milestone_number', 'ASC')
            ->get()->getResultArray();

        $allTasksCount = 0;
        $completedTasksCount = 0;

        foreach ($milestones as &$m) {
            $tasks = $db->table('internship_tasks')
                ->where('milestone_id', $m['id'])
                ->orderBy('sort_order', 'ASC')
                ->get()->getResultArray();

            foreach ($tasks as &$t) {
                $allTasksCount++;
                $sub = $db->table('internship_task_submissions')
                    ->where('enrollment_id', $enrollmentId)
                    ->where('task_id', $t['id'])
                    ->get()->getRowArray();

                if ($sub && $sub['faculty_status'] === 'approved') {
                    $completedTasksCount++;
                }

                $t['submission'] = $sub;
                $t['comments'] = $sub ? $db->table('internship_comments')->where('submission_id', $sub['id'])->orderBy('created_at', 'ASC')->get()->getResultArray() : [];
            }
            $m['tasks'] = $tasks;
        }

        $progressPct = ($allTasksCount > 0) ? round(($completedTasksCount / $allTasksCount) * 100, 1) : 0;

        return view('lms/internships/workspace', [
            'enrollment'  => $enrollment,
            'milestones'  => $milestones,
            'progressPct' => $progressPct
        ]);
    }

    public function submitTask()
    {
        $subModel = new InternshipSubmissionModel();
        $enrollmentId = $this->request->getPost('enrollment_id');
        $taskId = $this->request->getPost('task_id');
        $trackedSeconds = intval($this->request->getPost('tracked_time_seconds') ?: 0);

        $filePath = null;
        $file = $this->request->getFile('submission_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (!$this->validate([
                'submission_file' => [
                    'label' => 'Task Submission File',
                    'rules' => 'uploaded[submission_file]|ext_in[submission_file,pdf,doc,docx,zip,rar,txt,jpg,jpeg,png,webp,mp4]|max_size[submission_file,51200]'
                ]
            ])) {
                return redirect()->back()->withInput()->with('error', $this->validator->getError('submission_file') ?: 'Invalid submission file type or exceeds 50MB.');
            }
            $newName = $file->getRandomName();
            $targetDir = FCPATH . 'uploads/internship_tasks/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
            $file->move($targetDir, $newName);
            $filePath = 'uploads/internship_tasks/' . $newName;
        }

        $existing = $subModel->where('enrollment_id', $enrollmentId)->where('task_id', $taskId)->first();
        $payload = [
            'enrollment_id'        => $enrollmentId,
            'task_id'              => $taskId,
            'tracked_time_seconds' => ($existing['tracked_time_seconds'] ?? 0) + $trackedSeconds,
            'submission_text'      => $this->request->getPost('submission_text'),
            'submission_link'      => $this->request->getPost('submission_link'),
            'student_submitted_at' => date('Y-m-d H:i:s'),
            'faculty_status'       => 'pending',
            'supervisor_status'    => 'pending'
        ];

        if ($filePath) {
            $payload['submission_file'] = $filePath;
        }

        if ($existing) {
            $subModel->update($existing['id'], $payload);
        } else {
            $subModel->insert($payload);
        }

        return redirect()->back()->with('success', 'Task deliverable submitted for faculty mentor review!');
    }

    public function addComment()
    {
        $commentModel = new InternshipCommentModel();
        $subId = $this->request->getPost('submission_id');
        $msg = $this->request->getPost('message');

        $student = (new \App\Models\StudentModel())->find($this->student_id);
        $name = ($student['first_name'] ?? 'Student') . ' ' . ($student['last_name'] ?? '');

        $commentModel->insert([
            'submission_id' => $subId,
            'author_type'   => 'student',
            'author_id'     => $this->student_id,
            'author_name'   => $name,
            'message'       => $msg
        ]);

        return redirect()->back()->with('success', 'Comment posted.');
    }

    public function submitMidReview()
    {
        $enrollmentModel = new InternshipEnrollmentModel();
        $enrollmentId = $this->request->getPost('enrollment_id');
        $notes = $this->request->getPost('student_mid_review');

        $enrollmentModel->update($enrollmentId, [
            'student_mid_review' => $notes
        ]);

        return redirect()->back()->with('success', 'Midpoint review submitted to your mentor and supervisor.');
    }
}
