<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInternshipTables extends Migration
{
    public function up()
    {
        // 1. Internship Postings
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
            'company_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'role_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'description' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'required_skills' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
            ],
            'work_mode' => [
                'type'       => 'ENUM',
                'constraint' => ['onsite', 'remote', 'hybrid'],
                'default'    => 'onsite',
            ],
            'location' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'stipend_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'total_seats' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 1,
            ],
            'available_seats' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 1,
            ],
            'min_cgpa' => [
                'type'       => 'DECIMAL',
                'constraint' => '3,2',
                'default'    => 0.00,
            ],
            'target_department_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'application_deadline' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'start_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'end_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'allow_company_tasks' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'published', 'closed'],
                'default'    => 'draft',
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
        $this->forge->addKey('org_id');
        $this->forge->createTable('internship_postings', true);

        // 2. Internship Milestones
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'posting_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'milestone_number' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 1,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_midpoint_gate' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
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
        $this->forge->addKey('posting_id');
        $this->forge->createTable('internship_milestones', true);

        // 3. Internship Tasks
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'milestone_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'task_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'description' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'expected_output' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'estimated_hours' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
            ],
            'submission_type' => [
                'type'       => 'ENUM',
                'constraint' => ['file', 'link', 'text', 'video'],
                'default'    => 'file',
            ],
            'is_company_added' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'is_faculty_approved' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 4,
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
        $this->forge->addKey('milestone_id');
        $this->forge->createTable('internship_tasks', true);

        // 4. Internship Enrollments & Evaluation Gates
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'posting_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'student_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'faculty_mentor_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'supervisor_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'supervisor_email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'supervisor_magic_token' => [
                'type'       => 'VARCHAR',
                'constraint' => '128',
                'null'       => true,
            ],
            'token_expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'resume_file' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
            ],
            'cover_letter' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'offer_sent_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'offer_valid_until' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['applied', 'shortlisted', 'rejected', 'offered', 'in_progress', 'completed', 'cancelled'],
                'default'    => 'applied',
            ],
            'rejection_reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            // Midpoint Review
            'student_mid_review' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'supervisor_mid_review' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'supervisor_mid_rating' => [
                'type'       => 'DECIMAL',
                'constraint' => '3,1',
                'null'       => true,
            ],
            'faculty_mid_review_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'faculty_mid_approved' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            // 6-Dimension Rubric
            'rubric_technical' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
            ],
            'rubric_communication' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
            ],
            'rubric_discipline' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
            ],
            'rubric_problem_solving' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
            ],
            'rubric_quality_output' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
            ],
            'rubric_attendance' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
            ],
            'viva_marks' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
            ],
            'report_marks' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
            ],
            'total_weighted_grade' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'faculty_final_remarks' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            // Corporate Final Sign-off
            'supervisor_final_signoff' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            // Certificate & QR Verification
            'certificate_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'certificate_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'certificate_issued_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'certificate_revoked' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'revocation_reason' => [
                'type' => 'TEXT',
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
        $this->forge->addKey(['posting_id', 'student_id']);
        $this->forge->addKey('supervisor_magic_token');
        $this->forge->createTable('internship_enrollments', true);

        // 5. Internship Task Submissions (with Work Timer)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'enrollment_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'task_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tracked_time_seconds' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'submission_text' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'submission_link' => [
                'type'       => 'VARCHAR',
                'constraint' => '1000',
                'null'       => true,
            ],
            'submission_file' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
            ],
            'student_submitted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'faculty_status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected', 'reopened'],
                'default'    => 'pending',
            ],
            'faculty_feedback' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'faculty_approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'supervisor_status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'signed_off'],
                'default'    => 'pending',
            ],
            'supervisor_feedback' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'supervisor_signed_at' => [
                'type' => 'DATETIME',
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
        $this->forge->addKey(['enrollment_id', 'task_id']);
        $this->forge->createTable('internship_task_submissions', true);

        // 6. Comments Thread for Tasks
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'submission_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'author_type' => [
                'type'       => 'ENUM',
                'constraint' => ['student', 'faculty', 'supervisor'],
            ],
            'author_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'author_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('submission_id');
        $this->forge->createTable('internship_comments', true);
    }

    public function down()
    {
        $this->forge->dropTable('internship_comments', true);
        $this->forge->dropTable('internship_task_submissions', true);
        $this->forge->dropTable('internship_enrollments', true);
        $this->forge->dropTable('internship_tasks', true);
        $this->forge->dropTable('internship_milestones', true);
        $this->forge->dropTable('internship_postings', true);
    }
}
