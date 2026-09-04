<?php

namespace App\Controllers;

use App\Models\DocumentTemplateModel;
use App\Models\StudentModel;

class OrgCertificates extends BaseController
{
    public function index()
    {
        return redirect()->to('org/certificates/templates');
    }

    // List templates
    public function templates()
    {
        $orgId = session('org_id');
        $model = new DocumentTemplateModel();

        // If empty, auto-seed standard university templates!
        $existing = $model->where('org_id', $orgId)->findAll();
        if (empty($existing)) {
            $defaultTemplates = [
                [
                    'org_id' => $orgId,
                    'name' => 'Bonafide Certificate',
                    'category' => 'Student',
                    'page_size' => 'A4',
                    'orientation' => 'Portrait',
                    'html_content' => '<div style="text-align: center; border: 4px double #4f46e5; padding: 40px; border-radius: 12px; font-family: serif;">
                        <h1 style="color: #4f46e5; margin: 0; font-size: 28px; text-transform: uppercase;">{{organization_name}}</h1>
                        <p style="font-size: 14px; color: #64748b; margin: 4px 0 30px;">Official Academic Certification Division</p>
                        
                        <h2 style="text-decoration: underline; letter-spacing: 2px; font-size: 22px; margin-bottom: 30px;">BONAFIDE CERTIFICATE</h2>
                        
                        <p style="font-size: 16px; line-height: 2; text-align: justify;">
                            This is to certify that Mr./Ms. <strong>{{student_name}}</strong>, bearing Roll Number / Reg. No: <strong>{{roll_number}}</strong>, is a bonafide student of this institution studying in <strong>{{program_name}}</strong> for the academic academic year <strong>{{academic_year}}</strong>.
                        </p>
                        <p style="font-size: 16px; line-height: 2; text-align: justify;">
                            To the best of our knowledge and college records, his/her conduct and character during the course of study have been found to be <strong>GOOD</strong>. This certificate is issued upon his/her request for official purposes.
                        </p>
                        
                        <div style="display: flex; justify-content: space-between; margin-top: 80px; font-size: 14px;">
                            <div>
                                <p>Date of Issue: <strong>{{issue_date}}</strong></p>
                                <p>Place: Campus Office</p>
                            </div>
                            <div style="text-align: center;">
                                <div style="height: 50px;"></div>
                                <p style="font-weight: bold; border-top: 1px solid #000; padding-top: 6px; min-width: 180px;">Principal / Registrar</p>
                            </div>
                        </div>
                    </div>',
                    'created_by' => session('org_user_id')
                ],
                [
                    'org_id' => $orgId,
                    'name' => 'Transfer Certificate (TC)',
                    'category' => 'Student',
                    'page_size' => 'A4',
                    'orientation' => 'Portrait',
                    'html_content' => '<div style="text-align: center; border: 4px double #1e293b; padding: 40px; border-radius: 12px; font-family: serif;">
                        <h1 style="color: #1e293b; margin: 0; font-size: 28px; text-transform: uppercase;">{{organization_name}}</h1>
                        <p style="font-size: 14px; color: #64748b; margin: 4px 0 30px;">Statutory Transfer Certificate</p>
                        
                        <h2 style="text-decoration: underline; letter-spacing: 2px; font-size: 22px; margin-bottom: 30px;">TRANSFER CERTIFICATE</h2>
                        
                        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 15px; line-height: 2;">
                            <tr><td style="width: 45%;">1. Name of the Pupil:</td><td><strong>{{student_name}}</strong></td></tr>
                            <tr><td>2. Roll / Registration Number:</td><td><strong>{{roll_number}}</strong></td></tr>
                            <tr><td>3. Program / Degree Studied:</td><td><strong>{{program_name}}</strong></td></tr>
                            <tr><td>4. Date of Admission:</td><td><strong>{{admission_date}}</strong></td></tr>
                            <tr><td>5. Whether qualified for promotion:</td><td><strong>YES</strong></td></tr>
                            <tr><td>6. College Dues Paid up to:</td><td><strong>Full Paid</strong></td></tr>
                            <tr><td>7. General Conduct & Character:</td><td><strong>Satisfactory</strong></td></tr>
                            <tr><td>8. Date of application for certificate:</td><td><strong>{{issue_date}}</strong></td></tr>
                        </table>
                        
                        <div style="display: flex; justify-content: space-between; margin-top: 70px; font-size: 14px;">
                            <div><p>Date: {{issue_date}}</p></div>
                            <div style="text-align: center;"><p style="font-weight: bold; border-top: 1px solid #000; padding-top: 6px;">Dean / Head of Institution</p></div>
                        </div>
                    </div>',
                    'created_by' => session('org_user_id')
                ]
            ];
            foreach ($defaultTemplates as $dt) {
                $model->insert($dt);
            }
        }

        $data = [
            'activeModule' => 'administration',
            'templates' => $model->where('org_id', $orgId)->findAll()
        ];

        return view('org/certificates/templates', $data);
    }

