<?php
namespace App\Models;

use CodeIgniter\Model;

class AttendanceEditLogModel extends BaseModel
{
    protected $table = 'attendance_edit_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'session_id', 'edited_by_user_id', 'previous_data', 'new_data', 'reason', 'created_at'];
    protected $useTimestamps = false; // DB has default current_timestamp for created_at
}
