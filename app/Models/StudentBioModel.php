<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentBioModel extends BaseModel
{
    protected $table = 'student_bio';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'student_id', 'dob', 'gender', 'photo_path', 'email', 'phone', 
        'blood_group', 'aadhaar', 'address', 'city', 'state', 'pincode', 
        'caste_id', 'category', 'father_name', 'father_phone', 
        'mother_name', 'mother_phone', 'guardian_name', 'guardian_phone', 
        'annual_income', 'tenth_marks', 'twelfth_marks', 'entrance_rank'
    ];
    
    // Automatically manage created_at and updated_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
