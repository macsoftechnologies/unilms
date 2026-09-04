<?php

namespace App\Controllers;

use App\Models\InternshipPostingModel;
use App\Models\InternshipMilestoneModel;
use App\Models\InternshipTaskModel;
use App\Models\InternshipEnrollmentModel;
use App\Models\InternshipSubmissionModel;
use App\Models\InternshipCommentModel;
use App\Models\FacultyProfileModel;
use App\Models\DepartmentModel;
use App\Models\StudentModel;

class OrgInternships extends BaseController
{
    public function index()
    {
        $postingModel = new InternshipPostingModel();
        $db = \Config\Database::connect();

        $builder = $db->table('internship_postings p');
        $builder->select('p.*, d.name as department_name');
        $builder->join('departments d', 'd.id = p.target_department_id', 'left');
        $builder->where('p.org_id', $this->org_id);
        $builder->orderBy('p.created_at', 'DESC');
        $postings = $builder->get()->getResultArray();

        // Attach counts
        foreach ($postings as &$post) {
            $post['applicant_count'] = $db->table('internship_enrollments')
                ->where('posting_id', $post['id'])
                ->countAllResults();
            $post['active_count'] = $db->table('internship_enrollments')
                ->where('posting_id', $post['id'])
                ->where('status', 'in_progress')
                ->countAllResults();
        }

        return view('org/internships/index', [
            'postings' => $postings
        ]);
    }

    public function createPosting()
    {
        $deptModel = new DepartmentModel();
        $departments = $deptModel->where('org_id', $this->org_id)->findAll();

        return view('org/internships/create_posting', [
            'departments' => $departments,
            'posting' => null,
            'milestones' => []
        ]);
    }

    public function editPosting($id)
    {
        $postingModel = new InternshipPostingModel();
        $milestoneModel = new InternshipMilestoneModel();
        $taskModel = new InternshipTaskModel();
        $deptModel = new DepartmentModel();

        $posting = $postingModel->where('org_id', $this->org_id)->find($id);
        if (!$posting) {
            return redirect()->to('org/internships')->with('error', 'Posting not found.');
        }

        $departments = $deptModel->where('org_id', $this->org_id)->findAll();
        $milestones = $milestoneModel->where('posting_id', $id)->orderBy('milestone_number', 'ASC')->findAll();
        foreach ($milestones as &$m) {
            $m['tasks'] = $taskModel->where('milestone_id', $m['id'])->orderBy('sort_order', 'ASC')->findAll();
        }

        return view('org/internships/create_posting', [
            'departments' => $departments,
            'posting' => $posting,
            'milestones' => $milestones
        ]);
    }

    public function savePosting()
    {
        $postingModel = new InternshipPostingModel();
        $milestoneModel = new InternshipMilestoneModel();
        $taskModel = new InternshipTaskModel();

        $id = $this->request->getPost('id');
        $data = [
            'org_id'               => $this->org_id,
            'company_name'         => $this->request->getPost('company_name'),
            'role_title'           => $this->request->getPost('role_title'),
            'description'          => $this->request->getPost('description'),
            'required_skills'      => $this->request->getPost('required_skills'),
            'work_mode'            => $this->request->getPost('work_mode'),
            'location'             => $this->request->getPost('location'),
            'stipend_amount'       => $this->request->getPost('stipend_amount') ?: 0.00,
            'total_seats'          => $this->request->getPost('total_seats') ?: 1,
            'available_seats'      => $this->request->getPost('total_seats') ?: 1,
            'min_cgpa'             => $this->request->getPost('min_cgpa') ?: 0.00,
            'target_department_id' => $this->request->getPost('target_department_id') ?: null,
            'application_deadline' => $this->request->getPost('application_deadline'),
            'start_date'           => $this->request->getPost('start_date'),
            'end_date'             => $this->request->getPost('end_date'),
            'allow_company_tasks'  => $this->request->getPost('allow_company_tasks') ? 1 : 0,
            'status'               => $this->request->getPost('status') ?: 'published',
        ];

        if (empty($id)) {
            $postingId = $postingModel->insert($data);
        } else {
            $postingModel->update($id, $data);
            $postingId = $id;
        }

        // Process Roadmap (Milestones & Tasks)
        $milestonesPayload = $this->request->getPost('milestones') ?? [];
        if (!empty($milestonesPayload)) {
            // Delete existing tasks/milestones for clean update if editing
            $existingMilestones = $milestoneModel->where('posting_id', $postingId)->findAll();
            foreach ($existingMilestones as $em) {
                $taskModel->where('milestone_id', $em['id'])->delete();
            }
            $milestoneModel->where('posting_id', $postingId)->delete();

            foreach ($milestonesPayload as $mIdx => $m) {
                if (empty(trim($m['title'] ?? ''))) continue;

                $milestoneId = $milestoneModel->insert([
                    'posting_id'       => $postingId,
                    'milestone_number' => $mIdx + 1,
                    'title'            => $m['title'],
                    'description'      => $m['description'] ?? '',
                    'is_midpoint_gate' => !empty($m['is_midpoint_gate']) ? 1 : 0
                ]);

                $tasks = $m['tasks'] ?? [];
                foreach ($tasks as $tIdx => $t) {
                    if (empty(trim($t['task_title'] ?? ''))) continue;

                    $taskModel->insert([
                        'milestone_id'        => $milestoneId,
                        'task_title'          => $t['task_title'],
                        'description'         => $t['description'] ?? '',
                        'expected_output'     => $t['expected_output'] ?? '',
                        'estimated_hours'     => $t['estimated_hours'] ?? 5.00,
                        'submission_type'     => $t['submission_type'] ?? 'file',
                        'is_company_added'    => 0,
                        'is_faculty_approved' => 1,
                        'sort_order'          => $tIdx
                    ]);
                }
            }
        }

        return redirect()->to('org/internships')->with('success', 'Internship posting and roadmap published successfully.');
    }

