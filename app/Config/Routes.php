<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->get('superadmin/login', 'Auth::login');
$routes->post('superadmin/authenticate', 'Auth::authenticate');
$routes->get('superadmin/logout', 'Auth::logout');

$routes->group('superadmin', ['filter' => 'superauth'], function($routes) {
    $routes->get('/', 'SuperAdmin::index');
    $routes->get('organizations', 'SuperAdmin::organizations');
    $routes->get('create_organization', 'SuperAdmin::create_org_view');
    $routes->get('view_organization/(:segment)', 'SuperAdmin::view_organization/$1');
    $routes->get('edit_organization/(:segment)', 'SuperAdmin::edit_org_view/$1');
    $routes->post('save_organization', 'SuperAdmin::save_organization');
    $routes->post('renew_organization', 'SuperAdmin::renew_organization');
    $routes->post('toggle_module', 'SuperAdmin::toggle_module');
    $routes->get('expiring', 'SuperAdmin::expiring');
    $routes->get('plans', 'SuperAdmin::plans');
    $routes->post('add_plan', 'SuperAdmin::add_plan');
    $routes->post('edit_plan', 'SuperAdmin::edit_plan');
    $routes->post('delete_plan', 'SuperAdmin::delete_plan');
    $routes->get('payments', 'SuperAdmin::payments');
    $routes->post('toggle_status', 'SuperAdmin::toggle_status');
    $routes->get('settings', 'SuperAdminSettings::index');
    $routes->post('settings/save', 'SuperAdminSettings::save');
    $routes->get('logs', 'SuperAdminSettings::logs');
    
    $routes->get('users', 'SuperAdminUsers::index');
    $routes->post('users/save', 'SuperAdminUsers::save');
    $routes->post('users/delete', 'SuperAdminUsers::delete');
    
    // Content Creators Management
    $routes->get('creators', 'SuperAdminCreators::index');
    $routes->post('creators/save', 'SuperAdminCreators::save');
    $routes->post('creators/delete/(:segment)', 'SuperAdminCreators::delete/$1');
    
    $routes->get('export_organizations', 'SuperAdmin::export_organizations');
    $routes->get('export_payments', 'SuperAdmin::export_payments');
});

// =============================================
// ORGANIZATION PORTAL ROUTES
// =============================================
$routes->get('org/login', 'OrgAuth::login');
$routes->post('org/authenticate', 'OrgAuth::authenticate');
$routes->get('org/logout', 'OrgAuth::logout');

