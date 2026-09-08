<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

/**
 * Fix missing columns that cause 500 errors on:
 * - Quizzes (lms_quizzes.faculty_user_id)
 * - Parent Attendance (attendance_sessions.session_time)
 * - Parent Fees (fee_structures.name)
 * - Parent Assignments (lms_assignment_submissions.score)
 */
class FixMissingColumnsForUat extends Migration
{
    public function up()
    {
        // 1. lms_quizzes needs faculty_user_id
        if (!$this->db->fieldExists('faculty_user_id', 'lms_quizzes')) {
            $this->forge->addColumn('lms_quizzes', [
                'faculty_user_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'cohort_id'],
            ]);
        }

        // 2. attendance_sessions needs session_time
        if (!$this->db->fieldExists('session_time', 'attendance_sessions')) {
            $this->forge->addColumn('attendance_sessions', [
                'session_time' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true, 'default' => '09:00', 'after' => 'session_date'],
            ]);
        }

        // 3. fee_structures needs name column
        if (!$this->db->fieldExists('name', 'fee_structures')) {
            $this->forge->addColumn('fee_structures', [
                'name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'org_id'],
            ]);
            // Populate name from fee_type + program combo
            $this->db->query("UPDATE fee_structures SET name = CONCAT('Fee Structure #', id) WHERE name IS NULL");
        }

        // 4. lms_assignment_submissions needs score column (alias for marks_obtained)
        if (!$this->db->fieldExists('score', 'lms_assignment_submissions')) {
            $this->forge->addColumn('lms_assignment_submissions', [
                'score' => ['type' => 'DECIMAL', 'constraint' => '8,2', 'null' => true, 'after' => 'marks_obtained'],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('faculty_user_id', 'lms_quizzes')) {
            $this->forge->dropColumn('lms_quizzes', 'faculty_user_id');
        }
        if ($this->db->fieldExists('session_time', 'attendance_sessions')) {
            $this->forge->dropColumn('attendance_sessions', 'session_time');
        }
        if ($this->db->fieldExists('name', 'fee_structures')) {
            $this->forge->dropColumn('fee_structures', 'name');
        }
        if ($this->db->fieldExists('score', 'lms_assignment_submissions')) {
            $this->forge->dropColumn('lms_assignment_submissions', 'score');
        }
    }
}
