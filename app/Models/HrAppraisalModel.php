<?php
namespace App\Models;

class HrAppraisalModel extends BaseModel
{
    protected $table = 'hr_appraisals';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'employee_id', 'evaluator_id', 'reviewed_by',
        'academic_year_id', 'appraisal_period', 'review_period_start', 'review_period_end',
        'self_rating', 'self_score', 'hod_rating', 'reviewer_score', 'final_score',
        'review_date', 'strengths', 'areas_for_improvement', 'recommendation',
        'feedback', 'status'
    ];
    protected $useTimestamps = true;
}
