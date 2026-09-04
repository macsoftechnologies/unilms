<?php
namespace App\Controllers;

use App\Models\PlacementCompanyModel;
use App\Models\PlacementInternshipModel;
use App\Models\PlacementOfferModel;
use App\Models\StudentModel;

class OrgPlacements extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $orgId = session('org_id');

        // Dashboard Analytics
        $data['total_companies'] = $db->table('placement_companies')->where('org_id', $orgId)->countAllResults();
        $data['total_internships'] = $db->table('placement_internships')->where('org_id', $orgId)->countAllResults();
        $data['total_offers'] = $db->table('placement_offers')->where('org_id', $orgId)->countAllResults();
        
        // Highest CTC
        $highest = $db->table('placement_offers')->selectMax('ctc')->where('org_id', $orgId)->get()->getRow();
        $data['highest_ctc'] = $highest->ctc ?? 0;

        // Recent Offers
        $builder = $db->table('placement_offers po');
        $builder->select('po.*, s.first_name, s.last_name, s.roll_number, c.company_name');
        $builder->join('students s', 's.id = po.student_id');
        $builder->join('placement_companies c', 'c.id = po.company_id');
        $builder->where('po.org_id', $orgId);
        $builder->orderBy('po.offer_date', 'DESC');
        $builder->limit(5);
        $data['recent_offers'] = $builder->get()->getResultArray();

        return view('org/placements/index', $data);
    }

    // Companies
    public function companies()
    {
        $companyModel = new PlacementCompanyModel();
        $data['companies'] = $companyModel->where('org_id', session('org_id'))->findAll();
        return view('org/placements/companies', $data);
    }

    public function save_company()
    {
        $companyModel = new PlacementCompanyModel();
        $id = $this->request->getPost('id');
        
        $data = [
            'org_id' => session('org_id'),
            'company_name' => $this->request->getPost('company_name'),
            'industry' => $this->request->getPost('industry'),
            'hr_contact_name' => $this->request->getPost('hr_contact_name'),
            'hr_email' => $this->request->getPost('hr_email'),
            'hr_phone' => $this->request->getPost('hr_phone'),
            'mou_signed' => $this->request->getPost('mou_signed') ? 1 : 0
        ];

        if (empty($id)) {
            $companyModel->insert($data);
        } else {
            $companyModel->update($id, $data);
        }

        return redirect()->to('org/placements/companies')->with('success', 'Company saved successfully.');
    }

    // Internships
    public function internships()
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('placement_internships pi');
        $builder->select('pi.*, s.first_name, s.last_name, s.roll_number, c.company_name');
        $builder->join('students s', 's.id = pi.student_id');
        $builder->join('placement_companies c', 'c.id = pi.company_id');
        $builder->where('pi.org_id', session('org_id'));
        $data['internships'] = $builder->get()->getResultArray();
        
        $companyModel = new PlacementCompanyModel();
        $data['companies'] = $companyModel->where('org_id', session('org_id'))->findAll();
        
        $studentModel = new StudentModel();
        $data['students'] = $studentModel->where('org_id', session('org_id'))->findAll();

        return view('org/placements/internships', $data);
    }

    public function save_internship()
    {
        $internModel = new PlacementInternshipModel();
        
        $data = [
            'org_id' => session('org_id'),
            'student_id' => $this->request->getPost('student_id'),
            'company_id' => $this->request->getPost('company_id'),
            'role' => $this->request->getPost('role'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'stipend' => $this->request->getPost('stipend'),
            'status' => $this->request->getPost('status')
        ];

        $internModel->insert($data);
        return redirect()->to('org/placements/internships')->with('success', 'Internship recorded.');
    }

    // Job Offers
    public function offers()
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('placement_offers po');
        $builder->select('po.*, s.first_name, s.last_name, s.roll_number, c.company_name');
        $builder->join('students s', 's.id = po.student_id');
        $builder->join('placement_companies c', 'c.id = po.company_id');
        $builder->where('po.org_id', session('org_id'));
        $data['offers'] = $builder->get()->getResultArray();
        
        $companyModel = new PlacementCompanyModel();
        $data['companies'] = $companyModel->where('org_id', session('org_id'))->findAll();
        
        $studentModel = new StudentModel();
        $data['students'] = $studentModel->where('org_id', session('org_id'))->findAll();

        return view('org/placements/offers', $data);
    }

    public function save_offer()
    {
        $offerModel = new PlacementOfferModel();
        
        $data = [
            'org_id' => session('org_id'),
            'student_id' => $this->request->getPost('student_id'),
            'company_id' => $this->request->getPost('company_id'),
            'job_role' => $this->request->getPost('job_role'),
            'ctc' => $this->request->getPost('ctc'),
            'offer_date' => $this->request->getPost('offer_date'),
            'status' => $this->request->getPost('status')
        ];

        $offerModel->insert($data);
        return redirect()->to('org/placements/offers')->with('success', 'Offer recorded.');
    }

    // Campus Recruitment Drives
    public function drives()
    {
        $db = \Config\Database::connect();
        $orgId = session('org_id');

        $drives = $db->table('placement_drives pd')
            ->select('pd.*, pc.company_name')
            ->join('placement_companies pc', 'pc.id = pd.company_id', 'left')
            ->where('pd.org_id', $orgId)
            ->orderBy('pd.drive_date', 'DESC')
            ->get()->getResultArray();

        foreach ($drives as &$d) {
            $d['applicant_count'] = $db->table('placement_applications')
                ->where('drive_id', $d['id'])
                ->countAllResults();
            $d['selected_count'] = $db->table('placement_applications')
                ->where('drive_id', $d['id'])
                ->where('status', 'Selected')
                ->countAllResults();
        }

        $data['drives'] = $drives;
        $data['companies'] = (new PlacementCompanyModel())->where('org_id', $orgId)->findAll();
        $data['activeModule'] = 'placements';

        return view('org/placements/drives', $data);
    }

    public function save_drive()
    {
        $orgId = session('org_id');
        $driveModel = new \App\Models\PlacementDriveModel();

        $id = $this->request->getPost('id');
        $data = [
            'org_id' => $orgId,
            'company_id' => $this->request->getPost('company_id'),
            'title' => trim($this->request->getPost('title')),
            'job_role' => trim($this->request->getPost('job_role')),
            'description' => trim($this->request->getPost('description')),
            'location' => trim($this->request->getPost('location')),
            'ctc_details' => trim($this->request->getPost('ctc_details')),
            'required_cgpa' => (float)$this->request->getPost('required_cgpa'),
            'max_backlogs' => (int)$this->request->getPost('max_backlogs'),
            'eligible_programs' => $this->request->getPost('eligible_programs'),
            'deadline_date' => $this->request->getPost('deadline_date'),
            'drive_date' => $this->request->getPost('drive_date'),
            'status' => $this->request->getPost('status') ?: 'Upcoming'
        ];

        if ($id) {
            $driveModel->where('org_id', $orgId)->update($id, $data);
            return redirect()->to('org/placements/drives')->with('success', 'Recruitment drive updated successfully.');
        } else {
            $driveModel->insert($data);
            return redirect()->to('org/placements/drives')->with('success', 'Recruitment drive created successfully.');
        }
    }

    // Drive Student Applications
    public function drive_applications($driveId)
    {
        $db = \Config\Database::connect();
        $orgId = session('org_id');

        $drive = $db->table('placement_drives pd')
            ->select('pd.*, pc.company_name')
            ->join('placement_companies pc', 'pc.id = pd.company_id', 'left')
            ->where('pd.id', $driveId)
            ->where('pd.org_id', $orgId)
            ->get()->getRowArray();

        if (!$drive) return redirect()->to('org/placements/drives')->with('error', 'Drive not found.');

        $applications = $db->table('placement_applications pa')
            ->select('pa.*, s.first_name, s.last_name, s.roll_number, s.email, c.name as cohort_name')
            ->join('students s', 's.id = pa.student_id', 'left')
            ->join('cohorts c', 'c.id = s.cohort_id', 'left')
            ->where('pa.drive_id', $driveId)
            ->where('pa.org_id', $orgId)
            ->orderBy('pa.id', 'DESC')
            ->get()->getResultArray();

        $data['drive'] = $drive;
        $data['applications'] = $applications;
        $data['activeModule'] = 'placements';

        return view('org/placements/applications', $data);
    }

    public function update_application()
    {
        $orgId = session('org_id');
        $id = $this->request->getPost('application_id');
        $status = $this->request->getPost('status');
        $currentRound = $this->request->getPost('current_round');
        $remarks = $this->request->getPost('remarks');

        (new \App\Models\PlacementApplicationModel())
            ->where('org_id', $orgId)
            ->update($id, [
                'status' => $status,
                'current_round' => $currentRound,
                'remarks' => $remarks
            ]);

        return redirect()->back()->with('success', 'Candidate evaluation updated.');
    }

    public function issue_offer_from_applicant($applicationId)
    {
        $db = \Config\Database::connect();
        $orgId = session('org_id');

        $app = $db->table('placement_applications pa')
            ->select('pa.*, pd.company_id, pd.job_role, pd.ctc_details')
            ->join('placement_drives pd', 'pd.id = pa.drive_id', 'left')
            ->where('pa.id', $applicationId)
            ->where('pa.org_id', $orgId)
            ->get()->getRowArray();

        if (!$app) return redirect()->back()->with('error', 'Application not found.');

        // Insert into placement_offers
        (new PlacementOfferModel())->insert([
            'org_id' => $orgId,
            'student_id' => $app['student_id'],
            'company_id' => $app['company_id'],
            'job_role' => $app['job_role'],
            'ctc' => (float)preg_replace('/[^0-9.]/', '', $app['ctc_details']),
            'offer_date' => date('Y-m-d'),
            'status' => 'Accepted'
        ]);

        (new \App\Models\PlacementApplicationModel())
            ->where('id', $applicationId)
            ->update(['status' => 'Selected']);

        return redirect()->to('org/placements/offers')->with('success', 'Job offer issued and registered successfully.');
    }
}
