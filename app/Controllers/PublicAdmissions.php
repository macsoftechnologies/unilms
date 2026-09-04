<?php

namespace App\Controllers;

use App\Models\LeadModel;
use App\Models\ProgramModel;
use App\Models\OrganizationModel;

class PublicAdmissions extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // Fetch active organization (default to first active org if none specified)
        $org = $db->table('organizations')->where('status', 'Active')->get()->getRowArray();
        $orgId = $org['id'] ?? 1;

        $programs = (new ProgramModel())->where('org_id', $orgId)->findAll();

        return view('admissions/public_apply', [
            'org' => $org,
            'programs' => $programs
        ]);
    }

    public function submit()
    {
        $db = \Config\Database::connect();
        $orgId = $this->request->getPost('org_id') ?: 1;

        $fullName = trim($this->request->getPost('full_name'));
        $email = trim($this->request->getPost('email'));
        $phone = trim($this->request->getPost('phone'));
        $programId = $this->request->getPost('program_id');
        $parentName = trim($this->request->getPost('parent_name'));
        $address = trim($this->request->getPost('address'));
        $prevScore = trim($this->request->getPost('previous_percentage'));

        $leadNo = 'APP-' . date('Y') . '-' . rand(1000, 9999);

        // Save into leads table
        $leadModel = new LeadModel();
        $leadId = $leadModel->insert([
            'org_id' => $orgId,
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'program_id' => $programId ?: null,
            'source' => 'Public Online Portal',
            'status' => 'Interested',
            'notes' => "Online Application Ref: {$leadNo} | Parent: {$parentName} | Qualifying Marks: {$prevScore}% | Address: {$address}"
        ]);

        return view('admissions/public_success', [
            'application_number' => $leadNo,
            'student_name' => $fullName,
            'email' => $email,
            'phone' => $phone
        ]);
    }
}
