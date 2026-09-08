<?php
namespace App\Models;
use CodeIgniter\Model;

class ApplicationDocumentModel extends BaseModel
{
    protected $table = 'application_documents';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'application_id', 'document_type_id', 'doc_name', 'file_path',
        'status', 'rejection_reason', 'verified_by', 'verified_at'
    ];
    protected $useTimestamps = false;
}
