<?php

namespace App\Libraries;

use App\Models\AccessGroupModel;
use App\Models\PermissionModel;

class RbacService
{
    /**
     * Define the 9 Standard University Roles and their Default Permissions
     */
    public static function getDefaultRolesDefinition(): array
    {
        return [
            'Admin / College Administrator' => [
                'description' => 'Full administrative control across all CMS and LMS modules, academic structures, fee configurations, examinations, and settings.',
                'all' => true,
                'permissions' => [] // full wildcard
            ],

            'HOD (Head of Department)' => [
                'description' => 'Department leadership, faculty allocations, OBE PO/CO approvals, marks verification/locking, department attendance monitor, and timetable publishing.',
                'all' => false,
                'permissions' => [
                    // Academics
                    'cms.academics.academic_structure.view',
                    'cms.academics.academic_structure.edit',
                    'cms.academics.faculty_workload.view',
                    'cms.academics.faculty_workload.edit',
                    'cms.academics.attendance.view',
                    'cms.academics.attendance.edit',
                    'cms.academics.attendance.reports',
                    'cms.academics.marks_entry.view',
                    'cms.academics.marks_entry.edit',
                    'cms.academics.marks_entry.lock',
                    'cms.academics.marks_entry.unlock',
                    'cms.academics.obe_framework.view',
                    'cms.academics.obe_framework.edit',
                    'cms.academics.obe_framework.approve',
                    'cms.academics.assignments_materials.view',
                    'cms.academics.assignments_materials.add',
                    'cms.academics.assignments_materials.edit',
                    'cms.academics.quizzes.view',
                    'cms.academics.quizzes.add',
                    'cms.academics.quizzes.edit',
                    'cms.academics.timetable.view',
                    'cms.academics.timetable.edit',
                    'cms.academics.timetable.publish',
                    // Staff & Appraisals
                    'cms.staff.appraisals.view',
                    'cms.staff.attendance.view',
                    'cms.staff.leave.view',
                    'cms.staff.leave.approve',
                    'cms.staff.leave.reject'
                ]
            ],

            'Faculty / Teaching Staff' => [
                'description' => 'Daily academic teaching, period attendance marking, Course Outcomes (CO) setup & mapping, internal marks entry, assignment creation, study material uploads, and online quizzes.',
                'all' => false,
                'permissions' => [
                    'cms.academics.academic_structure.view',
                    'cms.academics.faculty_workload.view',
                    'cms.academics.attendance.view',
                    'cms.academics.attendance.mark',
                    'cms.academics.marks_entry.view',
                    'cms.academics.marks_entry.add',
                    'cms.academics.marks_entry.edit',
                    'cms.academics.obe_framework.view',
                    'cms.academics.obe_framework.edit',
                    'cms.academics.assignments_materials.view',
                    'cms.academics.assignments_materials.add',
                    'cms.academics.assignments_materials.edit',
                    'cms.academics.quizzes.view',
                    'cms.academics.quizzes.add',
                    'cms.academics.quizzes.edit',
                    'cms.academics.timetable.view',
                    'cms.staff.leave.request',
                    'cms.staff.leave.view'
                ]
            ],

            'Class Teacher / Mentor' => [
                'description' => 'Faculty duties + Class attendance telemetry, defaulter monitoring, parent circulars/notifications, and student progress oversight.',
                'all' => false,
                'permissions' => [
                    // Faculty base
                    'cms.academics.academic_structure.view',
                    'cms.academics.faculty_workload.view',
                    'cms.academics.attendance.view',
                    'cms.academics.attendance.mark',
                    'cms.academics.attendance.edit',
                    'cms.academics.attendance.reports',
                    'cms.academics.marks_entry.view',
                    'cms.academics.marks_entry.add',
                    'cms.academics.marks_entry.edit',
                    'cms.academics.obe_framework.view',
                    'cms.academics.obe_framework.edit',
                    'cms.academics.assignments_materials.view',
                    'cms.academics.assignments_materials.add',
                    'cms.academics.assignments_materials.edit',
                    'cms.academics.quizzes.view',
                    'cms.academics.quizzes.add',
                    'cms.academics.quizzes.edit',
                    'cms.academics.timetable.view',
                    'cms.staff.leave.request',
                    'cms.staff.leave.view',
                    // Class teacher extras
                    'cms.administration.notifications_circulars.add',
                    'cms.administration.notifications_circulars.send',
                    'cms.administration.notifications_circulars.view',
                    'cms.correspondence.complaints.view',
                    'cms.correspondence.complaints.add'
                ]
            ],

            'TPO (Training & Placement Officer)' => [
                'description' => 'Placement drives, corporate partners, job offers, student eligibility, enterprise internship milestone roadmaps, TPO audits, and verified credential issuance.',
                'all' => false,
                'permissions' => [
                    'cms.placements.companies_drives.view',
                    'cms.placements.companies_drives.add',
                    'cms.placements.companies_drives.edit',
                    'cms.placements.companies_drives.delete',
                    'cms.placements.applications_offers.view',
                    'cms.placements.applications_offers.add',
                    'cms.placements.applications_offers.edit',
                    'cms.placements.applications_offers.approve',
                    'cms.placements.internship.view',
                    'cms.placements.internship.add',
                    'cms.placements.internship.edit',
                    'cms.placements.internship.evaluate',
                    'cms.placements.industry_supervisor.view',
                    'cms.placements.industry_supervisor.grant_access',
                    'cms.placements.industry_supervisor.revoke_access',
                    'cms.placements.reports.view',
                    'cms.placements.reports.export',
                    'lms.learning.certificates.view',
                    'lms.learning.certificates.issue'
                ]
            ],

            'Industry / Company Supervisor' => [
                'description' => 'External corporate mentors evaluating student interns on milestone tasks, mid-term reviews, and final rubric signoffs.',
                'all' => false,
                'permissions' => [
                    'cms.placements.internship.view',
                    'cms.placements.internship.evaluate'
                ]
            ],

            'Admission Officer' => [
                'description' => 'End-to-end admission CRM, lead generation, application processing, document verification, quota allocation, offer generation, fee payments, and student enrollment.',
                'all' => false,
                'permissions' => [
                    'cms.admissions.dashboard.view',
                    'cms.admissions.leads.view',
                    'cms.admissions.leads.create',
                    'cms.admissions.leads.update',
                    'cms.admissions.leads.delete',
                    'cms.admissions.leads.assign',
                    'cms.admissions.leads.import',
                    'cms.admissions.leads.export',
                    'cms.admissions.applications.view',
                    'cms.admissions.applications.create',
                    'cms.admissions.applications.update',
                    'cms.admissions.applications.verify',
                    'cms.admissions.applications.reject',
                    'cms.admissions.applications.convert',
                    'cms.admissions.documents.view',
                    'cms.admissions.documents.upload',
                    'cms.admissions.documents.verify',
                    'cms.admissions.offers.view',
                    'cms.admissions.offers.create',
                    'cms.admissions.offers.update',
                    'cms.admissions.offers.expire',
                    'cms.admissions.offers.download',
                    'cms.admissions.payments.view',
                    'cms.admissions.payments.create',
                    'cms.admissions.payments.refund',
                    'cms.admissions.enrollment.view',
                    'cms.admissions.enrollment.create',
                    'cms.admissions.enrollment.enroll',
                    'cms.admissions.reports.view',
                    'cms.admissions.reports.export',
                    'cms.admissions.merit_list.view',
                    'cms.admissions.merit_list.generate'
                ]
            ],

            'Front Office Executive' => [
                'description' => 'Front desk enquiries, visitor logs, postal/courier logs, call logs, complaints ticketing, holidays calendar, institutional notice board.',
                'all' => false,
                'permissions' => [
                    'cms.correspondence.enquiries.view',
                    'cms.correspondence.enquiries.add',
                    'cms.correspondence.enquiries.edit',
                    'cms.correspondence.visitors.view',
                    'cms.correspondence.visitors.add',
                    'cms.correspondence.visitors.edit',
                    'cms.correspondence.postal_records.view',
                    'cms.correspondence.postal_records.add',
                    'cms.correspondence.postal_records.edit',
                    'cms.correspondence.call_logs.view',
                    'cms.correspondence.call_logs.add',
                    'cms.correspondence.call_logs.edit',
                    'cms.correspondence.complaints.view',
                    'cms.correspondence.complaints.add',
                    'cms.administration.holidays_calendar.view',
                    'cms.administration.notifications_circulars.view',
                    'cms.administration.institution_profile.view'
                ]
            ],

            'HR Team / HR Manager' => [
                'description' => 'Staff employee records, attendance monitoring, leave policy administration, leave requests approval, monthly payroll processing, staff duties, and certificate issuance.',
                'all' => false,
                'permissions' => [
                    'cms.staff.employee_records.view',
                    'cms.staff.employee_records.add',
                    'cms.staff.employee_records.edit',
                    'cms.staff.employee_records.delete',
                    'cms.staff.attendance.view',
                    'cms.staff.attendance.mark',
                    'cms.staff.attendance.edit',
                    'cms.staff.leave.view',
                    'cms.staff.leave.approve',
                    'cms.staff.leave.reject',
                    'cms.staff.payroll.view',
                    'cms.staff.payroll.process',
                    'cms.staff.payroll.add',
                    'cms.staff.payroll.edit',
                    'cms.staff.payroll.approve',
                    'cms.staff.appraisals.view',
                    'cms.staff.appraisals.add',
                    'cms.staff.appraisals.edit',
                    'cms.administration.certificate_templates.view',
                    'cms.administration.certificate_templates.add',
                    'cms.administration.certificate_templates.edit'
                ]
            ]
        ];
    }

