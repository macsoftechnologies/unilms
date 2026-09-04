<?php
namespace App\Models;

class PlacementDriveModel extends BaseModel
{
    protected $table = 'placement_drives';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'company_id', 'title', 'job_role', 'description', 
        'location', 'ctc_details', 'required_cgpa', 'max_backlogs', 
        'eligible_programs', 'rounds_json', 'deadline_date', 'drive_date', 'status'
    ];
    protected $useTimestamps = true;
}
