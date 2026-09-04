<?php

namespace App\Models;

class InternshipEnrollmentModel extends BaseModel
{
    protected $table            = 'internship_enrollments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'posting_id', 'student_id', 'faculty_mentor_id',
        'supervisor_name', 'supervisor_email', 'supervisor_magic_token', 'token_expires_at',
        'resume_file', 'cover_letter', 'offer_sent_at', 'offer_valid_until', 'status', 'rejection_reason',
        'student_mid_review', 'supervisor_mid_review', 'supervisor_mid_rating', 'faculty_mid_review_notes', 'faculty_mid_approved',
        'rubric_technical', 'rubric_communication', 'rubric_discipline', 'rubric_problem_solving',
        'rubric_quality_output', 'rubric_attendance', 'viva_marks', 'report_marks',
        'total_weighted_grade', 'faculty_final_remarks', 'supervisor_final_signoff',
        'certificate_number', 'certificate_hash', 'certificate_issued_at', 'certificate_revoked', 'revocation_reason'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
