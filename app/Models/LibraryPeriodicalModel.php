<?php
namespace App\Models;

use CodeIgniter\Model;

class LibraryPeriodicalModel extends BaseModel
{
    protected $table = 'library_periodicals';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id',
        'title',
        'issn',
        'frequency',
        'subscription_date',
        'valid_till',
        'copies',
        'status',
        'created_at'
    ];
}
