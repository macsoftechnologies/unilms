<?php

namespace App\Models;

use CodeIgniter\Model;

class CreatorLessonModel extends Model
{
    protected $table            = 'creator_lessons';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'course_id',
        'chapter_id',
        'lesson_title',
        'video_type',
        'video_url',
        'video_file',
        'notes_file',
        'duration_minutes',
        'order_seq'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getLessonsByChapter($chapterId)
    {
        return $this->where('chapter_id', $chapterId)
                    ->orderBy('order_seq', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }

    public function getLessonsByCourse($courseId)
    {
        return $this->where('course_id', $courseId)
                    ->orderBy('order_seq', 'ASC')
                    ->findAll();
    }
}
