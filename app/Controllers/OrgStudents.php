<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\StudentBioModel;
use App\Models\OrgUserModel;
use App\Models\CohortModel;
use App\Models\ProgramModel;
use App\Models\SemesterModel;
use App\Models\StudentFeeLedgerModel;
use App\Models\AttendanceRecordModel;
use App\Models\AttendanceSessionModel;
use App\Models\InternalMarkModel;
use App\Models\ParentStudentMapModel;
use App\Models\BacklogModel;

class OrgStudents extends BaseController
{
    public function index()
    {
        $orgId = session('org_id');
        $db = \Config\Database::connect();

        $cohortId = $this->request->getGet('cohort_id');
        $programId = $this->request->getGet('program_id');
        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');

        $builder = $db->table('students')
            ->select('students.*, cohorts.name as cohort_name, programs.name as program_name, programs.code as program_code, org_users.email, org_users.phone')
            ->join('cohorts', 'cohorts.id = students.cohort_id', 'left')
            ->join('programs', 'programs.id = cohorts.program_id', 'left')
            ->join('org_users', 'org_users.id = students.user_id', 'left')
            ->where('students.org_id', $orgId);

        if (!empty($cohortId)) {
            $builder->where('students.cohort_id', $cohortId);
        }
        if (!empty($programId)) {
            $builder->where('cohorts.program_id', $programId);
        }
        if (!empty($status)) {
            $builder->where('students.status', $status);
        }
        if (!empty($search)) {
            $builder->groupStart()
                ->like('students.roll_number', $search)
                ->orLike('students.first_name', $search)
                ->orLike('students.last_name', $search)
                ->orLike('org_users.email', $search)
                ->orLike('org_users.phone', $search)
            ->groupEnd();
        }

        $data['students'] = $builder->orderBy('students.id', 'DESC')->get()->getResultArray();
        $data['cohorts'] = (new CohortModel())->where('org_id', $orgId)->findAll();
        $data['programs'] = (new ProgramModel())->where('org_id', $orgId)->findAll();
        $data['selected_cohort'] = $cohortId;
        $data['selected_program'] = $programId;
        $data['selected_status'] = $status;
        $data['search'] = $search;

        return view('org/students/index', $data);
    }

