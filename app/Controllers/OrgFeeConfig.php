<?php
namespace App\Controllers;

use App\Models\FeeTypeModel;
use App\Models\FeeStructureModel;
use App\Models\ProgramModel;
use App\Models\SemesterModel;
use App\Models\AcademicYearModel;

class OrgFeeConfig extends BaseController
{
    // =============================================
    // FEE TYPES MANAGEMENT
    // =============================================
    public function feeTypes()
    {
        $feeTypeModel = new FeeTypeModel();
        $orgId = session()->get('org_id');
        
        $data['fee_types'] = $feeTypeModel->where('org_id', $orgId)->findAll();
        
        return view('org/fees/types', $data);
    }
    
    public function saveFeeType()
    {
        $feeTypeModel = new FeeTypeModel();
        $orgId = session()->get('org_id');
        $id = $this->request->getPost('id');
        
        $data = [
            'org_id' => $orgId,
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'applicable_to' => $this->request->getPost('applicable_to'),
            'gl_head' => $this->request->getPost('gl_head'),
        ];
        
        if (empty($id)) {
            $feeTypeModel->insert($data);
        } else {
            $feeTypeModel->update($id, $data);
        }
        
        return redirect()->to(base_url('org/fee-config/types'))->with('success', 'Fee Type saved successfully.');
    }
    
    public function deleteFeeType()
    {
        $feeTypeModel = new FeeTypeModel();
        $id = $this->request->getPost('id');
        $feeTypeModel->delete($id);
        
        return redirect()->back()->with('success', 'Fee Type deleted successfully.');
    }

    // =============================================
    // FEE STRUCTURES MANAGEMENT
    // =============================================
    // =============================================
    // FEE STRUCTURES - 3-TIER DRILL-DOWN FLOW
    // =============================================
    
