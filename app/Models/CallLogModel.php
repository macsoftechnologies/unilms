<?php
namespace App\Models;

class CallLogModel extends BaseModel
{
    protected $table = 'call_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'call_type', 'caller_name', 'phone_number', 'purpose', 
        'remarks', 'call_duration', 'call_result', 'followup_required', 
        'followup_date', 'created_by'
    ];
    protected $useTimestamps = true;
}
