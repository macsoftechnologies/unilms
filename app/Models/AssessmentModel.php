<?php

namespace App\Models;

class AssessmentModel extends BaseModel
{
    protected $table            = 'lms_assessments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'org_id', 'subject_id', 'cohort_id', 'faculty_user_id', 'co_id',
        'title', 'description', 'assessment_type', 'max_marks', 'due_date', 'is_published',
        'allowed_extensions', 'max_file_size_mb', 'reference_attachment',
        'time_limit_mins', 'max_attempts', 'randomize_questions', 'randomize_options', 'score_visibility',
        'video_source_type', 'video_url', 'video_duration_seconds',
        'min_word_count', 'max_word_count', 'submission_mode', 'allow_late_submissions', 'rubric_criteria'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
