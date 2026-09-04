<?php

namespace App\Models;

class AssessmentQuestionModel extends BaseModel
{
    protected $table            = 'lms_assessment_questions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'assessment_id', 'co_id', 'timestamp_seconds', 'question_type',
        'question_text', 'points', 'options', 'correct_option', 'explanation', 'sort_order'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
