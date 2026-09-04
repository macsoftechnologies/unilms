<?php
namespace App\Models;
use CodeIgniter\Model;

class HostelRoomModel extends BaseModel
{
    protected $table = 'hostel_rooms';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'hostel_id', 'room_no', 'floor', 'type', 'capacity', 'has_ac', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
