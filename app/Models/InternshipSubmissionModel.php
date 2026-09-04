<?php

namespace App\Models;

class InternshipSubmissionModel extends BaseModel
{
    protected $table            = 'internship_task_submissions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'enrollment_id', 'task_id', 'tracked_time_seconds', 'submission_text', 'submission_link',
        'submission_file', 'student_submitted_at', 'faculty_status', 'faculty_feedback',
        'faculty_approved_at', 'supervisor_status', 'supervisor_feedback', 'supervisor_signed_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
