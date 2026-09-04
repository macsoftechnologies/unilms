<?php

namespace App\Controllers;

use App\Models\InternshipEnrollmentModel;
use App\Models\InternshipSubmissionModel;
use App\Models\InternshipTaskModel;
use App\Models\InternshipCommentModel;

class InternshipSupervisor extends BaseController
{
    public function portal($rawToken)
    {
        $hashed = hash('sha256', $rawToken);
        $db = \Config\Database::connect();

        $enrollment = $db->table('internship_enrollments e')
            ->select('e.*, s.first_name, s.last_name, s.roll_number, s.email as student_email, p.company_name, p.role_title, p.allow_company_tasks')
            ->join('students s', 's.id = e.student_id')
            ->join('internship_postings p', 'p.id = e.posting_id')
            ->where('e.supervisor_magic_token', $hashed)
            ->where('e.token_expires_at >=', date('Y-m-d H:i:s'))
            ->get()->getRowArray();

        if (!$enrollment) {
            return view('supervisor/invalid_token');
        }

        // Milestones & Submissions
        $milestones = $db->table('internship_milestones')
            ->where('posting_id', $enrollment['posting_id'])
            ->orderBy('milestone_number', 'ASC')
            ->get()->getResultArray();

        $totalTasks = 0;
        $signedTasks = 0;

        foreach ($milestones as &$m) {
            $tasks = $db->table('internship_tasks')
                ->where('milestone_id', $m['id'])
                ->orderBy('sort_order', 'ASC')
                ->get()->getResultArray();

            foreach ($tasks as &$t) {
                $totalTasks++;
                $sub = $db->table('internship_task_submissions')
                    ->where('enrollment_id', $enrollment['id'])
                    ->where('task_id', $t['id'])
                    ->get()->getRowArray();

                if ($sub && $sub['supervisor_status'] === 'signed_off') {
                    $signedTasks++;
                }

                $t['submission'] = $sub;
                $t['comments'] = $sub ? $db->table('internship_comments')->where('submission_id', $sub['id'])->orderBy('created_at', 'ASC')->get()->getResultArray() : [];
            }
            $m['tasks'] = $tasks;
        }

        $progressPct = ($totalTasks > 0) ? round(($signedTasks / $totalTasks) * 100, 1) : 0;

        return view('supervisor/portal', [
            'enrollment'  => $enrollment,
            'milestones'  => $milestones,
            'progressPct' => $progressPct,
            'rawToken'    => $rawToken
        ]);
    }

    public function signoffTask()
    {
        $rawToken = $this->request->getPost('raw_token');
        $subId = $this->request->getPost('submission_id');
        $feedback = $this->request->getPost('supervisor_feedback');

        $subModel = new InternshipSubmissionModel();
        $subModel->update($subId, [
            'supervisor_status'    => 'signed_off',
            'supervisor_feedback'  => $feedback,
            'supervisor_signed_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('internship-supervisor/' . $rawToken)->with('success', 'Task successfully signed off!');
    }

    public function submitMidReview()
    {
        $rawToken = $this->request->getPost('raw_token');
        $enrollmentId = $this->request->getPost('enrollment_id');
        $rating = (float)$this->request->getPost('supervisor_mid_rating');
        $review = $this->request->getPost('supervisor_mid_review');

        $enrollmentModel = new InternshipEnrollmentModel();
        $enrollmentModel->update($enrollmentId, [
            'supervisor_mid_rating' => $rating,
            'supervisor_mid_review' => $review
        ]);

        return redirect()->to('internship-supervisor/' . $rawToken)->with('success', 'Corporate Mid-Review recorded.');
    }

    public function submitFinalSignoff()
    {
        $rawToken = $this->request->getPost('raw_token');
        $enrollmentId = $this->request->getPost('enrollment_id');
        $signoffText = $this->request->getPost('supervisor_final_signoff');

        $enrollmentModel = new InternshipEnrollmentModel();
        $enrollmentModel->update($enrollmentId, [
            'supervisor_final_signoff' => $signoffText
        ]);

        return redirect()->to('internship-supervisor/' . $rawToken)->with('success', 'Final Corporate Program Sign-off completed.');
    }

    public function addCompanyTask()
    {
        $rawToken = $this->request->getPost('raw_token');
        $milestoneId = $this->request->getPost('milestone_id');

        $taskModel = new InternshipTaskModel();
        $taskModel->insert([
            'milestone_id'        => $milestoneId,
            'task_title'          => $this->request->getPost('task_title'),
            'description'         => $this->request->getPost('description'),
            'expected_output'     => $this->request->getPost('expected_output'),
            'estimated_hours'     => (float)($this->request->getPost('estimated_hours') ?: 5),
            'submission_type'     => $this->request->getPost('submission_type') ?: 'file',
            'is_company_added'    => 1,
            'is_faculty_approved' => 1
        ]);

        return redirect()->to('internship-supervisor/' . $rawToken)->with('success', 'Company custom task added to student roadmap.');
    }
}
