<?php

namespace App\Controllers;

use App\Models\VisitorModel;
use App\Models\CallLogModel;
use App\Models\PostalRecordModel;
use App\Models\AdmissionEnquiryModel;
use App\Models\EnquiryFollowupModel;
use App\Models\DepartmentModel;
use App\Models\ProgramModel;
use App\Models\OrgUserModel;
use App\Models\LeadModel;

class OrgFrontOffice extends BaseController
{
    public function index()
    {
        $orgId = session('org_id');
        $db = \Config\Database::connect();
        $today = date('Y-m-d');

        $data['activeModule'] = 'front_office';

        // Summary counts
        $data['today_visitors'] = $db->table('visitors')
            ->where('org_id', $orgId)
            ->where('DATE(created_at)', $today)
            ->countAllResults();

        $data['currently_inside'] = $db->table('visitors')
            ->where('org_id', $orgId)
            ->where('in_time IS NOT NULL')
            ->where('out_time IS NULL')
            ->countAllResults();

        $data['today_calls'] = $db->table('call_logs')
            ->where('org_id', $orgId)
            ->where('DATE(created_at)', $today)
            ->countAllResults();

        $data['pending_followups'] = $db->table('call_logs')
            ->where('org_id', $orgId)
            ->where('followup_required', 1)
            ->where('followup_date <=', $today)
            ->countAllResults();

        $data['today_postal'] = $db->table('postal_records')
            ->where('org_id', $orgId)
            ->where('date', $today)
            ->countAllResults();

        $data['active_enquiries'] = $db->table('admission_enquiries')
            ->where('org_id', $orgId)
            ->whereIn('status', ['New', 'Follow Up', 'Interested'])
            ->countAllResults();

        // Recent visitors
        $data['recent_visitors'] = $db->table('visitors')
            ->where('org_id', $orgId)
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->get()->getResultArray();

        // Recent calls
        $data['recent_calls'] = $db->table('call_logs')
            ->where('org_id', $orgId)
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->get()->getResultArray();

        // Recent postal
        $data['recent_postal'] = $db->table('postal_records')
            ->where('org_id', $orgId)
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->get()->getResultArray();

        return view('org/front_office/index', $data);
    }

    public function visitors()
    {
        $orgId = session('org_id');
        $db = \Config\Database::connect();

        $status = $this->request->getGet('status'); // 'inside' or 'all'
        $date = $this->request->getGet('date') ?: date('Y-m-d');

        $builder = $db->table('visitors')
            ->select('visitors.*, departments.name as department_name')
            ->join('departments', 'departments.id = visitors.department_id', 'left')
            ->where('visitors.org_id', $orgId);

        if ($status === 'inside') {
            $builder->where('visitors.in_time IS NOT NULL')->where('visitors.out_time IS NULL');
        } elseif (!empty($date)) {
            $builder->where('DATE(visitors.created_at)', $date);
        }

        $data['visitors'] = $builder->orderBy('visitors.id', 'DESC')->get()->getResultArray();
        $data['departments'] = (new DepartmentModel())->where('org_id', $orgId)->findAll();
        $data['selected_status'] = $status;
        $data['selected_date'] = $date;
        $data['activeModule'] = 'front_office';

        return view('org/front_office/visitors', $data);
    }

    public function saveVisitor()
    {
        $orgId = session('org_id');
        $visitorModel = new VisitorModel();

        $id = $this->request->getPost('id');
        $visitorName = trim($this->request->getPost('visitor_name'));
        $phone = trim($this->request->getPost('phone'));
        $visitorType = $this->request->getPost('visitor_type') ?: 'Guest';
        $purpose = trim($this->request->getPost('purpose'));
        $personToMeet = trim($this->request->getPost('person_to_meet'));
        $deptId = $this->request->getPost('department_id') ?: null;
        $idProof = $this->request->getPost('id_proof');
        $remarks = $this->request->getPost('remarks');

        if ($id) {
            $visitorModel->where('org_id', $orgId)->update($id, [
                'visitor_name' => $visitorName,
                'phone' => $phone,
                'visitor_type' => $visitorType,
                'purpose' => $purpose,
                'person_to_meet' => $personToMeet,
                'department_id' => $deptId,
                'id_proof' => $idProof,
                'remarks' => $remarks
            ]);
            return redirect()->to('org/front-office/visitors')->with('success', 'Visitor details updated.');
        } else {
            $passNumber = 'PASS-' . date('ymd') . '-' . rand(100, 999);
            $visitorModel->insert([
                'org_id' => $orgId,
                'visitor_name' => $visitorName,
                'phone' => $phone,
                'visitor_type' => $visitorType,
                'purpose' => $purpose,
                'person_to_meet' => $personToMeet,
                'department_id' => $deptId,
                'in_time' => date('Y-m-d H:i:s'),
                'pass_number' => $passNumber,
                'id_proof' => $idProof,
                'remarks' => $remarks,
                'created_by' => session('org_user_id')
            ]);
            return redirect()->to('org/front-office/visitors')->with('success', "Visitor checked in successfully. Pass: {$passNumber}");
        }
    }

