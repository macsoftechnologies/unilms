<?php
namespace App\Models;
use CodeIgniter\Model;

class LibraryIssueModel extends BaseModel
{
    protected $table = 'library_issues';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'book_id', 'member_id', 'issue_date', 'due_date', 'return_date', 'fine_amount', 'fine_paid', 'issued_by', 'status', 'remarks', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
