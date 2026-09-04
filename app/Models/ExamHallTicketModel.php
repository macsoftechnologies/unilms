<?php
namespace App\Models;
use CodeIgniter\Model;

class ExamHallTicketModel extends BaseModel
{
    protected $table = 'exam_hall_tickets';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'exam_application_id', 'hall_ticket_number', 'issue_date', 'status', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
