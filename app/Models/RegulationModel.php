<?php
namespace App\Models;

class RegulationModel extends BaseModel
{
    protected $table = 'regulations';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'name', 'description', 'start_year', 
        'total_credits', 'pass_percentage', 'grading_scale_json', 'is_active'
    ];
    protected $useTimestamps = true;
}
