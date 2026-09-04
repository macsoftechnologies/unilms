<?php
namespace App\Controllers;

use App\Models\CertificateRequestModel;
use App\Models\StudentComplaintModel;
use App\Models\StudentFeedbackModel;
use App\Models\HostelRequestModel;
use App\Models\TransportRegistrationModel;
use App\Models\ExamApplicationModel;

class LmsPortal extends BaseController
{
    // Self Service Hub
    public function self_service()
    {
        return view('lms/portal/self_service');
    }

    // Grade Card (Internal CIA + External Exams)
    public function grade_card()
    {
        $db = \Config\Database::connect();
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        $orgId = session('org_id') ?: 5;

        // External Exam Marks
        $builder = $db->table('exam_marks_external em');
        $builder->select('em.*, es.exam_date, sub.name as subject_name, sub.code as subject_code, e.name as exam_name');
        $builder->join('exam_schedules es', 'es.id = em.exam_schedule_id', 'left');
        $builder->join('subjects sub', 'sub.id = es.subject_id', 'left');
        $builder->join('exam_names e', 'e.id = es.exam_id', 'left');
        $builder->where('em.org_id', $orgId);
        $builder->where('em.student_id', $studentId);
        $data['marks'] = $builder->get()->getResultArray();

        // Internal Marks (Continuous Internal Assessments)
        $data['internal_marks'] = $db->table('internal_marks im')
            ->select('im.*, mc.name as component_name, mc.max_marks as component_max, sub.name as subject_name, sub.code as subject_code')
            ->join('mark_components mc', 'mc.id = im.component_id', 'left')
            ->join('subjects sub', 'sub.id = mc.subject_id', 'left')
            ->where('im.org_id', $orgId)
            ->where('im.student_id', $studentId)
            ->get()->getResultArray();

        return view('lms/portal/grade_card', $data);
    }

    // Exam Applications
    public function exam_applications()
    {
        $db = \Config\Database::connect();
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        $orgId = session('org_id') ?: 5;

        // Get upcoming exams the student hasn't applied for yet
        $applied_ids = $db->table('exam_applications')->select('exam_id')->where('student_id', $studentId)->where('org_id', $orgId)->get()->getResultArray();
        $appIds = array_column($applied_ids, 'exam_id') ?: [0];
        
        $data['available_exams'] = $db->table('exam_names')
            ->where('org_id', $orgId)
            ->whereNotIn('id', $appIds)
            ->get()->getResultArray();

        $appModel = new ExamApplicationModel();
        $data['my_applications'] = $db->table('exam_applications ea')
            ->select('ea.*, e.name as exam_name')
            ->join('exam_names e', 'e.id = ea.exam_id', 'left')
            ->where('ea.org_id', $orgId)
            ->where('ea.student_id', $studentId)
            ->get()->getResultArray();

        return view('lms/portal/exam_applications', $data);
    }

    public function submit_exam_application()
    {
        $appModel = new ExamApplicationModel();
        $appModel->insert([
            'org_id' => session('org_id') ?: 5,
            'student_id' => session('student_id') ?: (session('lms_student_id') ?: 3),
            'exam_id' => $this->request->getPost('exam_id'),
            'status' => 'Applied',
            'fee_paid' => 0
        ]);
        return redirect()->back()->with('success', 'Exam application submitted successfully.');
    }

    // Certificate Requests
    public function certificate_requests()
    {
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        $orgId = session('org_id') ?: 5;
        $certModel = new CertificateRequestModel();
        $data['requests'] = $certModel->where('student_id', $studentId)->where('org_id', $orgId)->findAll();
        return view('lms/portal/certificate_requests', $data);
    }

    public function submit_certificate()
    {
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        $orgId = session('org_id') ?: 5;
        $certModel = new CertificateRequestModel();
        $certModel->insert([
            'org_id' => $orgId,
            'student_id' => $studentId,
            'certificate_type' => $this->request->getPost('certificate_type'),
            'reason' => $this->request->getPost('reason'),
            'status' => 'Pending'
        ]);
        return redirect()->back()->with('success', 'Certificate request submitted.');
    }

    // Complaints
    public function complaints()
    {
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        $orgId = session('org_id') ?: 5;
        $compModel = new StudentComplaintModel();
        $data['complaints'] = $compModel->where('student_id', $studentId)->where('org_id', $orgId)->findAll();
        return view('lms/portal/complaints', $data);
    }

    public function submit_complaint()
    {
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        $orgId = session('org_id') ?: 5;
        $compModel = new StudentComplaintModel();
        $compModel->insert([
            'org_id' => $orgId,
            'student_id' => $studentId,
            'category' => $this->request->getPost('category'),
            'subject' => $this->request->getPost('subject'),
            'description' => $this->request->getPost('description'),
            'status' => 'Open'
        ]);
        return redirect()->back()->with('success', 'Complaint submitted.');
    }

