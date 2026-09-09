<?php

namespace App\Controllers;

use App\Models\LeadModel;
use App\Models\ProgramModel;
use App\Models\OrgUserModel;
use App\Models\AdmissionActivityLogModel;
use App\Models\ApplicationModel;
use App\Models\AdmissionCycleModel;
use App\Models\DocumentTypeModel;
use App\Models\ApplicationDocumentModel;
use App\Models\AdmissionOfferModel;
use App\Models\AdmissionPaymentModel;
use App\Models\StudentModel;
use App\Models\AdmissionCategoryModel;
use App\Models\ScholarshipModel;
use App\Models\DetainedStudentModel;

class OrgAdmissions extends BaseController
{
    public function index()
    {
        return view('org/admissions/dashboard');
    }

    public function leads()
    {
        $leadModel = new LeadModel();
        $programModel = new ProgramModel();
        $userModel = new OrgUserModel();

        $data['leads'] = $leadModel->getLeads(session('org_id'));
        $data['programs'] = $programModel->where('org_id', session('org_id'))->findAll();
        
        // Fetch all staff who might be assigned a lead
        $data['staff'] = $userModel->where('org_id', session('org_id'))->findAll();

        return view('org/admissions/leads', $data);
    }

    public function save_lead()
    {
        $leadModel = new LeadModel();
        $logModel = new AdmissionActivityLogModel();
        
        $id = $this->request->getPost('id');
        $orgId = session('org_id');
        $userId = session('org_user_id');

        $data = [
            'org_id' => $orgId,
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            'program_id' => $this->request->getPost('program_id') ?: null,
            'source' => $this->request->getPost('source'),
            'referred_by' => $this->request->getPost('referred_by'),
            'city' => $this->request->getPost('city'),
            'assigned_to' => $this->request->getPost('assigned_to') ?: null,
            'status' => $this->request->getPost('status') ?: 'New',
            'follow_up_date' => $this->request->getPost('follow_up_date') ?: null
        ];

        if ($id) {
            // Update
            $leadModel->update($id, $data);
            
            $logModel->insert([
                'org_id' => $orgId,
                'lead_id' => $id,
                'action' => 'Lead Updated',
                'description' => 'Lead information was updated.',
                'performed_by' => $userId
            ]);

            return redirect()->back()->with('success', 'Lead updated successfully.');
        } else {
            // Check duplicates (Phone or Email)
            $exists = $leadModel->where('org_id', $orgId)
                                ->groupStart()
                                    ->where('phone', $data['phone'])
                                    ->orWhere('email', $data['email'])
                                ->groupEnd()
                                ->first();
            
            if ($exists && ($data['phone'] != '' || $data['email'] != '')) {
                return redirect()->back()->with('error', 'A lead with this phone number or email already exists.');
            }

            // Insert
            $newId = $leadModel->insert($data);
            
            $logModel->insert([
                'org_id' => $orgId,
                'lead_id' => $newId,
                'action' => 'Lead Created',
                'description' => 'New lead captured from ' . ($data['source'] ?: 'Manual Entry') . '.',
                'performed_by' => $userId
            ]);

            return redirect()->back()->with('success', 'Lead created successfully.');
        }
    }

    public function delete_lead($id)
    {
        $leadModel = new LeadModel();
        
        // Ensure it belongs to org
        $lead = $leadModel->findByIdOrUuid($id);
        if (!$lead || $lead['org_id'] != session('org_id')) {
            return redirect()->back()->with('error', 'Lead not found.');
        }

        $leadModel->delete($lead['id']);
        return redirect()->back()->with('success', 'Lead deleted successfully.');
    }

    public function applications()
    {
        $appModel = new ApplicationModel();
        $cycleModel = new AdmissionCycleModel();
        $programModel = new ProgramModel();

        $data['applications'] = $appModel->getApplications(session('org_id'));
        $data['cycles'] = $cycleModel->where('org_id', session('org_id'))->findAll();
        $data['programs'] = $programModel->where('org_id', session('org_id'))->findAll();

        return view('org/admissions/applications', $data);
    }