$routes->group('org', ['filter' => 'orgauth'], function($routes) {
    $routes->get('dashboard', 'OrgDashboard::index');
    
    // Systems Module
    $routes->get('systems/users', 'OrgSystems::users', ['filter' => 'orgauth:cms.systems.users.view']);
    $routes->post('systems/users/save', 'OrgSystems::saveUser', ['filter' => 'orgauth:cms.systems.users.edit']);
    $routes->post('systems/users/delete', 'OrgSystems::deleteUser', ['filter' => 'orgauth:cms.systems.users.delete']);
    $routes->get('systems/access-groups', 'OrgSystems::accessGroups', ['filter' => 'orgauth:cms.systems.access_groups.view']);
    $routes->get('systems/access-groups/create', 'OrgSystems::createGroup', ['filter' => 'orgauth:cms.systems.access_groups.add']);
    $routes->get('systems/access-groups/edit/(:segment)', 'OrgSystems::editGroup/$1', ['filter' => 'orgauth:cms.systems.access_groups.edit']);
    $routes->post('systems/access-groups/save', 'OrgSystems::saveGroup', ['filter' => 'orgauth:cms.systems.access_groups.edit']);
    $routes->post('systems/access-groups/delete', 'OrgSystems::deleteGroup', ['filter' => 'orgauth:cms.systems.access_groups.delete']);
    
    // Settings (Org Admin Only)
    $routes->get('systems/settings', 'OrgSystems::settings');
    $routes->post('systems/settings/save', 'OrgSystems::saveSettings');

    // Academics Module
    $routes->get('academics', 'OrgAcademics::index', ['filter' => 'orgauth:cms.academics.academic_structure.view']);
    $routes->get('academics/departments', 'OrgAcademics::departments', ['filter' => 'orgauth:cms.academics.academic_structure.view']);
    $routes->post('academics/departments/save', 'OrgAcademics::save_department', ['filter' => 'orgauth:cms.academics.academic_structure.edit']);
    $routes->get('academics/departments/delete/(:segment)', 'OrgAcademics::delete_department/$1', ['filter' => 'orgauth:cms.academics.academic_structure.delete']);
    $routes->get('academics/programs', 'OrgAcademics::programs', ['filter' => 'orgauth:cms.academics.academic_structure.view']);
    $routes->post('academics/programs/save', 'OrgAcademics::save_program', ['filter' => 'orgauth:cms.academics.academic_structure.edit']);
    $routes->get('academics/programs/delete/(:segment)', 'OrgAcademics::delete_program/$1', ['filter' => 'orgauth:cms.academics.academic_structure.delete']);
    $routes->get('academics/academic_years', 'OrgAcademics::academic_years', ['filter' => 'orgauth:cms.academics.academic_structure.view']);
    $routes->post('academics/academic_years/save', 'OrgAcademics::save_academic_year', ['filter' => 'orgauth:cms.academics.academic_structure.edit']);
    $routes->get('academics/academic_years/delete/(:segment)', 'OrgAcademics::delete_academic_year/$1', ['filter' => 'orgauth:cms.academics.academic_structure.delete']);
    $routes->get('academics/semesters', 'OrgAcademics::semesters', ['filter' => 'orgauth:cms.academics.academic_structure.view']);
    $routes->post('academics/semesters/save', 'OrgAcademics::save_semester', ['filter' => 'orgauth:cms.academics.academic_structure.edit']);
    $routes->get('academics/semesters/delete/(:segment)', 'OrgAcademics::delete_semester/$1', ['filter' => 'orgauth:cms.academics.academic_structure.delete']);
    $routes->get('academics/cohorts', 'OrgAcademics::cohorts', ['filter' => 'orgauth:cms.academics.academic_structure.view']);
    $routes->post('academics/cohorts/save', 'OrgAcademics::save_cohort', ['filter' => 'orgauth:cms.academics.academic_structure.edit']);
    $routes->get('academics/cohorts/delete/(:segment)', 'OrgAcademics::delete_cohort/$1', ['filter' => 'orgauth:cms.academics.academic_structure.delete']);
    $routes->get('academics/subjects', 'OrgAcademics::subjects', ['filter' => 'orgauth:cms.academics.academic_structure.view']);
    $routes->post('academics/subjects/save', 'OrgAcademics::save_subject', ['filter' => 'orgauth:cms.academics.academic_structure.edit']);
    $routes->post('academics/save_subject', 'OrgAcademics::save_subject', ['filter' => 'orgauth:cms.academics.academic_structure.edit']);
    $routes->get('academics/subjects/delete/(:segment)', 'OrgAcademics::delete_subject/$1', ['filter' => 'orgauth:cms.academics.academic_structure.delete']);
    
    // Academic Regulations
    $routes->get('academics/regulations', 'OrgAcademics::regulations', ['filter' => 'orgauth:cms.academics.academic_structure.view']);
    $routes->post('academics/regulations/save', 'OrgAcademics::save_regulation', ['filter' => 'orgauth:cms.academics.academic_structure.edit']);
    $routes->get('academics/regulations/delete/(:segment)', 'OrgAcademics::delete_regulation/$1', ['filter' => 'orgauth:cms.academics.academic_structure.delete']);

    // Cohort Sections
    $routes->get('academics/sections', 'OrgAcademics::sections', ['filter' => 'orgauth:cms.academics.academic_structure.view']);
    $routes->post('academics/sections/save', 'OrgAcademics::save_section', ['filter' => 'orgauth:cms.academics.academic_structure.edit']);
    $routes->get('academics/sections/delete/(:segment)', 'OrgAcademics::delete_section/$1', ['filter' => 'orgauth:cms.academics.academic_structure.delete']);

    // Timetable Module (Standardized routes to be implemented in future phase if needed)

    $routes->get('timetable', 'OrgTimetable::index');
    $routes->post('timetable/templates/create', 'OrgTimetable::createTemplate');
    $routes->get('timetable/periods/(:segment)', 'OrgTimetable::periods/$1');
    $routes->post('timetable/periods/auto-generate/(:segment)', 'OrgTimetable::autoGeneratePeriods/$1');
    $routes->post('timetable/periods/save', 'OrgTimetable::savePeriod');
    $routes->post('timetable/periods/delete/(:segment)', 'OrgTimetable::deletePeriod/$1');
    $routes->get('timetable/builder', 'OrgTimetable::builder');
    $routes->post('timetable/assign', 'OrgTimetable::assignTemplate');
    $routes->post('timetable/save_entry', 'OrgTimetable::saveEntry');

    // Faculty Module
    $routes->get('faculty/profiles', 'OrgFaculty::profiles', ['filter' => 'orgauth']);
    $routes->post('faculty/profiles/save', 'OrgFaculty::save_profile', ['filter' => 'orgauth']);
    $routes->post('faculty/profiles/delete', 'OrgFaculty::delete_profile', ['filter' => 'orgauth']);
    $routes->get('faculty/allocations', 'OrgFaculty::allocations', ['filter' => 'orgauth']);
    $routes->post('faculty/allocations/save', 'OrgFaculty::save_allocation', ['filter' => 'orgauth']);
    $routes->post('faculty/allocations/delete', 'OrgFaculty::delete_allocation', ['filter' => 'orgauth']);
    $routes->get('faculty/classTeacherMonitor', 'OrgFaculty::classTeacherMonitor', ['filter' => 'orgauth']);
    $routes->post('faculty/sendParentNotice', 'OrgFaculty::sendParentNotice', ['filter' => 'orgauth']);

    // Staff Module
    $routes->get('staff', 'OrgStaff::index', ['filter' => 'orgauth']);
    $routes->get('staff/directory', 'OrgStaff::directory', ['filter' => 'orgauth']);
    $routes->get('staff/duties', 'OrgStaff::duties', ['filter' => 'orgauth']);
    $routes->post('staff/save_duty', 'OrgStaff::save_duty', ['filter' => 'orgauth']);
    $routes->get('staff/certificates', 'OrgStaff::certificates', ['filter' => 'orgauth']);
    $routes->post('staff/save_certificate', 'OrgStaff::save_certificate', ['filter' => 'orgauth']);
    $routes->get('staff/generate_pdf/(:segment)', 'OrgStaff::generate_pdf/$1', ['filter' => 'orgauth']);
    $routes->get('staff/checklists', 'OrgStaff::checklists', ['filter' => 'orgauth']);
    $routes->post('staff/save_checklist', 'OrgStaff::save_checklist', ['filter' => 'orgauth']);

    // Attendance Module
    $routes->get('attendance', 'OrgAttendance::index');
    $routes->post('attendance/create_session', 'OrgAttendance::createSession');
    $routes->get('attendance/take/(:segment)', 'OrgAttendance::take/$1');
    $routes->post('attendance/save', 'OrgAttendance::save');
    $routes->get('attendance/report', 'OrgAttendance::report');
    $routes->get('attendance/classTeacherMonitor', 'OrgAttendance::classTeacherMonitor');

    // OBE Module
    $routes->get('obe/po-setup', 'OrgObe::poSetup', ['filter' => 'orgauth']);
    $routes->post('obe/po/save', 'OrgObe::savePo', ['filter' => 'orgauth']);
    $routes->post('obe/po/load-template', 'OrgObe::loadTemplate', ['filter' => 'orgauth']);
    $routes->post('obe/po/delete/(:segment)', 'OrgObe::deletePo/$1', ['filter' => 'orgauth']);
    $routes->get('obe/co-setup', 'OrgObe::coSetup', ['filter' => 'orgauth']);
    $routes->post('obe/co/save', 'OrgObe::saveCo', ['filter' => 'orgauth']);
    $routes->post('obe/co/delete/(:segment)', 'OrgObe::deleteCo/$1', ['filter' => 'orgauth']);
    $routes->get('obe/mapping', 'OrgObe::mappingMatrix', ['filter' => 'orgauth']);
    $routes->get('obe/matrix', 'OrgObe::mappingMatrix', ['filter' => 'orgauth']);
    $routes->get('obe/co-po-matrix', 'OrgObe::mappingMatrix', ['filter' => 'orgauth']);
    $routes->post('obe/mapping/save', 'OrgObe::saveMappings', ['filter' => 'orgauth']);
    $routes->post('obe/approveMapping', 'OrgObe::approveMapping', ['filter' => 'orgauth']);
    $routes->post('obe/rejectMapping', 'OrgObe::rejectMapping', ['filter' => 'orgauth']);
    $routes->post('obe/settings/save', 'OrgObe::saveSettings', ['filter' => 'orgauth']);
    $routes->get('obe/targets', 'OrgObe::targetsSetup');
    $routes->post('obe/targets/save', 'OrgObe::saveTargets');
    $routes->get('obe/attainment', 'OrgObe::attainmentReport');
    $routes->get('obe/assessment-config', 'OrgObe::assessmentConfig');
    $routes->post('obe/assessment-config/save', 'OrgObe::saveAssessmentConfig');
    $routes->get('obe/po-report', 'OrgObe::poReport');
    $routes->get('obe/gap-analysis', 'OrgObe::gapAnalysis');
    $routes->post('obe/gap-analysis/save', 'OrgObe::saveGapAnalysis');

    // Marks Module
    $routes->get('marks/components', 'OrgMarks::componentsSetup', ['filter' => 'orgauth']);
    $routes->post('marks/components/save', 'OrgMarks::saveComponent', ['filter' => 'orgauth']);
    $routes->post('marks/components/delete/(:segment)', 'OrgMarks::deleteComponent/$1', ['filter' => 'orgauth']);
    $routes->get('marks/entry', 'OrgMarks::marksEntry', ['filter' => 'orgauth']);
    $routes->post('marks/entry/save', 'OrgMarks::saveMarks', ['filter' => 'orgauth']);
    $routes->post('marks/entry/lock', 'OrgMarks::lockMarks', ['filter' => 'orgauth']);
    $routes->post('marks/unlockMarks', 'OrgMarks::unlockMarks', ['filter' => 'orgauth']);
    $routes->get('marks/auditTrail', 'OrgMarks::auditTrail', ['filter' => 'orgauth']);

    // Dynamic 4-in-1 Assessments Module (Module 1)
    $routes->get('assessments', 'OrgAssessments::index', ['filter' => 'orgauth']);
    $routes->get('assessments/create', 'OrgAssessments::create', ['filter' => 'orgauth']);
    $routes->get('assessments/edit/(:segment)', 'OrgAssessments::edit/$1', ['filter' => 'orgauth']);
    $routes->post('assessments/save', 'OrgAssessments::save', ['filter' => 'orgauth']);
    $routes->get('assessments/delete/(:segment)', 'OrgAssessments::delete/$1', ['filter' => 'orgauth']);
    $routes->get('assessments/submissions/(:segment)', 'OrgAssessments::submissions/$1', ['filter' => 'orgauth']);
    $routes->get('assessments/grade/(:segment)', 'OrgAssessments::grade/$1', ['filter' => 'orgauth']);
    $routes->post('assessments/saveGrade', 'OrgAssessments::saveGrade', ['filter' => 'orgauth']);

    // Enterprise Internships Module (Module 2)
    $routes->get('internships', 'OrgInternships::index', ['filter' => 'orgauth']);
    $routes->get('internships/createPosting', 'OrgInternships::createPosting', ['filter' => 'orgauth']);
    $routes->get('internships/editPosting/(:segment)', 'OrgInternships::editPosting/$1', ['filter' => 'orgauth']);
    $routes->post('internships/savePosting', 'OrgInternships::savePosting', ['filter' => 'orgauth']);
    $routes->get('internships/applications', 'OrgInternships::applications', ['filter' => 'orgauth']);
    $routes->get('internships/applications/(:segment)', 'OrgInternships::applications/$1', ['filter' => 'orgauth']);
    $routes->post('internships/updateApplicationStatus', 'OrgInternships::updateApplicationStatus', ['filter' => 'orgauth']);
    $routes->get('internships/mentorship', 'OrgInternships::mentorship', ['filter' => 'orgauth']);
    $routes->get('internships/reviewStudent/(:segment)', 'OrgInternships::reviewStudent/$1', ['filter' => 'orgauth']);
    $routes->post('internships/evaluateTask', 'OrgInternships::evaluateTask', ['filter' => 'orgauth']);
    $routes->post('internships/submitFinalRubric', 'OrgInternships::submitFinalRubric', ['filter' => 'orgauth']);
    $routes->get('internships/tpoAudit', 'OrgInternships::tpoAudit', ['filter' => 'orgauth']);
    $routes->post('internships/issueCertificate', 'OrgInternships::issueCertificate', ['filter' => 'orgauth']);

    // Assignments Module
    $routes->get('assignments', 'OrgAssignments::index', ['filter' => 'orgauth']);
    $routes->get('assignments/create', 'OrgAssignments::create', ['filter' => 'orgauth']);
    $routes->get('assignments/edit/(:segment)', 'OrgAssignments::edit/$1', ['filter' => 'orgauth']);
    $routes->post('assignments/save', 'OrgAssignments::save', ['filter' => 'orgauth']);
    $routes->post('assignments/delete/(:segment)', 'OrgAssignments::delete/$1', ['filter' => 'orgauth']);
    $routes->get('assignments/submissions/(:segment)', 'OrgAssignments::submissions/$1', ['filter' => 'orgauth']);
    $routes->post('assignments/grade', 'OrgAssignments::grade', ['filter' => 'orgauth']);

    // Quizzes (CMS)
    $routes->get('quizzes', 'OrgQuizzes::index');
    $routes->get('quizzes/create', 'OrgQuizzes::create');
    $routes->get('quizzes/edit/(:segment)', 'OrgQuizzes::edit/$1');
    $routes->post('quizzes/save', 'OrgQuizzes::save');
    $routes->post('quizzes/delete/(:segment)', 'OrgQuizzes::delete/$1');
    $routes->get('quizzes/questions/(:segment)', 'OrgQuizzes::questions/$1');
    $routes->post('quizzes/save_question', 'OrgQuizzes::saveQuestion');
    $routes->post('quizzes/delete_question/(:segment)', 'OrgQuizzes::deleteQuestion/$1');
    $routes->get('quizzes/results/(:segment)', 'OrgQuizzes::results/$1');

    // Materials Module
    $routes->get('materials', 'OrgMaterials::index', ['filter' => 'orgauth']);
    $routes->get('materials/create', 'OrgMaterials::create', ['filter' => 'orgauth']);
    $routes->get('materials/edit/(:segment)', 'OrgMaterials::edit/', ['filter' => 'orgauth']);
    $routes->post('materials/save', 'OrgMaterials::save', ['filter' => 'orgauth']);
    $routes->post('materials/delete/(:segment)', 'OrgMaterials::delete/', ['filter' => 'orgauth']);

    // HR & Payroll Module
    $routes->get('hr/settings', 'OrgHrSettings::index');
    $routes->post('hr/settings/save_department', 'OrgHrSettings::saveDepartment');
    $routes->post('hr/settings/delete_department/(:segment)', 'OrgHrSettings::deleteDepartment/$1');
    $routes->post('hr/settings/save_designation', 'OrgHrSettings::saveDesignation');
    $routes->post('hr/settings/delete_designation/(:segment)', 'OrgHrSettings::deleteDesignation/$1');
    $routes->post('hr/settings/save_employment_type', 'OrgHrSettings::saveEmploymentType');
    $routes->post('hr/settings/delete_employment_type/(:segment)', 'OrgHrSettings::deleteEmploymentType/$1');
    $routes->post('hr/settings/save_leave_policy', 'OrgHrSettings::saveLeavePolicy');
    $routes->post('hr/settings/delete_leave_policy/(:segment)', 'OrgHrSettings::deleteLeavePolicy/$1');
    $routes->get('hr/employees', 'OrgEmployees::index');
    $routes->post('hr/employees/save', 'OrgEmployees::save');
    $routes->post('hr/employees/delete/(:segment)', 'OrgEmployees::delete/$1');
    $routes->get('hr/leaves', 'OrgLeaves::index');
    $routes->post('hr/leaves/apply', 'OrgLeaves::apply');
    $routes->post('hr/leaves/update_status/(:segment)', 'OrgLeaves::updateStatus/$1');

    // HR Appraisals
    $routes->get('hr/appraisals', 'OrgHrAppraisals::index');
    $routes->post('hr/appraisals/save', 'OrgHrAppraisals::save');
    $routes->get('hr/appraisals/delete/(:segment)', 'OrgHrAppraisals::delete/$1');

    // HR Payroll, Structures & Components
    $routes->get('hr/payroll', 'OrgPayroll::payslips');
    $routes->get('payroll/components', 'OrgPayroll::components');
    $routes->post('payroll/components/save', 'OrgPayroll::save_component');
    $routes->get('payroll/components/delete/(:segment)', 'OrgPayroll::delete_component/$1');
    $routes->get('payroll/structures', 'OrgPayroll::structures');
    $routes->post('payroll/structures/save', 'OrgPayroll::save_structure');
    $routes->get('payroll/structures/delete/(:segment)', 'OrgPayroll::delete_structure/$1');
    $routes->get('payroll/payslips', 'OrgPayroll::payslips');

    // Admissions Module
    $routes->get('admissions', 'OrgAdmissions::index', ['filter' => 'orgauth:cms.admissions.dashboard.view']);
    $routes->get('admissions/api-live-leads', 'OrgAdmissions::api_live_leads', ['filter' => 'orgauth']);
    $routes->get('admissions/leads', 'OrgAdmissions::leads', ['filter' => 'orgauth:cms.admissions.leads.view']);
    $routes->post('admissions/leads/save', 'OrgAdmissions::save_lead', ['filter' => 'orgauth:cms.admissions.leads.create']);
    $routes->get('admissions/leads/delete/(:segment)', 'OrgAdmissions::delete_lead/$1', ['filter' => 'orgauth:cms.admissions.leads.delete']);
    $routes->get('admissions/leads/convert/(:segment)', 'OrgAdmissions::convert_lead/$1', ['filter' => 'orgauth:cms.admissions.applications.convert']);
    $routes->get('admissions/applications', 'OrgAdmissions::applications', ['filter' => 'orgauth:cms.admissions.applications.view']);
    $routes->get('admissions/documents', 'OrgAdmissions::documents', ['filter' => 'orgauth:cms.admissions.documents.view']);
    $routes->get('admissions/offers', 'OrgAdmissions::offers', ['filter' => 'orgauth:cms.admissions.offers.view']);
    $routes->post('admissions/offers/generate', 'OrgAdmissions::generate_offer', ['filter' => 'orgauth:cms.admissions.offers.create']);
    $routes->get('admissions/payments', 'OrgAdmissions::payments', ['filter' => 'orgauth:cms.admissions.payments.view']);
    $routes->get('admissions/enrollment', 'OrgAdmissions::enrollment', ['filter' => 'orgauth:cms.admissions.dashboard.view']); 
    $routes->get('admissions/enrollment/process/(:segment)', 'OrgAdmissions::enroll_student/$1', ['filter' => 'orgauth:cms.admissions.enrollment.create']);
    $routes->get('admissions/college-strength', 'OrgAdmissions::college_strength', ['filter' => 'orgauth']);
    $routes->get('admissions/categories', 'OrgAdmissions::categories', ['filter' => 'orgauth']);
    $routes->post('admissions/save-category', 'OrgAdmissions::save_category', ['filter' => 'orgauth']);
    $routes->get('admissions/scholarships', 'OrgAdmissions::scholarships', ['filter' => 'orgauth']);
    $routes->post('admissions/save-scholarship', 'OrgAdmissions::save_scholarship', ['filter' => 'orgauth']);
    $routes->get('admissions/detained', 'OrgAdmissions::detained', ['filter' => 'orgauth']);
    $routes->post('admissions/save-detained', 'OrgAdmissions::save_detained', ['filter' => 'orgauth']);

    // Fee Configuration & Payments Module
    $routes->get('fee-config/types', 'OrgFeeConfig::feeTypes', ['filter' => 'orgauth']);
    $routes->post('fee-config/save-type', 'OrgFeeConfig::saveFeeType', ['filter' => 'orgauth']);
    $routes->post('fee-config/delete-type', 'OrgFeeConfig::deleteFeeType', ['filter' => 'orgauth']);
    $routes->get('fee-config/structures', 'OrgFeeConfig::feeStructures', ['filter' => 'orgauth']);
    $routes->get('fee-config/program/(:segment)', 'OrgFeeConfig::programBatches/$1', ['filter' => 'orgauth']);
    $routes->get('fee-config/semester-plan/(:segment)/(:segment)/(:segment)', 'OrgFeeConfig::semesterPlan/$1/$2/$3', ['filter' => 'orgauth']);
    $routes->post('fee-config/save-structure', 'OrgFeeConfig::saveFeeStructure', ['filter' => 'orgauth']);
    $routes->post('fee-config/delete-structure', 'OrgFeeConfig::deleteFeeStructure', ['filter' => 'orgauth']);
    $routes->get('fee-config/manual-assign', 'OrgFeeConfig::manualAssign', ['filter' => 'orgauth']);
    $routes->post('fee-config/save-manual-assign', 'OrgFeeConfig::saveManualAssign', ['filter' => 'orgauth']);
    $routes->get('fee-payments/dues', 'OrgFeePayments::duesList', ['filter' => 'orgauth']);
    $routes->post('fee-payments/pay', 'OrgFeePayments::payFee', ['filter' => 'orgauth']);
    $routes->get('fee-payments/receipt/(:segment)', 'OrgFeePayments::receipt/$1', ['filter' => 'orgauth']);

    // Examinations Module
    $routes->get('examinations', 'OrgExaminations::index', ['filter' => 'orgauth']);
    $routes->post('examinations/save_exam', 'OrgExaminations::save_exam', ['filter' => 'orgauth']);
    $routes->get('examinations/schedules', 'OrgExaminations::schedules', ['filter' => 'orgauth']);
    $routes->post('examinations/save_schedule', 'OrgExaminations::save_schedule', ['filter' => 'orgauth']);
    $routes->get('examinations/applications', 'OrgExaminations::applications', ['filter' => 'orgauth']);
    $routes->post('examinations/update_application_status/(:segment)', 'OrgExaminations::update_application_status/$1', ['filter' => 'orgauth']);
    $routes->get('examinations/hall-tickets', 'OrgExaminations::hall_tickets', ['filter' => 'orgauth']);
    $routes->get('examinations/hall_tickets', 'OrgExaminations::hall_tickets', ['filter' => 'orgauth']);
    $routes->post('examinations/generate-hall-ticket', 'OrgExaminations::generate_hall_ticket', ['filter' => 'orgauth']);
    $routes->get('examinations/print-hall-ticket/(:segment)', 'OrgExaminations::print_hall_ticket/$1', ['filter' => 'orgauth']);
    $routes->get('examinations/marks', 'OrgExaminations::marks', ['filter' => 'orgauth']);
    $routes->get('examinations/enter-marks/(:segment)', 'OrgExaminations::enter_marks/$1', ['filter' => 'orgauth']);
    $routes->post('examinations/save_marks', 'OrgExaminations::save_marks', ['filter' => 'orgauth']);
    $routes->get('examinations/backlogs', 'OrgExaminations::backlogs', ['filter' => 'orgauth']);
    $routes->get('examinations/set_papers', 'OrgExaminations::set_papers', ['filter' => 'orgauth']);
    $routes->post('examinations/save_paper', 'OrgExaminations::save_paper', ['filter' => 'orgauth']);
    $routes->get('examinations/external_registrations', 'OrgExaminations::external_registrations', ['filter' => 'orgauth']);
    $routes->post('examinations/save_external_registration', 'OrgExaminations::save_external_registration', ['filter' => 'orgauth']);
    $routes->get('examinations/invigilation', 'OrgExaminations::invigilation', ['filter' => 'orgauth']);
    $routes->post('examinations/save_invigilation', 'OrgExaminations::save_invigilation', ['filter' => 'orgauth']);
    $routes->get('examinations/finance', 'OrgExaminations::finance', ['filter' => 'orgauth']);
    $routes->post('examinations/save_grant', 'OrgExaminations::save_grant', ['filter' => 'orgauth']);
    $routes->post('examinations/save_expenditure', 'OrgExaminations::save_expenditure', ['filter' => 'orgauth']);
    $routes->get('examinations/reports', 'OrgExaminations::reports', ['filter' => 'orgauth']);

    // Administration Module
    $routes->get('administration', 'OrgAdministration::index', ['filter' => 'orgauth']);
    $routes->get('administration/college-details', 'OrgAdministration::college_details', ['filter' => 'orgauth']);
    $routes->post('administration/save-college-details', 'OrgAdministration::save_college_details', ['filter' => 'orgauth']);
    $routes->get('administration/lecture-halls', 'OrgAdministration::lecture_halls', ['filter' => 'orgauth']);
    $routes->post('administration/save-lecture-hall', 'OrgAdministration::save_lecture_hall', ['filter' => 'orgauth']);
    $routes->post('administration/delete-lecture-hall/(:segment)', 'OrgAdministration::delete_lecture_hall/$1', ['filter' => 'orgauth']);
    $routes->get('administration/settings', 'OrgAdministration::settings', ['filter' => 'orgauth']);
    $routes->post('administration/save-settings', 'OrgAdministration::save_settings', ['filter' => 'orgauth']);
    $routes->get('administration/bank-details', 'OrgAdministration::bank_details', ['filter' => 'orgauth']);
    $routes->post('administration/save_bank', 'OrgAdministration::save_bank', ['filter' => 'orgauth']);
    $routes->get('administration/locations', 'OrgAdministration::locations', ['filter' => 'orgauth']);
    $routes->post('administration/save_location', 'OrgAdministration::save_location', ['filter' => 'orgauth']);
    $routes->get('administration/agents', 'OrgAdministration::agents', ['filter' => 'orgauth']);
    $routes->post('administration/save_agent', 'OrgAdministration::save_agent', ['filter' => 'orgauth']);
    $routes->get('administration/holidays', 'OrgAdministration::holidays', ['filter' => 'orgauth']);
    $routes->post('administration/save_holiday', 'OrgAdministration::save_holiday', ['filter' => 'orgauth']);
    $routes->get('administration/certificates', 'OrgAdministration::certificates', ['filter' => 'orgauth']);
    $routes->post('administration/save_certificate', 'OrgAdministration::save_certificate', ['filter' => 'orgauth']);
    $routes->get('administration/complaints', 'OrgAdministration::complaints', ['filter' => 'orgauth']);
    $routes->post('administration/update_complaint_status', 'OrgAdministration::update_complaint_status', ['filter' => 'orgauth']);
    $routes->get('administration/diary', 'OrgAdministration::diary', ['filter' => 'orgauth']);
    $routes->post('administration/save_diary', 'OrgAdministration::save_diary', ['filter' => 'orgauth']);

    // Transport Module
    $routes->match(['get', 'post'], 'transport/vehicles', 'OrgTransport::vehicles', ['filter' => 'orgauth']);
    $routes->get('transport/delete_vehicle/(:segment)', 'OrgTransport::delete_vehicle/$1', ['filter' => 'orgauth']);
    $routes->match(['get', 'post'], 'transport/routes', 'OrgTransport::routes', ['filter' => 'orgauth']);
    $routes->get('transport/delete_route/(:segment)', 'OrgTransport::delete_route/$1', ['filter' => 'orgauth']);
    $routes->match(['get', 'post'], 'transport/halts', 'OrgTransport::halts', ['filter' => 'orgauth']);
    $routes->get('transport/delete_halt/(:segment)', 'OrgTransport::delete_halt/$1', ['filter' => 'orgauth']);
    $routes->match(['get', 'post'], 'transport/subscriptions', 'OrgTransport::subscriptions', ['filter' => 'orgauth']);
    $routes->get('transport/delete_subscription/(:segment)', 'OrgTransport::delete_subscription/$1', ['filter' => 'orgauth']);
    $routes->match(['get', 'post'], 'transport/logbook', 'OrgTransport::logbook', ['filter' => 'orgauth']);
    $routes->get('transport/delete_logbook/(:segment)', 'OrgTransport::delete_logbook/$1', ['filter' => 'orgauth']);

    // Hostel Module
    $routes->get('hostel', 'OrgHostel::index', ['filter' => 'orgauth']);
    $routes->post('hostel/save', 'OrgHostel::save_hostel', ['filter' => 'orgauth']);
    $routes->get('hostel/rooms', 'OrgHostel::rooms', ['filter' => 'orgauth']);
    $routes->post('hostel/save_room', 'OrgHostel::save_room', ['filter' => 'orgauth']);
    $routes->get('hostel/registrations', 'OrgHostel::registrations', ['filter' => 'orgauth']);
    $routes->post('hostel/save_registration', 'OrgHostel::save_registration', ['filter' => 'orgauth']);
    $routes->get('hostel/outings', 'OrgHostel::outings', ['filter' => 'orgauth']);
    $routes->post('hostel/update_outing_status', 'OrgHostel::update_outing_status', ['filter' => 'orgauth']);

    // Library Module
    $routes->get('library', 'OrgLibrary::index', ['filter' => 'orgauth']);
    $routes->get('library/books', 'OrgLibrary::books', ['filter' => 'orgauth']);
    $routes->post('library/save_book', 'OrgLibrary::save_book', ['filter' => 'orgauth']);
    $routes->get('library/members', 'OrgLibrary::members', ['filter' => 'orgauth']);
    $routes->post('library/save_member', 'OrgLibrary::save_member', ['filter' => 'orgauth']);
    $routes->get('library/issues', 'OrgLibrary::issues', ['filter' => 'orgauth']);
    $routes->post('library/issue_book', 'OrgLibrary::issue_book', ['filter' => 'orgauth']);
    $routes->post('library/return_book', 'OrgLibrary::return_book', ['filter' => 'orgauth']);
    $routes->get('library/categories', 'OrgLibrary::categories');
    $routes->post('library/categories', 'OrgLibrary::categories');
    $routes->get('library/suppliers', 'OrgLibrary::suppliers');
    $routes->post('library/suppliers', 'OrgLibrary::suppliers');
    $routes->get('library/periodicals', 'OrgLibrary::periodicals');
    $routes->post('library/periodicals', 'OrgLibrary::periodicals');
    $routes->get('library/stock', 'OrgLibrary::stock');
    $routes->post('library/stock', 'OrgLibrary::stock');

    // Accounts Module
    $routes->get('accounts', 'OrgAccounts::index');
    $routes->get('accounts/heads', 'OrgAccounts::heads');
    $routes->post('accounts/save_head', 'OrgAccounts::save_head');
    $routes->get('accounts/banks', 'OrgAccounts::banks');
    $routes->post('accounts/save_bank', 'OrgAccounts::save_bank');
    $routes->get('accounts/transactions', 'OrgAccounts::transactions');
    $routes->post('accounts/save_transaction', 'OrgAccounts::save_transaction');
    $routes->get('accounts/daybook', 'OrgAccounts::daybook');
    $routes->get('accounts/pl-statement', 'OrgAccounts::pl_statement');

    // Placements Module
    $routes->get('placements', 'OrgPlacements::index');
    $routes->get('placements/drives', 'OrgPlacements::drives');
    $routes->post('placements/drives/save', 'OrgPlacements::save_drive');
    $routes->get('placements/drives/(:segment)/applications', 'OrgPlacements::drive_applications/$1');
    $routes->post('placements/applications/update', 'OrgPlacements::update_application');
    $routes->get('placements/applications/issue-offer/(:segment)', 'OrgPlacements::issue_offer_from_applicant/$1');
    $routes->get('placements/companies', 'OrgPlacements::companies');
    $routes->post('placements/save_company', 'OrgPlacements::save_company');
    $routes->get('placements/internships', 'OrgPlacements::internships');
    $routes->post('placements/save_internship', 'OrgPlacements::save_internship');
    $routes->get('placements/offers', 'OrgPlacements::offers');
    $routes->post('placements/save_offer', 'OrgPlacements::save_offer');
    
    // Correspondence Module
    $routes->get('correspondence', 'OrgCorrespondence::index');
    $routes->get('correspondence/templates', 'OrgCorrespondence::templates');
    $routes->post('correspondence/save_template', 'OrgCorrespondence::save_template');
    $routes->get('correspondence/delete_template/(:segment)', 'OrgCorrespondence::delete_template/$1');
    $routes->get('correspondence/festivals', 'OrgCorrespondence::festivals');
    $routes->post('correspondence/save_festival', 'OrgCorrespondence::save_festival');
    
    // Student Lifecycle & Directory Module
    $routes->get('students', 'OrgStudents::index', ['filter' => 'orgauth']);
    $routes->get('students/profile/(:segment)', 'OrgStudents::profile/$1', ['filter' => 'orgauth']);
    $routes->get('students/create', 'OrgStudents::create', ['filter' => 'orgauth']);
    $routes->get('students/edit/(:segment)', 'OrgStudents::edit/$1', ['filter' => 'orgauth']);
    $routes->post('students/save', 'OrgStudents::save', ['filter' => 'orgauth']);
    $routes->get('students/import', 'OrgStudents::import', ['filter' => 'orgauth']);
    $routes->get('students/download-sample-csv', 'OrgStudents::downloadSampleCsv', ['filter' => 'orgauth']);
    $routes->post('students/process-import', 'OrgStudents::processImport', ['filter' => 'orgauth']);
    $routes->get('students/bulk-promote', 'OrgStudents::bulkPromote', ['filter' => 'orgauth']);
    $routes->post('students/process-bulk-promote', 'OrgStudents::processBulkPromote', ['filter' => 'orgauth']);
    $routes->get('students/parents', 'OrgStudents::parents', ['filter' => 'orgauth']);
    $routes->post('students/save-parent', 'OrgStudents::saveParent', ['filter' => 'orgauth']);
    $routes->get('students/unlink-parent/(:segment)', 'OrgStudents::unlinkParent/$1', ['filter' => 'orgauth']);

    // Front Office & Reception Module
    $routes->get('front-office', 'OrgFrontOffice::index', ['filter' => 'orgauth']);
    $routes->get('front-office/visitors', 'OrgFrontOffice::visitors', ['filter' => 'orgauth']);
    $routes->post('front-office/visitors/save', 'OrgFrontOffice::saveVisitor', ['filter' => 'orgauth']);
    $routes->get('front-office/visitors/checkout/(:segment)', 'OrgFrontOffice::checkoutVisitor/$1', ['filter' => 'orgauth']);
    $routes->get('front-office/visitors/print-pass/(:segment)', 'OrgFrontOffice::printPass/$1', ['filter' => 'orgauth']);
    $routes->get('front-office/calls', 'OrgFrontOffice::calls', ['filter' => 'orgauth']);
    $routes->post('front-office/calls/save', 'OrgFrontOffice::saveCall', ['filter' => 'orgauth']);
    $routes->get('front-office/calls/delete/(:segment)', 'OrgFrontOffice::deleteCall/$1', ['filter' => 'orgauth']);
    $routes->get('front-office/postal', 'OrgFrontOffice::postal', ['filter' => 'orgauth']);
    $routes->post('front-office/postal/save', 'OrgFrontOffice::savePostal', ['filter' => 'orgauth']);
    $routes->get('front-office/postal/delete/(:segment)', 'OrgFrontOffice::deletePostal/$1', ['filter' => 'orgauth']);
    $routes->get('front-office/enquiries', 'OrgFrontOffice::enquiries', ['filter' => 'orgauth']);
    $routes->post('front-office/enquiries/save', 'OrgFrontOffice::saveEnquiry', ['filter' => 'orgauth']);
    $routes->post('front-office/enquiries/followup', 'OrgFrontOffice::saveFollowup', ['filter' => 'orgauth']);
    $routes->get('front-office/enquiries/convert/(:segment)', 'OrgFrontOffice::convertToAdmission/$1', ['filter' => 'orgauth']);
    $routes->get('front-office/api-live-stats', 'OrgFrontOffice::api_live_stats', ['filter' => 'orgauth']);

    // Correspondence & Notifications
    $routes->get('correspondence', 'OrgCorrespondence::index', ['filter' => 'orgauth']);
    $routes->get('correspondence/templates', 'OrgCorrespondence::templates', ['filter' => 'orgauth']);
    $routes->post('correspondence/save_template', 'OrgCorrespondence::save_template', ['filter' => 'orgauth']);
    $routes->get('correspondence/festivals', 'OrgCorrespondence::festivals', ['filter' => 'orgauth']);
    $routes->post('correspondence/save_festival', 'OrgCorrespondence::save_festival', ['filter' => 'orgauth']);
    $routes->get('notifications/unread-count', 'OrgCorrespondence::unread_count', ['filter' => 'orgauth']);

    // Certificate & Document Template Builder
    $routes->get('certificates', 'OrgCertificates::templates', ['filter' => 'orgauth']);
    $routes->get('certificates/templates', 'OrgCertificates::templates', ['filter' => 'orgauth']);
    $routes->post('certificates/templates/save', 'OrgCertificates::save_template', ['filter' => 'orgauth']);
    $routes->get('certificates/delete/(:segment)', 'OrgCertificates::delete_template/$1', ['filter' => 'orgauth']);
    $routes->get('certificates/generate', 'OrgCertificates::generate', ['filter' => 'orgauth']);
    $routes->get('certificates/print/(:segment)/(:segment)', 'OrgCertificates::print_certificate/$1/$2', ['filter' => 'orgauth']);

    $routes->get('lms/courses', function() {
        return redirect()->to('org/materials');
    });
});

