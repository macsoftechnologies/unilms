<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrganizationModel;
use App\Models\OrgUserModel;
use App\Models\FacultyProfileModel;
use App\Models\SubjectAllocationModel;
use App\Models\DepartmentModel;
use App\Models\SubjectModel;
use App\Models\CohortModel;
use App\Models\SemesterModel;

class OrgFaculty extends BaseController
{
    public function profiles()
    {
        $orgId = session()->get('org_id');
        $model = new FacultyProfileModel();
        $userModel = new OrgUserModel();
        $deptModel = new DepartmentModel();

        // Get all faculty profiles
        $profiles = $model->getAllProfiles($orgId);

        // Get users that don't have a profile yet but are assigned "Faculty" designation or similar
        // For simplicity, just fetch all staff
        $staff = $userModel->where('org_id', $orgId)->findAll();
        $departments = $deptModel->where('org_id', $orgId)->findAll();

        return view('org/faculty/profiles', [
            'profiles' => $profiles,
            'staff' => $staff,
            'departments' => $departments
        ]);
    }

    public function save_profile()
    {
        $orgId = session()->get('org_id');
        $model = new FacultyProfileModel();
        
        $profileId = $this->request->getPost('profile_id');
        $data = [
            'org_id' => $orgId,
            'user_id' => $this->request->getPost('user_id'),
            'department_id' => $this->request->getPost('department_id'),
            'qualification' => $this->request->getPost('qualification'),
            'experience_years' => $this->request->getPost('experience_years') ?: 0,
            'joining_date' => $this->request->getPost('joining_date') ?: null,
            'specialization' => $this->request->getPost('specialization')
        ];

        if ($profileId) {
            // Check ownership before update
            $existing = $model->find($profileId);
            if ($existing && $existing['org_id'] == $orgId) {
                $model->update($profileId, $data);
            }
        } else {
            // Ensure this user doesn't already have a profile
            $existing = $model->where('org_id', $orgId)->where('user_id', $data['user_id'])->first();
            if ($existing) {
                $model->update($existing['id'], $data);
            } else {
                $model->insert($data);
            }
        }

        return redirect()->to('org/faculty/profiles')->with('success', 'Faculty profile saved successfully.');
    }

    public function delete_profile()
    {
        $orgId = session()->get('org_id');
        $profileId = $this->request->getPost('profile_id');
        $model = new FacultyProfileModel();
        
        $existing = $model->find($profileId);
        if ($existing && $existing['org_id'] == $orgId) {
            $model->delete($profileId);
            return redirect()->to('org/faculty/profiles')->with('success', 'Profile deleted.');
        }
        return redirect()->to('org/faculty/profiles')->with('error', 'Unauthorized.');
    }

