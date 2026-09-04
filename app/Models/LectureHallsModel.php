<?php

namespace App\Models;

use CodeIgniter\Model;

class LectureHallsModel extends BaseModel
{
    protected $table = 'lecture_halls';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'name', 'hall_name', 'capacity', 'seating_capacity', 'hall_type', 
        'building_name', 'floor_number', 'is_ac', 'has_projector', 'is_active'
    ];
    
    // Automatically manage created_at and updated_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