    public function applications($postingId = null)
    {
        $db = \Config\Database::connect();
        $facultyModel = new FacultyProfileModel();
        $faculty = $facultyModel->where('org_id', $this->org_id)->findAll();

        $builder = $db->table('internship_enrollments e');
        $builder->select('e.*, s.first_name, s.last_name, s.roll_number, s.email as student_email, p.company_name, p.role_title, f.first_name as mentor_first, f.last_name as mentor_last');
        $builder->join('students s', 's.id = e.student_id');
        $builder->join('internship_postings p', 'p.id = e.posting_id');
        $builder->join('faculty_profiles f', 'f.id = e.faculty_mentor_id', 'left');
        $builder->where('p.org_id', $this->org_id);

        if ($postingId) {
            $builder->where('e.posting_id', $postingId);
        }
        $builder->orderBy('e.created_at', 'DESC');
        $applications = $builder->get()->getResultArray();

        return view('org/internships/applications', [
            'applications' => $applications,
            'faculty'      => $faculty,
            'postingId'    => $postingId
        ]);
    }

    public function updateApplicationStatus()
    {
        $enrollmentModel = new InternshipEnrollmentModel();
        $id = $this->request->getPost('enrollment_id');
        $action = $this->request->getPost('action');

        $enrollment = $enrollmentModel->find($id);
        if (!$enrollment) {
            return redirect()->back()->with('error', 'Application record not found.');
        }

        $data = [];
        if ($action === 'shortlist') {
            $data['status'] = 'shortlisted';
        } elseif ($action === 'reject') {
            $data['status'] = 'rejected';
            $data['rejection_reason'] = $this->request->getPost('rejection_reason');
        } elseif ($action === 'extend_offer') {
            $validityDays = intval($this->request->getPost('validity_days') ?: 3);
            $data['status'] = 'offered';
            $data['faculty_mentor_id'] = $this->request->getPost('faculty_mentor_id');
            $data['offer_sent_at'] = date('Y-m-d H:i:s');
            $data['offer_valid_until'] = date('Y-m-d H:i:s', strtotime("+{$validityDays} days"));
        } elseif ($action === 'invite_supervisor') {
            $rawToken = bin2hex(random_bytes(32));
            $data['supervisor_name'] = $this->request->getPost('supervisor_name');
            $data['supervisor_email'] = $this->request->getPost('supervisor_email');
            $data['supervisor_magic_token'] = hash('sha256', $rawToken);
            $data['token_expires_at'] = date('Y-m-d H:i:s', strtotime('+120 days'));
            
            $enrollmentModel->update($id, $data);
            $magicLink = site_url('internship-supervisor/' . $rawToken);
            return redirect()->back()->with('success', "Magic token generated! Supervisor Access Link: {$magicLink}");
        }

        $enrollmentModel->update($id, $data);
        return redirect()->back()->with('success', 'Application status updated.');
    }

    public function mentorship()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('internship_enrollments e');
        $builder->select('e.*, s.first_name, s.last_name, s.roll_number, p.company_name, p.role_title');
        $builder->join('students s', 's.id = e.student_id');
        $builder->join('internship_postings p', 'p.id = e.posting_id');
        $builder->where('e.status', 'in_progress');
        
        if (!session('is_org_admin') && !$this->hasPermission('manage_academics_global')) {
            $builder->where('e.faculty_mentor_id', $this->org_user_id);
        }
        $enrollments = $builder->get()->getResultArray();

