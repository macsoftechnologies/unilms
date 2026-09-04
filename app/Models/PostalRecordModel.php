<?php
namespace App\Models;

class PostalRecordModel extends BaseModel
{
    protected $table = 'postal_records';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'record_type', 'reference_number', 'sender_receiver_name', 
        'address', 'courier_name', 'tracking_number', 'date', 'description', 
        'handled_by', 'attachment'
    ];
    protected $useTimestamps = true;
}
