<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizOptionModel extends BaseModel
{
    protected $table            = 'lms_quiz_options';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['org_id', 'question_id', 'option_text', 'is_correct', 'order_index'];
    protected $useTimestamps    = true;
}

