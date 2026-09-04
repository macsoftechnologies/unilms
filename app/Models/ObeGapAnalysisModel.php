<?php
namespace App\Models;
use CodeIgniter\Model;

class ObeGapAnalysisModel extends BaseModel
{
    protected $table = 'obe_gap_analysis';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'subject_id', 'co_id', 'attained_value', 'target_value', 'gap_identified', 'action_plan', 'status', 'verified_by', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
