<?php

namespace App\Models;

use CodeIgniter\Model;

class CreatorChapterModel extends Model
{
    protected $table            = 'creator_chapters';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'course_id',
        'chapter_title',
        'description',
        'order_seq'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getChaptersByCourse($courseId)
    {
        return $this->where('course_id', $courseId)
                    ->orderBy('order_seq', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }
}