    public function checkoutVisitor($id)
    {
        $orgId = session('org_id');
        (new VisitorModel())->where('org_id', $orgId)->update($id, [
            'out_time' => date('Y-m-d H:i:s')
        ]);
        return redirect()->to('org/front-office/visitors')->with('success', 'Visitor checked out successfully.');
    }

    public function printPass($id)
    {
        $orgId = session('org_id');
        $db = \Config\Database::connect();

        $visitor = $db->table('visitors')
            ->select('visitors.*, departments.name as department_name, organizations.name as org_name')
            ->join('departments', 'departments.id = visitors.department_id', 'left')
            ->join('organizations', 'organizations.id = visitors.org_id', 'left')
            ->where('visitors.id', $id)
            ->where('visitors.org_id', $orgId)
            ->get()->getRowArray();

        if (!$visitor) return redirect()->to('org/front-office/visitors')->with('error', 'Visitor not found.');

        return view('org/front_office/pass_print', ['visitor' => $visitor]);
    }

    public function calls()
    {
        $orgId = session('org_id');
        $db = \Config\Database::connect();
        $type = $this->request->getGet('type');

        $builder = $db->table('call_logs')->where('org_id', $orgId);
        if ($type) {
            $builder->where('call_type', $type);
        }

        $data['calls'] = $builder->orderBy('id', 'DESC')->get()->getResultArray();
        $data['selected_type'] = $type;
        $data['activeModule'] = 'front_office';

        return view('org/front_office/call_logs', $data);
    }

    public function saveCall()
    {
        $orgId = session('org_id');
        $callModel = new CallLogModel();

        $callModel->insert([
            'org_id' => $orgId,
            'call_type' => $this->request->getPost('call_type'),
            'caller_name' => trim($this->request->getPost('caller_name')),
            'phone_number' => trim($this->request->getPost('phone_number')),
            'purpose' => trim($this->request->getPost('purpose')),
            'call_duration' => trim($this->request->getPost('call_duration')),
            'call_result' => $this->request->getPost('call_result'),
            'followup_required' => $this->request->getPost('followup_required') ? 1 : 0,
            'followup_date' => $this->request->getPost('followup_date') ?: null,
            'remarks' => $this->request->getPost('remarks'),
            'created_by' => session('org_user_id')
        ]);

        return redirect()->to('org/front-office/calls')->with('success', 'Call log recorded successfully.');
    }

    public function deleteCall($id)
    {
        $orgId = session('org_id');
        (new CallLogModel())->where('org_id', $orgId)->delete($id);
        return redirect()->to('org/front-office/calls')->with('success', 'Call log deleted.');
    }

    public function postal()
    {
        $orgId = session('org_id');
        $db = \Config\Database::connect();
        $recordType = $this->request->getGet('type');

        $builder = $db->table('postal_records')->where('org_id', $orgId);
        if ($recordType) {
            $builder->where('record_type', $recordType);
        }

        $data['records'] = $builder->orderBy('id', 'DESC')->get()->getResultArray();
        $data['selected_type'] = $recordType;
        $data['activeModule'] = 'front_office';

        return view('org/front_office/postal', $data);
    }

    public function savePostal()
    {
        $orgId = session('org_id');
        $postalModel = new PostalRecordModel();

        $refNo = trim($this->request->getPost('reference_number'));
        if (empty($refNo)) {
            $refNo = 'POST-' . date('Ymd') . '-' . rand(100, 999);
        }

        $postalModel->insert([
            'org_id' => $orgId,
            'record_type' => $this->request->getPost('record_type'),
            'reference_number' => $refNo,
            'sender_receiver_name' => trim($this->request->getPost('sender_receiver_name')),
            'address' => trim($this->request->getPost('address')),
            'courier_name' => trim($this->request->getPost('courier_name')),
            'tracking_number' => trim($this->request->getPost('tracking_number')),
            'date' => $this->request->getPost('date') ?: date('Y-m-d'),
            'description' => trim($this->request->getPost('description')),
            'handled_by' => session('org_user_id')
        ]);

        return redirect()->to('org/front-office/postal')->with('success', 'Postal record registered successfully.');
    }

    public function deletePostal($id)
    {
        $orgId = session('org_id');
        (new PostalRecordModel())->where('org_id', $orgId)->delete($id);
        return redirect()->to('org/front-office/postal')->with('success', 'Postal record deleted.');
    }

    public function enquiries()
    {
        $orgId = session('org_id');
        $db = \Config\Database::connect();
        $status = $this->request->getGet('status');

        $builder = $db->table('admission_enquiries')
            ->select('admission_enquiries.*, programs.name as program_name, org_users.full_name as counsellor_name')
            ->join('programs', 'programs.id = admission_enquiries.program_id', 'left')
            ->join('org_users', 'org_users.id = admission_enquiries.assigned_counsellor', 'left')
            ->where('admission_enquiries.org_id', $orgId);

        if ($status) {
            $builder->where('admission_enquiries.status', $status);
        }

        $data['enquiries'] = $builder->orderBy('admission_enquiries.id', 'DESC')->get()->getResultArray();
        $data['programs'] = (new ProgramModel())->where('org_id', $orgId)->findAll();
        $data['counsellors'] = (new OrgUserModel())->where('org_id', $orgId)->where('user_type', 'staff')->findAll();
        $data['selected_status'] = $status;
        $data['activeModule'] = 'front_office';

        return view('org/front_office/enquiries', $data);
    }

