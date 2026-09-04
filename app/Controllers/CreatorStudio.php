<?php

namespace App\Controllers;

use App\Models\CreatorCourseModel;
use App\Models\CreatorChapterModel;
use App\Models\CreatorLessonModel;

class CreatorStudio extends BaseController
{
    protected $creatorId;
    protected $helpers = ['text', 'form', 'url'];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        helper(['text', 'form', 'url']);
        $this->creatorId = session()->get('creator_id');
    }

    protected function getCourseForCreator($courseId)
    {
        $courseModel = new CreatorCourseModel();
        $course = $courseModel->find($courseId);
        if (!$course) {
            return null;
        }
        $orgId = session()->get('creator_org_id') ?: session()->get('org_id');
        if ($course['creator_id'] == $this->creatorId || (!empty($orgId) && $course['org_id'] == $orgId) || empty($this->creatorId)) {
            return $course;
        }
        return null;
    }

    // Screen 1: Course Overview Dashboard
    public function index()
    {
        return $this->courses();
    }

    public function courses()
    {
        $courseModel = new CreatorCourseModel();
        $orgId = session()->get('creator_org_id') ?: session()->get('org_id');
        $courses = $courseModel->getCoursesByCreator($this->creatorId, $orgId);

        $db = \Config\Database::connect();
        foreach ($courses as &$c) {
            $c['lesson_count'] = $db->table('creator_lessons')->where('course_id', $c['id'])->countAllResults();
            $c['chapter_count'] = $db->table('creator_chapters')->where('course_id', $c['id'])->countAllResults();
        }

        return view('creator/courses', [
            'courses' => $courses
        ]);
    }

    // Screen 2: Course Info Creation & Editing
    public function create()
    {
        $orgModel = new \App\Models\OrganizationModel();
        $organizations = $orgModel->where('status', 'active')->orderBy('name', 'ASC')->findAll();

        return view('creator/create', [
            'course' => null,
            'organizations' => $organizations
        ]);
    }

    public function edit($id)
    {
        $course = $this->getCourseForCreator($id);
        if (!$course) {
            return redirect()->to('/creator/courses')->with('error', 'Course not found or unauthorized.');
        }

        $orgModel = new \App\Models\OrganizationModel();
        $organizations = $orgModel->where('status', 'active')->orderBy('name', 'ASC')->findAll();

        return view('creator/create', [
            'course' => $course,
            'organizations' => $organizations
        ]);
    }

    // Screen: Preview Course exactly as student sees it
    public function preview($courseId)
    {
        $course = $this->getCourseForCreator($courseId);
        if (!$course) {
            return redirect()->to('/creator/courses')->with('error', 'Course not found or unauthorized.');
        }

        $chapterModel = new CreatorChapterModel();
        $lessonModel = new CreatorLessonModel();

        $chapters = $chapterModel->getChaptersByCourse($courseId);
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

        return view('creator/preview', [
            'course'       => $course,
            'chapters'     => $chapters,
            'activeLesson' => $activeLesson ?: ($allLessons[0] ?? null),
            'allLessons'   => $allLessons
        ]);
    }

    public function saveCourse()
    {
        $courseModel = new CreatorCourseModel();
        $id = $this->request->getPost('id');

        $orgId = session()->get('creator_org_id');
        if (empty($orgId)) {
            $postOrgId = $this->request->getPost('org_id');
            $orgId = !empty($postOrgId) ? (int)$postOrgId : 5;
        }

        $data = [
            'creator_id'  => $this->creatorId ?: 1,
            'org_id'      => $orgId,
            'title'       => trim($this->request->getPost('title')),
            'category'    => trim($this->request->getPost('category')),
            'description' => trim($this->request->getPost('description'))
        ];

        // Handle thumbnail upload
        $thumbnailFile = $this->request->getFile('thumbnail_file');
        if ($thumbnailFile && $thumbnailFile->isValid() && !$thumbnailFile->hasMoved()) {
            if (!$this->validate([
                'thumbnail_file' => [
                    'label' => 'Course Thumbnail',
                    'rules' => 'uploaded[thumbnail_file]|is_image[thumbnail_file]|ext_in[thumbnail_file,jpg,jpeg,png,webp]|max_size[thumbnail_file,5120]'
                ]
            ])) {
                return redirect()->back()->withInput()->with('error', $this->validator->getError('thumbnail_file') ?: 'Invalid image format or thumbnail exceeds 5MB.');
            }
            $newName = $thumbnailFile->getRandomName();
            $uploadPath = FCPATH . 'uploads/courses';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $thumbnailFile->move($uploadPath, $newName);
            
            // Also mirror to public/uploads/courses if separate
            $pubPath = ROOTPATH . 'public/uploads/courses';
            if (!is_dir($pubPath)) {
                mkdir($pubPath, 0755, true);
            }
            if (file_exists($uploadPath . '/' . $newName) && !file_exists($pubPath . '/' . $newName)) {
                @copy($uploadPath . '/' . $newName, $pubPath . '/' . $newName);
            }

            $data['thumbnail'] = 'uploads/courses/' . $newName;
        }

        if ($id) {
            $existing = $this->getCourseForCreator($id);
            if (!$existing) {
                return redirect()->to('/creator/courses')->with('error', 'Unauthorized or course not found.');
            }
            $courseModel->update($id, $data);
            $courseId = $id;
            $msg = 'Course information updated.';
        } else {
            $data['status'] = 'draft';
            $courseId = $courseModel->insert($data);
            $msg = 'Course created! Now build your chapters and video lessons in the Studio.';
        }

        return redirect()->to('/creator/courses/builder/' . $courseId)->with('success', $msg);
    }

    // Screen 2: Course & Video Upload Studio Builder
    public function builder($courseId)
    {
        $course = $this->getCourseForCreator($courseId);
        if (!$course) {
            return redirect()->to('/creator/courses')->with('error', 'Course not found or unauthorized.');
        }

        $chapterModel = new CreatorChapterModel();
        $lessonModel = new CreatorLessonModel();

        $chapters = $chapterModel->getChaptersByCourse($courseId);
        foreach ($chapters as &$ch) {
            $ch['lessons'] = $lessonModel->getLessonsByChapter($ch['id']);
        }

        return view('creator/builder', [
            'course'   => $course,
            'chapters' => $chapters
        ]);
    }

    public function saveChapter()
    {
        $courseId = $this->request->getPost('course_id');
        $course = $this->getCourseForCreator($courseId);

        if (!$course) {
            return redirect()->to('/creator/courses')->with('error', 'Unauthorized.');
        }

        $chapterModel = new CreatorChapterModel();
        $chapterId = $this->request->getPost('chapter_id');

        $data = [
            'course_id'     => $courseId,
            'chapter_title' => trim($this->request->getPost('chapter_title')),
            'description'   => trim($this->request->getPost('description') ?: ''),
            'order_seq'     => (int)$this->request->getPost('order_seq')
        ];

        if ($chapterId) {
            $chapterModel->update($chapterId, $data);
            $msg = 'Chapter updated.';
        } else {
            $chapterModel->insert($data);
            $msg = 'New Chapter added to course.';
        }

        return redirect()->to('/creator/courses/builder/' . $courseId)->with('success', $msg);
    }

    public function deleteChapter($chapterId)
    {
        $chapterModel = new CreatorChapterModel();
        $lessonModel = new CreatorLessonModel();
        $chapter = $chapterModel->find($chapterId);

        if ($chapter) {
            $course = $this->getCourseForCreator($chapter['course_id']);
            if ($course) {
                $lessonModel->where('chapter_id', $chapterId)->delete();
                $chapterModel->delete($chapterId);
                return redirect()->to('/creator/courses/builder/' . $course['id'])->with('success', 'Chapter and its lessons deleted.');
            }
        }

        return redirect()->to('/creator/courses')->with('error', 'Unauthorized.');
    }

    public function saveLesson()
    {
        $courseId = $this->request->getPost('course_id');
        $course = $this->getCourseForCreator($courseId);

        if (!$course) {
            return redirect()->to('/creator/courses')->with('error', 'Unauthorized.');
        }

        $courseModel = new CreatorCourseModel();
        $lessonModel = new CreatorLessonModel();
        $lessonId = $this->request->getPost('lesson_id');

        $data = [
            'course_id'        => $courseId,
            'chapter_id'       => (int)$this->request->getPost('chapter_id'),
            'lesson_title'     => trim($this->request->getPost('lesson_title')),
            'video_type'       => $this->request->getPost('video_type') ?: 'url',
            'video_url'        => trim($this->request->getPost('video_url') ?: ''),
            'duration_minutes' => (int)$this->request->getPost('duration_minutes') ?: 10,
            'order_seq'        => (int)$this->request->getPost('order_seq')
        ];

        // Handle video file upload if provided
        $videoFile = $this->request->getFile('video_file');
        if ($videoFile && $videoFile->isValid() && !$videoFile->hasMoved()) {
            if (!$this->validate([
                'video_file' => [
                    'label' => 'Video File',
                    'rules' => 'uploaded[video_file]|ext_in[video_file,mp4,webm,mov,mkv]|max_size[video_file,204800]'
                ]
            ])) {
                return redirect()->back()->withInput()->with('error', $this->validator->getError('video_file') ?: 'Invalid video format or file exceeds 200MB limit.');
            }
            $newName = $videoFile->getRandomName();
            $uploadPath = FCPATH . 'uploads/lessons/videos';
            if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);
            $videoFile->move($uploadPath, $newName);
            
            $pubPath = ROOTPATH . 'public/uploads/lessons/videos';
            if (!is_dir($pubPath)) mkdir($pubPath, 0755, true);
            if (file_exists($uploadPath . '/' . $newName) && !file_exists($pubPath . '/' . $newName)) {
                @copy($uploadPath . '/' . $newName, $pubPath . '/' . $newName);
            }

            $data['video_file'] = 'uploads/lessons/videos/' . $newName;
        }

        // Handle notes/slides PDF upload if provided
        $notesFile = $this->request->getFile('notes_file');
        if ($notesFile && $notesFile->isValid() && !$notesFile->hasMoved()) {
            if (!$this->validate([
                'notes_file' => [
                    'label' => 'Notes / Slides File',
                    'rules' => 'uploaded[notes_file]|ext_in[notes_file,pdf,ppt,pptx,doc,docx,txt,zip]|max_size[notes_file,51200]'
                ]
            ])) {
                return redirect()->back()->withInput()->with('error', $this->validator->getError('notes_file') ?: 'Invalid document format or file exceeds 50MB limit.');
            }
            $newName = $notesFile->getRandomName();
            $uploadPath = FCPATH . 'uploads/lessons/notes';
            if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);
            $notesFile->move($uploadPath, $newName);

            $pubPath = ROOTPATH . 'public/uploads/lessons/notes';
            if (!is_dir($pubPath)) mkdir($pubPath, 0755, true);
            if (file_exists($uploadPath . '/' . $newName) && !file_exists($pubPath . '/' . $newName)) {
                @copy($uploadPath . '/' . $newName, $pubPath . '/' . $newName);
            }

            $data['notes_file'] = 'uploads/lessons/notes/' . $newName;
        }

        if ($lessonId) {
            $lessonModel->update($lessonId, $data);
            $msg = 'Lesson updated.';
        } else {
            $lessonModel->insert($data);
            $msg = 'Lesson added successfully.';
        }

        // Recalculate total course duration
        $db = \Config\Database::connect();
        $totalMins = $db->table('creator_lessons')->where('course_id', $courseId)->selectSum('duration_minutes')->get()->getRow()->duration_minutes ?? 0;
        $courseModel->update($courseId, ['total_duration_minutes' => (int)$totalMins]);

        return redirect()->to('/creator/courses/builder/' . $courseId)->with('success', $msg);
    }

    public function deleteLesson($lessonId)
    {
        $lessonModel = new CreatorLessonModel();
        $lesson = $lessonModel->find($lessonId);

        if ($lesson) {
            $course = $this->getCourseForCreator($lesson['course_id']);
            if ($course) {
                $lessonModel->delete($lessonId);
                return redirect()->to('/creator/courses/builder/' . $course['id'])->with('success', 'Lesson removed.');
            }
        }

        return redirect()->to('/creator/courses')->with('error', 'Unauthorized.');
    }

    // Publish / Unpublish Toggle
    public function togglePublish($courseId)
    {
        $course = $this->getCourseForCreator($courseId);
        if (!$course) {
            return redirect()->to('/creator/courses')->with('error', 'Course not found.');
        }

        $courseModel = new CreatorCourseModel();
        $newStatus = ($course['status'] === 'published') ? 'draft' : 'published';
        $courseModel->update($courseId, ['status' => $newStatus]);

        $msg = ($newStatus === 'published') 
            ? 'Course published! It is now live in the student LMS course catalog.' 
            : 'Course moved back to draft.';

        return redirect()->back()->with('success', $msg);
    }

    public function deleteCourse($courseId)
    {
        $course = $this->getCourseForCreator($courseId);
        if ($course) {
            (new CreatorLessonModel())->where('course_id', $courseId)->delete();
            (new CreatorChapterModel())->where('course_id', $courseId)->delete();
            (new CreatorCourseModel())->delete($courseId);
            return redirect()->to('/creator/courses')->with('success', 'Course deleted.');
        }

        return redirect()->to('/creator/courses')->with('error', 'Unauthorized.');
    }
}
