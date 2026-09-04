<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizQuestionModel extends BaseModel
{
    protected $table            = 'lms_quiz_questions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['org_id', 'quiz_id', 'question_text', 'question_type', 'marks', 'order_index'];
    protected $useTimestamps    = true;
}

