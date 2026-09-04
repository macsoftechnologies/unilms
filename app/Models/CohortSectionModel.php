<?php
namespace App\Models;

class CohortSectionModel extends BaseModel
{
    protected $table = 'cohort_sections';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'cohort_id', 'name', 'max_students', 'is_active'
    ];
    protected $useTimestamps = true;
}