// ==========================================
// LMS PORTAL ROUTES (STUDENT)
// ==========================================
$routes->get('lms/login', 'LmsAuth::login');
$routes->post('lms/authenticate', 'LmsAuth::authenticate');
$routes->get('lms/logout', 'LmsAuth::logout');

$routes->group('lms', ['filter' => 'lmsauth'], function($routes) {
    $routes->get('/', 'LmsDashboard::index');
    $routes->get('dashboard', 'LmsDashboard::index');
    
    // Dynamic Assessments (Module 1)
    $routes->get('assessments', 'LmsAssessments::index');
    $routes->get('assessments/view/(:segment)', 'LmsAssessments::view/$1');
    $routes->post('assessments/submit/(:segment)', 'LmsAssessments::submit/$1');

    // Enterprise Internships (Module 2)
    $routes->get('internships', 'LmsInternships::index');
    $routes->get('internships/apply/(:segment)', 'LmsInternships::apply/$1');
    $routes->post('internships/submitApplication', 'LmsInternships::submitApplication');
    $routes->post('internships/respondOffer', 'LmsInternships::respondOffer');
    $routes->get('internships/workspace/(:segment)', 'LmsInternships::workspace/$1');
    $routes->post('internships/submitTask', 'LmsInternships::submitTask');
    $routes->post('internships/addComment', 'LmsInternships::addComment');
    $routes->post('internships/submitMidReview', 'LmsInternships::submitMidReview');

    // Materials
    $routes->get('materials', 'LmsMaterials::index');
    $routes->get('materials/watch/(:segment)', 'LmsMaterials::watchCourse/$1');
    
    // Assignments
    $routes->get('assignments', 'LmsAssignments::index');
    $routes->get('assignments/view/(:segment)', 'LmsAssignments::view/$1');
    $routes->post('assignments/submit', 'LmsAssignments::submit');
    
    // Quizzes (LMS)
    $routes->get('quizzes', 'LmsQuizzes::index');
    $routes->get('quizzes/start/(:segment)', 'LmsQuizzes::start/$1');
    $routes->get('quizzes/exam/(:segment)', 'LmsQuizzes::exam/$1');
    $routes->post('quizzes/save_answer', 'LmsQuizzes::saveAnswer');
    $routes->post('quizzes/submit', 'LmsQuizzes::submit');

    // Timetable (LMS)
    $routes->get('timetable', 'LmsTimetable::index');

    // Attendance (LMS)
    $routes->get('attendance', 'LmsAttendance::index');

    // LMS Portal (Extensions)
    $routes->get('grade-card', 'LmsPortal::grade_card');
    $routes->get('exam-applications', 'LmsPortal::exam_applications');
    $routes->post('exam-applications/submit', 'LmsPortal::submit_exam_application');
    $routes->get('certificates', 'LmsPortal::certificate_requests');
    $routes->post('certificates/submit', 'LmsPortal::submit_certificate');
    $routes->get('complaints', 'LmsPortal::complaints');
    $routes->post('complaints/submit', 'LmsPortal::submit_complaint');
    $routes->get('feedback', 'LmsPortal::feedback');
    $routes->post('feedback/submit', 'LmsPortal::submit_feedback');
    $routes->get('diary', 'LmsPortal::diary');
    $routes->get('hostel', 'LmsPortal::hostel');
    $routes->post('hostel/submit', 'LmsPortal::submit_hostel');
    $routes->get('transport', 'LmsPortal::transport');
    $routes->post('transport/submit', 'LmsPortal::submit_transport');
    $routes->get('self-service', 'LmsPortal::self_service');
    $routes->get('fees', 'LmsPortal::fees');
    $routes->get('obe', 'LmsPortal::obe');
    $routes->get('placements', 'LmsPortal::placements');
    $routes->post('placements/apply', 'LmsPortal::apply_placement');

    // Next-Gen LMS Learning Hub
    $routes->get('learn', 'LmsLearn::index');

    // Career & Employability Suite
    $routes->get('career', 'LmsCareer::index');
    $routes->get('career/resume', 'LmsCareer::resumeBuilder');
    $routes->get('career/mock-interview', 'LmsCareer::mockInterview');
    $routes->get('career/portfolio', 'LmsCareer::portfolio');
});