    public function saveEnquiry()
    {
        $orgId = session('org_id');
        $enquiryModel = new AdmissionEnquiryModel();

        $id = $this->request->getPost('id');
        $enqNo = 'ENQ-' . date('ymd') . '-' . rand(100, 999);

        $data = [
            'org_id' => $orgId,
            'student_name' => trim($this->request->getPost('student_name')),
            'parent_name' => trim($this->request->getPost('parent_name')),
            'mobile' => trim($this->request->getPost('mobile')),
            'email' => trim($this->request->getPost('email')),
            'program_id' => $this->request->getPost('program_id') ?: null,
            'enquiry_source' => $this->request->getPost('enquiry_source') ?: 'Walk-In',
            'remarks' => trim($this->request->getPost('remarks')),
            'assigned_counsellor' => $this->request->getPost('assigned_counsellor') ?: null,
            'status' => $this->request->getPost('status') ?: 'New'
        ];

        if ($id) {
            $enquiryModel->where('org_id', $orgId)->update($id, $data);
            return redirect()->to('org/front-office/enquiries')->with('success', 'Enquiry updated successfully.');
        } else {
            $data['enquiry_number'] = $enqNo;
            $data['created_by'] = session('org_user_id');
            $enquiryModel->insert($data);
            return redirect()->to('org/front-office/enquiries')->with('success', "Enquiry registered. Reference No: {$enqNo}");
        }
    }

    public function saveFollowup()
    {
        $orgId = session('org_id');
        $followupModel = new EnquiryFollowupModel();
        $enquiryModel = new AdmissionEnquiryModel();

        $enquiryId = $this->request->getPost('enquiry_id');
        $newStatus = $this->request->getPost('status');

        $followupModel->insert([
            'org_id' => $orgId,
            'enquiry_id' => $enquiryId,
            'notes' => trim($this->request->getPost('notes')),
            'followup_date' => date('Y-m-d H:i:s'),
            'next_followup_date' => $this->request->getPost('next_followup_date') ?: null,
            'followed_by' => session('org_user_id'),
            'followup_status' => 'Completed'
        ]);

        if ($newStatus) {
            $enquiryModel->where('org_id', $orgId)->update($enquiryId, ['status' => $newStatus]);
        }

        return redirect()->to('org/front-office/enquiries')->with('success', 'Follow-up interaction recorded.');
    }

    public function convertToAdmission($id)
    {
        $orgId = session('org_id');
        $enquiry = (new AdmissionEnquiryModel())->where('org_id', $orgId)->find($id);
        if (!$enquiry) return redirect()->to('org/front-office/enquiries')->with('error', 'Enquiry not found.');

        // Convert into Lead in Admissions CRM
        $leadModel = new LeadModel();
        $leadModel->insert([
            'org_id' => $orgId,
            'full_name' => $enquiry['student_name'],
            'phone' => $enquiry['mobile'],
            'email' => $enquiry['email'],
            'program_id' => $enquiry['program_id'],
            'source' => $enquiry['enquiry_source'],
            'referred_by' => 'Front Office Enquiry #' . $enquiry['enquiry_number'],
            'assigned_to' => $enquiry['assigned_counsellor'],
            'status' => 'Interested'
        ]);

        (new AdmissionEnquiryModel())->where('org_id', $orgId)->update($id, ['status' => 'Sent to Admin Officer']);

        return redirect()->to('org/admissions/leads')->with('success', "Enquiry #{$enquiry['enquiry_number']} successfully forwarded to Admissions CRM as a Lead.");
    }

    public function api_live_stats()
    {
        $orgId = session('org_id');
        $db = \Config\Database::connect();
        $today = date('Y-m-d');

        $todayVisitors = $db->table('visitors')
            ->where('org_id', $orgId)
            ->where('DATE(created_at)', $today)
            ->countAllResults();

        $currentlyInside = $db->table('visitors')
            ->where('org_id', $orgId)
            ->where('in_time IS NOT NULL')
            ->where('out_time IS NULL')
            ->countAllResults();

        $todayCalls = $db->table('call_logs')
            ->where('org_id', $orgId)
            ->where('DATE(created_at)', $today)
            ->countAllResults();

        $pendingFollowups = $db->table('call_logs')
            ->where('org_id', $orgId)
            ->where('followup_required', 1)
            ->where('followup_date <=', $today)
            ->countAllResults();

        $recentVisitors = $db->table('visitors')
            ->where('org_id', $orgId)
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'timestamp' => date('H:i:s'),
            'today_visitors' => $todayVisitors,
            'currently_inside' => $currentlyInside,
            'today_calls' => $todayCalls,
            'pending_followups' => $pendingFollowups,
            'recent_visitors' => $recentVisitors
        ]);
    }
}
