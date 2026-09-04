<?php

namespace App\Controllers;

use App\Models\QuizModel;
use App\Models\QuizQuestionModel;
use App\Models\QuizOptionModel;
use App\Models\QuizAttemptModel;
use App\Models\SubjectModel;
use App\Models\CohortModel;

class OrgQuizzes extends BaseController
{
    public function index()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        
        $quizModel = new QuizModel();
        
        if (session('is_org_admin') || $this->hasPermission('manage_academics_global')) {
            $quizzes = $quizModel->getQuizzesByOrg($this->org_id);
        } else {
            $quizzes = $quizModel->where('org_id', $this->org_id)
                                 ->where('faculty_user_id', $this->org_user_id)
                                 ->findAll();
            // Manually attach subject and cohort names for faculty
            $subjectModel = new SubjectModel();
            $cohortModel = new CohortModel();
            foreach($quizzes as &$q) {
                $subj = $subjectModel->find($q['subject_id']);
                $coh = $cohortModel->find($q['cohort_id']);
                $q['subject_name'] = $subj ? $subj['name'] : '';
                $q['subject_code'] = $subj ? $subj['code'] : '';
                $q['cohort_name'] = $coh ? $coh['name'] : '';
                $q['full_name'] = session('org_user_name');
            }
        }

        return view('org/quizzes/index', ['quizzes' => $quizzes]);
    }

    public function create()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $subjectModel = new SubjectModel();
        $cohortModel = new CohortModel();

        $subjects = $subjectModel->where('org_id', $this->org_id)->findAll();
        $cohorts = $cohortModel->where('org_id', $this->org_id)->findAll();

        return view('org/quizzes/form', [
            'subjects' => $subjects,
            'cohorts' => $cohorts,
            'quiz' => null
        ]);
    }

    public function edit($id)
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $quizModel = new QuizModel();
        $quiz = $quizModel->where('org_id', $this->org_id)->find($id);

        if (!$quiz) {
            return redirect()->to('org/quizzes')->with('error', 'Quiz not found.');
        }

        $subjectModel = new SubjectModel();
        $cohortModel = new CohortModel();

        $subjects = $subjectModel->where('org_id', $this->org_id)->findAll();
        $cohorts = $cohortModel->where('org_id', $this->org_id)->findAll();

        return view('org/quizzes/form', [
            'subjects' => $subjects,
            'cohorts' => $cohorts,
            'quiz' => $quiz
        ]);
    }

    public function save()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $quizModel = new QuizModel();
        $id = $this->request->getPost('quiz_id');

        $data = [
            'org_id' => $this->org_id,
            'subject_id' => $this->request->getPost('subject_id'),
            'cohort_id' => $this->request->getPost('cohort_id'),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'time_limit_minutes' => $this->request->getPost('time_limit_minutes') ?: 0,
            'max_attempts' => $this->request->getPost('max_attempts') ?: 1,
            'start_date' => $this->request->getPost('start_date') ?: null,
            'end_date' => $this->request->getPost('end_date') ?: null,
            'is_published' => $this->request->getPost('is_published') ? 1 : 0
        ];

        if ($id) {
            $quizModel->update($id, $data);
            return redirect()->to('org/quizzes/questions/' . $id)->with('success', 'Quiz settings updated. You can now build questions.');
        } else {
            $data['faculty_user_id'] = $this->org_user_id;
            $new_id = $quizModel->insert($data);
            return redirect()->to('org/quizzes/questions/' . $new_id)->with('success', 'Quiz created successfully. Start adding questions.');
        }
    }

    public function delete($id)
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');
        
        $quizModel = new QuizModel();
        $quiz = $quizModel->where('org_id', $this->org_id)->find($id);
        if ($quiz) {
            $quizModel->delete($id);
        }
        return redirect()->to('org/quizzes')->with('success', 'Quiz deleted.');
    }

    // --- QUESTION BUILDER ---

    public function questions($quiz_id)
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $quizModel = new QuizModel();
        $quiz = $quizModel->where('org_id', $this->org_id)->find($quiz_id);

        if (!$quiz) return redirect()->to('org/quizzes');

        $questionModel = new QuizQuestionModel();
        $optionModel = new QuizOptionModel();

        $questions = $questionModel->where('quiz_id', $quiz_id)->orderBy('order_index', 'ASC')->findAll();
        
        foreach($questions as &$q) {
            $q['options'] = $optionModel->where('question_id', $q['id'])->orderBy('order_index', 'ASC')->findAll();
        }

        return view('org/quizzes/builder', [
            'quiz' => $quiz,
            'questions' => $questions
        ]);
    }

    public function saveQuestion()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $quiz_id = $this->request->getPost('quiz_id');
        $question_text = $this->request->getPost('question_text');
        $marks = $this->request->getPost('marks');
        $options = $this->request->getPost('options'); // array of texts
        $correct_option_index = $this->request->getPost('correct_option'); // index of the correct option

        $questionModel = new QuizQuestionModel();
        $optionModel = new QuizOptionModel();

        $q_id = $questionModel->insert([
            'org_id' => $this->org_id,
            'quiz_id' => $quiz_id,
            'question_text' => $question_text,
            'question_type' => 'mcq',
            'marks' => $marks,
            'order_index' => $questionModel->where('quiz_id', $quiz_id)->countAllResults() + 1
        ]);

        if (is_array($options)) {
            foreach($options as $idx => $opt_text) {
                if (trim($opt_text) !== '') {
                    $optionModel->insert([
                        'org_id' => $this->org_id,
                        'question_id' => $q_id,
                        'option_text' => trim($opt_text),
                        'is_correct' => ($idx == $correct_option_index) ? 1 : 0,
                        'order_index' => $idx
                    ]);
                }
            }
        }

        return redirect()->to('org/quizzes/questions/' . $quiz_id)->with('success', 'Question added.');
    }

    public function deleteQuestion($id)
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $questionModel = new QuizQuestionModel();
        $q = $questionModel->where('org_id', $this->org_id)->find($id);
        if ($q) {
            $questionModel->delete($id);
            return redirect()->back()->with('success', 'Question removed.');
        }
        return redirect()->back();
    }

    // --- QUIZ RESULTS ---
    
    public function results($quiz_id)
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $quizModel = new QuizModel();
        $quiz = $quizModel->where('org_id', $this->org_id)->find($quiz_id);

        if (!$quiz) return redirect()->to('org/quizzes');

        $subjectModel = new SubjectModel();
        $cohortModel = new CohortModel();
        $subj = $subjectModel->find($quiz['subject_id']);
        $coh = $cohortModel->find($quiz['cohort_id']);
        $quiz['subject_name'] = $subj ? $subj['name'] : '';
        $quiz['cohort_name'] = $coh ? $coh['name'] : '';

        $db = \Config\Database::connect();
        $attempts = $db->table('lms_quiz_attempts a')
                       ->select('a.*, s.roll_number, s.first_name, s.last_name')
                       ->join('students s', 's.id = a.student_id')
                       ->where('a.org_id', $this->org_id)
                       ->where('a.quiz_id', $quiz_id)
                       ->orderBy('a.start_time', 'DESC')
                       ->get()->getResultArray();

        return view('org/quizzes/results', [
            'quiz' => $quiz,
            'attempts' => $attempts
        ]);
    }
}