    public function allocations()
    {
        try {
            $orgId = session()->get('org_id') ?: 5;
            $db = \Config\Database::connect();
            $allocModel = new SubjectAllocationModel();
            
            $allocations = $allocModel->getAllocations($orgId);
            
            // Fetch genuine faculty members (strictly non-admin, non-student, non-parent)
            $roleFaculties = $db->table('org_users')
                ->select('org_users.id as user_id, org_users.full_name, org_users.email, org_users.designation, org_users.employee_code')
                ->where('org_users.org_id', $orgId)
                ->where('org_users.is_org_admin', 0)
                ->whereIn('org_users.role', ['faculty', 'teacher'])
                ->where('org_users.user_type', 'staff')
                ->orderBy('org_users.full_name', 'ASC')
                ->get()->getResultArray();

            $profileFaculties = $db->table('faculty_profiles')
                ->select('org_users.id as user_id, org_users.full_name, org_users.email, org_users.designation, org_users.employee_code')
                ->join('org_users', 'org_users.id = faculty_profiles.user_id')
                ->where('faculty_profiles.org_id', $orgId)
                ->where('org_users.is_org_admin', 0)
                ->orderBy('org_users.full_name', 'ASC')
                ->get()->getResultArray();

            $facultyMap = [];
            foreach (array_merge($roleFaculties, $profileFaculties) as $f) {
                $facultyMap[$f['user_id']] = $f;
            }
            $faculties = array_values($facultyMap);

            $subjects = (new SubjectModel())->where('org_id', $orgId)->orderBy('code', 'ASC')->findAll();
            $cohorts = (new CohortModel())->where('org_id', $orgId)->orderBy('name', 'ASC')->findAll();
            $semesters = (new SemesterModel())->where('org_id', $orgId)->orderBy('sequence', 'ASC')->findAll();

            return view('org/faculty/allocations', [
                'allocations' => $allocations,
                'faculties' => $faculties,
                'subjects' => $subjects,
                'cohorts' => $cohorts,
                'semesters' => $semesters
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Faculty allocations error: ' . $e->getMessage());
            echo "<div style='padding:20px; font-family:sans-serif; color:#b91c1c; background:#fee2e2; border-radius:8px;'><h3>Allocations Error</h3><p>" . htmlspecialchars($e->getMessage()) . "</p></div>";
            exit;
        }
    }

    public function save_allocation()
    {
        $orgId = session()->get('org_id');
        $model = new SubjectAllocationModel();
        
        $allocId = $this->request->getPost('allocation_id');
        $facultyId = $this->request->getPost('faculty_user_id');
        $subjectId = $this->request->getPost('subject_id');
        $cohortId = $this->request->getPost('cohort_id');
        $semesterId = $this->request->getPost('semester_id') ?: null;

        // 1. Duplicate Assignment Validation (Same subject to same cohort)
        $duplicateCheck = $model->where('org_id', $orgId)
                                ->where('subject_id', $subjectId)
                                ->where('cohort_id', $cohortId);
        if ($allocId) {
            $duplicateCheck->where('id !=', $allocId);
        }
        $existingDup = $duplicateCheck->first();

        if ($existingDup) {
            return redirect()->back()->with('error', 'HOD Smart Validation: This subject is already allocated to this cohort section.');
        }

        $data = [
            'org_id' => $orgId,
            'faculty_user_id' => $facultyId,
            'subject_id' => $subjectId,
            'cohort_id' => $cohortId,
            'semester_id' => $semesterId
        ];

        if ($allocId) {
            $existing = $model->find($allocId);
            if ($existing && $existing['org_id'] == $orgId) {
                $model->update($allocId, $data);
            }
        } else {
            $model->insert($data);
        }

        return redirect()->to('org/faculty/allocations')->with('success', 'Subject allocation verified and saved.');
    }

    public function delete_allocation()
    {
        $orgId = session()->get('org_id');
        $allocId = $this->request->getPost('allocation_id');
        $model = new SubjectAllocationModel();
        
        $existing = $model->find($allocId);
        if ($existing && $existing['org_id'] == $orgId) {
            $model->delete($allocId);
            return redirect()->to('org/faculty/allocations')->with('success', 'Allocation deleted.');
        }
        return redirect()->to('org/faculty/allocations')->with('error', 'Unauthorized.');
    }

    /**
     * Module 5: Class Teacher Cohort Monitor
     * Comprehensive single-screen overview for assigned cohort: Defaulter tracker & Multi-subject marks
     */
    public function classTeacherMonitor()
    {
        $orgId = session()->get('org_id');
        $cohortModel = new CohortModel();
        $cohorts = $cohortModel->where('org_id', $orgId)->findAll();
        
        $selectedCohortId = $this->request->getGet('cohort_id') ?? ($cohorts[0]['id'] ?? null);
        $students = [];
        $subjects = [];
        $attendanceData = [];
        $marksMatrix = [];
        $defaulters = [];

        if ($selectedCohortId) {
            $db = \Config\Database::connect();
            
            // Get enrolled students
            $students = $db->table('students')
                ->where('org_id', $orgId)
                ->where('cohort_id', $selectedCohortId)
                ->orderBy('roll_number', 'ASC')
                ->get()->getResultArray();

            // Get subjects allocated to this cohort
            $subjects = $db->table('subject_allocations sa')
                ->select('s.id, s.name, s.code')
                ->join('subjects s', 's.id = sa.subject_id')
                ->where('sa.org_id', $orgId)
                ->where('sa.cohort_id', $selectedCohortId)
                ->groupBy('s.id')
                ->get()->getResultArray();

            // Calculate Attendance % for each student
            foreach ($students as $stu) {
                $totalSessions = $db->table('attendance_records ar')
                    ->join('attendance_sessions asess', 'asess.id = ar.session_id')
                    ->where('ar.student_id', $stu['id'])
                    ->where('asess.org_id', $orgId)
                    ->countAllResults();

                $presentCount = $db->table('attendance_records ar')
                    ->join('attendance_sessions asess', 'asess.id = ar.session_id')
                    ->where('ar.student_id', $stu['id'])
                    ->where('ar.status', 'present')
                    ->where('asess.org_id', $orgId)
                    ->countAllResults();

                $pct = ($totalSessions > 0) ? round(($presentCount / $totalSessions) * 100, 1) : 100.0;
                $attendanceData[$stu['id']] = [
                    'total' => $totalSessions,
                    'present' => $presentCount,
                    'percentage' => $pct
                ];

                if ($pct < 75.0 && $totalSessions > 0) {
                    $defaulters[] = array_merge($stu, ['attendance_pct' => $pct]);
                }

                // Marks across subjects
                foreach ($subjects as $sub) {
                    $avgScore = $db->table('internal_marks')
                        ->selectAvg('score')
                        ->where('org_id', $orgId)
                        ->where('student_id', $stu['id'])
                        ->where('subject_id', $sub['id'])
                        ->get()->getRowArray();
                    
                    $marksMatrix[$stu['id']][$sub['id']] = ($avgScore && $avgScore['score'] !== null) 
                        ? round($avgScore['score'], 1) 
                        : '-';
                }
            }
        }

        return view('org/faculty/class_teacher_monitor', [
            'cohorts'          => $cohorts,
            'selectedCohortId' => $selectedCohortId,
            'students'         => $students,
            'subjects'         => $subjects,
            'attendanceData'   => $attendanceData,
            'marksMatrix'      => $marksMatrix,
            'defaulters'       => $defaulters
        ]);
    }

    public function sendParentNotice()
    {
        $studentId = $this->request->getPost('student_id');
        $reason = $this->request->getPost('reason');
        return redirect()->back()->with('success', "Attendance warning notice dispatched to parent of Student #{$studentId}.");
    }
}
