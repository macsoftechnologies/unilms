<?php

namespace App\Controllers;

use App\Models\AgentModel;
use App\Models\BankDetailModel;
use App\Models\LocationModel;
use App\Models\HolidayModel;
use App\Models\CollegeDetailsModel;
use App\Models\LectureHallsModel;

class OrgAdministration extends BaseController
{
    public function index()
    {
        return view('org/administration/index');
    }

    // ==========================================
    // COLLEGE DETAILS
    // ==========================================
    public function college_details()
    {
        $model = new CollegeDetailsModel();
        $data['details'] = $model->where('org_id', session('org_id'))->first();
        return view('org/administration/college_details', $data);
    }

    public function save_college_details()
    {
        try {
            $orgId = session('org_id') ?: 5;
            $db = \Config\Database::connect();

            // Ensure columns exist on database
            try {
                $db->query("ALTER TABLE `college_details` ADD COLUMN IF NOT EXISTS `name` VARCHAR(255) NULL");
                $db->query("ALTER TABLE `college_details` ADD COLUMN IF NOT EXISTS `college_name` VARCHAR(255) NULL");
                $db->query("ALTER TABLE `college_details` ADD COLUMN IF NOT EXISTS `logo_path` VARCHAR(255) NULL");
                $db->query("ALTER TABLE `college_details` ADD COLUMN IF NOT EXISTS `naac_grade` VARCHAR(50) NULL");
            } catch (\Throwable $migErr) {}

            $id = $this->request->getPost('id');
            $collegeName = $this->request->getPost('name') ?: 'V Apex Institute of Technology';
            
            $data = [
                'org_id'        => $orgId,
                'name'          => $collegeName,
                'college_name'  => $collegeName,
                'college_code'  => $this->request->getPost('naac_grade') ?: 'COL-001',
                'affiliation'   => $this->request->getPost('affiliation'),
                'naac_grade'    => $this->request->getPost('naac_grade'),
                'contact_email' => $this->request->getPost('contact_email'),
                'contact_phone' => $this->request->getPost('contact_phone'),
                'address'       => $this->request->getPost('address')
            ];

            // Handle Logo Upload safely
            try {
                $logo = $this->request->getFile('logo');
                if ($logo && $logo->isValid() && !$logo->hasMoved()) {
                    $newName = $logo->getRandomName();
                    $targetDir = FCPATH . 'uploads/org_logos/';
                    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                    $logo->move($targetDir, $newName);
                    $data['logo_path'] = 'uploads/org_logos/' . $newName;
                }
            } catch (\Throwable $uploadErr) {}

            // Direct DB Upsert to avoid Model hook failures
            $existing = $db->table('college_details')->where('org_id', $orgId)->get()->getRowArray();
            if ($existing) {
                $db->table('college_details')->where('id', $existing['id'])->update($data);
            } else {
                $db->table('college_details')->insert($data);
            }

            // Sync name in organizations table
            try {
                $db->table('organizations')->where('id', $orgId)->update(['name' => $collegeName]);
            } catch (\Throwable $orgSyncErr) {}

            return redirect()->to(base_url('org/administration/college-details'))->with('success', 'College profile and institutional details updated successfully.');
        } catch (\Throwable $e) {
            log_message('error', 'College details save error: ' . $e->getMessage());
            return redirect()->to(base_url('org/administration/college-details'))->with('error', 'Error saving details: ' . $e->getMessage());
        }
    }

    // ==========================================
    // LECTURE HALLS
    // ==========================================
    public function lecture_halls()
    {
        $orgId = session('org_id') ?: 5;
        $db = \Config\Database::connect();
        try {
            $db->query("ALTER TABLE `lecture_halls` ADD COLUMN IF NOT EXISTS `name` VARCHAR(100) NULL");
            $db->query("ALTER TABLE `lecture_halls` ADD COLUMN IF NOT EXISTS `capacity` INT NULL");
            $db->query("ALTER TABLE `lecture_halls` ADD COLUMN IF NOT EXISTS `hall_type` VARCHAR(50) NULL");
        } catch (\Throwable $e) {}

        $halls = $db->table('lecture_halls')->where('org_id', $orgId)->get()->getResultArray();
        
        foreach ($halls as &$h) {
            if (empty($h['name'])) $h['name'] = $h['hall_name'] ?? 'Classroom';
            if (empty($h['capacity'])) $h['capacity'] = $h['seating_capacity'] ?? 60;
            if (empty($h['hall_type'])) $h['hall_type'] = 'Lecture Hall';
        }

        $data['halls'] = $halls;
        return view('org/administration/lecture_halls', $data);
    }

