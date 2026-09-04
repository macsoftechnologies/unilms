<?php
namespace App\Models;
use CodeIgniter\Model;

class LibraryMemberModel extends BaseModel
{
    protected $table = 'library_members';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'member_type', 'student_id', 'staff_id', 'max_books_allowed', 'valid_till', 'status', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