// ==========================================
// PASSWORDLESS MAGIC LINK FOR SUPERVISORS
// ==========================================
$routes->get('internship-supervisor/(:any)', 'InternshipSupervisor::portal/$1');
$routes->post('internship-supervisor/signoffTask', 'InternshipSupervisor::signoffTask');
$routes->post('internship-supervisor/submitMidReview', 'InternshipSupervisor::submitMidReview');
$routes->post('internship-supervisor/submitFinalSignoff', 'InternshipSupervisor::submitFinalSignoff');
$routes->post('internship-supervisor/addCompanyTask', 'InternshipSupervisor::addCompanyTask');

// ==========================================
// PUBLIC CERTIFICATE QR VERIFICATION
// ==========================================
$routes->get('verify-certificate/(:any)', 'CertificateVerify::verify/$1');

// ==========================================
// PARENT PORTAL ROUTES
// ==========================================
$routes->get('parent/login', 'ParentAuth::login');
$routes->post('parent/authenticate', 'ParentAuth::authenticate');
$routes->get('parent/logout', 'ParentAuth::logout');

$routes->group('parent', ['filter' => 'parentauth'], function($routes) {
    $routes->get('/', 'ParentDashboard::index');
    $routes->get('dashboard', 'ParentDashboard::index');
    $routes->get('dashboard/select_student/(:segment)', 'ParentDashboard::select_student/$1');
    $routes->get('attendance', 'ParentDashboard::attendance');
    $routes->get('fees', 'ParentDashboard::fees');
    $routes->get('marks', 'ParentDashboard::marks');
    $routes->get('assignments', 'ParentDashboard::assignments');
    $routes->get('timetable', 'ParentDashboard::timetable');
});

