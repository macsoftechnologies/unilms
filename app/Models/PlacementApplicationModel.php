<?php
namespace App\Models;

class PlacementApplicationModel extends BaseModel
{
    protected $table = 'placement_applications';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'drive_id', 'student_id', 'resume_file', 
        'current_round', 'status', 'remarks'
    ];
    protected $useTimestamps = true;
}