    /**
     * Seeds the 9 default access groups and permissions for a specific organization
     */
    public static function seedDefaultAccessGroupsForOrg(int $orgId): array
    {
        $db = \Config\Database::connect();
        $groupModel = new AccessGroupModel();
        $permModel = new PermissionModel();

        $roles = self::getDefaultRolesDefinition();
        $allPermissions = $permModel->findAll();
        $permissionKeyMap = [];
        foreach ($allPermissions as $p) {
            $permissionKeyMap[$p['permission_key']] = $p['id'];
        }

        $seededGroups = [];

        foreach ($roles as $roleName => $def) {
            // Check if group already exists for this org
            $existingGroup = $groupModel->where('org_id', $orgId)->where('name', $roleName)->first();
            
            if (!$existingGroup) {
                $groupId = $groupModel->insert([
                    'org_id' => $orgId,
                    'name' => $roleName,
                    'description' => $def['description']
                ]);
            } else {
                $groupId = $existingGroup['id'];
            }

            // Determine permission IDs to assign
            $targetPermIds = [];
            if (!empty($def['all'])) {
                // All permissions
                $targetPermIds = array_values($permissionKeyMap);
            } else {
                foreach ($def['permissions'] as $key) {
                    if (isset($permissionKeyMap[$key])) {
                        $targetPermIds[] = $permissionKeyMap[$key];
                    }
                }
            }

            // Insert into access_group_permissions with org_id
            foreach ($targetPermIds as $permId) {
                // Check if already mapped
                $exists = $db->table('access_group_permissions')
                    ->where('org_id', $orgId)
                    ->where('group_id', $groupId)
                    ->where('permission_id', $permId)
                    ->countAllResults();

                if (!$exists) {
                    $db->table('access_group_permissions')->insert([
                        'org_id' => $orgId,
                        'group_id' => $groupId,
                        'permission_id' => $permId
                    ]);
                }
            }

            $seededGroups[$roleName] = count($targetPermIds);
        }

        return $seededGroups;
    }
}
