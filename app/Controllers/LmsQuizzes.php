<?php

namespace App\Controllers;

use App\Models\QuizModel;
use App\Models\QuizQuestionModel;
use App\Models\QuizOptionModel;
use App\Models\QuizAttemptModel;
use App\Models\QuizAnswerModel;
use App\Models\SubjectModel;

class LmsQuizzes extends BaseController
{
    public function index()
    {
        $quizModel = new QuizModel();
        $attemptModel = new QuizAttemptModel();
        $subjectModel = new SubjectModel();
        
        $db = \Config\Database::connect();
        
        $orgId = $this->org_id ?: (session('org_id') ?: 5);
        $cohortId = session('cohort_id') ?: (session('lms_cohort_id') ?: 1);
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        
        // Find quizzes assigned to this student's cohort
        $builder = $db->table('lms_quizzes q')
                      ->select('q.*, s.name as subject_name, s.code as subject_code')
                      ->join('subjects s', 's.id = q.subject_id', 'left')
                      ->where('q.org_id', $orgId)
                      ->where('q.is_published', 1);

        if ($cohortId) {
            $builder->groupStart()
                    ->where('q.cohort_id', $cohortId)
                    ->orWhere('q.cohort_id', null)
                    ->orWhere('q.cohort_id', 0)
                    ->groupEnd();
        }

        $quizzes = $builder->orderBy('q.created_at', 'DESC')->get()->getResultArray();
                      
        // Map attempts
        foreach($quizzes as &$q) {
            $q['max_attempts'] = isset($q['max_attempts']) ? (int)$q['max_attempts'] : 3;
            $attempts = $attemptModel->where('org_id', $orgId)
                                     ->where('quiz_id', $q['id'])
                                     ->where('student_id', $studentId)
                                     ->orderBy('id', 'DESC')
                                     ->findAll() ?: [];
                                     
            $q['attempts'] = $attempts;
            $q['has_active_attempt'] = false;
            $q['can_attempt'] = count($attempts) < $q['max_attempts'];
            
            foreach($attempts as $a) {
                if (($a['status'] ?? '') === 'in_progress') {
                    $q['has_active_attempt'] = true;
                    $q['active_attempt_id'] = !empty($a['uuid']) ? $a['uuid'] : $a['id'];
                    $q['active_attempt_uuid'] = !empty($a['uuid']) ? $a['uuid'] : $a['id'];
                    break;
                }
            }
        }

        return view('lms/quizzes/index', ['quizzes' => $quizzes]);
    }

    public function start($quiz_id)
    {
        $quizModel = new QuizModel();
        $attemptModel = new QuizAttemptModel();
        
        $orgId = session('org_id') ?: (session('lms_org_id') ?: 5);
        $cohortId = session('cohort_id') ?: (session('lms_cohort_id') ?: (session('student_cohort_id') ?: 1));
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        
        $quiz = $quizModel->where('org_id', $orgId)
                          ->where('is_published', 1)
                          ->findByIdOrUuid($quiz_id);
                          
        if (!$quiz) return redirect()->to('lms/quizzes')->with('error', 'Quiz not found.');
        $realQuizId = $quiz['id'];
        
        // Check for active attempt
        $active_attempt = $attemptModel->where('org_id', $orgId)
                                       ->where('quiz_id', $realQuizId)
                                       ->where('student_id', $studentId)
                                       ->where('status', 'in_progress')
                                       ->first();
                                       
        if ($active_attempt) {
            return redirect()->to('lms/quizzes/exam/' . ($active_attempt['uuid'] ?? $active_attempt['id']));
        }
        
        // Check max attempts
        $attempt_count = $attemptModel->where('org_id', $orgId)
                                      ->where('quiz_id', $realQuizId)
                                      ->where('student_id', $studentId)
                                      ->countAllResults();
                                      
        $maxAttempts = isset($quiz['max_attempts']) ? (int)$quiz['max_attempts'] : 3;
        if ($attempt_count >= $maxAttempts) {
            return redirect()->to('lms/quizzes')->with('error', 'Maximum attempts reached.');
        }
        
        // Create new attempt (BaseModel automatically assigns uuid_v7)
        $attempt_id = $attemptModel->insert([
            'org_id' => $orgId,
            'quiz_id' => $realQuizId,
            'student_id' => $studentId,
            'started_at' => date('Y-m-d H:i:s'),
            'start_time' => date('Y-m-d H:i:s'),
            'status' => 'in_progress'
        ]);
        
        $newAttempt = $attemptModel->find($attempt_id);
        $attemptIdentifier = !empty($newAttempt['uuid']) ? $newAttempt['uuid'] : $attempt_id;
        
        return redirect()->to('lms/quizzes/exam/' . $attemptIdentifier);
    }