    // Tier 1: Department & Degree Programs Hub
    public function feeStructures()
    {
        $orgId = session()->get('org_id');
        $db = \Config\Database::connect();

        // Fetch all departments
        $departments = $db->table('departments')
                          ->where('org_id', $orgId)
                          ->get()->getResultArray();

        // Fetch all programs with department info and fee aggregates
        $programs = $db->table('programs p')
                       ->select('p.*, d.name as department_name, 
                                 COUNT(DISTINCT fs.semester_id) as configured_semesters_count, 
                                 SUM(fs.amount) as total_program_fee')
                       ->join('departments d', 'd.id = p.dept_id', 'left')
                       ->join('fee_structures fs', 'fs.program_id = p.id AND fs.org_id = ' . $db->escape($orgId), 'left')
                       ->where('p.org_id', $orgId)
                       ->groupBy('p.id')
                       ->get()->getResultArray();

        // Group programs by department
        $deptGrouped = [];
        foreach ($programs as $prog) {
            $deptName = $prog['department_name'] ?: 'General Engineering & Technology';
            if (!isset($deptGrouped[$deptName])) {
                $deptGrouped[$deptName] = [];
            }
            $deptGrouped[$deptName][] = $prog;
        }

        $programModel = new ProgramModel();
        $semesterModel = new SemesterModel();
        $ayModel = new AcademicYearModel();
        $feeTypeModel = new FeeTypeModel();

        $data = [
            'dept_grouped_programs' => $deptGrouped,
            'departments'           => $departments,
            'programs'              => $programModel->where('org_id', $orgId)->findAll(),
            'semesters'             => $semesterModel->where('org_id', $orgId)->orderBy('sequence', 'ASC')->findAll(),
            'academic_years'        => $ayModel->where('org_id', $orgId)->findAll(),
            'fee_types'             => $feeTypeModel->where('org_id', $orgId)->findAll(),
        ];

        return view('org/fees/structures', $data);
    }

    // Tier 2: Batches & Year Levels for a Selected Program
    public function programBatches($programId)
    {
        $orgId = session()->get('org_id');
        $db = \Config\Database::connect();

        $program = $db->table('programs p')
                      ->select('p.*, d.name as department_name')
                      ->join('departments d', 'd.id = p.dept_id', 'left')
                      ->where('p.id', $programId)
                      ->where('p.org_id', $orgId)
                      ->get()->getRowArray();

        if (!$program) return redirect()->to(base_url('org/fee-config/structures'));

        // Fetch all fee structures for this program
        $builder = $db->table('fee_structures fs');
        $builder->select('fs.*, s.name as semester_name, s.sequence as semester_sequence, ay.name as academic_year_name, ft.name as fee_type_name');
        $builder->join('semesters s', 's.id = fs.semester_id');
        $builder->join('academic_years ay', 'ay.id = fs.academic_year_id');
        $builder->join('fee_types ft', 'ft.id = fs.fee_type_id');
        $builder->where('fs.org_id', $orgId);
        $builder->where('fs.program_id', $programId);
        $builder->orderBy('s.sequence', 'ASC');

        $rawStructures = $builder->get()->getResultArray();

        // Group into semester packages
        $packages = [];
        foreach ($rawStructures as $fs) {
            $key = $fs['semester_id'] . '_' . $fs['academic_year_id'];
            if (!isset($packages[$key])) {
                $packages[$key] = [
                    'key'                => $key,
                    'program_id'         => $programId,
                    'semester_id'        => $fs['semester_id'],
                    'semester_name'      => $fs['semester_name'],
                    'semester_sequence'  => $fs['semester_sequence'],
                    'academic_year_id'   => $fs['academic_year_id'],
                    'academic_year_name' => $fs['academic_year_name'],
                    'due_date'           => $fs['due_date'],
                    'total_amount'       => 0,
                    'heads_count'        => 0,
                    'items'              => []
                ];
            }
            $packages[$key]['total_amount'] += (float)$fs['amount'];
            $packages[$key]['heads_count']++;
            $packages[$key]['items'][] = $fs;
        }

        // Group by Year Level / Batch (Year 1: Sem 1,2 | Year 2: Sem 3,4 | Year 3: Sem 5,6 | Year 4: Sem 7,8)
        $yearLevels = [
            'Year 1' => ['title' => '1st Year (Fresher Batch)', 'badge' => '2026–2030 Batch', 'semesters' => []],
            'Year 2' => ['title' => '2nd Year (Sophomore Batch)', 'badge' => '2025–2029 Batch', 'semesters' => []],
            'Year 3' => ['title' => '3rd Year (Junior Batch)', 'badge' => '2024–2028 Batch', 'semesters' => []],
            'Year 4' => ['title' => '4th Year (Senior Batch)', 'badge' => '2023–2027 Batch', 'semesters' => []],
        ];

        foreach ($packages as $pkg) {
            $yearNum = ceil($pkg['semester_sequence'] / 2);
            $yearKey = "Year {$yearNum}";
            if (isset($yearLevels[$yearKey])) {
                $yearLevels[$yearKey]['semesters'][] = $pkg;
            } else {
                $yearLevels[$yearKey] = ['title' => "Year {$yearNum}", 'badge' => 'Active Batch', 'semesters' => [$pkg]];
            }
        }

        $semesterModel = new SemesterModel();
        $ayModel = new AcademicYearModel();
        $feeTypeModel = new FeeTypeModel();

        $data = [
            'program'        => $program,
            'year_levels'    => $yearLevels,
            'semesters'      => $semesterModel->where('org_id', $orgId)->orderBy('sequence', 'ASC')->findAll(),
            'academic_years' => $ayModel->where('org_id', $orgId)->findAll(),
            'fee_types'      => $feeTypeModel->where('org_id', $orgId)->findAll(),
        ];

        return view('org/fees/program_batches', $data);
    }

    // Tier 3: Dedicated Semester Fee Breakdown & Editor
    public function semesterPlan($programId, $semesterId, $ayId)
    {
        $orgId = session()->get('org_id');
        $db = \Config\Database::connect();

        $program = $db->table('programs p')
                      ->select('p.*, d.name as department_name')
                      ->join('departments d', 'd.id = p.dept_id', 'left')
                      ->where('p.id', $programId)
                      ->where('p.org_id', $orgId)
                      ->get()->getRowArray();

        $semester = $db->table('semesters')->where('id', $semesterId)->where('org_id', $orgId)->get()->getRowArray();
        $academicYear = $db->table('academic_years')->where('id', $ayId)->where('org_id', $orgId)->get()->getRowArray();

        if (!$program || !$semester || !$academicYear) {
            return redirect()->to(base_url('org/fee-config/structures'));
        }

        // Fetch line items for this exact semester
        $builder = $db->table('fee_structures fs');
        $builder->select('fs.*, ft.name as fee_type_name, ft.gl_head, ft.applicable_to');
        $builder->join('fee_types ft', 'ft.id = fs.fee_type_id');
        $builder->where('fs.org_id', $orgId);
        $builder->where('fs.program_id', $programId);
        $builder->where('fs.semester_id', $semesterId);
        $builder->where('fs.academic_year_id', $ayId);
        $items = $builder->get()->getResultArray();

        $totalAmount = 0;
        $dueDate = null;
        foreach ($items as $item) {
            $totalAmount += (float)$item['amount'];
            if (!$dueDate && !empty($item['due_date'])) {
                $dueDate = $item['due_date'];
            }
        }

        $feeTypeModel = new FeeTypeModel();
        $allFeeTypes = $feeTypeModel->where('org_id', $orgId)->findAll();

        $data = [
            'program'       => $program,
            'semester'      => $semester,
            'academic_year' => $academicYear,
            'items'         => $items,
            'total_amount'  => $totalAmount,
            'due_date'      => $dueDate,
            'fee_types'     => $allFeeTypes,
        ];

        return view('org/fees/semester_plan', $data);
    }
    
    public function saveFeeStructure()
    {
        $feeStructureModel = new FeeStructureModel();
        $orgId = session()->get('org_id');
        $id = $this->request->getPost('id');
        
        $program_id = $this->request->getPost('program_id');
        $semester_id = $this->request->getPost('semester_id');
        $academic_year_id = $this->request->getPost('academic_year_id');
        $due_date = $this->request->getPost('due_date');

        // Check if multi-head fee package is submitted
        $fee_amounts = $this->request->getPost('fee_amounts');
        
        if (!empty($fee_amounts) && is_array($fee_amounts)) {
            $savedCount = 0;
            foreach ($fee_amounts as $feeTypeId => $amount) {
                $amount = (float)$amount;
                if ($amount > 0) {
                    $existing = $feeStructureModel->where('org_id', $orgId)
                                                 ->where('program_id', $program_id)
                                                 ->where('semester_id', $semester_id)
                                                 ->where('academic_year_id', $academic_year_id)
                                                 ->where('fee_type_id', $feeTypeId)
                                                 ->first();
                    $data = [
                        'org_id' => $orgId,
                        'program_id' => $program_id,
                        'semester_id' => $semester_id,
                        'academic_year_id' => $academic_year_id,
                        'fee_type_id' => $feeTypeId,
                        'amount' => $amount,
                        'due_date' => $due_date,
                    ];

                    if ($existing) {
                        $feeStructureModel->update($existing['id'], $data);
                    } else {
                        $feeStructureId = $feeStructureModel->insert($data);

                        $db = \Config\Database::connect();
                        $students = $db->table('students s')
                                       ->join('cohorts c', 'c.id = s.cohort_id')
                                       ->where('s.org_id', $orgId)
                                       ->where('c.program_id', $program_id)
                                       ->where('c.current_semester_id', $semester_id)
                                       ->get()->getResultArray();
                                       
                        if (!empty($students)) {
                            $ledgerModel = new \App\Models\StudentFeeLedgerModel();
                            $ledgerEntries = [];
                            foreach ($students as $student) {
                                $ledgerEntries[] = [
                                    'org_id' => $orgId,
                                    'student_id' => $student['id'],
                                    'fee_structure_id' => $feeStructureId,
                                    'amount_due' => $amount,
                                    'amount_paid' => 0.00,
                                    'balance' => $amount,
                                    'status' => 'unpaid'
                                ];
                            }
                            $ledgerModel->insertBatch($ledgerEntries);
                        }
                    }
                    $savedCount++;
                }
            }
            return redirect()->to(base_url('org/fee-config/structures'))->with('success', "Fee package saved successfully ($savedCount fee heads configured).");
        }

        // Single entry fallback
        $data = [
            'org_id' => $orgId,
            'program_id' => $program_id,
            'semester_id' => $semester_id,
            'academic_year_id' => $academic_year_id,
            'fee_type_id' => $this->request->getPost('fee_type_id'),
            'amount' => $this->request->getPost('amount'),
            'due_date' => $due_date,
        ];
        
        if (empty($id)) {
            $feeStructureId = $feeStructureModel->insert($data);
            
            $db = \Config\Database::connect();
            $students = $db->table('students s')
                           ->join('cohorts c', 'c.id = s.cohort_id')
                           ->where('s.org_id', $orgId)
                           ->where('c.program_id', $data['program_id'])
                           ->where('c.current_semester_id', $data['semester_id'])
                           ->get()->getResultArray();
                           
            if (!empty($students)) {
                $ledgerModel = new \App\Models\StudentFeeLedgerModel();
                $ledgerEntries = [];
                foreach ($students as $student) {
                    $ledgerEntries[] = [
                        'org_id' => $orgId,
                        'student_id' => $student['id'],
                        'fee_structure_id' => $feeStructureId,
                        'amount_due' => $data['amount'],
                        'amount_paid' => 0.00,
                        'balance' => $data['amount'],
                        'status' => 'unpaid'
                    ];
                }
                $ledgerModel->insertBatch($ledgerEntries);
            }
        } else {
            $feeStructureModel->update($id, $data);
        }
        
        return redirect()->to(base_url('org/fee-config/structures'))->with('success', 'Fee Structure saved successfully.');
    }
    
    public function deleteFeeStructure()
    {
        $feeStructureModel = new FeeStructureModel();
        $id = $this->request->getPost('id');
        $feeStructureModel->delete($id);
        
        return redirect()->back()->with('success', 'Fee Structure deleted successfully.');
    }
    public function manualAssign()
    {
        $orgId = session()->get('org_id');
        $feeStructureModel = new FeeStructureModel();
        
        $db = \Config\Database::connect();
        
        // Get all structures
        $builder = $db->table('fee_structures fs');
        $builder->select('fs.*, p.name as program_name, s.name as semester_name, ay.name as academic_year_name, ft.name as fee_type_name');
        $builder->join('programs p', 'p.id = fs.program_id');
        $builder->join('semesters s', 's.id = fs.semester_id');
        $builder->join('academic_years ay', 'ay.id = fs.academic_year_id');
        $builder->join('fee_types ft', 'ft.id = fs.fee_type_id');
        $builder->where('fs.org_id', $orgId);
        $data['fee_structures'] = $builder->get()->getResultArray();
        
        // Get all students
        $data['students'] = $db->table('students s')
                               ->select('s.id, s.first_name, s.last_name, s.roll_number, c.program_id, c.current_semester_id')
                               ->join('cohorts c', 'c.id = s.cohort_id', 'left')
                               ->where('s.org_id', $orgId)
                               ->get()->getResultArray();
                               
        return view('org/fees/manual_assign', $data);
    }
    
    public function saveManualAssign()
    {
        $orgId = session()->get('org_id');
        $studentId = $this->request->getPost('student_id');
        $structureId = $this->request->getPost('fee_structure_id');
        
        $feeStructureModel = new FeeStructureModel();
        $ledgerModel = new \App\Models\StudentFeeLedgerModel();
        
        $structure = $feeStructureModel->where('org_id', $orgId)->find($structureId);
        if (!$structure) {
            return redirect()->back()->with('error', 'Invalid fee structure.');
        }
        
        // Check if already assigned
        $exists = $ledgerModel->where('org_id', $orgId)
                              ->where('student_id', $studentId)
                              ->where('fee_structure_id', $structureId)
                              ->first();
                              
        if ($exists) {
            return redirect()->back()->with('error', 'This fee structure is already assigned to the student.');
        }
        
        $ledgerModel->insert([
            'org_id' => $orgId,
            'student_id' => $studentId,
            'fee_structure_id' => $structureId,
            'amount_due' => $structure['amount'],
            'amount_paid' => 0.00,
            'balance' => $structure['amount'],
            'status' => 'unpaid'
        ]);
        
        return redirect()->to(base_url('org/fee-config/manual-assign'))->with('success', 'Fee structure manually assigned successfully.');
    }
}
