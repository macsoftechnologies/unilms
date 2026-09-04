<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizAttemptModel extends BaseModel
{
    protected $table            = 'lms_quiz_attempts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['org_id', 'quiz_id', 'student_id', 'start_time', 'end_time', 'status', 'score_obtained', 'max_score'];
    protected $useTimestamps    = true;
}

