<?php
namespace App\Models;

use CodeIgniter\Model;

class LibrarySupplierModel extends BaseModel
{
    protected $table = 'library_suppliers';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id',
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'created_at'
    ];
}