    public function save_lecture_hall()
    {
        try {
            $orgId = session('org_id') ?: 5;
            $db = \Config\Database::connect();
            try {
                $db->query("ALTER TABLE `lecture_halls` ADD COLUMN IF NOT EXISTS `name` VARCHAR(100) NULL");
                $db->query("ALTER TABLE `lecture_halls` ADD COLUMN IF NOT EXISTS `capacity` INT NULL");
                $db->query("ALTER TABLE `lecture_halls` ADD COLUMN IF NOT EXISTS `hall_type` VARCHAR(50) NULL");
            } catch (\Throwable $e) {}

            $id = $this->request->getPost('id');
            $hallName = $this->request->getPost('name') ?: 'Room 101';
            $capacity = (int)($this->request->getPost('capacity') ?: 60);
            $hallType = $this->request->getPost('hall_type') ?: 'Lecture Hall';

            $data = [
                'org_id'           => $orgId,
                'name'             => $hallName,
                'hall_name'        => $hallName,
                'capacity'         => $capacity,
                'seating_capacity' => $capacity,
                'hall_type'        => $hallType,
                'building_name'    => 'Main Academic Block',
                'is_active'        => 1
            ];

            if (empty($id)) {
                $db->table('lecture_halls')->insert($data);
            } else {
                $db->table('lecture_halls')->where('id', $id)->where('org_id', $orgId)->update($data);
            }

            return redirect()->to(base_url('org/administration/lecture-halls'))->with('success', 'Classroom / Laboratory saved successfully.');
        } catch (\Throwable $e) {
            log_message('error', 'Lecture hall save error: ' . $e->getMessage());
            return redirect()->to(base_url('org/administration/lecture-halls'))->with('error', 'Error saving room: ' . $e->getMessage());
        }
    }

    public function delete_lecture_hall($id)
    {
        $orgId = session('org_id') ?: 5;
        $db = \Config\Database::connect();
        $db->table('lecture_halls')->where('id', $id)->where('org_id', $orgId)->delete();
        return redirect()->to(base_url('org/administration/lecture-halls'))->with('success', 'Lecture hall deleted successfully.');
    }

    // ==========================================
    // SETTINGS
    // ==========================================
    public function settings()
    {
        return view('org/administration/settings');
    }

    public function save_settings()
    {
        // Settings logic (future)
        return redirect()->to('org/administration/settings')->with('success', 'Settings saved.');
    }

    // ==========================================
    // BANK DETAILS
    // ==========================================
    public function bank_details()
    {
        $bankModel = new BankDetailModel();
        $data['banks'] = $bankModel->where('org_id', session('org_id'))->findAll();
        return view('org/administration/bank_details', $data);
    }

    public function save_bank()
    {
        $bankModel = new BankDetailModel();
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => session('org_id'),
            'bank_name' => $this->request->getPost('bank_name'),
            'account_name' => $this->request->getPost('account_name'),
            'account_number' => $this->request->getPost('account_number'),
            'ifsc_code' => $this->request->getPost('ifsc_code'),
            'branch_name' => $this->request->getPost('branch_name'),
            'is_primary' => $this->request->getPost('is_primary') ? 1 : 0
        ];

        if ($data['is_primary']) {
            // Unset other primary banks
            $bankModel->where('org_id', session('org_id'))->set(['is_primary' => 0])->update();
        }

        if (empty($id)) {
            $bankModel->insert($data);
        } else {
            $bankModel->update($id, $data);
        }