    public function save_template()
    {
        $orgId = session('org_id');
        $model = new DocumentTemplateModel();

        $id = $this->request->getPost('id');
        $data = [
            'org_id' => $orgId,
            'name' => trim($this->request->getPost('name')),
            'category' => $this->request->getPost('category') ?: 'Student',
            'page_size' => $this->request->getPost('page_size') ?: 'A4',
            'orientation' => $this->request->getPost('orientation') ?: 'Portrait',
            'html_content' => $this->request->getPost('html_content'),
            'created_by' => session('org_user_id')
        ];

        if ($id) {
            $model->where('org_id', $orgId)->update($id, $data);
            return redirect()->to('org/certificates/templates')->with('success', 'Template updated.');
        } else {
            $model->insert($data);
            return redirect()->to('org/certificates/templates')->with('success', 'New certificate template created.');
        }
    }

    public function delete_template($id)
    {
        $orgId = session('org_id');
        (new DocumentTemplateModel())->where('org_id', $orgId)->delete($id);
        return redirect()->to('org/certificates/templates')->with('success', 'Template removed.');
    }

    // Certificate Generator Wizard
    public function generate()
    {
        $orgId = session('org_id');
        $db = \Config\Database::connect();

        $data['templates'] = (new DocumentTemplateModel())->where('org_id', $orgId)->findAll();
        $data['students'] = $db->table('students s')
            ->select('s.id, s.roll_number, s.first_name, s.last_name, p.name as program_name')
            ->join('cohorts c', 'c.id = s.cohort_id', 'left')
            ->join('programs p', 'p.id = c.program_id', 'left')
            ->where('s.org_id', $orgId)
            ->where('s.status', 'Active')
            ->get()->getResultArray();
        $data['activeModule'] = 'administration';

        return view('org/certificates/generate', $data);
    }

    // Print & Render Certificate
    public function print_certificate($templateId, $studentId)
    {
        $orgId = session('org_id');
        $db = \Config\Database::connect();

        $template = (new DocumentTemplateModel())->where('org_id', $orgId)->find($templateId);
        if (!$template) return 'Certificate template not found.';

        $student = $db->table('students s')
            ->select('s.*, c.name as cohort_name, p.name as program_name, org.name as organization_name')
            ->join('cohorts c', 'c.id = s.cohort_id', 'left')
            ->join('programs p', 'p.id = c.program_id', 'left')
            ->join('organizations org', 'org.id = s.org_id', 'left')
            ->where('s.id', $studentId)
            ->where('s.org_id', $orgId)
            ->get()->getRowArray();

        if (!$student) return 'Student record not found.';

        $html = $template['html_content'];

        $replacements = [
            '{{organization_name}}' => esc($student['organization_name'] ?? 'University Institution'),
            '{{student_name}}' => esc($student['first_name'] . ' ' . $student['last_name']),
            '{{roll_number}}' => esc($student['roll_number']),
            '{{program_name}}' => esc($student['program_name'] ?? 'Academic Program'),
            '{{cohort_name}}' => esc($student['cohort_name'] ?? ''),
            '{{academic_year}}' => date('Y') . '-' . (date('Y') + 1),
            '{{admission_date}}' => date('d M Y', strtotime($student['admission_date'] ?? date('Y-m-d'))),
            '{{issue_date}}' => date('d F Y')
        ];

        foreach ($replacements as $placeholder => $val) {
            $html = str_replace($placeholder, $val, $html);
        }

        return view('org/certificates/print_slip', [
            'template' => $template,
            'renderedHtml' => $html
        ]);
    }
}
