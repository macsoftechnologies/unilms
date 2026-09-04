<?php
namespace App\Models;
use CodeIgniter\Model;

class LibraryReservationModel extends BaseModel
{
    protected $table = 'library_reservations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'book_id', 'member_id', 'reservation_date', 'expiry_date', 'status', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