    public function profile($id)
    {
        try {
            $orgId = session('org_id') ?: 5;
            $db = \Config\Database::connect();

            $student = $db->table('students')
                ->select('students.*, cohorts.name as cohort_name, cohorts.current_semester_id, programs.name as program_name, programs.code as program_code, org_users.email, org_users.phone')
                ->join('cohorts', 'cohorts.id = students.cohort_id', 'left')
                ->join('programs', 'programs.id = cohorts.program_id', 'left')
                ->join('org_users', 'org_users.id = students.user_id', 'left')
                ->where('students.id', $id)
                ->where('students.org_id', $orgId)
                ->get()->getRowArray();

            if (!$student) {
                return redirect()->to(base_url('org/students'))->with('error', 'Student not found.');
            }

            // Bio details
            $bio = [];
            try {
                $bio = $db->table('student_bio')->where('student_id', $id)->get()->getRowArray();
            } catch (\Throwable $e) {}

            // Fee ledger with correct fee_types join
            $fees = [];
            $totalFeeDue = 0;
            $totalFeePaid = 0;
            $totalFeeBalance = 0;
            try {
                $fees = $db->table('student_fee_ledger')
                    ->select('student_fee_ledger.*, COALESCE(fee_types.name, \'Semester Tuition & Fee\') as structure_name')
                    ->join('fee_structures', 'fee_structures.id = student_fee_ledger.fee_structure_id', 'left')
                    ->join('fee_types', 'fee_types.id = fee_structures.fee_type_id', 'left')
                    ->where('student_fee_ledger.student_id', $id)
                    ->get()->getResultArray();

                foreach ($fees as $f) {
                    $totalFeeDue += (float)($f['amount_due'] ?? 0);
                    $totalFeePaid += (float)($f['amount_paid'] ?? 0);
                    $totalFeeBalance += (float)($f['balance'] ?? 0);
                }
            } catch (\Throwable $e) {}

            // Attendance stats
            $totalSessions = 0;
            $presentSessions = 0;
            $attendancePct = 0;
            try {
                $totalSessions = $db->table('attendance_records')
                    ->where('student_id', $id)
                    ->countAllResults();

                $presentSessions = $db->table('attendance_records')
                    ->where('student_id', $id)
                    ->where('status', 'Present')
                    ->countAllResults();

                $attendancePct = $totalSessions > 0 ? round(($presentSessions / $totalSessions) * 100, 1) : 0;
            } catch (\Throwable $e) {}

            // Internal marks
            $marks = [];
            try {
                $marks = $db->table('internal_marks')
                    ->select('internal_marks.*, mark_components.name as component_name, mark_components.max_marks, subjects.name as subject_name, subjects.code as subject_code')
                    ->join('mark_components', 'mark_components.id = internal_marks.component_id', 'left')
                    ->join('subjects', 'subjects.id = mark_components.subject_id', 'left')
                    ->where('internal_marks.student_id', $id)
                    ->get()->getResultArray();
            } catch (\Throwable $e) {}

            // Parents linked
            $parents = [];
            try {
                $parents = $db->table('parent_student_map')
                    ->select('parent_student_map.*, parents.first_name, parents.last_name, CONCAT(parents.first_name, \' \', parents.last_name) as full_name, parents.email, parents.phone')
                    ->join('parents', 'parents.id = parent_student_map.parent_id', 'left')
                    ->where('parent_student_map.student_id', $id)
                    ->get()->getResultArray();
            } catch (\Throwable $e) {}

            // Backlogs
            $backlogs = [];
            try {
                $backlogs = $db->table('backlogs')
                    ->select('backlogs.*, subjects.name as subject_name, subjects.code as subject_code')
                    ->join('subjects', 'subjects.id = backlogs.subject_id', 'left')
                    ->where('backlogs.student_id', $id)
                    ->get()->getResultArray();
            } catch (\Throwable $e) {}

            $data = [
                'student' => $student,
                'bio' => $bio,
                'fees' => $fees,
                'fee_summary' => [
                    'due' => $totalFeeDue,
                    'paid' => $totalFeePaid,
                    'balance' => $totalFeeBalance
                ],
                'attendance' => [
                    'total' => $totalSessions,
                    'present' => $presentSessions,
                    'percentage' => $attendancePct
                ],
                'marks' => $marks,
                'parents' => $parents,
                'backlogs' => $backlogs
            ];

            return view('org/students/profile', $data);
        } catch (\Throwable $e) {
            log_message('error', 'Student profile error: ' . $e->getMessage());
            echo "<div style='padding:20px; font-family:sans-serif; color:#b91c1c; background:#fee2e2; border-radius:8px;'><h3>Student Profile Error</h3><p>" . htmlspecialchars($e->getMessage()) . "</p></div>";
            exit;
        }
    }

    public function create()
    {
        $orgId = session('org_id');
        $data['cohorts'] = (new CohortModel())->where('org_id', $orgId)->findAll();
        $data['programs'] = (new ProgramModel())->where('org_id', $orgId)->findAll();
        $data['student'] = null;
        $data['bio'] = null;

        return view('org/students/create_edit', $data);
    }

    public function edit($id)
    {
        $orgId = session('org_id');
        $studentModel = new StudentModel();
        $bioModel = new StudentBioModel();
        $userModel = new OrgUserModel();

        $student = $studentModel->where('org_id', $orgId)->find($id);
        if (!$student) {
            return redirect()->to('org/students')->with('error', 'Student not found.');
        }

        $user = $userModel->find($student['user_id']);
        $bio = $bioModel->where('student_id', $id)->first();

        $data['student'] = $student;
        $data['user'] = $user;
        $data['bio'] = $bio;
        $data['cohorts'] = (new CohortModel())->where('org_id', $orgId)->findAll();
        $data['programs'] = (new ProgramModel())->where('org_id', $orgId)->findAll();

        return view('org/students/create_edit', $data);
    }

