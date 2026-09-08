<?php

namespace App\Controllers;

use App\Models\MaterialModel;
use App\Models\SubjectModel;
use App\Models\CohortModel;

class OrgMaterials extends BaseController
{
    public function index()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        
        $materialModel = new MaterialModel();
        
        if (session('is_org_admin') || $this->hasPermission('manage_academics_global')) {
            $materials = $materialModel->getMaterialsByOrg($this->org_id);
        } else {
            $materials = $materialModel->getMaterialsByFaculty($this->org_id, $this->org_user_id);
        }

        return view('org/materials/index', [
            'materials' => $materials
        ]);
    }

    public function create()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $subjectModel = new SubjectModel();
        $cohortModel = new CohortModel();

        $subjects = $subjectModel->where('org_id', $this->org_id)->findAll();
        $cohorts = $cohortModel->where('org_id', $this->org_id)->findAll();

        return view('org/materials/form', [
            'subjects' => $subjects,
            'cohorts' => $cohorts,
            'material' => null
        ]);
    }

    public function edit($id)
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $materialModel = new MaterialModel();
        $material = $materialModel->where('org_id', $this->org_id)->findByIdOrUuid($id);

        if (!$material) {
            return redirect()->to('org/materials')->with('error', 'Material not found.');
        }

        $subjectModel = new SubjectModel();
        $cohortModel = new CohortModel();

        $subjects = $subjectModel->where('org_id', $this->org_id)->findAll();
        $cohorts = $cohortModel->where('org_id', $this->org_id)->findAll();

        return view('org/materials/form', [
            'subjects' => $subjects,
            'cohorts' => $cohorts,
            'material' => $material
        ]);
    }

    public function save()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $materialModel = new MaterialModel();
        $id = $this->request->getPost('material_id');

        $data = [
            'org_id' => $this->org_id,
            'subject_id' => $this->request->getPost('subject_id'),
            'cohort_id' => $this->request->getPost('cohort_id'),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'type' => $this->request->getPost('type'),
            'external_url' => $this->request->getPost('external_url'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        // Handle File Upload
        if ($data['type'] === 'file') {
            $file = $this->request->getFile('attachment');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $validationRules = [
                    'attachment' => [
                        'label' => 'Attachment',
                        'rules' => 'uploaded[attachment]|ext_in[attachment,pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar,jpg,jpeg,png,webp,mp4,mp3]|max_size[attachment,51200]'
                    ]
                ];
                if (!$this->validate($validationRules)) {
                    return redirect()->back()->withInput()->with('error', $this->validator->getError('attachment') ?: 'Invalid file type or file exceeds maximum allowed size (50MB).');
                }
                $newName = $file->getRandomName();
                $targetDir = FCPATH . 'uploads/org_' . $this->org_id . '/materials/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                $file->move($targetDir, $newName);
                $data['file_path'] = 'uploads/org_' . $this->org_id . '/materials/' . $newName;
                $data['external_url'] = null; // Clear URL if switching to file
            }
        } else {
            $data['file_path'] = null; // Clear file path if switching to link/youtube
        }

        if ($id) {
            $existing = $materialModel->where('org_id', $this->org_id)->findByIdOrUuid($id);
            if ($existing) {
                if ($data['type'] === 'file' && !isset($data['file_path'])) {
                    unset($data['file_path']); // Keep existing file path if no new file uploaded
                }
                $materialModel->update($existing['id'], $data);
            }
        } else {
            $data['faculty_user_id'] = $this->org_user_id;
            $materialModel->insert($data);
        }

        return redirect()->to('org/materials')->with('success', 'Study Material saved successfully.');
    }

    public function delete($id)
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        
        $materialModel = new MaterialModel();
        $existing = $materialModel->where('org_id', $this->org_id)->findByIdOrUuid($id);
        if ($existing) {
            $materialModel->delete($existing['id']);
        }
        return redirect()->to('org/materials')->with('success', 'Study Material deleted.');
    }
}
