<?php

namespace App\Models;

use CodeIgniter\Model;

class AssignmentSubmissionModel extends BaseModel
{
    protected $table            = 'lms_assignment_submissions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['org_id', 'assignment_id', 'student_id', 'file_path', 'submission_text', 'remarks', 'status', 'marks_obtained', 'feedback', 'submitted_at', 'graded_at', 'graded_by'];

    public function getSubmissionsForAssignment($org_id, $assignment_id)
    {
        return $this->select('lms_assignment_submissions.*, students.roll_number, students.first_name, students.last_name')
                    ->join('students', 'students.id = lms_assignment_submissions.student_id')
                    ->where('lms_assignment_submissions.org_id', $org_id)
                    ->where('lms_assignment_submissions.assignment_id', $assignment_id)
                    ->orderBy('students.roll_number', 'ASC')
                    ->findAll();
    }
}