    public function save()
    {
        $orgId = session('org_id');
        $studentModel = new StudentModel();
        $bioModel = new StudentBioModel();
        $userModel = new OrgUserModel();

        $id = $this->request->getPost('id');
        $rollNumber = trim($this->request->getPost('roll_number'));
        $firstName = trim($this->request->getPost('first_name'));
        $lastName = trim($this->request->getPost('last_name'));
        $email = trim($this->request->getPost('email'));
        $phone = trim($this->request->getPost('phone'));
        $cohortId = $this->request->getPost('cohort_id');
        $status = $this->request->getPost('status') ?: 'Active';

        if (empty($rollNumber)) {
            return redirect()->back()->withInput()->with('error', 'Student Roll Number is required.');
        }

        // 1. Check duplicate roll_number in students table
        $existingRoll = $studentModel->where('org_id', $orgId)->where('roll_number', $rollNumber);
        if ($id) {
            $existingRoll->where('id !=', $id);
        }
        if ($existingRoll->first()) {
            return redirect()->back()->withInput()->with('error', "Roll Number '{$rollNumber}' is already assigned to another student in this institution.");
        }

        // 2. Check duplicate employee_code/roll_number in org_users table
        $existingUserCode = $userModel->where('org_id', $orgId)->where('employee_code', $rollNumber);
        if ($id) {
            $currentStudent = $studentModel->find($id);
            if (!empty($currentStudent['user_id'])) {
                $existingUserCode->where('id !=', $currentStudent['user_id']);
            }
        }
        if ($existingUserCode->first()) {
            return redirect()->back()->withInput()->with('error', "The code '{$rollNumber}' is already associated with another user account in this organization.");
        }

        if ($id) {
            // Update
            $student = $studentModel->where('org_id', $orgId)->find($id);
            if (!$student) return redirect()->to('org/students')->with('error', 'Invalid student.');

            // Update user account
            if ($student['user_id']) {
                $userUpdate = [
                    'full_name' => $firstName . ' ' . $lastName,
                    'employee_code' => $rollNumber,
                    'phone' => $phone
                ];
                if (!empty($email)) {
                    $existingEmail = $userModel->where('org_id', $orgId)->where('email', $email)->where('id !=', $student['user_id'])->first();
                    if ($existingEmail) {
                        return redirect()->back()->withInput()->with('error', "Email '{$email}' is already registered to another user.");
                    }
                    $userUpdate['email'] = $email;
                }
                if ($this->request->getPost('password')) {
                    $userUpdate['password_hash'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
                }
                $userModel->update($student['user_id'], $userUpdate);
            }

            // Update student
            $studentModel->update($id, [
                'roll_number' => $rollNumber,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'parent_name' => $this->request->getPost('parent_name'),
                'parent_phone' => $this->request->getPost('parent_phone'),
                'cohort_id' => $cohortId ?: null,
                'status' => $status
            ]);

            // Update bio
            $bioData = [
                'father_name' => $this->request->getPost('father_name'),
                'mother_name' => $this->request->getPost('mother_name'),
                'blood_group' => $this->request->getPost('blood_group'),
                'aadhar_number' => $this->request->getPost('aadhar_number'),
                'address' => $this->request->getPost('address'),
                'city' => $this->request->getPost('city'),
                'state' => $this->request->getPost('state'),
                'pincode' => $this->request->getPost('pincode'),
                'emergency_contact' => $this->request->getPost('emergency_contact')
            ];

            $existingBio = $bioModel->where('student_id', $id)->first();
            if ($existingBio) {
                $bioModel->update($existingBio['id'], $bioData);
            } else {
                $bioData['org_id'] = $orgId;
                $bioData['student_id'] = $id;
                $bioModel->insert($bioData);
            }

            return redirect()->to('org/students/profile/' . $id)->with('success', 'Student record updated successfully.');
        } else {
            // Create user
            if (!empty($email)) {
                $existingUser = $userModel->where('org_id', $orgId)->where('email', $email)->first();
                if ($existingUser) {
                    return redirect()->back()->withInput()->with('error', 'Email already registered in this organization.');
                }
            }

            $userId = $userModel->insert([
                'org_id' => $orgId,
                'employee_code' => $rollNumber,
                'full_name' => $firstName . ' ' . $lastName,
                'email' => $email ?: strtolower($rollNumber) . '@student.lms.edu',
                'phone' => $phone,
                'password_hash' => password_hash($this->request->getPost('password') ?: 'Password@123', PASSWORD_DEFAULT),
                'user_type' => 'student',
                'role' => 'STUDENT',
                'is_org_admin' => 0
            ]);

            $studentId = $studentModel->insert([
                'org_id' => $orgId,
                'user_id' => $userId,
                'roll_number' => $rollNumber,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'parent_name' => $this->request->getPost('parent_name'),
                'parent_phone' => $this->request->getPost('parent_phone'),
                'cohort_id' => $cohortId ?: null,
                'status' => $status
            ]);

            $bioModel->insert([
                'org_id' => $orgId,
                'student_id' => $studentId,
                'father_name' => $this->request->getPost('father_name'),
                'mother_name' => $this->request->getPost('mother_name'),
                'blood_group' => $this->request->getPost('blood_group'),
                'aadhar_number' => $this->request->getPost('aadhar_number'),
                'address' => $this->request->getPost('address'),
                'city' => $this->request->getPost('city'),
                'state' => $this->request->getPost('state'),
                'pincode' => $this->request->getPost('pincode'),
                'emergency_contact' => $this->request->getPost('emergency_contact')
            ]);

            return redirect()->to('org/students/profile/' . $studentId)->with('success', 'Student registered successfully.');
        }
    }

    public function import()
    {
        $orgId = session('org_id');
        $data['cohorts'] = (new CohortModel())->where('org_id', $orgId)->findAll();
        return view('org/students/import', $data);
    }

    public function downloadSampleCsv()
    {
        $csv = "roll_number,first_name,last_name,email,phone,parent_name,parent_phone,cohort_id\n";
        $csv .= "STU-2026-0001,John,Doe,john.doe@example.com,9876543210,Robert Doe,9876543211,1\n";
        $csv .= "STU-2026-0002,Jane,Smith,jane.smith@example.com,9876543212,Mark Smith,9876543213,1\n";

        return $this->response->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="students_import_sample.csv"')
            ->setBody($csv);
    }

    public function processImport()
    {
        $orgId = session('org_id');
        $file = $this->request->getFile('csv_file');
        $defaultCohortId = $this->request->getPost('default_cohort_id');

        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return redirect()->back()->with('error', 'Please upload a valid CSV file.');
        }

        if (!$this->validate([
            'csv_file' => [
                'label' => 'CSV File',
                'rules' => 'uploaded[csv_file]|ext_in[csv_file,csv,txt]|max_size[csv_file,10240]'
            ]
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->getError('csv_file') ?: 'Invalid file format. Please upload a valid CSV file (max 10MB).');
        }

        $handle = fopen($file->getTempName(), 'r');
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return redirect()->back()->with('error', 'Empty or invalid CSV.');
        }

        $headers = array_map(function($h) { return trim(strtolower($h)); }, $headers);

        $studentModel = new StudentModel();
        $bioModel = new StudentBioModel();
        $userModel = new OrgUserModel();

        $imported = 0;
        $skipped = 0;
        $errors = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (empty(array_filter($row))) continue;

            $data = array_combine($headers, $row);
            $roll = trim($data['roll_number'] ?? '');
            $first = trim($data['first_name'] ?? '');
            $last = trim($data['last_name'] ?? '');
            $email = trim($data['email'] ?? '');
            $phone = trim($data['phone'] ?? '');
            $parentName = trim($data['parent_name'] ?? '');
            $parentPhone = trim($data['parent_phone'] ?? '');
            $cohortId = !empty($data['cohort_id']) ? intval($data['cohort_id']) : $defaultCohortId;

            if (empty($roll) || empty($first) || empty($email)) {
                $skipped++;
                $errors[] = "Row with roll '{$roll}' missing roll, first name, or email.";
                continue;
            }

            // Check duplicate roll or email
            $existingRoll = $studentModel->where('org_id', $orgId)->where('roll_number', $roll)->first();
            $existingUser = $userModel->where('org_id', $orgId)->where('email', $email)->first();

            if ($existingRoll || $existingUser) {
                $skipped++;
                $errors[] = "Duplicate roll number '{$roll}' or email '{$email}'.";
                continue;
            }

            // Create user
            $userId = $userModel->insert([
                'org_id' => $orgId,
                'full_name' => $first . ($last ? ' ' . $last : ''),
                'email' => $email,
                'phone' => $phone,
                'password_hash' => password_hash('welcome123', PASSWORD_DEFAULT),
                'user_type' => 'student',
                'role' => 'STUDENT',
                'is_org_admin' => 0
            ]);

            $studentId = $studentModel->insert([
                'org_id' => $orgId,
                'user_id' => $userId,
                'roll_number' => $roll,
                'first_name' => $first,
                'last_name' => $last,
                'parent_name' => $parentName,
                'parent_phone' => $parentPhone,
                'cohort_id' => $cohortId ?: null,
                'status' => 'Active'
            ]);

            $bioModel->insert([
                'org_id' => $orgId,
                'student_id' => $studentId,
                'father_name' => $parentName
            ]);

            $imported++;
        }