// ==========================================
// CREATOR STUDIO ROUTES
// ==========================================
$routes->get('creator', 'CreatorAuth::login');
$routes->get('creator/login', 'CreatorAuth::login');
$routes->post('creator/authenticate', 'CreatorAuth::authenticate');
$routes->get('creator/logout', 'CreatorAuth::logout');

$routes->group('creator', ['filter' => 'creatorauth'], function($routes) {
    $routes->get('/', 'CreatorStudio::index');
    $routes->get('courses', 'CreatorStudio::courses');
    $routes->get('courses/create', 'CreatorStudio::create');
    $routes->get('courses/edit/(:segment)', 'CreatorStudio::edit/$1');
    $routes->post('courses/saveCourse', 'CreatorStudio::saveCourse');
    $routes->get('courses/builder/(:segment)', 'CreatorStudio::builder/$1');
    $routes->get('courses/preview/(:segment)', 'CreatorStudio::preview/$1');
    $routes->post('courses/saveChapter', 'CreatorStudio::saveChapter');
    $routes->post('courses/deleteChapter/(:segment)', 'CreatorStudio::deleteChapter/$1');
    $routes->post('courses/saveLesson', 'CreatorStudio::saveLesson');
    $routes->post('courses/deleteLesson/(:segment)', 'CreatorStudio::deleteLesson/$1');
    $routes->get('courses/togglePublish/(:segment)', 'CreatorStudio::togglePublish/$1');
    $routes->get('courses/delete/(:segment)', 'CreatorStudio::deleteCourse/$1');
});

