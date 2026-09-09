<?php

namespace App\Controllers;

use App\Models\ExamNameModel;
use App\Models\ExamScheduleModel;
use App\Models\ExamApplicationModel;
use App\Models\ExamHallTicketModel;
use App\Models\ExamMarksExternalModel;
use App\Models\ProgramModel;
use App\Models\SemesterModel;
use App\Models\SubjectModel;
use App\Models\StudentModel;

class OrgExaminations extends BaseController
{
    public function index()
    {
        $examModel = new ExamNameModel();
        $data['exams'] = $examModel->where('org_id', session('org_id'))->findAll();
        return view('org/examinations/index', $data);
    }

    public function save_exam()
    {
        $examModel = new ExamNameModel();
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => session('org_id'),
            'name' => $this->request->getPost('name'),
            'type' => $this->request->getPost('type'),
            'description' => $this->request->getPost('description')
        ];

        if (empty($id)) {
            $examModel->insert($data);
        } else {
            $examModel->update($id, $data);
        }

        return redirect()->to('org/examinations')->with('success', 'Exam saved successfully.');
    }

    public function schedules()
    {
        $examModel = new ExamNameModel();
        $programModel = new ProgramModel();
        $semesterModel = new SemesterModel();
        $subjectModel = new SubjectModel();
        
        $db = \Config\Database::connect();
        $builder = $db->table('exam_schedules es');
        $builder->select('es.*, e.name as exam_name, p.name as program_name, s.name as semester_name, sub.name as subject_name');
        $builder->join('exam_names e', 'e.id = es.exam_id');
        $builder->join('programs p', 'p.id = es.program_id');
        $builder->join('semesters s', 's.id = es.semester_id');
        $builder->join('subjects sub', 'sub.id = es.subject_id');
        $builder->where('es.org_id', session('org_id'));
        $builder->orderBy('es.exam_date', 'ASC');
        
        $data['schedules'] = $builder->get()->getResultArray();
        
        $data['exams'] = $examModel->where('org_id', session('org_id'))->findAll();
        $data['programs'] = $programModel->where('org_id', session('org_id'))->findAll();
        $data['semesters'] = $semesterModel->where('org_id', session('org_id'))->findAll();
        $data['subjects'] = $subjectModel->where('org_id', session('org_id'))->findAll();
        
        return view('org/examinations/schedules', $data);
    }

    public function save_schedule()
    {
        $scheduleModel = new ExamScheduleModel();
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => session('org_id'),
            'exam_id' => $this->request->getPost('exam_id'),
            'program_id' => $this->request->getPost('program_id'),
            'semester_id' => $this->request->getPost('semester_id'),
            'subject_id' => $this->request->getPost('subject_id'),
            'exam_date' => $this->request->getPost('exam_date'),
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time'),
            'max_marks' => $this->request->getPost('max_marks') ?: 100,
            'passing_marks' => $this->request->getPost('passing_marks') ?: 40
        ];

        if (empty($id)) {
            $scheduleModel->insert($data);
        } else {
            $scheduleModel->update($id, $data);
        }

        return redirect()->to('org/examinations/schedules')->with('success', 'Exam schedule saved successfully.');
    }

    public function applications()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('exam_applications ea');
        $builder->select('ea.*, e.name as exam_name, s.first_name, s.last_name, s.roll_number');
        $builder->join('exam_names e', 'e.id = ea.exam_id');
        $builder->join('students s', 's.id = ea.student_id');
        $builder->where('ea.org_id', session('org_id'));
        
        $data['applications'] = $builder->get()->getResultArray();
        $data['exams'] = $db->table('exam_names')->where('org_id', session('org_id'))->get()->getResultArray();
        $data['students'] = $db->table('students')->where('org_id', session('org_id'))->get()->getResultArray();
        return view('org/examinations/applications', $data);
    }

    public function save_application()
    {
        $appModel = new ExamApplicationModel();
        $orgId = session('org_id');
        $studentId = $this->request->getPost('student_id');
        $examId = $this->request->getPost('exam_id');
        $status = $this->request->getPost('status') ?: 'Approved';
        $feePaid = $this->request->getPost('fee_paid') ? 1 : 0;

        $existing = $appModel->where('org_id', $orgId)->where('student_id', $studentId)->where('exam_id', $examId)->first();
        if ($existing) {
            $appModel->update($existing['id'], ['status' => $status, 'fee_paid' => $feePaid]);
        } else {
            $appModel->insert([
                'org_id' => $orgId,
                'student_id' => $studentId,
                'exam_id' => $examId,
                'status' => $status,
                'fee_paid' => $feePaid
            ]);
        }

        return redirect()->to('org/examinations/applications')->with('success', 'Exam application registered successfully.');
    }

    public function update_application_status($id)
    {
        $appModel = new ExamApplicationModel();
        $status = $this->request->getPost('status');
        
        $appModel->update($id, ['status' => $status]);
        
        return redirect()->to('org/examinations/applications')->with('success', 'Application status updated.');
    }

    public function hall_tickets()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('exam_hall_tickets ht');
        $builder->select('ht.*, ea.status as app_status, e.name as exam_name, s.first_name, s.last_name, s.roll_number');
        $builder->join('exam_applications ea', 'ea.id = ht.exam_application_id');
        $builder->join('exam_names e', 'e.id = ea.exam_id');
        $builder->join('students s', 's.id = ea.student_id');
        $builder->where('ht.org_id', session('org_id'));
        
        $data['tickets'] = $builder->get()->getResultArray();
        
        // Find approved applications without hall tickets
        $builder_apps = $db->table('exam_applications ea');
        $builder_apps->select('ea.*, e.name as exam_name, s.first_name, s.last_name, s.roll_number');
        $builder_apps->join('exam_names e', 'e.id = ea.exam_id');
        $builder_apps->join('students s', 's.id = ea.student_id');
        $builder_apps->join('exam_hall_tickets ht', 'ht.exam_application_id = ea.id', 'left');
        $builder_apps->where('ea.org_id', session('org_id'));
        $builder_apps->where('ea.status', 'Approved');
        $builder_apps->where('ht.id IS NULL');
        
        $data['pending_apps'] = $builder_apps->get()->getResultArray();
        
        return view('org/examinations/hall_tickets', $data);
    }

    public function generate_hall_ticket()
    {
        $htModel = new ExamHallTicketModel();
        $appId = $this->request->getPost('exam_application_id');
        $orgId = session('org_id');
        
        // Generate a random HT number
        $htNumber = 'HT-' . date('Y') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
        
        $htModel->insert([
            'org_id' => $orgId,
            'exam_application_id' => $appId,
            'hall_ticket_number' => $htNumber,
            'issue_date' => date('Y-m-d'),
            'status' => 'Valid'
        ]);
        
        return redirect()->to('org/examinations/hall_tickets')->with('success', 'Hall Ticket Generated successfully.');
    }

    public function print_hall_ticket($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('exam_hall_tickets ht');
        $builder->select('ht.*, e.name as exam_name, s.first_name, s.last_name, s.roll_number, s.photo, p.name as program_name, sem.name as semester_name, ea.semester_id, ea.program_id');
        $builder->join('exam_applications ea', 'ea.id = ht.exam_application_id');
        $builder->join('exam_names e', 'e.id = ea.exam_id');
        $builder->join('students s', 's.id = ea.student_id');
        $builder->join('programs p', 'p.id = ea.program_id', 'left');
        $builder->join('semesters sem', 'sem.id = ea.semester_id', 'left');
        $builder->where('ht.id', $id);
        $builder->where('ht.org_id', session('org_id'));
        $data['ticket'] = $builder->get()->getRowArray();

        if (!$data['ticket']) return redirect()->back()->with('error', 'Hall ticket not found');

        // Get subjects for this semester
        $subBuilder = $db->table('exam_schedules es');
        $subBuilder->select('es.exam_date, es.start_time, es.end_time, sub.name as subject_name, sub.code as subject_code');
        $subBuilder->join('subjects sub', 'sub.id = es.subject_id');
        $subBuilder->where('es.exam_id', $data['ticket']['exam_id'] ?? 0); // Need to join exam_id properly
        // Actually, ea.exam_id is correct. Wait, we don't have exam_id in the ticket row directly, we have it in the application. Let's fetch schedules by org, program, semester, exam.
        // Quick fix: we will just fetch all schedules for that exam and program.
        
        // Let's get the application's exam_id
        $app = $db->table('exam_applications')->where('id', $data['ticket']['exam_application_id'])->get()->getRowArray();
        if ($app) {
            $subBuilder->where('es.exam_id', $app['exam_id']);
            $subBuilder->where('es.program_id', $app['program_id']);
            $data['schedules'] = $subBuilder->get()->getResultArray();
        } else {
            $data['schedules'] = [];
        }

        return view('org/examinations/print_hall_ticket', $data);
    }

    public function marks()
    {
        $examModel = new ExamNameModel();
        $scheduleModel = new ExamScheduleModel();
        
        $data['exams'] = $examModel->where('org_id', session('org_id'))->findAll();
        
        $examId = $this->request->getGet('exam_id');
        if ($examId) {
            $db = \Config\Database::connect();
            $builder = $db->table('exam_schedules es');
            $builder->select('es.*, p.name as program_name, sub.name as subject_name');
            $builder->join('programs p', 'p.id = es.program_id');
            $builder->join('subjects sub', 'sub.id = es.subject_id');
            $builder->where('es.org_id', session('org_id'));
            $builder->where('es.exam_id', $examId);
            $data['schedules'] = $builder->get()->getResultArray();
            $data['selected_exam_id'] = $examId;
        }
        
        return view('org/examinations/marks_entry', $data);
    }
    
    public function enter_marks($scheduleId)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('exam_schedules es');
        $builder->select('es.*, sub.name as subject_name, e.name as exam_name');
        $builder->join('subjects sub', 'sub.id = es.subject_id');
        $builder->join('exam_names e', 'e.id = es.exam_id');
        $builder->where('es.id', $scheduleId);
        $builder->where('es.org_id', session('org_id'));
        $data['schedule'] = $builder->get()->getRowArray();
        
        if (!$data['schedule']) return redirect()->back()->with('error', 'Schedule not found');
        
        // Get all students enrolled in this program/semester
        // AND who applied and got approved for this exam
        $builder_stu = $db->table('students s');
        $builder_stu->select('s.id as student_id, s.first_name, s.last_name, s.roll_number, em.marks_obtained, em.status as marks_status, em.id as marks_id');
        $builder_stu->join('cohorts c', 'c.id = s.cohort_id', 'left');
        $builder_stu->join('exam_applications ea', 'ea.student_id = s.id AND ea.exam_id = ' . $data['schedule']['exam_id'] . ' AND ea.status = "Approved"');
        $builder_stu->join('exam_marks_external em', 'em.student_id = s.id AND em.exam_schedule_id = ' . $scheduleId, 'left');
        $builder_stu->where('s.org_id', session('org_id'));
        $builder_stu->where('c.program_id', $data['schedule']['program_id']);
        
        $data['students'] = $builder_stu->get()->getResultArray();
        
        return view('org/examinations/marks_enter', $data);
    }
    
    public function save_marks()
    {
        $marksModel = new ExamMarksExternalModel();
        $scheduleId = $this->request->getPost('exam_schedule_id');
        $marksData = $this->request->getPost('marks'); // Array of student_id => [marks, status]
        
        foreach ($marksData as $studentId => $details) {
            $existing = $marksModel->where('exam_schedule_id', $scheduleId)->where('student_id', $studentId)->first();
            
            $saveData = [
                'org_id' => session('org_id'),
                'student_id' => $studentId,
                'exam_schedule_id' => $scheduleId,
                'marks_obtained' => $details['marks_obtained'] === '' ? null : $details['marks_obtained'],
                'status' => $details['status'],
                'entered_by' => session('org_user_id')
            ];
            
            if ($existing) {
                $marksModel->update($existing['id'], $saveData);
            } else {
                $marksModel->insert($saveData);
            }
        }
        
        return redirect()->to('org/examinations/enter-marks/' . $scheduleId)->with('success', 'Marks saved successfully.');
    }
    public function backlogs()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('backlogs b');
        $builder->select('b.*, s.first_name, s.last_name, s.roll_number, sub.name as subject_name, e.name as exam_name');
        $builder->join('students s', 's.id = b.student_id');
        $builder->join('subjects sub', 'sub.id = b.subject_id');
        $builder->join('exam_schedules es', 'es.id = b.exam_schedule_id');
        $builder->join('exam_names e', 'e.id = es.exam_id');
        $builder->where('b.org_id', session('org_id'));
        
        $data['backlogs'] = $builder->get()->getResultArray();
        return view('org/examinations/backlogs', $data);
    }

    public function set_papers()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('exam_papers ep');
        $builder->select('ep.*, e.name as exam_name, sub.name as subject_name, u.full_name as faculty_name');
        $builder->join('exam_names e', 'e.id = ep.exam_id');
        $builder->join('subjects sub', 'sub.id = ep.subject_id');
        $builder->join('org_users u', 'u.id = ep.faculty_id');
        $builder->where('ep.org_id', session('org_id'));
        $data['papers'] = $builder->get()->getResultArray();
        
        $data['exams'] = $db->table('exam_names')->where('org_id', session('org_id'))->get()->getResultArray();
        $data['subjects'] = $db->table('subjects')->where('org_id', session('org_id'))->get()->getResultArray();
        $data['faculty'] = $db->table('org_users')->select('id, full_name as name')->where('org_id', session('org_id'))->where('role', 'faculty')->get()->getResultArray();
        
        return view('org/examinations/set_papers', $data);
    }

    public function save_paper()
    {
        $paperModel = new \App\Models\ExamPaperModel();
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => session('org_id'),
            'exam_id' => $this->request->getPost('exam_id'),
            'subject_id' => $this->request->getPost('subject_id'),
            'faculty_id' => $this->request->getPost('faculty_id'),
            'paper_type' => $this->request->getPost('paper_type'),
            'deadline' => $this->request->getPost('deadline'),
            'status' => $this->request->getPost('status') ?: 'Pending'
        ];
        if (empty($id)) {
            $paperModel->insert($data);
        } else {
            $paperModel->update($id, $data);
        }
        return redirect()->to('org/examinations/set_papers')->with('success', 'Set paper task saved.');
    }

    public function external_registrations()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('exam_external_registrations er');
        $builder->select('er.*, s.first_name, s.last_name, s.roll_number');
        $builder->join('students s', 's.id = er.student_id');
        $builder->where('er.org_id', session('org_id'));
        $data['registrations'] = $builder->get()->getResultArray();
        
        $data['students'] = $db->table('students')->select('id, first_name, last_name, roll_number')->where('org_id', session('org_id'))->get()->getResultArray();
        
        return view('org/examinations/external_registrations', $data);
    }

    public function save_external_registration()
    {
        $regModel = new \App\Models\ExamExternalRegistrationModel();
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => session('org_id'),
            'student_id' => $this->request->getPost('student_id'),
            'exam_name' => $this->request->getPost('exam_name'),
            'registration_no' => $this->request->getPost('registration_no'),
            'fee_paid' => $this->request->getPost('fee_paid') ? 1 : 0,
            'registration_date' => $this->request->getPost('registration_date') ?: date('Y-m-d')
        ];
        if (empty($id)) {
            $regModel->insert($data);
        } else {
            $regModel->update($id, $data);
        }
        return redirect()->to('org/examinations/external_registrations')->with('success', 'Registration saved.');
    }

    public function invigilation()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('exam_invigilation ei');
        $builder->select('ei.*, u.full_name as invigilator_name, e.name as exam_name, es.exam_date');
        $builder->join('org_users u', 'u.id = ei.invigilator_id');
        $builder->join('exam_schedules es', 'es.id = ei.exam_schedule_id');
        $builder->join('exam_names e', 'e.id = es.exam_id');
        $builder->where('ei.org_id', session('org_id'));
        $data['duties'] = $builder->get()->getResultArray();
        
        $builder_sch = $db->table('exam_schedules es');
        $builder_sch->select('es.*, e.name as exam_name, p.name as program_name');
        $builder_sch->join('exam_names e', 'e.id = es.exam_id');
        $builder_sch->join('programs p', 'p.id = es.program_id');
        $builder_sch->where('es.org_id', session('org_id'));
        $data['schedules'] = $builder_sch->get()->getResultArray();
        
        $data['staff'] = $db->table('org_users')->select('id, full_name as name, role')->where('org_id', session('org_id'))->get()->getResultArray();
        
        return view('org/examinations/invigilation', $data);
    }

    public function save_invigilation()
    {
        $invModel = new \App\Models\ExamInvigilationModel();
        $id = $this->request->getPost('id');
        $sessions = $this->request->getPost('sessions') ?: 1;
        $charge = $this->request->getPost('charge_per_session');
        
        $data = [
            'org_id' => session('org_id'),
            'exam_schedule_id' => $this->request->getPost('exam_schedule_id'),
            'invigilator_id' => $this->request->getPost('invigilator_id'),
            'charge_per_session' => $charge,
            'total_charge' => $charge * $sessions,
            'paid' => $this->request->getPost('paid') ? 1 : 0
        ];
        if (empty($id)) {
            $invModel->insert($data);
        } else {
            $invModel->update($id, $data);
        }
        return redirect()->to('org/examinations/invigilation')->with('success', 'Invigilation duty saved.');
    }

    public function finance()
    {
        $db = \Config\Database::connect();
        $data['grants'] = $db->table('exam_grants')->where('org_id', session('org_id'))->get()->getResultArray();
        
        $builder = $db->table('exam_expenditures ee');
        $builder->select('ee.*, e.name as exam_name');
        $builder->join('exam_names e', 'e.id = ee.exam_id', 'left');
        $builder->where('ee.org_id', session('org_id'));
        $data['expenditures'] = $builder->get()->getResultArray();
        
        $data['exams'] = $db->table('exam_names')->where('org_id', session('org_id'))->get()->getResultArray();
        
        return view('org/examinations/finance', $data);
    }

    public function save_grant()
    {
        $grantModel = new \App\Models\ExamGrantModel();
        $data = [
            'org_id' => session('org_id'),
            'grant_type' => $this->request->getPost('grant_type'),
            'amount' => $this->request->getPost('amount'),
            'source' => $this->request->getPost('source'),
            'received_date' => $this->request->getPost('received_date'),
            'purpose' => $this->request->getPost('purpose')
        ];
        $grantModel->insert($data);
        return redirect()->to('org/examinations/finance')->with('success', 'Grant saved.');
    }

    public function save_expenditure()
    {
        $expModel = new \App\Models\ExamExpenditureModel();
        $data = [
            'org_id' => session('org_id'),
            'exam_id' => $this->request->getPost('exam_id') ?: null,
            'head' => $this->request->getPost('head'),
            'amount' => $this->request->getPost('amount'),
            'description' => $this->request->getPost('description'),
            'date' => $this->request->getPost('date')
        ];
        $expModel->insert($data);
        return redirect()->to('org/examinations/finance')->with('success', 'Expenditure saved.');
    }

    public function reports()
    {
        $db = \Config\Database::connect();
        
        // 1. D-Form (Exam Fee Default) - Students who applied but fee_paid = 0
        $builder_dues = $db->table('exam_applications ea');
        $builder_dues->select('ea.*, e.name as exam_name, s.first_name, s.last_name, s.roll_number');
        $builder_dues->join('exam_names e', 'e.id = ea.exam_id');
        $builder_dues->join('students s', 's.id = ea.student_id');
        $builder_dues->where('ea.org_id', session('org_id'));
        $builder_dues->where('ea.fee_paid', 0);
        $data['dues'] = $builder_dues->get()->getResultArray();
        
        // 2. Admissions/Registration Count per Exam
        $builder_counts = $db->table('exam_applications ea');
        $builder_counts->select('e.name as exam_name, COUNT(ea.id) as total_applied, SUM(CASE WHEN ea.fee_paid = 1 THEN 1 ELSE 0 END) as total_paid');
        $builder_counts->join('exam_names e', 'e.id = ea.exam_id');
        $builder_counts->where('ea.org_id', session('org_id'));
        $builder_counts->groupBy('ea.exam_id');
        $data['counts'] = $builder_counts->get()->getResultArray();
        
        $data['students'] = $db->table('students')->select('id, first_name, last_name, roll_number')->where('org_id', session('org_id'))->get()->getResultArray();
        
        $studentId = $this->request->getGet('student_id');
        if ($studentId) {
            // Progress Report for student
            $builder_marks = $db->table('exam_marks_external em');
            $builder_marks->select('em.*, es.exam_date, es.max_marks, es.passing_marks, sub.name as subject_name, e.name as exam_name');
            $builder_marks->join('exam_schedules es', 'es.id = em.exam_schedule_id');
            $builder_marks->join('subjects sub', 'sub.id = es.subject_id');
            $builder_marks->join('exam_names e', 'e.id = es.exam_id');
            $builder_marks->where('em.org_id', session('org_id'));
            $builder_marks->where('em.student_id', $studentId);
            $data['progress'] = $builder_marks->get()->getResultArray();
            $data['selected_student'] = $db->table('students')->where('id', $studentId)->get()->getRowArray();
        }
        
        return view('org/examinations/reports', $data);
    }
}