    // Feedback
    public function feedback()
    {
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        $orgId = session('org_id') ?: 5;
        $feedModel = new StudentFeedbackModel();
        $data['feedbacks'] = $feedModel->where('student_id', $studentId)->where('org_id', $orgId)->findAll();
        
        $db = \Config\Database::connect();
        $data['subjects'] = $db->table('subjects')->where('org_id', $orgId)->get()->getResultArray();
        $data['faculty'] = $db->table('hr_employees')->where('org_id', $orgId)->get()->getResultArray();
        
        return view('lms/portal/feedback', $data);
    }

    public function submit_feedback()
    {
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        $orgId = session('org_id') ?: 5;
        $feedModel = new StudentFeedbackModel();
        $feedModel->insert([
            'org_id' => $orgId,
            'student_id' => $studentId,
            'subject_id' => $this->request->getPost('subject_id'),
            'faculty_id' => $this->request->getPost('faculty_id'),
            'rating' => $this->request->getPost('rating'),
            'comments' => $this->request->getPost('comments')
        ]);
        return redirect()->back()->with('success', 'Feedback submitted anonymously.');
    }

    // Diary / Notice View
    public function diary()
    {
        $orgId = session('org_id') ?: 5;
        $db = \Config\Database::connect();
        $data['notices'] = $db->table('diary')
            ->where('org_id', $orgId)
            ->whereIn('target_audience', ['All', 'Students'])
            ->where('publish_date <=', date('Y-m-d'))
            ->orderBy('publish_date', 'DESC')
            ->get()->getResultArray();
        return view('lms/portal/diary', $data);
    }

    // Hostel Requests
    public function hostel()
    {
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        $orgId = session('org_id') ?: 5;
        $hostelModel = new HostelRequestModel();
        $data['requests'] = $hostelModel->where('student_id', $studentId)->where('org_id', $orgId)->findAll();
        return view('lms/portal/hostel', $data);
    }

    public function submit_hostel()
    {
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        $orgId = session('org_id') ?: 5;
        $hostelModel = new HostelRequestModel();
        $hostelModel->insert([
            'org_id' => $orgId,
            'student_id' => $studentId,
            'preferred_room_type' => $this->request->getPost('preferred_room_type'),
            'joining_date' => $this->request->getPost('joining_date'),
            'status' => 'Pending'
        ]);
        return redirect()->back()->with('success', 'Hostel request submitted.');
    }

    // Transport Registrations
    public function transport()
    {
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        $orgId = session('org_id') ?: 5;
        $transModel = new TransportRegistrationModel();
        $data['registrations'] = $transModel->where('student_id', $studentId)->where('org_id', $orgId)->findAll();
        return view('lms/portal/transport', $data);
    }

    public function submit_transport()
    {
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        $orgId = session('org_id') ?: 5;
        $transModel = new TransportRegistrationModel();
        $transModel->insert([
            'org_id' => $orgId,
            'student_id' => $studentId,
            'route_id' => null,
            'halt_id' => null,
            'status' => 'Pending'
        ]);
        return redirect()->back()->with('success', 'Transport facility requested.');
    }

    // Fee Dues & Ledger Hub
    public function fees()
    {
        $db = \Config\Database::connect();
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        $orgId = session('org_id') ?: 5;

        $data['ledger'] = $db->table('student_fee_ledger fl')
            ->select('fl.*, ft.name as structure_name')
            ->join('fee_structures fs', 'fs.id = fl.fee_structure_id', 'left')
            ->join('fee_types ft', 'ft.id = fs.fee_type_id', 'left')
            ->where('fl.student_id', $studentId)
            ->where('fl.org_id', $orgId)
            ->get()->getResultArray();

        $student = $db->table('students')->where('id', $studentId)->get()->getRowArray();
        $data['payments'] = $db->table('fee_receipts')
            ->where('student_id', $studentId)
            ->where('org_id', $orgId)
            ->orderBy('id', 'DESC')
            ->get()->getResultArray();

        $totalDue = 0; $totalPaid = 0; $balance = 0;
        foreach ($data['ledger'] as $l) {
            $totalDue += (float)$l['amount_due'];
            $totalPaid += (float)$l['amount_paid'];
            $balance += (float)$l['balance'];
        }

        $data['summary'] = [
            'due' => $totalDue,
            'paid' => $totalPaid,
            'balance' => $balance
        ];

        return view('lms/fees/index', $data);
    }