    public function convert_lead($leadId)
    {
        $leadModel = new LeadModel();
        $appModel = new ApplicationModel();
        $programModel = new ProgramModel();
        $logModel = new AdmissionActivityLogModel();
        
        $orgId = session('org_id');
        
        $lead = $leadModel->where('org_id', $orgId)->findByIdOrUuid($leadId);
        if (!$lead) return redirect()->back()->with('error', 'Lead not found.');

        // Snapshot program info
        $programName = null;
        if ($lead['program_id']) {
            $prog = $programModel->find($lead['program_id']);
            if ($prog) $programName = $prog['name'];
        }

        // Generate Admission Number APP-YYYY-XXXX
        $year = date('Y');
        $count = $appModel->where('org_id', $orgId)->countAllResults() + 1;
        $admNumber = 'APP-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $appData = [
            'org_id' => $orgId,
            'lead_id' => $leadId,
            'adm_number' => $admNumber,
            'full_name' => $lead['full_name'],
            'phone' => $lead['phone'],
            'email' => $lead['email'],
            'program_id' => $lead['program_id'],
            'program_name' => $programName,
            'status' => 'Submitted'
        ];

        $appId = $appModel->insert($appData);
        
        // Update Lead status
        $leadModel->update($leadId, ['status' => 'Converted']);
        
        $logModel->insert([
            'org_id' => $orgId,
            'lead_id' => $leadId,
            'application_id' => $appId,
            'action' => 'Application Created',
            'description' => 'Lead converted to Application ' . $admNumber,
            'performed_by' => session('org_user_id')
        ]);

