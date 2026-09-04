<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizModel extends BaseModel
{
    protected $table            = 'lms_quizzes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['org_id', 'faculty_user_id', 'subject_id', 'cohort_id', 'title', 'description', 'time_limit_minutes', 'max_attempts', 'start_date', 'end_date', 'is_published'];
    protected $useTimestamps    = true;

    public function getQuizzesByOrg($org_id)
    {
        return $this->select('lms_quizzes.*, subjects.name as subject_name, subjects.code as subject_code, cohorts.name as cohort_name, org_users.full_name')
                    ->join('subjects', 'subjects.id = lms_quizzes.subject_id')
                    ->join('cohorts', 'cohorts.id = lms_quizzes.cohort_id')
                    ->join('org_users', 'org_users.id = lms_quizzes.faculty_user_id')
                    ->where('lms_quizzes.org_id', $org_id)
                    ->orderBy('lms_quizzes.created_at', 'DESC')
                    ->findAll();
    }
}

