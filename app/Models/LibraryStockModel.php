<?php
namespace App\Models;

use CodeIgniter\Model;

class LibraryStockModel extends BaseModel
{
    protected $table = 'library_stock_verifications';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id',
        'rack_no',
        'expected_count',
        'physical_count',
        'missing_books',
        'verified_by',
        'verified_on',
        'created_at'
    ];
}