        fclose($handle);

        $msg = "Import finished: {$imported} students added.";
        if ($skipped > 0) {
            $msg .= " {$skipped} rows skipped.";
        }

        return redirect()->to('org/students')->with('success', $msg);
    }

    public function bulkPromote()
    {
        $orgId = session('org_id');
        $db = \Config\Database::connect();
        $cohortId = $this->request->getGet('cohort_id');

        $data['cohorts'] = (new CohortModel())->where('org_id', $orgId)->findAll();
        $data['semesters'] = (new SemesterModel())->where('org_id', $orgId)->orderBy('sequence', 'ASC')->findAll();
        $data['selected_cohort'] = $cohortId;

        $students = [];
        if ($cohortId) {
            $rawStudents = $db->table('students')
                ->select('students.*, cohorts.name as cohort_name')
                ->join('cohorts', 'cohorts.id = students.cohort_id', 'left')
                ->where('students.org_id', $orgId)
                ->where('students.cohort_id', $cohortId)
                ->get()->getResultArray();

            foreach ($rawStudents as $st) {
                // Compute attendance %
                $totalSessions = $db->table('attendance_records')->where('student_id', $st['id'])->countAllResults();
                $presentSessions = $db->table('attendance_records')->where('student_id', $st['id'])->where('status', 'Present')->countAllResults();
                $attPct = $totalSessions > 0 ? round(($presentSessions / $totalSessions) * 100, 1) : 100;

                // Backlogs count
                $backlogsCount = $db->table('backlogs')->where('student_id', $st['id'])->countAllResults();

                $isEligible = ($attPct >= 75.0 && $backlogsCount <= 2);

                $st['attendance_pct'] = $attPct;
                $st['backlogs_count'] = $backlogsCount;
                $st['is_eligible'] = $isEligible;
                $students[] = $st;
            }
        }

        $data['students'] = $students;

        return view('org/students/bulk_promote', $data);
    }

    public function processBulkPromote()
    {
        $orgId = session('org_id');
        $targetCohortId = $this->request->getPost('target_cohort_id');
        $studentIds = $this->request->getPost('student_ids') ?: [];
        $action = $this->request->getPost('action'); // 'promote' or 'detain'

        if (empty($studentIds)) {
            return redirect()->back()->with('error', 'No students selected.');
        }

        $studentModel = new StudentModel();

        if ($action === 'promote') {
            if (!$targetCohortId) {
                return redirect()->back()->with('error', 'Please select a destination cohort for promotion.');
            }
            foreach ($studentIds as $sId) {
                $studentModel->where('org_id', $orgId)->update($sId, [
                    'cohort_id' => $targetCohortId,
                    'status' => 'Active'
                ]);
            }
            return redirect()->to('org/students?cohort_id=' . $targetCohortId)->with('success', count($studentIds) . ' students successfully promoted.');
        } elseif ($action === 'detain') {
            foreach ($studentIds as $sId) {
                $studentModel->where('org_id', $orgId)->update($sId, [
                    'status' => 'Detained'
                ]);
            }
            return redirect()->back()->with('success', count($studentIds) . ' students marked as Detained.');
        }

        return redirect()->back()->with('error', 'Invalid action.');
    }

    public function parents()
    {
        $orgId = session('org_id') ?: 5;
        $db = \Config\Database::connect();

        $parents = $db->table('parent_student_map')
            ->select('parent_student_map.*, COALESCE(CONCAT(p.first_name, \' \', p.last_name), u.full_name, \'Parent\') as parent_name, COALESCE(p.email, u.email) as parent_email, COALESCE(p.phone, u.phone) as parent_phone, students.roll_number, students.first_name, students.last_name')
            ->join('parents p', 'p.id = parent_student_map.parent_id', 'left')
            ->join('org_users u', 'u.id = parent_student_map.parent_id', 'left')
            ->join('students', 'students.id = parent_student_map.student_id', 'left')
            ->where('parent_student_map.org_id', $orgId)
            ->get()->getResultArray();

        $data['parents'] = $parents;
        $data['students'] = (new StudentModel())->where('org_id', $orgId)->where('status', 'Active')->findAll();

        return view('org/students/parents', $data);
    }

    public function saveParent()
    {
        $orgId = session('org_id') ?: 5;
        $db = \Config\Database::connect();

        $name = trim($this->request->getPost('name'));
        $email = trim($this->request->getPost('email'));
        $phone = trim($this->request->getPost('phone'));
        $studentId = $this->request->getPost('student_id');
        $relationship = $this->request->getPost('relationship') ?: 'Father';

        // Check or insert into parents table
        $existingParent = $db->table('parents')->where('org_id', $orgId)->where('email', $email)->get()->getRowArray();
        if (!$existingParent) {
            $nameParts = explode(' ', $name, 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? 'Parent';
            $db->table('parents')->insert([
                'org_id'        => $orgId,
                'parent_code'   => 'PAR-' . date('y') . '-' . rand(100, 999),
                'first_name'    => $firstName,
                'last_name'     => $lastName,
                'phone'         => $phone,
                'email'         => $email,
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'status'        => 'Active'
            ]);
            $parentId = $db->insertID();
        } else {
            $parentId = $existingParent['id'];
        }

        // Map parent to student
        $existingMap = $db->table('parent_student_map')
            ->where('org_id', $orgId)
            ->where('parent_id', $parentId)
            ->where('student_id', $studentId)
            ->get()->getRowArray();

        if (!$existingMap) {
            $db->table('parent_student_map')->insert([
                'org_id'       => $orgId,
                'parent_id'    => $parentId,
                'student_id'   => $studentId,
                'relationship' => $relationship
            ]);
        }

        return redirect()->to(base_url('org/students/parents'))->with('success', 'Parent account linked successfully.');
    }

    public function unlinkParent($id)
    {
        $orgId = session('org_id') ?: 5;
        $db = \Config\Database::connect();
        $db->table('parent_student_map')->where('org_id', $orgId)->where('id', $id)->delete();
        return redirect()->to(base_url('org/students/parents'))->with('success', 'Parent unlinked successfully.');
    }
}
