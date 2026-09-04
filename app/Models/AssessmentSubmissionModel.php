<?php

namespace App\Models;

class AssessmentSubmissionModel extends BaseModel
{
    protected $table            = 'lms_assessment_submissions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'assessment_id', 'student_id', 'attempt_number', 'started_at', 'submitted_at', 'is_late',
        'submitted_file', 'submitted_essay', 'essay_word_count', 'student_comments',
        'answers_payload', 'auto_score', 'final_marks', 'rubric_breakdown', 'faculty_feedback',
        'graded_by_user_id', 'graded_at', 'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
