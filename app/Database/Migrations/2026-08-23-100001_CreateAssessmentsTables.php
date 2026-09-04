<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAssessmentsTables extends Migration
{
    public function up()
    {
        // 1. LMS Assessments Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'org_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'subject_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'cohort_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'faculty_user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'co_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'description' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'assessment_type' => [
                'type'       => 'ENUM',
                'constraint' => ['file_upload', 'cbt_quiz', 'interactive_video', 'essay'],
                'default'    => 'file_upload',
            ],
            'max_marks' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 100.00,
            ],
            'due_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'is_published' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            // File Upload Type
            'allowed_extensions' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'max_file_size_mb' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 10,
            ],
            'reference_attachment' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
            ],
            // CBT Quiz Type
            'time_limit_mins' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 0,
            ],
            'max_attempts' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 1,
            ],
            'randomize_questions' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'randomize_options' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'score_visibility' => [
                'type'       => 'ENUM',
                'constraint' => ['immediate', 'after_due_date', 'never'],
                'default'    => 'immediate',
            ],
            // Interactive Video Type
            'video_source_type' => [
                'type'       => 'ENUM',
                'constraint' => ['youtube', 'mp4_url'],
                'default'    => 'youtube',
                'null'       => true,
            ],
            'video_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '1000',
                'null'       => true,
            ],
            'video_duration_seconds' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            // Essay Type
            'min_word_count' => [
                'type'       => 'INT',
                'constraint' => 6,
                'default'    => 0,
            ],
            'max_word_count' => [
                'type'       => 'INT',
                'constraint' => 6,
                'default'    => 0,
            ],
            'submission_mode' => [
                'type'       => 'ENUM',
                'constraint' => ['rich_text', 'doc_upload'],
                'default'    => 'rich_text',
            ],
            'allow_late_submissions' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'rubric_criteria' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['org_id', 'subject_id', 'cohort_id']);
        $this->forge->createTable('lms_assessments', true);

        // 2. Assessment Questions (for CBT & Video Assessments)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'assessment_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'co_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'timestamp_seconds' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'question_type' => [
                'type'       => 'ENUM',
                'constraint' => ['mcq', 'true_false'],
                'default'    => 'mcq',
            ],
            'question_text' => [
                'type' => 'LONGTEXT',
            ],
            'points' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 1.00,
            ],
            'options' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'correct_option' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'explanation' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('assessment_id');
        $this->forge->createTable('lms_assessment_questions', true);

        // 3. Assessment Submissions Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'assessment_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'student_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'attempt_number' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 1,
            ],
            'started_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'submitted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'is_late' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'submitted_file' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
            ],
            'submitted_essay' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'essay_word_count' => [
                'type'       => 'INT',
                'constraint' => 6,
                'default'    => 0,
            ],
            'student_comments' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'answers_payload' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'auto_score' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],
            'final_marks' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],
            'rubric_breakdown' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'faculty_feedback' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'graded_by_user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'graded_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['in_progress', 'submitted', 'graded'],
                'default'    => 'submitted',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['assessment_id', 'student_id']);
        $this->forge->createTable('lms_assessment_submissions', true);
    }

    public function down()
    {
        $this->forge->dropTable('lms_assessment_submissions', true);
        $this->forge->dropTable('lms_assessment_questions', true);
        $this->forge->dropTable('lms_assessments', true);
    }
}
