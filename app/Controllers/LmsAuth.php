<?php

namespace App\Controllers;

use App\Models\OrganizationModel;

class LmsAuth extends BaseController
{
    public function login()
    {
        if (session()->get('is_lms_logged_in')) {
            return redirect()->to('lms/dashboard');
        }

        // Just load the login view (will create this next)
        return view('lms/login');
    }

    public function authenticate()
    {
        try {
            $rollNumber = trim((string)($this->request->getPost('roll_number') ?: $this->request->getPost('login_id')));
            $password = (string)$this->request->getPost('password');

            if (empty($rollNumber) || empty($password)) {
                return redirect()->to(base_url('lms/login'))->with('error', 'Please enter your Student Roll Number and password.');
            }

            $db = \Config\Database::connect();
            
            // 1. Look up student by roll_number
            $student = $db->table('students')->where('roll_number', $rollNumber)->get()->getRowArray();

            if (!$student || empty($student['user_id'])) {
                return redirect()->to(base_url('lms/login'))->with('error', 'Invalid Student Roll Number or password.');
            }

            // 2. Fetch linked student user account
            $user = $db->table('org_users')->where('id', $student['user_id'])->get()->getRowArray();

            if (!$user) {
                return redirect()->to(base_url('lms/login'))->with('error', 'Student user account not found.');
            }

            if (!password_verify($password, $user['password_hash'])) {
                return redirect()->to(base_url('lms/login'))->with('error', 'Invalid Student Roll Number or password.');
            }

            // 3. Check Organization
            $org = $db->table('organizations')->where('id', $user['org_id'])->get()->getRowArray();

            if ($org && isset($org['status']) && $org['status'] === 'suspended') {
                return redirect()->to(base_url('lms/login'))->with('error', 'Your institution\'s account is suspended.');
            }

            // 4. Fetch student details with joined academic info
            $academicInfo = $db->table('students s')
                               ->select('s.*, c.name as cohort_name, p.name as program_short, p.code as program_name, sem.name as semester_name, ay.name as academic_year_name')
                               ->join('cohorts c', 'c.id = s.cohort_id', 'left')
                               ->join('programs p', 'p.id = c.program_id', 'left')
                               ->join('semesters sem', 'sem.id = c.current_semester_id', 'left')
                               ->join('academic_years ay', 'ay.id = c.academic_year_id', 'left')
                               ->where('s.id', $student['id'])
                               ->get()->getRowArray();

            if ($academicInfo) {
                $student = array_merge($student, $academicInfo);
            }

            // 5. Set LMS Session with robust fallback defaults
            $sessionData = [
                'is_lms_logged_in'   => true,
                'org_id'             => $user['org_id'] ?? 5,
                'lms_org_id'         => $user['org_id'] ?? 5,
                'org_name'           => $org['name'] ?? 'Apex Institute of Technology',
                'org_user_id'        => $user['id'],
                'lms_user_id'        => $user['id'],
                'student_id'         => $student['id'],
                'lms_student_id'     => $student['id'],
                'user_type'          => 'student',
                'first_name'         => $student['first_name'] ?? 'Student',
                'last_name'          => $student['last_name'] ?? '',
                'user_name'          => trim(($student['first_name'] ?? 'Student') . ' ' . ($student['last_name'] ?? '')),
                'email'              => $user['email'] ?? '',
                'roll_number'        => $student['roll_number'],
                'cohort_id'          => $student['cohort_id'] ?? 1,
                'lms_cohort_id'      => $student['cohort_id'] ?? 1,
                'student_cohort_id'  => $student['cohort_id'] ?? 1,
                'cohort_name'        => $student['cohort_name'] ?? 'B.Tech CSE - 2026 Batch',
                'program_name'       => $student['program_name'] ?? 'B.Tech Computer Science & Engineering',
                'program_code'       => $student['program_short'] ?? 'B.Tech CSE',
                'semester_name'      => $student['semester_name'] ?? 'Semester 1',
                'academic_year_name' => $student['academic_year_name'] ?? '2026-2027'
            ];
            
            session()->set($sessionData);

            return redirect()->to(base_url('lms/dashboard'));
        } catch (\Throwable $e) {
            log_message('error', 'LmsAuth authenticate exception: ' . $e->getMessage());
            return redirect()->to(base_url('lms/login'))->with('error', 'Authentication error: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('lms/login')->with('success', 'You have been successfully logged out.');
    }
}
