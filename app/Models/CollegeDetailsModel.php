<?php

namespace App\Models;

use CodeIgniter\Model;

class CollegeDetailsModel extends BaseModel
{
    protected $table = 'college_details';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'name', 'college_name', 'college_code', 'logo_path', 'affiliation', 
        'naac_grade', 'contact_email', 'contact_phone', 'address', 'website'
    ];
    
    // Automatically manage created_at and updated_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
