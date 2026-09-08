<?php

namespace App\Controllers;

use App\Models\MaterialModel;
use App\Models\CreatorCourseModel;
use App\Models\CreatorChapterModel;
use App\Models\CreatorLessonModel;

class LmsMaterials extends BaseController
{
    protected $helpers = ['text', 'form', 'url'];

    public function index()
    {
        helper(['text', 'form', 'url']);
        $org_id = session()->get('org_id') ?: 5;
        $cohort_id = session()->get('cohort_id');

        $db = \Config\Database::connect();
        
        // Fetch subject materials
        $builder = $db->table('lms_materials')
                        ->select('lms_materials.*, subjects.name as subject_name, subjects.code as subject_code')
                        ->join('subjects', 'subjects.id = lms_materials.subject_id', 'left')
                        ->where('lms_materials.org_id', $org_id)
                        ->where('lms_materials.is_active', 1);

        if (!empty($cohort_id)) {
            $builder->groupStart()
                ->where('lms_materials.cohort_id', $cohort_id)
                ->orWhere('lms_materials.cohort_id', null)
                ->orWhere('lms_materials.cohort_id', 0)
            ->groupEnd();
        }

        $materials = $builder->orderBy('subjects.name', 'ASC')
                             ->orderBy('lms_materials.created_at', 'DESC')
                             ->get()->getResultArray();

        $grouped_materials = [];
        foreach($materials as $m) {
            $subjectLabel = (!empty($m['subject_code']) ? $m['subject_code'] . ' - ' : '') . ($m['subject_name'] ?? 'General Study Material');
            $grouped_materials[$subjectLabel][] = $m;
        }

        // Fetch Published Video Courses created by Content Managers
        $courseModel = new CreatorCourseModel();
        $publishedCourses = $courseModel->getPublishedCourses($org_id);

        foreach ($publishedCourses as &$c) {
            $c['lesson_count'] = $db->table('creator_lessons')->where('course_id', $c['id'])->countAllResults();
            $c['chapter_count'] = $db->table('creator_chapters')->where('course_id', $c['id'])->countAllResults();
        }

        return view('lms/materials/index', [
            'grouped_materials' => $grouped_materials,
            'publishedCourses'  => $publishedCourses
        ]);
    }

    public function watchCourse($courseId)
    {
        $courseModel = new CreatorCourseModel();
        $chapterModel = new CreatorChapterModel();
        $lessonModel = new CreatorLessonModel();

        $course = $courseModel->where('status', 'published')->findByIdOrUuid($courseId);
        if (!$course) {
            return redirect()->to('lms/materials')->with('error', 'Course not found or not published.');
        }
        $realCourseId = $course['id'];

        $chapters = $chapterModel->getChaptersByCourse($realCourseId);
        $allLessons = [];
        foreach ($chapters as &$ch) {
            $ch['lessons'] = $lessonModel->getLessonsByChapter($ch['id']);
            foreach ($ch['lessons'] as $les) {
                $allLessons[] = $les;
            }
        }

        $selectedLessonId = (int)$this->request->getGet('lesson') ?: ($allLessons[0]['id'] ?? 0);
        $activeLesson = null;
        foreach ($allLessons as $les) {
            if ($les['id'] == $selectedLessonId) {
                $activeLesson = $les;
                break;
            }
        }

        return view('lms/materials/watch_course', [
            'course'       => $course,
            'chapters'     => $chapters,
            'activeLesson' => $activeLesson ?: ($allLessons[0] ?? null),
            'allLessons'   => $allLessons
        ]);
    }
}
