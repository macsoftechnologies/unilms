<?php
namespace App\Models;
use CodeIgniter\Model;

class TransportRegistrationModel extends BaseModel
{
    protected $table = 'transport_registrations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'route_id', 'halt_id', 'status', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
