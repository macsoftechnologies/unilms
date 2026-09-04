<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMissingParityTables extends Migration
{
    public function up()
    {
        // 1. visitors
        if (!$this->db->tableExists('visitors')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
                'org_id' => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
                'visitor_name' => ['type' => 'VARCHAR', 'constraint' => 255],
                'phone' => ['type' => 'VARCHAR', 'constraint' => 20],
                'visitor_type' => ['type' => 'ENUM', 'constraint' => ['Parent','Vendor','Guest','Student','Government Officer','Other'], 'default' => 'Guest'],
                'purpose' => ['type' => 'VARCHAR', 'constraint' => 255],
                'person_to_meet' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'department_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'in_time' => ['type' => 'DATETIME', 'null' => true],
                'out_time' => ['type' => 'DATETIME', 'null' => true],
                'pass_number' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                'id_proof' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'remarks' => ['type' => 'TEXT', 'null' => true],
                'created_by' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('org_id');
            $this->forge->createTable('visitors');
        }

        // 2. call_logs
        if (!$this->db->tableExists('call_logs')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
                'org_id' => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
                'call_type' => ['type' => 'ENUM', 'constraint' => ['Incoming','Outgoing'], 'default' => 'Incoming'],
                'caller_name' => ['type' => 'VARCHAR', 'constraint' => 255],
                'phone_number' => ['type' => 'VARCHAR', 'constraint' => 20],
                'purpose' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'remarks' => ['type' => 'TEXT', 'null' => true],
                'call_duration' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                'call_result' => ['type' => 'ENUM', 'constraint' => ['Answered','Missed','Busy','Callback Required'], 'default' => 'Answered'],
                'followup_required' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
                'followup_date' => ['type' => 'DATE', 'null' => true],
                'created_by' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('org_id');
            $this->forge->createTable('call_logs');
        }

        // 3. postal_records
        if (!$this->db->tableExists('postal_records')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
                'org_id' => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
                'record_type' => ['type' => 'ENUM', 'constraint' => ['Receive','Dispatch'], 'default' => 'Receive'],
                'reference_number' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                'sender_receiver_name' => ['type' => 'VARCHAR', 'constraint' => 255],
                'address' => ['type' => 'TEXT', 'null' => true],
                'courier_name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'tracking_number' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'date' => ['type' => 'DATE', 'null' => true],
                'description' => ['type' => 'TEXT', 'null' => true],
                'handled_by' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'attachment' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('org_id');
            $this->forge->createTable('postal_records');
        }

        // 4. admission_enquiries
        if (!$this->db->tableExists('admission_enquiries')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
                'org_id' => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
                'enquiry_number' => ['type' => 'VARCHAR', 'constraint' => 50],
                'student_name' => ['type' => 'VARCHAR', 'constraint' => 255],
                'parent_name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'mobile' => ['type' => 'VARCHAR', 'constraint' => 20],
                'email' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'program_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'enquiry_source' => ['type' => 'ENUM', 'constraint' => ['Website','Walk-In','Phone','Facebook','Instagram','Reference','Education Fair','Other'], 'default' => 'Walk-In'],
                'remarks' => ['type' => 'TEXT', 'null' => true],
                'assigned_counsellor' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'status' => ['type' => 'ENUM', 'constraint' => ['New','Follow Up','Interested','Applied','Closed','Sent to Admin Officer'], 'default' => 'New'],
                'created_by' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('org_id');
            $this->forge->createTable('admission_enquiries');
        }

        // 5. enquiry_followups
        if (!$this->db->tableExists('enquiry_followups')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
                'org_id' => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
                'enquiry_id' => ['type' => 'INT', 'constraint' => 11],
                'notes' => ['type' => 'TEXT', 'null' => true],
                'followup_date' => ['type' => 'DATETIME'],
                'next_followup_date' => ['type' => 'DATETIME', 'null' => true],
                'followed_by' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'followup_status' => ['type' => 'ENUM', 'constraint' => ['Pending','Completed','Missed'], 'default' => 'Pending'],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('enquiry_id');
            $this->forge->createTable('enquiry_followups');
        }

        // 6. placement_drives
        if (!$this->db->tableExists('placement_drives')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
                'org_id' => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
                'company_id' => ['type' => 'INT', 'constraint' => 11],
                'title' => ['type' => 'VARCHAR', 'constraint' => 255],
                'job_role' => ['type' => 'VARCHAR', 'constraint' => 255],
                'description' => ['type' => 'TEXT', 'null' => true],
                'location' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'ctc_details' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'required_cgpa' => ['type' => 'DECIMAL', 'constraint' => '4,2', 'default' => 0.00],
                'max_backlogs' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
                'eligible_programs' => ['type' => 'TEXT', 'null' => true],
                'rounds_json' => ['type' => 'TEXT', 'null' => true],
                'deadline_date' => ['type' => 'DATE', 'null' => true],
                'drive_date' => ['type' => 'DATE', 'null' => true],
                'status' => ['type' => 'ENUM', 'constraint' => ['Upcoming','Active','Completed','Cancelled'], 'default' => 'Upcoming'],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('org_id');
            $this->forge->createTable('placement_drives');
        }

        // 7. placement_applications
        if (!$this->db->tableExists('placement_applications')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
                'org_id' => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
                'drive_id' => ['type' => 'INT', 'constraint' => 11],
                'student_id' => ['type' => 'INT', 'constraint' => 11],
                'resume_file' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'current_round' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => 'Round 1'],
                'status' => ['type' => 'ENUM', 'constraint' => ['Applied','Shortlisted','Interviewing','Offered','Rejected','Accepted'], 'default' => 'Applied'],
                'remarks' => ['type' => 'TEXT', 'null' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('drive_id');
            $this->forge->addKey('student_id');
            $this->forge->createTable('placement_applications');
        }

        // 8. hr_appraisals
        if (!$this->db->tableExists('hr_appraisals')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
                'org_id' => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
                'employee_id' => ['type' => 'INT', 'constraint' => 11],
                'evaluator_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'review_period_start' => ['type' => 'DATE', 'null' => true],
                'review_period_end' => ['type' => 'DATE', 'null' => true],
                'self_score' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
                'reviewer_score' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
                'final_score' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
                'feedback' => ['type' => 'TEXT', 'null' => true],
                'status' => ['type' => 'ENUM', 'constraint' => ['draft','submitted','reviewed','acknowledged'], 'default' => 'draft'],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('org_id');
            $this->forge->addKey('employee_id');
            $this->forge->createTable('hr_appraisals');
        }

        // 9. salary_components
        if (!$this->db->tableExists('salary_components')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
                'org_id' => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
                'name' => ['type' => 'VARCHAR', 'constraint' => 255],
                'component_type' => ['type' => 'ENUM', 'constraint' => ['Earning','Deduction'], 'default' => 'Earning'],
                'calculation_type' => ['type' => 'ENUM', 'constraint' => ['Flat Amount','Percentage'], 'default' => 'Flat Amount'],
                'default_value' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
                'description' => ['type' => 'TEXT', 'null' => true],
                'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('org_id');
            $this->forge->createTable('salary_components');
        }

        // 10. salary_structures
        if (!$this->db->tableExists('salary_structures')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
                'org_id' => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
                'name' => ['type' => 'VARCHAR', 'constraint' => 255],
                'designation_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'base_salary' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
                'description' => ['type' => 'TEXT', 'null' => true],
                'components_json' => ['type' => 'LONGTEXT', 'null' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('org_id');
            $this->forge->createTable('salary_structures');
        }

        // 11. regulations
        if (!$this->db->tableExists('regulations')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
                'org_id' => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
                'name' => ['type' => 'VARCHAR', 'constraint' => 50],
                'description' => ['type' => 'TEXT', 'null' => true],
                'start_year' => ['type' => 'YEAR', 'null' => true],
                'total_credits' => ['type' => 'INT', 'constraint' => 11, 'default' => 160],
                'pass_percentage' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 40.00],
                'grading_scale_json' => ['type' => 'TEXT', 'null' => true],
                'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('org_id');
            $this->forge->createTable('regulations');
        }

        // 12. cohort_sections
        if (!$this->db->tableExists('cohort_sections')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
                'org_id' => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
                'cohort_id' => ['type' => 'INT', 'constraint' => 11],
                'name' => ['type' => 'VARCHAR', 'constraint' => 50],
                'max_students' => ['type' => 'INT', 'constraint' => 11, 'default' => 60],
                'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('cohort_id');
            $this->forge->createTable('cohort_sections');
        }

        // 13. document_templates
        if (!$this->db->tableExists('document_templates')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
                'org_id' => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
                'name' => ['type' => 'VARCHAR', 'constraint' => 255],
                'category' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => 'Certificate'],
                'html_content' => ['type' => 'LONGTEXT', 'null' => true],
                'css_styles' => ['type' => 'LONGTEXT', 'null' => true],
                'placeholders_json' => ['type' => 'TEXT', 'null' => true],
                'page_size' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'A4'],
                'orientation' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'portrait'],
                'created_by' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('org_id');
            $this->forge->createTable('document_templates');
        }
    }

    public function down()
    {
        $tables = [
            'document_templates', 'cohort_sections', 'regulations', 
            'salary_structures', 'salary_components', 'hr_appraisals', 
            'placement_applications', 'placement_drives', 
            'enquiry_followups', 'admission_enquiries', 'postal_records', 
            'call_logs', 'visitors'
        ];
        foreach ($tables as $table) {
            $this->forge->dropTable($table, true);
        }
    }
}
