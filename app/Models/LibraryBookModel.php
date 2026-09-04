<?php
namespace App\Models;
use CodeIgniter\Model;

class LibraryBookModel extends BaseModel
{
    protected $table = 'library_books';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'accession_no', 'title', 'author', 'publisher', 'publication_year', 'edition', 'isbn', 'subject', 'category', 'rack_no', 'copies', 'status', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
