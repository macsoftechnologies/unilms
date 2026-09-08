<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixHrAppraisalsAndPayrollColumns extends Migration
{
    public function up()
    {
        // 1. hr_appraisals column fixes
        if ($this->db->tableExists('hr_appraisals')) {
            $fieldsToAdd = [];
            if (!$this->db->fieldExists('reviewed_by', 'hr_appraisals')) {
                $fieldsToAdd['reviewed_by'] = ['type' => 'INT', 'constraint' => 11, 'null' => true];
            }
            if (!$this->db->fieldExists('academic_year_id', 'hr_appraisals')) {
                $fieldsToAdd['academic_year_id'] = ['type' => 'INT', 'constraint' => 11, 'null' => true];
            }
            if (!$this->db->fieldExists('appraisal_period', 'hr_appraisals')) {
                $fieldsToAdd['appraisal_period'] = ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true];
            }
            if (!$this->db->fieldExists('self_rating', 'hr_appraisals')) {
                $fieldsToAdd['self_rating'] = ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00];
            }
            if (!$this->db->fieldExists('hod_rating', 'hr_appraisals')) {
                $fieldsToAdd['hod_rating'] = ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00];
            }
            if (!$this->db->fieldExists('review_date', 'hr_appraisals')) {
                $fieldsToAdd['review_date'] = ['type' => 'DATE', 'null' => true];
            }
            if (!$this->db->fieldExists('strengths', 'hr_appraisals')) {
                $fieldsToAdd['strengths'] = ['type' => 'TEXT', 'null' => true];
            }
            if (!$this->db->fieldExists('areas_for_improvement', 'hr_appraisals')) {
                $fieldsToAdd['areas_for_improvement'] = ['type' => 'TEXT', 'null' => true];
            }
            if (!$this->db->fieldExists('recommendation', 'hr_appraisals')) {
                $fieldsToAdd['recommendation'] = ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true];
            }

            if (!empty($fieldsToAdd)) {
                $this->forge->addColumn('hr_appraisals', $fieldsToAdd);
            }

            // Modify status column from ENUM to VARCHAR to allow custom statuses like 'Submitted'
            $this->forge->modifyColumn('hr_appraisals', [
                'status' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'default'    => 'Submitted'
                ]
            ]);
        }

        // 2. salary_components column fixes
        if ($this->db->tableExists('salary_components')) {
            $compFieldsToAdd = [];
            if (!$this->db->fieldExists('code', 'salary_components')) {
                $compFieldsToAdd['code'] = ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true];
            }
            if (!$this->db->fieldExists('type', 'salary_components')) {
                $compFieldsToAdd['type'] = ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'Earning'];
            }
            if (!$this->db->fieldExists('percentage_of', 'salary_components')) {
                $compFieldsToAdd['percentage_of'] = ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true];
            }
            if (!$this->db->fieldExists('default_amount', 'salary_components')) {
                $compFieldsToAdd['default_amount'] = ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00];
            }
            if (!$this->db->fieldExists('is_taxable', 'salary_components')) {
                $compFieldsToAdd['is_taxable'] = ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1];
            }

            if (!empty($compFieldsToAdd)) {
                $this->forge->addColumn('salary_components', $compFieldsToAdd);
            }
        }

        // 3. salary_structures column fixes
        if ($this->db->tableExists('salary_structures')) {
            $structFieldsToAdd = [];
            if (!$this->db->fieldExists('basic_salary', 'salary_structures')) {
                $structFieldsToAdd['basic_salary'] = ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00];
            }
            if (!$this->db->fieldExists('total_earnings', 'salary_structures')) {
                $structFieldsToAdd['total_earnings'] = ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00];
            }
            if (!$this->db->fieldExists('total_deductions', 'salary_structures')) {
                $structFieldsToAdd['total_deductions'] = ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00];
            }
            if (!$this->db->fieldExists('net_salary', 'salary_structures')) {
                $structFieldsToAdd['net_salary'] = ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00];
            }
            if (!$this->db->fieldExists('status', 'salary_structures')) {
                $structFieldsToAdd['status'] = ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'Active'];
            }

            if (!empty($structFieldsToAdd)) {
                $this->forge->addColumn('salary_structures', $structFieldsToAdd);
            }
        }
    }

    public function down()
    {
        // Drop added columns
        if ($this->db->tableExists('hr_appraisals')) {
            $hrCols = ['reviewed_by', 'academic_year_id', 'appraisal_period', 'self_rating', 'hod_rating', 'review_date', 'strengths', 'areas_for_improvement', 'recommendation'];
            foreach ($hrCols as $col) {
                if ($this->db->fieldExists($col, 'hr_appraisals')) {
                    $this->forge->dropColumn('hr_appraisals', $col);
                }
            }
        }

        if ($this->db->tableExists('salary_components')) {
            $compCols = ['code', 'type', 'percentage_of', 'default_amount', 'is_taxable'];
            foreach ($compCols as $col) {
                if ($this->db->fieldExists($col, 'salary_components')) {
                    $this->forge->dropColumn('salary_components', $col);
                }
            }
        }

        if ($this->db->tableExists('salary_structures')) {
            $structCols = ['basic_salary', 'total_earnings', 'total_deductions', 'net_salary', 'status'];
            foreach ($structCols as $col) {
                if ($this->db->fieldExists($col, 'salary_structures')) {
                    $this->forge->dropColumn('salary_structures', $col);
                }
            }
        }
    }
}
