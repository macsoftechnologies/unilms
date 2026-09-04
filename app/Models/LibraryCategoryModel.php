<?php
namespace App\Models;

use CodeIgniter\Model;

class LibraryCategoryModel extends BaseModel
{
    protected $table = 'library_categories';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id',
        'name',
        'description',
        'created_at'
    ];
}