        return redirect()->to('org/admissions/applications')->with('success', 'Lead converted to application successfully.');
    }

    public function documents()
    {
        $appModel = new ApplicationModel();
        $docModel = new ApplicationDocumentModel();

        $data['applications'] = $appModel->getApplications(session('org_id'));
        // We would fetch documents per application in a more detailed view.
        
        return view('org/admissions/documents', $data);
    }

    public function verify_documents($appId)
    {
        $appModel = new ApplicationModel();
        $logModel = new AdmissionActivityLogModel();
        $orgId = session('org_id');
        $app = $appModel->where('org_id', $orgId)->findByIdOrUuid($appId);
        if (!$app) return redirect()->back()->with('error', 'Application not found.');
        
        $appModel->update($app['id'], ['status' => 'Verified']);
        $logModel->insert([
            'org_id' => $orgId,
            'application_id' => $app['id'],
            'action' => 'Documents Verified',
            'description' => 'Mandatory admission documents verified.',
            'performed_by' => session('org_user_id')
        ]);
        return redirect()->to('org/admissions/documents')->with('success', 'Documents verified for ' . esc($app['full_name']));
    }

    public function offers()
    {
        $offerModel = new AdmissionOfferModel();
        $appModel = new ApplicationModel();
        
        $data['offers'] = $offerModel->getOffers(session('org_id'));
        $data['applications'] = $appModel->where('org_id', session('org_id'))->whereIn('status', ['Verified', 'Submitted'])->findAll();
        
        return view('org/admissions/offers', $data);
    }

    public function generate_offer()
    {
        $offerModel = new AdmissionOfferModel();
        $appModel = new ApplicationModel();
        $logModel = new AdmissionActivityLogModel();
        $orgId = session('org_id');

        $appId = $this->request->getPost('application_id');
        $fee = $this->request->getPost('fee_amount');
        $dueDate = $this->request->getPost('due_date');

        $app = $appModel->where('org_id', $orgId)->find($appId);
        if (!$app) return redirect()->back()->with('error', 'Application not found.');

        // Versioning logic: Find latest version
        $existingOffer = $offerModel->where('application_id', $appId)->orderBy('version', 'DESC')->first();
        $version = $existingOffer ? $existingOffer['version'] + 1 : 1;

        $offerId = $offerModel->insert([
            'org_id' => $orgId,
            'application_id' => $appId,
            'version' => $version,
            'fee_amount' => $fee,
            'due_date' => $dueDate,
            'status' => 'Pending'
        ]);

        $appModel->update($appId, ['status' => 'Offer Made']);
        
        $logModel->insert([
            'org_id' => $orgId,
            'application_id' => $appId,
            'action' => 'Offer Generated',
            'description' => 'Offer v' . $version . ' generated for amount ' . $fee,
            'performed_by' => session('org_user_id')
        ]);

        return redirect()->to('org/admissions/offers')->with('success', 'Offer generated successfully.');
    }

    public function accept_offer($offerId)
    {
        $offerModel = new AdmissionOfferModel();
        $logModel = new AdmissionActivityLogModel();
        $orgId = session('org_id');
        $offer = $offerModel->where('org_id', $orgId)->findByIdOrUuid($offerId);
        if (!$offer) return redirect()->back()->with('error', 'Offer not found.');

        $offerModel->update($offer['id'], ['status' => 'Accepted']);
        $logModel->insert([
            'org_id' => $orgId,
            'application_id' => $offer['application_id'],
            'action' => 'Offer Accepted',
            'description' => 'Admission offer accepted by applicant/admin.',
            'performed_by' => session('org_user_id')
        ]);
        return redirect()->to('org/admissions/offers')->with('success', 'Offer marked as accepted! Application is now eligible for final enrollment.');
    }

    public function offer_pdf($offerId)
    {
        $orgId = session('org_id');
        $db = \Config\Database::connect();
        
        $builder = $db->table('admission_offers o')
            ->select('o.*, a.adm_number, a.full_name, a.email, a.phone, a.admission_category, a.program_name, org.name as org_name, org.code as institution_code')
            ->join('applications a', 'a.id = o.application_id', 'left')
            ->join('organizations org', 'org.id = o.org_id', 'left')
            ->where('o.org_id', $orgId);

        if (is_uuid($offerId)) {
            $builder->where('o.uuid', $offerId);
        } else {
            $builder->where('o.id', $offerId);
        }

        $offer = $builder->get()->getRowArray();
        if (!$offer) return redirect()->to('org/admissions/offers')->with('error', 'Offer letter not found.');

        // Printable / PDF View
        return view('org/admissions/offer_letter_pdf', ['offer' => $offer]);
    }

    public function payments()
    {
        $paymentModel = new AdmissionPaymentModel();
        $offerModel = new AdmissionOfferModel();
        $data['payments'] = $paymentModel->getPayments(session('org_id'));
        $data['offers'] = $offerModel->getOffers(session('org_id'));
        
        return view('org/admissions/payments', $data);
    }

    public function save_payment()
    {
        $orgId = session('org_id');
        $paymentModel = new AdmissionPaymentModel();
        $offerModel = new AdmissionOfferModel();
        $logModel = new AdmissionActivityLogModel();

        $offerId = $this->request->getPost('offer_id');
        $amount = (float)$this->request->getPost('amount');
        $mode = $this->request->getPost('payment_mode') ?: 'Cash';
        $ref = $this->request->getPost('gateway_reference') ?: 'MANUAL-' . strtoupper(substr(uniqid(), -6));

        $offer = $offerModel->where('org_id', $orgId)->find($offerId);
        if (!$offer) {
            return redirect()->back()->with('error', 'Please select a valid admission offer.');
        }

        // Generate Receipt No.
        $count = $paymentModel->where('org_id', $orgId)->countAllResults() + 1;
        $receiptNumber = 'REC-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $paymentModel->insert([
            'org_id' => $orgId,
            'offer_id' => $offerId,
            'amount' => $amount,
            'payment_status' => 'Paid',
            'payment_mode' => $mode,
            'gateway' => 'Manual Office Receipt',
            'gateway_reference' => $ref,
            'receipt_number' => $receiptNumber,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Auto-accept offer if still pending
        if ($offer['status'] === 'Pending') {
            $offerModel->update($offerId, ['status' => 'Accepted']);
        }

        $logModel->insert([
            'org_id' => $orgId,
            'application_id' => $offer['application_id'],
            'action' => 'Payment Recorded',
            'description' => 'Manual payment of ₹' . number_format($amount, 2) . ' received via ' . $mode . '. Receipt: ' . $receiptNumber,
            'performed_by' => session('org_user_id')
        ]);

        return redirect()->to('org/admissions/payments')->with('success', 'Payment recorded successfully! Receipt generated: ' . $receiptNumber);
    }

    public function enrollment()
    {
        $appModel = new ApplicationModel();
        // Get applications that have accepted offers but aren't enrolled yet
        $data['applications'] = $appModel->select('applications.*')
                                         ->join('admission_offers', 'admission_offers.application_id = applications.id')
                                         ->where('applications.org_id', session('org_id'))
                                         ->where('admission_offers.status', 'Accepted')
                                         ->where('applications.status !=', 'Enrolled')
                                         ->findAll();
                                         
        return view('org/admissions/enrollment', $data);
    }

    public function enroll_student($appId)
    {
        $appModel = new ApplicationModel();
        $studentModel = new StudentModel();
        $userModel = new OrgUserModel();
        $logModel = new AdmissionActivityLogModel();
        $orgId = session('org_id');

        $app = null;
        if (is_uuid($appId)) {
            $app = $appModel->where('org_id', $orgId)->where('uuid', $appId)->first();
        }
        if (!$app) {
            $app = $appModel->where('org_id', $orgId)->find($appId);
        }
        if (!$app) return redirect()->back()->with('error', 'Application not found.');
        $realAppId = $app['id'];

        // 1. Check or Create org_user for the student (Unified Auth)
        $existingUser = $userModel->where('org_id', $orgId)->where('email', $app['email'])->first();
        if ($existingUser) {
            $userId = $existingUser['id'];
        } else {
            $userId = $userModel->insert([
                'org_id' => $orgId,
                'full_name' => $app['full_name'],
                'email' => $app['email'],
                'phone' => $app['phone'],
                'password_hash' => password_hash('welcome123', PASSWORD_DEFAULT),
                'user_type' => 'student',
                'role' => 'STUDENT',
                'is_org_admin' => 0
            ]);
        }

        // 2. Generate Roll Number
        $year = date('Y');
        $count = $studentModel->where('org_id', $orgId)->countAllResults() + 1;
        $rollNumber = 'STU-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        // 3. Create Student Domain Record with cohort assignment
        $nameParts = explode(' ', $app['full_name'], 2);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

        // Find matching cohort for the program
        $cohortModel = new \App\Models\CohortModel();
        $cohort = null;
        if (!empty($app['program_id'])) {
            $cohort = $cohortModel->where('org_id', $orgId)->where('program_id', $app['program_id'])->first();
        }
        $cohortId = $cohort ? $cohort['id'] : null;

        $studentModel->insert([
            'org_id' => $orgId,
            'user_id' => $userId,
            'application_id' => $realAppId,
            'cohort_id' => $cohortId,
            'roll_number' => $rollNumber,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'parent_name' => $app['parent_name'] ?? null,
            'parent_phone' => $app['parent_phone'] ?? null,
            'status' => 'Active'
        ]);

        // 4. Update Application Status
        $appModel->update($realAppId, ['status' => 'Enrolled']);
        
        $logModel->insert([
            'org_id' => $orgId,
            'application_id' => $realAppId,
            'action' => 'Enrolled',
            'description' => 'Student enrolled successfully with Roll Number: ' . $rollNumber,
            'performed_by' => session('org_user_id')
        ]);

        return redirect()->to('org/admissions/enrollment')->with('success', 'Student enrolled successfully! Roll Number: ' . $rollNumber);
    }

    // =============================================
    // MISSING ADMISSION FEATURES
    // =============================================

    public function college_strength()
    {
        $orgId = session('org_id');
        $db = \Config\Database::connect();
        
        $builder = $db->table('programs p');
        $builder->select('p.name, p.code, COUNT(DISTINCT s.id) as enrolled_count');
        $builder->join('cohorts c', 'c.program_id = p.id AND c.org_id = p.org_id', 'left');
        $builder->join('students s', 's.cohort_id = c.id AND s.org_id = p.org_id', 'left');
        $builder->where('p.org_id', $orgId);
        $builder->groupBy('p.id, p.name, p.code');
        
        $data['strength'] = $builder->get()->getResultArray();
        return view('org/admissions/college_strength', $data);
    }

    public function categories()
    {
        $categoryModel = new AdmissionCategoryModel();
        $data['categories'] = $categoryModel->where('org_id', session('org_id'))->findAll();
        return view('org/admissions/categories', $data);
    }

    public function save_category()
    {
        $categoryModel = new AdmissionCategoryModel();
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => session('org_id'),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description')
        ];
        
        if (empty($id)) {
            $categoryModel->insert($data);
        } else {
            $categoryModel->update($id, $data);
        }
        
        return redirect()->to('org/admissions/categories')->with('success', 'Category saved successfully.');
    }

    public function scholarships()
    {
        $scholarshipModel = new ScholarshipModel();
        $db = \Config\Database::connect();
        
        $builder = $db->table('scholarships sc');
        $builder->select('sc.*, s.first_name, s.last_name, s.roll_number');
        $builder->join('students s', 's.id = sc.student_id');
        $builder->where('sc.org_id', session('org_id'));
        
        $data['scholarships'] = $builder->get()->getResultArray();
        
        $studentModel = new StudentModel();
        $data['students'] = $studentModel->where('org_id', session('org_id'))->findAll();
        
        return view('org/admissions/scholarships', $data);
    }

    public function save_scholarship()
    {
        $scholarshipModel = new ScholarshipModel();
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => session('org_id'),
            'student_id' => $this->request->getPost('student_id'),
            'scholarship_name' => $this->request->getPost('scholarship_name'),
            'donor_name' => $this->request->getPost('donor_name'),
            'amount' => $this->request->getPost('amount'),
            'status' => $this->request->getPost('status')
        ];
        
        if (empty($id)) {
            $scholarshipModel->insert($data);
        } else {
            $scholarshipModel->update($id, $data);
        }
        
        return redirect()->to('org/admissions/scholarships')->with('success', 'Scholarship saved successfully.');
    }

    public function detained()
    {
        $detainedModel = new DetainedStudentModel();
        $db = \Config\Database::connect();
        
        $builder = $db->table('detained_students ds');
        $builder->select('ds.*, s.first_name, s.last_name, s.roll_number');
        $builder->join('students s', 's.id = ds.student_id');
        $builder->where('ds.org_id', session('org_id'));
        
        $data['detained'] = $builder->get()->getResultArray();
        
        $studentModel = new StudentModel();
        $data['students'] = $studentModel->where('org_id', session('org_id'))->findAll();
        
        return view('org/admissions/detained', $data);
    }

    public function save_detained()
    {
        $detainedModel = new DetainedStudentModel();
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => session('org_id'),
            'student_id' => $this->request->getPost('student_id'),
            'date_detained' => $this->request->getPost('date_detained'),
            'reason' => $this->request->getPost('reason'),
            'status' => $this->request->getPost('status')
        ];
        
        if (empty($id)) {
            $detainedModel->insert($data);
        } else {
            $detainedModel->update($id, $data);
        }
        
        return redirect()->to('org/admissions/detained')->with('success', 'Detained record saved successfully.');
    }

    public function api_live_leads()
    {
        $orgId = session('org_id');
        $db = \Config\Database::connect();
        $today = date('Y-m-d');

        $totalLeads = $db->table('leads')->where('org_id', $orgId)->countAllResults();
        $todayLeads = $db->table('leads')->where('org_id', $orgId)->where('DATE(created_at)', $today)->countAllResults();
        
        $latestLeads = $db->table('leads l')
            ->select('l.*, p.name as program_name')
            ->join('programs p', 'p.id = l.program_id', 'left')
            ->where('l.org_id', $orgId)
            ->orderBy('l.id', 'DESC')
            ->limit(15)
            ->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'timestamp' => date('H:i:s'),
            'total_leads' => $totalLeads,
            'today_leads' => $todayLeads,
            'latest_leads' => $latestLeads
        ]);
    }
}
