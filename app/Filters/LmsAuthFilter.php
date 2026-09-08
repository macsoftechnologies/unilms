<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\OrganizationModel;

class LmsAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Auto-bootstrap student LMS session if already logged in through main auth
        if (!$session->get('is_lms_logged_in') && $session->get('org_user_id')) {
            $db = \Config\Database::connect();
            $user = $db->table('org_users')->where('id', $session->get('org_user_id'))->get()->getRowArray();
            if ($user && ($user['user_type'] === 'student' || ($user['role'] ?? '') === 'student')) {
                $student = $db->table('students s')
                              ->select('s.*, c.name as cohort_name, p.name as program_short, p.code as program_name, sem.name as semester_name, ay.name as academic_year_name')
                              ->join('cohorts c', 'c.id = s.cohort_id', 'left')
                              ->join('programs p', 'p.id = c.program_id', 'left')
                              ->join('semesters sem', 'sem.id = c.current_semester_id', 'left')
                              ->join('academic_years ay', 'ay.id = c.academic_year_id', 'left')
                              ->where('s.user_id', $user['id'])
                              ->get()->getRowArray();
                
                $orgModel = new OrganizationModel();
                $org = $orgModel->find($user['org_id']);

                $session->set([
                    'is_lms_logged_in'   => true,
                    'org_id'             => $user['org_id'],
                    'lms_org_id'         => $user['org_id'],
                    'org_name'           => $org['name'] ?? 'Apex Institute of Technology',
                    'org_user_id'        => $user['id'],
                    'lms_user_id'        => $user['id'],
                    'student_id'         => $student['id'] ?? 3,
                    'lms_student_id'     => $student['id'] ?? 3,
                    'user_type'          => 'student',
                    'first_name'         => $student['first_name'] ?? $user['full_name'],
                    'last_name'          => $student['last_name'] ?? '',
                    'user_name'          => trim(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? '')) ?: $user['full_name'],
                    'email'              => $user['email'],
                    'roll_number'        => $student['roll_number'] ?? $user['employee_code'],
                    'cohort_id'          => $student['cohort_id'] ?? 1,
                    'lms_cohort_id'      => $student['cohort_id'] ?? 1,
                    'student_cohort_id'  => $student['cohort_id'] ?? 1,
                    'cohort_name'        => $student['cohort_name'] ?? 'B.Tech CSE - 2026 Batch',
                    'program_name'       => $student['program_name'] ?? 'B.Tech Computer Science & Engineering',
                    'program_code'       => $student['program_short'] ?? 'B.Tech CSE',
                    'semester_name'      => $student['semester_name'] ?? 'Semester 1',
                    'academic_year_name' => $student['academic_year_name'] ?? '2026-2027'
                ]);
            }
        }

        if (!$session->get('is_lms_logged_in')) {
            return redirect()->to(base_url('lms/login'))->with('error', 'Please log in to access the student portal.');
        }

        // Verify user is actually a student
        if ($session->get('user_type') !== 'student') {
            $session->destroy();
            return redirect()->to(base_url('lms/login'))->with('error', 'Access denied. You are not a student.');
        }

        // Check organization subscription status and LMS access
        $orgModel = new OrganizationModel();
        $org = $orgModel->find($session->get('org_id'));

        if (!$org || $org['status'] !== 'active') {
            $session->destroy();
            return redirect()->to('/lms/login')->with('error', 'Your institution\'s account is currently inactive. Please contact your administration.');
        }

        // Always sync organization toggle in session
        $session->set('lms_enabled', (int)($org['lms_enabled'] ?? 0));
        $session->set('cms_enabled', (int)($org['cms_enabled'] ?? 0));

        // If trying to access LMS learning hub/materials when LMS toggle is off, redirect to portal dashboard
        $uriPath = trim($request->getUri()->getPath(), '/');
        if (!$org['lms_enabled'] && (strpos($uriPath, 'lms/learn') !== false || strpos($uriPath, 'lms/materials') !== false)) {
            return redirect()->to(base_url('lms/dashboard'))->with('error', 'The Learning Management System is not enabled for your institution.');
        }
        
        $currentDate = date('Y-m-d');
        if ($org['subscription_end_date'] && $org['subscription_end_date'] < $currentDate) {
            $session->destroy();
            return redirect()->to('/lms/login')->with('error', 'Your institution\'s subscription has expired.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0');
        $response->setHeader('Pragma', 'no-cache');
        $response->setHeader('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        return $response;
    }
}
