<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMarksAuditTables extends Migration
{
    public function up()
    {
        // 1. Exam Marks Audit Log Table
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
            'component_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'exam_type' => [
                'type'       => 'ENUM',
                'constraint' => ['internal1', 'internal2', 'internal3', 'midterm', 'final', 'practical'],
                'default'    => 'internal1',
            ],
            'action' => [
                'type'       => 'ENUM',
                'constraint' => ['submitted', 'locked', 'unlocked', 'term_closed'],
            ],
            'performed_by_user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'performed_by_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'unlock_reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['org_id', 'subject_id', 'cohort_id']);
        $this->forge->createTable('org_exam_marks_audit', true);
    }

    public function down()
    {
        $this->forge->dropTable('org_exam_marks_audit', true);
    }
}
