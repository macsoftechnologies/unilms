<?php
namespace App\Models;
use CodeIgniter\Model;

class ExamExpenditureModel extends BaseModel
{
    protected $table = 'exam_expenditures';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'exam_id', 'head', 'amount', 'description', 'date', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
