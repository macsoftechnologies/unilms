<?php
namespace App\Controllers;

use App\Models\StaffDutyModel;
use App\Models\StaffCertificateModel;
use App\Models\StaffChecklistModel;
use App\Models\OrgUserModel;

use Dompdf\Dompdf;
use Dompdf\Options;

class OrgStaff extends BaseController
{
    public function index()
    {
        return redirect()->to('org/staff/directory');
    }

    public function directory()
    {
        $orgId = session('org_id');
        $userModel = new OrgUserModel();
        
        $data['activeModule'] = 'staff';
        $data['active_menu'] = 'staff';
        $data['active_submenu'] = 'directory';
        
        $data['staff'] = $userModel->where('org_id', $orgId)->whereIn('role', ['faculty', 'admin', 'superadmin'])->findAll();

        return view('org/staff/directory', $data);
    }

    public function duties()
    {
        $orgId = session('org_id');
        $dutyModel = new StaffDutyModel();
        $userModel = new OrgUserModel();
        
        $data['activeModule'] = 'staff';
        $data['active_menu'] = 'staff';
        $data['active_submenu'] = 'duties';
        
        $data['duties'] = $dutyModel->select('staff_duties.*, s.full_name as staff_name, a.full_name as assigner_name')
            ->join('org_users s', 's.id = staff_duties.staff_id')
            ->join('org_users a', 'a.id = staff_duties.assigned_by', 'left')
            ->where('staff_duties.org_id', $orgId)
            ->findAll();
            
        $data['staff_list'] = $userModel->where('org_id', $orgId)->whereIn('role', ['faculty', 'admin'])->findAll();

        return view('org/staff/duties', $data);
    }

    public function save_duty()
    {
        $dutyModel = new StaffDutyModel();
        $id = $this->request->getPost('id');
        
        $data = [
            'org_id' => session('org_id'),
            'staff_id' => $this->request->getPost('staff_id'),
            'task_name' => $this->request->getPost('task_name'),
            'assigned_by' => session('org_user_id'),
            'due_date' => $this->request->getPost('due_date'),
            'remarks' => $this->request->getPost('remarks'),
            'status' => $this->request->getPost('status') ?: 'Pending'
        ];

        if ($id) {
            $dutyModel->update($id, $data);
        } else {
            $dutyModel->insert($data);
        }
        return redirect()->to('org/staff/duties')->with('success', 'Academic duty assigned.');
    }

    public function certificates()
    {
        $orgId = session('org_id');
        $certModel = new StaffCertificateModel();
        $userModel = new OrgUserModel();
        
        $data['activeModule'] = 'staff';
        $data['active_menu'] = 'staff';
        $data['active_submenu'] = 'certificates';
        
        $data['certificates'] = $certModel->select('staff_certificates.*, s.full_name as staff_name')
            ->join('org_users s', 's.id = staff_certificates.staff_id')
            ->where('staff_certificates.org_id', $orgId)
            ->findAll();
            
        $data['staff_list'] = $userModel->where('org_id', $orgId)->whereIn('role', ['faculty', 'admin'])->findAll();

        return view('org/staff/certificates', $data);
    }

    public function save_certificate()
    {
        $certModel = new StaffCertificateModel();
        $id = $this->request->getPost('id');
        
        $data = [
            'org_id' => session('org_id'),
            'staff_id' => $this->request->getPost('staff_id'),
            'certificate_type' => $this->request->getPost('certificate_type'),
            'issue_date' => $this->request->getPost('issue_date'),
            'signatory_id' => session('user_id')
        ];

        if ($id) {
            $certModel->update($id, $data);
        } else {
            $id = $certModel->insert($data);
        }
        
        return redirect()->to('org/staff/certificates')->with('success', 'Certificate record created.');
    }

    public function generate_pdf($id)
    {
        $orgId = session('org_id');
        $certModel = new StaffCertificateModel();
        
        $cert = $certModel->select('staff_certificates.*, s.full_name as staff_name')
            ->join('org_users s', 's.id = staff_certificates.staff_id')
            ->where('staff_certificates.id', $id)
            ->where('staff_certificates.org_id', $orgId)
            ->first();

        if (!$cert) return redirect()->back()->with('error', 'Not found');

        $html = "
            <h1 style='text-align:center;'>{$cert['certificate_type']} Certificate</h1>
            <hr>
            <p style='font-size: 18px;'>This is to certify that <strong>{$cert['staff_name']}</strong> is an employee of our organization.</p>
            <p>Issued on: {$cert['issue_date']}</p>
            <br><br><br>
            <p>Authorized Signatory</p>
        ";

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Certificate_".$cert['staff_name'].".pdf", array("Attachment" => false));
    }

    public function checklists()
    {
        $orgId = session('org_id');
        $checklistModel = new StaffChecklistModel();
        $userModel = new OrgUserModel();
        
        $data['activeModule'] = 'staff';
        $data['active_menu'] = 'staff';
        $data['active_submenu'] = 'checklists';
        
        $data['checklists'] = $checklistModel->select('staff_checklists.*, s.full_name as staff_name')
            ->join('org_users s', 's.id = staff_checklists.staff_id')
            ->where('staff_checklists.org_id', $orgId)
            ->findAll();
            
        $data['staff_list'] = $userModel->where('org_id', $orgId)->whereIn('role', ['faculty', 'admin'])->findAll();

        return view('org/staff/checklists', $data);
    }

    public function save_checklist()
    {
        $checklistModel = new StaffChecklistModel();
        $id = $this->request->getPost('id');
        
        $data = [
            'org_id' => session('org_id'),
            'staff_id' => $this->request->getPost('staff_id'),
            'checklist_type' => $this->request->getPost('checklist_type'),
            'item_name' => $this->request->getPost('item_name'),
            'is_completed' => $this->request->getPost('is_completed') ? 1 : 0
        ];

        if ($id) {
            $checklistModel->update($id, $data);
        } else {
            $checklistModel->insert($data);
        }
        return redirect()->to('org/staff/checklists')->with('success', 'Checklist item saved.');
    }
}
