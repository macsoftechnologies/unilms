<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizAnswerModel extends BaseModel
{
    protected $table            = 'lms_quiz_answers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['org_id', 'attempt_id', 'question_id', 'selected_option_id', 'is_correct', 'marks_awarded'];
    protected $useTimestamps    = true;
}

