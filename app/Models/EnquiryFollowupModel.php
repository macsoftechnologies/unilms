<?php
namespace App\Models;

class EnquiryFollowupModel extends BaseModel
{
    protected $table = 'enquiry_followups';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'enquiry_id', 'notes', 'followup_date', 
        'next_followup_date', 'followed_by', 'followup_status'
    ];
    protected $useTimestamps = true;
}
