<?php
namespace App\Models;
use CodeIgniter\Model;

class PlacementOfferModel extends BaseModel
{
    protected $table = 'placement_offers';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'student_id', 'company_id', 'job_role', 'ctc', 'offer_date', 'status', 'offer_letter_path', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