    // Outcome Based Education (OBE) Student Hub
    public function obe()
    {
        $db = \Config\Database::connect();
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        $orgId = session('org_id') ?: 5;

        $student = $db->table('students')
            ->select('students.*, cohorts.program_id')
            ->join('cohorts', 'cohorts.id = students.cohort_id', 'left')
            ->where('students.id', $studentId)
            ->get()->getRowArray();

        $programId = $student['program_id'] ?? 1;

        // Fetch Program Outcomes (POs)
        $data['pos'] = $db->table('po_definitions')
            ->where('org_id', $orgId)
            ->where('program_id', $programId)
            ->orderBy('code', 'ASC')
            ->get()->getResultArray();

        // Fetch subjects for this cohort / program
        $subjects = $db->table('subjects')
            ->where('org_id', $orgId)
            ->where('program_id', $programId)
            ->get()->getResultArray();

        // Fetch COs for these subjects
        $subjectCos = [];
        foreach ($subjects as $sub) {
            $cos = $db->table('co_definitions')
                ->where('org_id', $orgId)
                ->where('subject_id', $sub['id'])
                ->orderBy('code', 'ASC')
                ->get()->getResultArray();
            $sub['cos'] = $cos;
            $subjectCos[] = $sub;
        }

        $data['subjects'] = $subjectCos;
        $data['student'] = $student;

        return view('lms/obe/index', $data);
    }

    // Student Placement Drives Hub
    public function placements()
    {
        $db = \Config\Database::connect();
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        $orgId = session('org_id') ?: 5;

        $student = $db->table('students')->where('id', $studentId)->get()->getRowArray();
        if (!$student) {
            $student = [
                'id' => $studentId,
                'first_name' => session('first_name') ?: 'Aarav',
                'last_name' => session('last_name') ?: 'Patel',
                'roll_number' => session('roll_number') ?: '26CSE001'
            ];
        }

        // Drives
        $drives = $db->table('placement_drives pd')
            ->select('pd.*, pc.company_name')
            ->join('placement_companies pc', 'pc.id = pd.company_id', 'left')
            ->where('pd.org_id', $orgId)
            ->whereIn('pd.status', ['Upcoming', 'Active'])
            ->orderBy('pd.drive_date', 'ASC')
            ->get()->getResultArray();

        // My applications
        $myApps = $db->table('placement_applications pa')
            ->select('pa.*, pd.title as drive_title, pc.company_name')
            ->join('placement_drives pd', 'pd.id = pa.drive_id', 'left')
            ->join('placement_companies pc', 'pc.id = pd.company_id', 'left')
            ->where('pa.student_id', $studentId)
            ->get()->getResultArray();

        $appliedDriveIds = array_column($myApps, 'drive_id');

        // Backlogs count for eligibility check
        $backlogsCount = $db->table('backlogs')->where('student_id', $studentId)->countAllResults();

        $processedDrives = [];
        foreach ($drives as $d) {
            $isEligible = ($backlogsCount <= (int)$d['max_backlogs']);
            $hasApplied = in_array($d['id'], $appliedDriveIds);
            $d['is_eligible'] = $isEligible;
            $d['has_applied'] = $hasApplied;
            $processedDrives[] = $d;
        }

        $data['drives'] = $processedDrives;
        $data['my_applications'] = $myApps;
        $data['student'] = $student;
        $data['backlogs_count'] = $backlogsCount;

        return view('lms/placements/index', $data);
    }

    // Apply for Placement Drive
    public function apply_placement()
    {
        $db = \Config\Database::connect();
        $studentId = session('lms_student_id');
        $orgId = session('org_id');
        $driveId = $this->request->getPost('drive_id');

        $appModel = new \App\Models\PlacementApplicationModel();

        // Check if already applied
        $exists = $appModel->where('org_id', $orgId)->where('drive_id', $driveId)->where('student_id', $studentId)->first();
        if ($exists) {
            return redirect()->back()->with('error', 'You have already applied for this placement drive.');
        }

        $resumePath = null;
        $file = $this->request->getFile('resume');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (!$this->validate([
                'resume' => [
                    'label' => 'Resume Document',
                    'rules' => 'uploaded[resume]|ext_in[resume,pdf,doc,docx]|max_size[resume,10240]'
                ]
            ])) {
                return redirect()->back()->withInput()->with('error', $this->validator->getError('resume') ?: 'Invalid resume format (PDF/DOCX allowed) or exceeds 10MB limit.');
            }
            $newName = $file->getRandomName();
            $targetDir = FCPATH . 'uploads/resumes/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
            $file->move($targetDir, $newName);
            $resumePath = 'uploads/resumes/' . $newName;
        }

        $appModel->insert([
            'org_id' => $orgId,
            'drive_id' => $driveId,
            'student_id' => $studentId,
            'resume_file' => $resumePath,
            'current_round' => 'Round 1 (Aptitude / Screening)',
            'status' => 'Applied'
        ]);

        return redirect()->back()->with('success', 'Application submitted successfully for this placement drive.');
    }
}
