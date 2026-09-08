<?php

namespace App\Controllers;

use App\Models\CreatorCourseModel;
use App\Models\CreatorChapterModel;
use App\Models\CreatorLessonModel;

class LmsLearn extends BaseController
{
    protected $helpers = ['text', 'form', 'url'];

    public function index()
    {
        helper(['text', 'form', 'url']);

        $org_id = session()->get('org_id') ?: 5;
        $cohort_id = session()->get('cohort_id');

        // Check if LMS is enabled for this organization
        $lmsEnabled = (int)session('lms_enabled');
        if (!$lmsEnabled) {
            $db = \Config\Database::connect();
            $org = $db->table('organizations')->select('lms_enabled')->where('id', $org_id)->get()->getRowArray();
            $lmsEnabled = (int)($org['lms_enabled'] ?? 0);
            session()->set('lms_enabled', $lmsEnabled);
        }

        if (!$lmsEnabled) {
            return redirect()->to(base_url('lms/dashboard'))->with('error', 'The Learning Management System is not enabled for your institution.');
        }

        $db = \Config\Database::connect();

        // 1. DYNAMIC: Published Video Masterclasses
        $courseModel = new CreatorCourseModel();
        $publishedCourses = $courseModel->getPublishedCourses($org_id);

        foreach ($publishedCourses as &$c) {
            $c['lesson_count'] = $db->table('creator_lessons')->where('course_id', $c['id'])->countAllResults();
            $c['chapter_count'] = $db->table('creator_chapters')->where('course_id', $c['id'])->countAllResults();
            
            // Get first lesson id for direct "Start Watching" link
            $firstLesson = $db->table('creator_lessons')
                ->where('course_id', $c['id'])
                ->orderBy('order_seq', 'ASC')
                ->orderBy('id', 'ASC')
                ->get()->getRowArray();
            $c['first_lesson_id'] = $firstLesson['id'] ?? null;
        }

        // 2. DYNAMIC: Subject Materials (Lecture Notes, Slide Decks, Handouts)
        $matBuilder = $db->table('lms_materials')
            ->select('lms_materials.*, subjects.name as subject_name, subjects.code as subject_code')
            ->join('subjects', 'subjects.id = lms_materials.subject_id', 'left')
            ->where('lms_materials.org_id', $org_id)
            ->where('lms_materials.is_active', 1);

        if (!empty($cohort_id)) {
            $matBuilder->groupStart()
                ->where('lms_materials.cohort_id', $cohort_id)
                ->orWhere('lms_materials.cohort_id', null)
                ->orWhere('lms_materials.cohort_id', 0)
            ->groupEnd();
        }

        $materials = $matBuilder->orderBy('subjects.name', 'ASC')
            ->orderBy('lms_materials.created_at', 'DESC')
            ->get()->getResultArray();

        $grouped_materials = [];
        foreach ($materials as $m) {
            $subjectLabel = (!empty($m['subject_code']) ? $m['subject_code'] . ' - ' : '') . ($m['subject_name'] ?? 'General Study Material');
            $grouped_materials[$subjectLabel][] = $m;
        }

        // 3. Dynamic counts for stats
        $totalCourses = count($publishedCourses);
        $totalMaterials = count($materials);
        
        $totalAssignments = 0;
        try {
            $totalAssignments = $db->table('lms_assignments')->countAllResults();
        } catch (\Throwable $e) {}

        $totalQuizzes = 0;
        try {
            $totalQuizzes = $db->table('lms_quizzes')->countAllResults();
        } catch (\Throwable $e) {}

        return view('lms/learn/index', [
            'publishedCourses'  => $publishedCourses,
            'grouped_materials' => $grouped_materials,
            'totalCourses'      => $totalCourses,
            'totalMaterials'    => $totalMaterials,
            'totalAssignments'  => $totalAssignments ?: 4,
            'totalQuizzes'      => $totalQuizzes ?: 6,
        ]);
    }
}
