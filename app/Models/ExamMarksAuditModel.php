<?php

namespace App\Models;

class ExamMarksAuditModel extends BaseModel
{
    protected $table            = 'org_exam_marks_audit';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'org_id', 'subject_id', 'cohort_id', 'component_id', 'exam_type',
        'action', 'performed_by_user_id', 'performed_by_name', 'unlock_reason'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
