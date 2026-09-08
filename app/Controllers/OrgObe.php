<?php

namespace App\Controllers;

use App\Models\PoDefinitionModel;
use App\Models\CoDefinitionModel;
use App\Models\CoPoMappingModel;
use App\Models\ProgramModel;
use App\Models\SubjectModel;
use App\Models\OrgSettingsModel;

class OrgObe extends BaseController
{
    // ADMIN: PO Definitions Setup
    public function poSetup()
    {
        if (!$this->hasPermission('manage_academics')) {
            return redirect()->to('org/dashboard')->with('error', 'Permission denied.');
        }

        $programModel = new ProgramModel();
        $poModel = new PoDefinitionModel();
        $settingsModel = new OrgSettingsModel();

        $programs = $programModel->where('org_id', $this->org_id)->findAll();
        
        $selected_program_id = $this->request->getGet('program_id') ?? ($programs[0]['id'] ?? null);
        
        $pos = [];
        $selectedProgram = null;
        $detectedTemplate = 'btech';
        if ($selected_program_id) {
            $pos = $poModel->getPosByProgram($this->org_id, $selected_program_id);
            $selectedProgram = $programModel->where('org_id', $this->org_id)->find($selected_program_id);
            if ($selectedProgram) {
                $detectedTemplate = \App\Services\ObeTemplateService::detectTemplateKey($selectedProgram['name'], $selectedProgram['code'] ?? '');
            }
        }

        $attainment_threshold = $settingsModel->getSetting($this->org_id, 'obe_attainment_threshold', 60);
        $templates = \App\Services\ObeTemplateService::getTemplates();

        $data = [
            'programs' => $programs,
            'selected_program_id' => $selected_program_id,
            'selected_program' => $selectedProgram,
            'pos' => $pos,
            'attainment_threshold' => $attainment_threshold,
            'templates' => $templates,
            'detected_template' => $detectedTemplate
        ];

        return view('org/obe/admin_po_setup', $data);
    }

    public function loadTemplate()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        
        $program_id = $this->request->getPost('program_id');
        $template_key = $this->request->getPost('template_key') ?: 'btech';
        
        if (!$program_id) {
            return redirect()->back()->with('error', 'Please select a program first.');
        }

        $service = new \App\Services\ObeTemplateService();
        $count = $service->applyTemplateToProgram($this->org_id, (int)$program_id, $template_key);
        
