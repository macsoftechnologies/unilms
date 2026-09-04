<?php
namespace App\Models;

class VisitorModel extends BaseModel
{
    protected $table = 'visitors';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'visitor_name', 'phone', 'visitor_type', 'purpose', 
        'person_to_meet', 'department_id', 'in_time', 'out_time', 
        'pass_number', 'id_proof', 'remarks', 'created_by'
    ];
    protected $useTimestamps = true;
}
