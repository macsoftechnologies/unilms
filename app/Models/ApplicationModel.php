<?php
namespace App\Models;
use CodeIgniter\Model;

class ApplicationModel extends BaseModel
{
    protected $table = 'applications';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'lead_id', 'cycle_id', 'adm_number', 'admission_category',
        'full_name', 'dob', 'gender', 'category', 'nationality', 'phone', 'email', 'address',
        'prev_qualification', 'board_university', 'year_passing', 'marks_percentage', 'entrance_exam', 'entrance_score',
        'program_id', 'program_name', 'program_code', 'fee_structure_version',
        'counselling_rank', 'allotment_order_number', 'counselling_round', 'passport_number', 'nri_sponsor',
        'status'
    ];
    protected $useTimestamps = true;

    public function getApplications($orgId) {
        return $this->select('applications.*, admission_cycles.name as cycle_name')
                    ->join('admission_cycles', 'admission_cycles.id = applications.cycle_id', 'left')
                    ->where('applications.org_id', $orgId)
                    ->orderBy('applications.created_at', 'DESC')
                    ->findAll();
    }
}