        return view('org/internships/faculty_mentorship', [
            'enrollments' => $enrollments
        ]);
    }

    public function reviewStudent($enrollmentId)
    {
        $db = \Config\Database::connect();
        $enrollment = $db->table('internship_enrollments e')
            ->select('e.*, s.first_name, s.last_name, s.roll_number, p.company_name, p.role_title')
            ->join('students s', 's.id = e.student_id')
            ->join('internship_postings p', 'p.id = e.posting_id')
            ->where('e.id', $enrollmentId)
            ->get()->getRowArray();

        if (!$enrollment) {
            return redirect()->to('org/internships/mentorship')->with('error', 'Student enrollment not found.');
        }

        // Milestones & Submissions
        $milestones = $db->table('internship_milestones')
            ->where('posting_id', $enrollment['posting_id'])
            ->orderBy('milestone_number', 'ASC')
            ->get()->getResultArray();

        foreach ($milestones as &$m) {
            $tasks = $db->table('internship_tasks')
                ->where('milestone_id', $m['id'])
                ->orderBy('sort_order', 'ASC')
                ->get()->getResultArray();

            foreach ($tasks as &$t) {
                $sub = $db->table('internship_task_submissions')
                    ->where('enrollment_id', $enrollmentId)
                    ->where('task_id', $t['id'])
                    ->get()->getRowArray();
                
                $t['submission'] = $sub;
                $t['comments'] = $sub ? $db->table('internship_comments')->where('submission_id', $sub['id'])->orderBy('created_at', 'ASC')->get()->getResultArray() : [];
            }
            $m['tasks'] = $tasks;
        }

        return view('org/internships/review_student', [
            'enrollment' => $enrollment,
            'milestones' => $milestones
        ]);
    }

    public function evaluateTask()
    {
        $subModel = new InternshipSubmissionModel();
        $subId = $this->request->getPost('submission_id');
        $status = $this->request->getPost('faculty_status');
        $feedback = $this->request->getPost('faculty_feedback');

        $subModel->update($subId, [
            'faculty_status'      => $status,
            'faculty_feedback'    => $feedback,
            'faculty_approved_at' => ($status === 'approved') ? date('Y-m-d H:i:s') : null
        ]);

        return redirect()->back()->with('success', 'Task review recorded.');
    }

    public function submitFinalRubric()
    {
        $enrollmentModel = new InternshipEnrollmentModel();
        $id = $this->request->getPost('enrollment_id');

        $tech = (float)$this->request->getPost('rubric_technical');
        $comm = (float)$this->request->getPost('rubric_communication');
        $disc = (float)$this->request->getPost('rubric_discipline');
        $prob = (float)$this->request->getPost('rubric_problem_solving');
        $qual = (float)$this->request->getPost('rubric_quality_output');
        $att  = (float)$this->request->getPost('rubric_attendance');
        $viva = (float)$this->request->getPost('viva_marks');
        $rep  = (float)$this->request->getPost('report_marks');

        $avgScore = ($tech + $comm + $disc + $prob + $qual + $att) / 6;
        $weightedTotal = ($avgScore * 0.40) + ($viva * 0.30) + ($rep * 0.30);

        $grade = 'A+';
        if ($weightedTotal < 50) $grade = 'F';
        elseif ($weightedTotal < 60) $grade = 'C';
        elseif ($weightedTotal < 75) $grade = 'B';
        elseif ($weightedTotal < 85) $grade = 'A';

        $enrollmentModel->update($id, [
            'rubric_technical'       => $tech,
            'rubric_communication'   => $comm,
            'rubric_discipline'      => $disc,
            'rubric_problem_solving' => $prob,
            'rubric_quality_output'  => $qual,
            'rubric_attendance'      => $att,
            'viva_marks'             => $viva,
            'report_marks'           => $rep,
            'total_weighted_grade'   => $grade . " (" . round($weightedTotal, 1) . "%)",
            'faculty_final_remarks'  => $this->request->getPost('faculty_final_remarks')
        ]);

        return redirect()->back()->with('success', 'Final 6-Dimension Rubric evaluation submitted for TPO Audit.');
    }

    public function tpoAudit()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('internship_enrollments e');
        $builder->select('e.*, s.first_name, s.last_name, s.roll_number, p.company_name, p.role_title');
        $builder->join('students s', 's.id = e.student_id');
        $builder->join('internship_postings p', 'p.id = e.posting_id');
        $builder->where('e.status', 'in_progress');
        $builder->where('e.total_weighted_grade IS NOT NULL', null, false);
        $candidates = $builder->get()->getResultArray();

        return view('org/internships/tpo_audit', [
            'candidates' => $candidates
        ]);
    }

    public function issueCertificate()
    {
        $enrollmentModel = new InternshipEnrollmentModel();
        $id = $this->request->getPost('enrollment_id');
        $enrollment = $enrollmentModel->find($id);

        if (!$enrollment) {
            return redirect()->back()->with('error', 'Record not found.');
        }

        $certNumber = 'CERT-' . date('Y') . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
        $certHash = hash('sha256', $id . $certNumber . microtime(true) . 'UNILMS_KEY_SALT');

        $enrollmentModel->update($id, [
            'status'                => 'completed',
            'certificate_number'    => $certNumber,
            'certificate_hash'      => $certHash,
            'certificate_issued_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', "Certificate #{$certNumber} officially issued with verifiable QR hash!");
    }
}