// ==========================================
// PUBLIC ADMISSIONS PORTAL
// ==========================================
$routes->get('admissions/apply', 'PublicAdmissions::index');
$routes->post('admissions/apply', 'PublicAdmissions::submit');
$routes->get('public/admissions', function() {
    return redirect()->to('admissions/apply');
});

// ==========================================
// MOBILE REST API SUITE (STUDENTS & PARENTS)
// ==========================================
$routes->group('api', function($routes) {
    // Auth endpoints (Public)
    $routes->post('auth/login', 'Api\AuthApiController::login');
    $routes->post('auth/logout', 'Api\AuthApiController::logout');
    $routes->get('auth/me', 'Api\AuthApiController::me', ['filter' => 'apiauth']);

    // Student App Endpoints (Protected by apiauth:student)
    $routes->group('student', ['filter' => 'apiauth:student'], function($routes) {
        $routes->get('dashboard', 'Api\StudentApiController::dashboard');
        $routes->get('profile', 'Api\StudentApiController::profile');
        $routes->get('attendance', 'Api\StudentApiController::attendance');
        $routes->get('timetable', 'Api\StudentApiController::timetable');
        $routes->get('marks', 'Api\StudentApiController::marks');
        $routes->get('fees', 'Api\StudentApiController::fees');
        $routes->get('placements', 'Api\StudentApiController::placements');
    });

    // Parent App Endpoints (Protected by apiauth:parent)
    $routes->group('parent', ['filter' => 'apiauth:parent'], function($routes) {
        $routes->get('dashboard', 'Api\ParentApiController::dashboard');
        $routes->get('wards', 'Api\ParentApiController::wards');
        $routes->get('ward/(:segment)/attendance', 'Api\ParentApiController::ward_attendance/$1');
        $routes->get('ward/(:segment)/timetable', 'Api\ParentApiController::ward_timetable/$1');
        $routes->get('ward/(:segment)/marks', 'Api\ParentApiController::ward_marks/$1');
        $routes->get('ward/(:segment)/fees', 'Api\ParentApiController::ward_fees/$1');
    });
});

