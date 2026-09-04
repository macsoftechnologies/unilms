<?php
namespace App\Models;

class DocumentTemplateModel extends BaseModel
{
    protected $table = 'document_templates';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'org_id', 'name', 'category', 'html_content', 'css_styles', 
        'placeholders_json', 'page_size', 'orientation', 'created_by'
    ];
    protected $useTimestamps = true;
}