        return redirect()->to('org/administration/bank-details')->with('success', 'Bank details saved.');
    }

    // ==========================================
    // LOCATIONS
    // ==========================================
    public function locations()
    {
        $locModel = new LocationModel();
        $data['locations'] = $locModel->where('org_id', session('org_id'))->findAll();
        return view('org/administration/locations', $data);
    }

    public function save_location()
    {
        $locModel = new LocationModel();
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => session('org_id'),
            'name' => $this->request->getPost('name'),
            'type' => $this->request->getPost('type'),
            'capacity' => $this->request->getPost('capacity') ?: 0,
            'parent_id' => $this->request->getPost('parent_id') ?: null
        ];

        if (empty($id)) {
            $locModel->insert($data);
        } else {
            $locModel->update($id, $data);
        }

        return redirect()->to('org/administration/locations')->with('success', 'Location saved.');
    }

    // ==========================================
    // AGENTS
    // ==========================================
    public function agents()
    {
        $agentModel = new AgentModel();
        $data['agents'] = $agentModel->where('org_id', session('org_id'))->findAll();
        return view('org/administration/agents', $data);
    }

    public function save_agent()
    {
        $agentModel = new AgentModel();
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => session('org_id'),
            'name' => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            'commission_rate' => $this->request->getPost('commission_rate') ?: 0,
            'status' => $this->request->getPost('status')
        ];

        if (empty($id)) {
            $agentModel->insert($data);
        } else {
            $agentModel->update($id, $data);
        }

        return redirect()->to('org/administration/agents')->with('success', 'Agent saved.');
    }

    // ==========================================
    // HOLIDAYS
    // ==========================================
    public function holidays()
    {
        $holModel = new HolidayModel();
        $data['holidays'] = $holModel->where('org_id', session('org_id'))->orderBy('start_date', 'ASC')->findAll();
        return view('org/administration/holidays', $data);
    }

    public function save_holiday()
    {
        $holModel = new HolidayModel();
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => session('org_id'),
            'title' => $this->request->getPost('title'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'type' => $this->request->getPost('type')
        ];

        if (empty($id)) {
            $holModel->insert($data);
        } else {
            $holModel->update($id, $data);
        }

        return redirect()->to('org/administration/holidays')->with('success', 'Holiday saved.');
    }

    // ==========================================
    // CERTIFICATES MASTER
    // ==========================================
    public function certificates()
    {
        $certModel = new \App\Models\CertificateMasterModel();
        $data['certificates'] = $certModel->where('org_id', session('org_id'))->findAll();
        return view('org/administration/certificates', $data);
    }

    public function save_certificate()
    {
        $certModel = new \App\Models\CertificateMasterModel();
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => session('org_id'),
            'certificate_name' => $this->request->getPost('certificate_name'),
            'template' => $this->request->getPost('template')
        ];

        if (empty($id)) {
            $certModel->insert($data);
        } else {
            $certModel->update($id, $data);
        }
        return redirect()->to('org/administration/certificates')->with('success', 'Certificate template saved.');
    }

    // ==========================================
    // COMPLAINTS
    // ==========================================
    public function complaints()
    {
        $compModel = new \App\Models\ComplaintModel();
        $data['complaints'] = $compModel->select('complaints.*, org_users.full_name as user_name, org_users.role')
                                        ->join('org_users', 'org_users.id = complaints.user_id', 'left')
                                        ->where('complaints.org_id', session('org_id'))
                                        ->orderBy('created_at', 'DESC')
                                        ->findAll();
        return view('org/administration/complaints', $data);
    }

    public function update_complaint_status()
    {
        $compModel = new \App\Models\ComplaintModel();
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        
        $compModel->update($id, ['status' => $status]);
        return redirect()->to('org/administration/complaints')->with('success', 'Complaint status updated.');
    }

    // ==========================================
    // DIARY / NOTICE BOARD
    // ==========================================
    public function diary()
    {
        $diaryModel = new \App\Models\DiaryModel();
        $data['diaries'] = $diaryModel->select('diary.*, org_users.full_name as created_by_name')
                                      ->join('org_users', 'org_users.id = diary.created_by', 'left')
                                      ->where('diary.org_id', session('org_id'))
                                      ->orderBy('publish_date', 'DESC')
                                      ->findAll();
        return view('org/administration/diary', $data);
    }

    public function save_diary()
    {
        $diaryModel = new \App\Models\DiaryModel();
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => session('org_id'),
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'target_audience' => $this->request->getPost('target_audience'),
            'publish_date' => $this->request->getPost('publish_date'),
            'expiry_date' => $this->request->getPost('expiry_date') ?: null,
            'created_by' => session('org_user_id')
        ];

        if (empty($id)) {
            $diaryModel->insert($data);
        } else {
            $diaryModel->update($id, $data);
        }
        return redirect()->to('org/administration/diary')->with('success', 'Diary entry saved.');
    }
}
