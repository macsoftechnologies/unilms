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
        return $this->select('lms_quizzes.*, COALESCE(subjects.name, "General") as subject_name, COALESCE(subjects.code, "GEN") as subject_code, COALESCE(cohorts.name, "All Cohorts") as cohort_name, COALESCE(org_users.full_name, "Faculty") as full_name')
                    ->join('subjects', 'subjects.id = lms_quizzes.subject_id', 'left')
                    ->join('cohorts', 'cohorts.id = lms_quizzes.cohort_id', 'left')
                    ->join('org_users', 'org_users.id = lms_quizzes.faculty_user_id', 'left')
                    ->where('lms_quizzes.org_id', $org_id)
                    ->orderBy('lms_quizzes.created_at', 'DESC')
                    ->findAll();
    }
}