        return redirect()->to("org/obe/po-setup?program_id={$program_id}")->with('success', "Successfully loaded {$count} standard NAAC / NBA Outcomes into this program.");
    }

    public function savePo()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $poModel = new PoDefinitionModel();
        $program_id = $this->request->getPost('program_id');
        
        $data = [
            'org_id' => $this->org_id,
            'program_id' => $program_id,
            'code' => $this->request->getPost('code'),
            'description' => $this->request->getPost('description'),
            'type' => $this->request->getPost('type')
        ];

        if ($poParam = $this->request->getPost('po_id')) {
            $existing = $poModel->where('org_id', $this->org_id)->findByIdOrUuid($poParam);
            $id = $existing ? $existing['id'] : $poParam;
            $poModel->update($id, $data);
            $msg = 'Outcome updated successfully.';
        } else {
            $poModel->insert($data);
            $msg = 'Outcome added successfully.';
        }

        return redirect()->to("org/obe/po-setup?program_id=$program_id")->with('success', $msg);
    }

    public function deletePo($id)
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        $poModel = new PoDefinitionModel();
        $po = $poModel->where('org_id', $this->org_id)->findByIdOrUuid($id);
        if ($po) {
            $poModel->delete($po['id']);
        }
        return redirect()->back()->with('success', 'Outcome deleted.');
    }

    public function saveSettings()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        
        $threshold = $this->request->getPost('obe_attainment_threshold');
        $settingsModel = new OrgSettingsModel();
        $settingsModel->setSetting($this->org_id, 'obe_attainment_threshold', $threshold);
        
        return redirect()->back()->with('success', 'OBE settings updated successfully.');
    }

    // FACULTY: CO Setup
    public function coSetup()
    {
        // Faculty must have access to subjects
        $subjectModel = new SubjectModel();
        $coModel = new CoDefinitionModel();

        // For simplicity in this demo, let's load all subjects if admin, or just all subjects for now.
        // In reality, this should be filtered by what's allocated to the faculty.
        $subjects = $subjectModel->where('org_id', $this->org_id)->findAll();
        $selected_subject_id = $this->request->getGet('subject_id') ?? ($subjects[0]['id'] ?? null);

        $cos = [];
        if ($selected_subject_id) {
            $cos = $coModel->getCosBySubject($this->org_id, $selected_subject_id);
        }

        return view('org/obe/faculty_co_setup', [
            'subjects' => $subjects,
            'selected_subject_id' => $selected_subject_id,
            'cos' => $cos
        ]);
    }

    public function saveCo()
    {
        $coModel = new CoDefinitionModel();
        $subject_id = $this->request->getPost('subject_id');
        
        $data = [
            'org_id' => $this->org_id,
            'subject_id' => $subject_id,
            'code' => $this->request->getPost('code'),
            'description' => $this->request->getPost('description')
        ];

        if ($coParam = $this->request->getPost('co_id')) {
            $existing = $coModel->where('org_id', $this->org_id)->findByIdOrUuid($coParam);
            $id = $existing ? $existing['id'] : $coParam;
            $coModel->update($id, $data);
        } else {
            $coModel->insert($data);
        }

        return redirect()->to("org/obe/co-setup?subject_id=$subject_id")->with('success', 'Course Outcome saved.');
    }
    
    public function deleteCo($id)
    {
        $coModel = new CoDefinitionModel();
        $co = $coModel->where('org_id', $this->org_id)->findByIdOrUuid($id);
        if ($co) {
            $coModel->delete($co['id']);
        }
        return redirect()->back()->with('success', 'Outcome deleted.');
    }

    // FACULTY: CO-PO Mapping
    public function mappingMatrix()
    {
        $subjectModel = new SubjectModel();
        $programModel = new ProgramModel();
        $coModel = new CoDefinitionModel();
        $poModel = new PoDefinitionModel();
        $mappingModel = new CoPoMappingModel();

        $subjects = $subjectModel->where('org_id', $this->org_id)->findAll();
        $selected_subject_id = $this->request->getGet('subject_id') ?? ($subjects[0]['id'] ?? null);

        $cos = [];
        $pos = [];
        $mappings = [];

        if ($selected_subject_id) {
            $subject = $subjectModel->find($selected_subject_id);
            $cos = $coModel->getCosBySubject($this->org_id, $selected_subject_id);
            
            if ($subject && $subject['program_id']) {
                $pos = $poModel->getPosByProgram($this->org_id, $subject['program_id']);
                
                $co_ids = array_column($cos, 'id');
                $raw_mappings = $mappingModel->getMappingsForSubjectCos($this->org_id, $co_ids);
                
                foreach($raw_mappings as $m) {
                    $mappings[$m['co_id']][$m['po_id']] = $m['strength'];
                }
            }
        }

        return view('org/obe/faculty_mapping_matrix', [
            'subjects' => $subjects,
            'selected_subject_id' => $selected_subject_id,
            'cos' => $cos,
            'pos' => $pos,
            'mappings' => $mappings,
            'approval_status' => (new OrgSettingsModel())->getSetting($this->org_id, "obe_mapping_status_{$selected_subject_id}", 'draft'),
            'approval_notes' => (new OrgSettingsModel())->getSetting($this->org_id, "obe_mapping_notes_{$selected_subject_id}", '')
        ]);
    }

    public function saveMappings()
    {
        $mappingModel = new CoPoMappingModel();
        $subject_id = $this->request->getPost('subject_id');
        $co_po = $this->request->getPost('mapping'); // Array format: mapping[co_id][po_id] = strength
        
        $coModel = new CoDefinitionModel();
        $cos = $coModel->getCosBySubject($this->org_id, $subject_id);
        $co_ids = array_column($cos, 'id');

        // Delete existing for these COs
        if (!empty($co_ids)) {
            $mappingModel->where('org_id', $this->org_id)->whereIn('co_id', $co_ids)->delete();
        }

        // Insert new
        if (is_array($co_po)) {
            $inserts = [];
            foreach ($co_po as $co_id => $po_data) {
                foreach ($po_data as $po_id => $strength) {
                    if ($strength >= 1 && $strength <= 3) {
                        $inserts[] = [
                            'org_id' => $this->org_id,
                            'co_id' => $co_id,
                            'po_id' => $po_id,
                            'strength' => $strength
                        ];
                    }
                }
            }
            if (!empty($inserts)) {
                $mappingModel->insertBatch($inserts);
            }
        }

        // Set status to pending HOD review
        (new OrgSettingsModel())->setSetting($this->org_id, "obe_mapping_status_{$subject_id}", 'pending_hod');

        return redirect()->to("org/obe/mapping?subject_id=$subject_id")->with('success', 'CO-PO Mappings saved and submitted for HOD Approval.');
    }

    public function approveMapping()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        $subject_id = $this->request->getPost('subject_id');
        (new OrgSettingsModel())->setSetting($this->org_id, "obe_mapping_status_{$subject_id}", 'approved');
        (new OrgSettingsModel())->setSetting($this->org_id, "obe_mapping_notes_{$subject_id}", 'Approved by HOD on ' . date('Y-m-d'));
        return redirect()->back()->with('success', 'CO-PO Mapping Matrix approved by HOD.');
    }

    public function rejectMapping()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        $subject_id = $this->request->getPost('subject_id');
        $notes = $this->request->getPost('feedback_notes');
        (new OrgSettingsModel())->setSetting($this->org_id, "obe_mapping_status_{$subject_id}", 'rejected');
        (new OrgSettingsModel())->setSetting($this->org_id, "obe_mapping_notes_{$subject_id}", $notes);
        return redirect()->back()->with('success', 'CO-PO Mapping rejected with feedback notes.');
    }

    // --- ATTAINMENT TARGETS & REPORTS ---

    public function targetsSetup()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        
        $targetModel = new \App\Models\ObeTargetModel();
        $targets = $targetModel->getGlobalTargets($this->org_id);
        
        return view('org/obe/targets_setup', [
            'targets' => $targets
        ]);
    }

    public function saveTargets()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        
        $targetModel = new \App\Models\ObeTargetModel();
        $types = ['co', 'po', 'pso'];
        
        foreach($types as $type) {
            $data = [
                'org_id' => $this->org_id,
                'target_type' => $type,
                'entity_id' => null, // Global level
                'target_percentage' => $this->request->getPost("{$type}_target_percentage"),
                'level_1_threshold' => $this->request->getPost("{$type}_level_1"),
                'level_2_threshold' => $this->request->getPost("{$type}_level_2"),
                'level_3_threshold' => $this->request->getPost("{$type}_level_3")
            ];
            
            $existing = $targetModel->where('org_id', $this->org_id)
                                    ->where('target_type', $type)
                                    ->where('entity_id', null)
                                    ->first();
                                    
            if ($existing) {
                $targetModel->update($existing['id'], $data);
            } else {
                $targetModel->insert($data);
            }
        }
        
        return redirect()->to('org/obe/targets')->with('success', 'Attainment targets updated.');
    }

    public function attainmentReport()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        
        $cohortModel = new \App\Models\CohortModel();
        $cohorts = $cohortModel->where('org_id', $this->org_id)->findAll();
        
        $selected_cohort_id = $this->request->getGet('cohort_id') ?? ($cohorts[0]['id'] ?? null);
        
        $attainments = [];
        $poAttainments = [];
        $db = \Config\Database::connect();

        if ($selected_cohort_id) {
            $coModel = new \App\Models\CoDefinitionModel();
            $poModel = new \App\Models\PoDefinitionModel();
            $mappingModel = new \App\Models\CoPoMappingModel();

            $cohort = $cohortModel->find($selected_cohort_id);
            $cos = $coModel->where('org_id', $this->org_id)->findAll();
            $pos = $cohort ? $poModel->getPosByProgram($this->org_id, $cohort['program_id']) : [];

            // Get targets
            $targetModel = new \App\Models\ObeTargetModel();
            $targets = $targetModel->getGlobalTargets($this->org_id);
            $co_target = $targets['co'] ?? ['target_percentage' => 60, 'level_1_threshold' => 50, 'level_2_threshold' => 60, 'level_3_threshold' => 70];
            
            $coLevels = []; // co_id => level

            foreach($cos as $co) {
                $marks = $db->table('internal_marks m')
                    ->select('m.score, mc.max_marks')
                    ->join('mark_components mc', 'mc.id = m.component_id')
                    ->join('assessment_co_mapping acm', 'acm.component_id = mc.id')
                    ->where('acm.co_id', $co['id'])
                    ->where('m.org_id', $this->org_id)
                    ->where('m.score IS NOT NULL', null, false)
                    ->get()->getResultArray();
                
                $total_students = count($marks);
                $students_above_target = 0;
                
                if ($total_students > 0) {
                    foreach($marks as $m) {
                        $percent = ($m['score'] / ($m['max_marks'] ?: 100)) * 100;
                        if ($percent >= $co_target['target_percentage']) {
                            $students_above_target++;
                        }
                    }
                    $class_percent = ($students_above_target / $total_students) * 100;
                    
                    $level = 1;
                    if ($class_percent >= $co_target['level_3_threshold']) $level = 3;
                    elseif ($class_percent >= $co_target['level_2_threshold']) $level = 2;
                    elseif ($class_percent >= $co_target['level_1_threshold']) $level = 1;
                    
                    $coLevels[$co['id']] = $level;

                    $attainments[] = [
                        'co_id' => $co['id'],
                        'co_code' => $co['code'],
                        'description' => $co['description'],
                        'students_attempted' => $total_students,
                        'students_above_target' => $students_above_target,
                        'class_percentage' => round($class_percent, 2),
                        'attainment_level' => $level
                    ];
                } else {
                    $attainments[] = [
                        'co_id' => $co['id'],
                        'co_code' => $co['code'],
                        'description' => $co['description'],
                        'students_attempted' => 0,
                        'students_above_target' => 0,
                        'class_percentage' => 0,
                        'attainment_level' => 0
                    ];
                }
            }

            // Mathematical PO Attainment calculation: Sum(CO_level * Weight) / Sum(Weight)
            foreach ($pos as $po) {
                $rawMappings = $mappingModel->where('org_id', $this->org_id)->where('po_id', $po['id'])->findAll();
                $weightedSum = 0;
                $weightTotal = 0;

                foreach ($rawMappings as $rm) {
                    $coId = $rm['co_id'];
                    $weight = (float)$rm['strength'];
                    $coLevel = $coLevels[$coId] ?? 0;

                    if ($weight > 0 && $coLevel > 0) {
                        $weightedSum += ($coLevel * $weight);
                        $weightTotal += $weight;
                    }
                }

                $calculatedPoScore = ($weightTotal > 0) ? round($weightedSum / $weightTotal, 2) : 0;
                $poAttainments[] = [
                    'po_code'        => $po['code'],
                    'description'    => $po['description'],
                    'attainment_score' => $calculatedPoScore,
                    'attainment_level' => ($calculatedPoScore >= 2.5) ? 'High' : (($calculatedPoScore >= 1.5) ? 'Medium' : 'Low')
                ];
            }
        }
        
        return view('org/obe/attainment_report', [
            'cohorts'            => $cohorts,
            'selected_cohort_id' => $selected_cohort_id,
            'attainments'        => $attainments,
            'poAttainments'      => $poAttainments
        ]);
    }
    // --- PHASE 3: OBE NAAC CONFIG EXTENSIONS ---

    public function assessmentConfig()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        
        $sessionModel = new \App\Models\ObeExamSessionModel();
        $configModel = new \App\Models\ObeExamConfigModel();
        
        $sessions = $sessionModel->where('org_id', $this->org_id)->findAll();
        
        $configs = [];
        if (!empty($sessions)) {
            $configs = $configModel->where('org_id', $this->org_id)->findAll();
        }
        
        return view('org/obe/assessment_config', [
            'sessions' => $sessions,
            'configs' => $configs
        ]);
    }

    public function saveAssessmentConfig()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        
        // Handle Session creation
        if ($this->request->getPost('action') == 'add_session') {
            $sessionModel = new \App\Models\ObeExamSessionModel();
            $sessionModel->insert([
                'org_id' => $this->org_id,
                'session_name' => $this->request->getPost('session_name'),
                'type' => $this->request->getPost('type')
            ]);
            return redirect()->to('org/obe/assessment-config')->with('success', 'Exam session added.');
        }
        
        // Handle Config creation
        if ($this->request->getPost('action') == 'add_config') {
            $configModel = new \App\Models\ObeExamConfigModel();
            $configModel->insert([
                'org_id' => $this->org_id,
                'exam_session_id' => $this->request->getPost('exam_session_id'),
                'short_name' => $this->request->getPost('short_name'),
                'max_marks' => $this->request->getPost('max_marks'),
                'passing_marks' => $this->request->getPost('passing_marks'),
                'average_logic' => $this->request->getPost('average_logic'),
                'n_value' => $this->request->getPost('n_value') ?: 1
            ]);
            return redirect()->to('org/obe/assessment-config')->with('success', 'Exam configuration added.');
        }
        
        return redirect()->to('org/obe/assessment-config');
    }

    public function poReport()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        
        $programModel = new ProgramModel();
        $programs = $programModel->where('org_id', $this->org_id)->findAll();
        $selected_program_id = $this->request->getGet('program_id');
        
        $po_attainment = [];
        
        if ($selected_program_id) {
            $poModel = new PoDefinitionModel();
            $pos = $poModel->getPosByProgram($this->org_id, $selected_program_id);
            
            // Mocking PO attainment calculation by just assigning random NAAC-style data for the view
            foreach($pos as $po) {
                // In real app, this multiplies CO attainment with CO-PO mapping matrix
                $direct = rand(50, 95) / 100 * 3; // Out of 3
                $indirect = rand(60, 95) / 100 * 3; // Out of 3
                $total = ($direct * 0.8) + ($indirect * 0.2); // 80% direct, 20% indirect
                
                $po_attainment[] = [
                    'po_code' => $po['code'],
                    'description' => $po['description'],
                    'direct_attainment' => round($direct, 2),
                    'indirect_attainment' => round($indirect, 2),
                    'total_attainment' => round($total, 2)
                ];
            }
        }
        
        return view('org/obe/po_report', [
            'programs' => $programs,
            'selected_program_id' => $selected_program_id,
            'po_attainment' => $po_attainment
        ]);
    }

    public function gapAnalysis()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        
        $subjectModel = new SubjectModel();
        $subjects = $subjectModel->where('org_id', $this->org_id)->findAll();
        
        $gapModel = new \App\Models\ObeGapAnalysisModel();
        $builder = \Config\Database::connect()->table('obe_gap_analysis g');
        $builder->select('g.*, s.name as subject_name, c.code as co_code');
        $builder->join('subjects s', 's.id = g.subject_id');
        $builder->join('co_definitions c', 'c.id = g.co_id');
        $builder->where('g.org_id', $this->org_id);
        $gaps = $builder->get()->getResultArray();
        
        return view('org/obe/gap_analysis', [
            'subjects' => $subjects,
            'gaps' => $gaps
        ]);
    }

    public function saveGapAnalysis()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        
        $gapModel = new \App\Models\ObeGapAnalysisModel();
        $gapModel->insert([
            'org_id' => $this->org_id,
            'subject_id' => $this->request->getPost('subject_id'),
            'co_id' => $this->request->getPost('co_id'),
            'attained_value' => $this->request->getPost('attained_value'),
            'target_value' => $this->request->getPost('target_value'),
            'gap_identified' => $this->request->getPost('gap_identified'),
            'action_plan' => $this->request->getPost('action_plan'),
            'status' => 'Identified'
        ]);
        
        return redirect()->to('org/obe/gap-analysis')->with('success', 'Gap analysis recorded.');
    }
}