    public function exam($attempt_id)
    {
        $attemptModel = new QuizAttemptModel();
        $quizModel = new QuizModel();
        $questionModel = new QuizQuestionModel();
        $optionModel = new QuizOptionModel();
        $answerModel = new QuizAnswerModel();
        
        $orgId = session('org_id') ?: (session('lms_org_id') ?: 5);
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        
        $attempt = $attemptModel->where('org_id', $orgId)
                                ->where('student_id', $studentId)
                                ->findByIdOrUuid($attempt_id);
                                
        if (!$attempt) return redirect()->to('lms/quizzes');
        $realAttemptId = $attempt['id'];
        
        if (($attempt['status'] ?? '') !== 'in_progress') {
            return redirect()->to('lms/quizzes')->with('success', 'This attempt has already been submitted.');
        }
        
        $quiz = $quizModel->find($attempt['quiz_id']);
        
        // Check time limit
        if (!empty($quiz['time_limit_minutes']) && $quiz['time_limit_minutes'] > 0) {
            $start_time = strtotime($attempt['start_time'] ?? $attempt['started_at'] ?? date('Y-m-d H:i:s'));
            $current_time = time();
            $allowed_time = $quiz['time_limit_minutes'] * 60;
            
            $time_remaining = $allowed_time - ($current_time - $start_time);
            
            if ($time_remaining <= 0) {
                // Auto submit
                return $this->submitExam($realAttemptId, true);
            }
        } else {
            $time_remaining = 0;
        }

        $db = \Config\Database::connect();
        $questions = $db->table('lms_quiz_questions')
                        ->where('quiz_id', $quiz['id'])
                        ->get()->getResultArray();

        $saved_answers = $db->table('lms_quiz_answers')
                            ->where('attempt_id', $realAttemptId)
                            ->get()->getResultArray();
        
        $answers_map = [];
        foreach($saved_answers as $ans) {
            $answers_map[$ans['question_id']] = $ans['selected_option_id'];
        }

        foreach($questions as &$q) {
            $q['options'] = $db->table('lms_quiz_options')
                              ->where('question_id', $q['id'])
                              ->get()->getResultArray();
            $q['saved_option_id'] = $answers_map[$q['id']] ?? null;
        }

        return view('lms/quizzes/exam', [
            'quiz' => $quiz,
            'attempt' => $attempt,
            'questions' => $questions,
            'time_remaining' => $time_remaining
        ]);
    }

    public function saveAnswer()
    {
        $attempt_id = $this->request->getPost('attempt_id');
        $question_id = $this->request->getPost('question_id');
        $option_id = $this->request->getPost('option_id');
        
        $orgId = session('org_id') ?: (session('lms_org_id') ?: 5);
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        
        $db = \Config\Database::connect();
        $builder = $db->table('lms_quiz_attempts')
                      ->where('student_id', $studentId)
                      ->where('status', 'in_progress');
        if (is_uuid($attempt_id)) {
            $builder->where('uuid', $attempt_id);
        } else {
            $builder->where('id', $attempt_id);
        }
        $attempt = $builder->get()->getRowArray();
                                
        if (!$attempt) return $this->response->setJSON(['success' => false]);
        $realAttemptId = $attempt['id'];
        
        $existing = $db->table('lms_quiz_answers')
                       ->where('attempt_id', $realAttemptId)
                       ->where('question_id', $question_id)
                       ->get()->getRowArray();
                                 
        if ($existing) {
            $db->table('lms_quiz_answers')
               ->where('id', $existing['id'])
               ->update(['selected_option_id' => $option_id]);
        } else {
            $db->table('lms_quiz_answers')->insert([
                'attempt_id' => $realAttemptId,
                'question_id' => $question_id,
                'selected_option_id' => $option_id
            ]);
        }
        
        return $this->response->setJSON(['success' => true]);
    }

    public function submit()
    {
        $attempt_id = $this->request->getPost('attempt_id');
        return $this->submitExam($attempt_id, false);
    }

    private function submitExam($attempt_id, $auto_submit = false)
    {
        $orgId = session('org_id') ?: (session('lms_org_id') ?: 5);
        $studentId = session('student_id') ?: (session('lms_student_id') ?: 3);
        
        $db = \Config\Database::connect();
        $builder = $db->table('lms_quiz_attempts')
                      ->where('student_id', $studentId);
        if (is_uuid($attempt_id)) {
            $builder->where('uuid', $attempt_id);
        } else {
            $builder->where('id', $attempt_id);
        }
        $attempt = $builder->get()->getRowArray();
                                 
        if (!$attempt || ($attempt['status'] ?? '') !== 'in_progress') {
            return redirect()->to('lms/quizzes');
        }
        $realAttemptId = $attempt['id'];

        // Calculate score
        $questions = $db->table('lms_quiz_questions')->where('quiz_id', $attempt['quiz_id'])->get()->getResultArray();
        $answers = $db->table('lms_quiz_answers')->where('attempt_id', $realAttemptId)->get()->getResultArray();
        
        $answers_map = [];
        foreach($answers as $ans) {
            $answers_map[$ans['question_id']] = $ans;
        }
        
        $total_score = 0;
        $max_score = 0;
        
        foreach($questions as $q) {
            $qMarks = (float)($q['marks'] ?? 5);
            $max_score += $qMarks;
            
            if (isset($answers_map[$q['id']])) {
                $ans = $answers_map[$q['id']];
                $selected_option = $db->table('lms_quiz_options')->where('id', $ans['selected_option_id'])->get()->getRowArray();
                
                if ($selected_option && !empty($selected_option['is_correct'])) {
                    $total_score += $qMarks;
                    $db->table('lms_quiz_answers')->where('id', $ans['id'])->update([
                        'is_correct' => 1,
                        'marks_awarded' => $qMarks
                    ]);
                } else {
                    $db->table('lms_quiz_answers')->where('id', $ans['id'])->update([
                        'is_correct' => 0,
                        'marks_awarded' => 0
                    ]);
                }
            }
        }
        
        $db->table('lms_quiz_attempts')->where('id', $realAttemptId)->update([
            'status' => $auto_submit ? 'auto_submitted' : 'completed',
            'submitted_at' => date('Y-m-d H:i:s'),
            'end_time' => date('Y-m-d H:i:s'),
            'score' => $total_score,
            'score_obtained' => $total_score,
            'max_score' => $max_score
        ]);
        
        return redirect()->to('lms/quizzes')->with('success', 'Exam submitted successfully! You scored ' . $total_score . ' out of ' . $max_score);
    }
}

