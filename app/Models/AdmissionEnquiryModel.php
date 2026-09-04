<?php
namespace App\Models;

class AdmissionEnquiryModel extends BaseModel
{
    protected $table = 'admission_enquiries';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'enquiry_number', 'student_name', 'parent_name', 'mobile', 
        'email', 'program_id', 'enquiry_source', 'remarks', 'assigned_counsellor', 
        'status', 'created_by'
    ];
    protected $useTimestamps = true;
}
