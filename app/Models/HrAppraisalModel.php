<?php
namespace App\Models;

class HrAppraisalModel extends BaseModel
{
    protected $table = 'hr_appraisals';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'employee_id', 'evaluator_id', 'review_period_start', 
        'review_period_end', 'self_score', 'reviewer_score', 'final_score', 
        'feedback', 'status'
    ];
    protected $useTimestamps = true;
}
