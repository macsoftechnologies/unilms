<?php

namespace App\Models;

use CodeIgniter\Model;

class CreatorCourseModel extends Model
{
    protected $table            = 'creator_courses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'org_id',
        'creator_id',
        'title',
        'category',
        'description',
        'thumbnail',
        'status',
        'total_duration_minutes'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getCoursesByCreator($creatorId = null, $orgId = null)
    {
        $builder = $this->builder();
        if (!empty($creatorId) && !empty($orgId)) {
            $builder->groupStart()
                    ->where('creator_id', $creatorId)
                    ->orWhere('org_id', $orgId)
                    ->groupEnd();
        } elseif (!empty($orgId)) {
            $builder->where('org_id', $orgId);
        } elseif (!empty($creatorId)) {
            $builder->where('creator_id', $creatorId);
        }
        return $builder->orderBy('created_at', 'DESC')->get()->getResultArray();
    }

    public function getPublishedCourses($orgId = null)
    {
        $builder = $this->where('status', 'published');
        if ($orgId) {
            $builder->groupStart()
                    ->where('org_id', $orgId)
                    ->orWhere('org_id', null)
                    ->groupEnd();
        }
        return $builder->orderBy('created_at', 'DESC')->findAll();
    }
}
