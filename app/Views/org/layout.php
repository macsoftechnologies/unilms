<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc(session()->get('org_name')) ?> | UniLMS</title>
    <link rel="stylesheet" href="<?= base_url('index.css?v=' . (file_exists(FCPATH . 'index.css') ? filemtime(FCPATH . 'index.css') : (file_exists(ROOTPATH . 'index.css') ? filemtime(ROOTPATH . 'index.css') : time()))) ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Flatpickr for Universal DD/MM/YYYY Date Inputs -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <!-- Anti-cache meta tags -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <script>
        const themeGradients = {
            purple: {
                primary: '#7C3AED',
                primaryHover: '#6D28D9',
                sidebarBg: 'radial-gradient(ellipse 130% 50% at 15% 0%, #7C3AED 0%, #6D28D9 40%, transparent 80%), radial-gradient(ellipse 80% 40% at 100% 30%, rgba(192, 132, 252, 0.28) 0%, transparent 70%), linear-gradient(180deg, #4C1D95 0%, #2E1065 35%, #170A38 70%, #0D0520 100%)',
                waveBg: 'linear-gradient(160deg, rgba(124, 58, 237, 0.55) 0%, rgba(192, 132, 252, 0.25) 65%, transparent 100%)'
            },
            blue: {
                primary: '#2563EB',
                primaryHover: '#1D4ED8',
                sidebarBg: 'radial-gradient(ellipse 130% 50% at 15% 0%, #2563EB 0%, #1D4ED8 40%, transparent 80%), radial-gradient(ellipse 80% 40% at 100% 30%, rgba(6, 182, 212, 0.28) 0%, transparent 70%), linear-gradient(180deg, #1E3A8A 0%, #132252 35%, #0B1437 70%, #070D24 100%)',
                waveBg: 'linear-gradient(160deg, rgba(37, 99, 235, 0.55) 0%, rgba(6, 182, 212, 0.25) 65%, transparent 100%)'
            },
            emerald: {
                primary: '#059669',
                primaryHover: '#047857',
                sidebarBg: 'radial-gradient(ellipse 130% 50% at 15% 0%, #059669 0%, #047857 40%, transparent 80%), radial-gradient(ellipse 80% 40% at 100% 30%, rgba(52, 211, 153, 0.28) 0%, transparent 70%), linear-gradient(180deg, #064E3B 0%, #022C22 35%, #061A14 70%, #020D0A 100%)',
                waveBg: 'linear-gradient(160deg, rgba(5, 150, 105, 0.55) 0%, rgba(52, 211, 153, 0.25) 65%, transparent 100%)'
            },
            amber: {
                primary: '#D97706',
                primaryHover: '#B45309',
                sidebarBg: 'radial-gradient(ellipse 130% 50% at 15% 0%, #D97706 0%, #B45309 40%, transparent 80%), radial-gradient(ellipse 80% 40% at 100% 30%, rgba(251, 191, 36, 0.28) 0%, transparent 70%), linear-gradient(180deg, #78350F 0%, #451A03 35%, #240E02 70%, #140701 100%)',
                waveBg: 'linear-gradient(160deg, rgba(217, 119, 6, 0.55) 0%, rgba(251, 191, 36, 0.25) 65%, transparent 100%)'
            },
            rose: {
                primary: '#E11D48',
                primaryHover: '#BE123C',
                sidebarBg: 'radial-gradient(ellipse 130% 50% at 15% 0%, #E11D48 0%, #BE123C 40%, transparent 80%), radial-gradient(ellipse 80% 40% at 100% 30%, rgba(251, 113, 133, 0.28) 0%, transparent 70%), linear-gradient(180deg, #881337 0%, #4C0519 35%, #26020C 70%, #140106 100%)',
                waveBg: 'linear-gradient(160deg, rgba(225, 29, 72, 0.55) 0%, rgba(251, 113, 133, 0.25) 65%, transparent 100%)'
            },
            teal: {
                primary: '#0D9488',
                primaryHover: '#0F766E',
                sidebarBg: 'radial-gradient(ellipse 130% 50% at 15% 0%, #0D9488 0%, #0F766E 40%, transparent 80%), radial-gradient(ellipse 80% 40% at 100% 30%, rgba(45, 212, 191, 0.28) 0%, transparent 70%), linear-gradient(180deg, #134E4A 0%, #042F2E 35%, #021C1C 70%, #010F0F 100%)',
                waveBg: 'linear-gradient(160deg, rgba(13, 148, 136, 0.55) 0%, rgba(45, 212, 191, 0.25) 65%, transparent 100%)'
            }
        };

        function applyDynamicThemeStyle(color) {
            const t = themeGradients[color] || themeGradients.purple;
            let styleTag = document.getElementById('dynamic-theme-style');
            if (!styleTag) {
                styleTag = document.createElement('style');
                styleTag.id = 'dynamic-theme-style';
                document.head.appendChild(styleTag);
            }
            styleTag.innerHTML = `
                :root, html, body {
                    --primary: ${t.primary} !important;
                    --primary-hover: ${t.primaryHover} !important;
                }
                .sidebar {
                    background: ${t.sidebarBg} !important;
                }
                .sidebar::before {
                    background: ${t.waveBg} !important;
                }
                .nav-item.active {
                    color: ${t.primary} !important;
                }
                .btn-primary {
                    background: ${t.primary} !important;
                }
            `;
        }

        (function() {
            const savedColor = localStorage.getItem('org_theme_color') || 'purple';
            const savedMode = localStorage.getItem('org_theme_mode') || localStorage.getItem('org_theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedColor);
            if (savedMode === 'dark' || savedMode === 'dark-mode') {
                document.documentElement.classList.add('dark-mode');
            }
            applyDynamicThemeStyle(savedColor);
        })();
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ajaxSend(function(e, xhr, options) {
            if (options.type && options.type.toUpperCase() !== 'GET') {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            }
        });
    </script>
</head>
<body class="org-theme">
    <script>
        (function() {
            const savedColor = localStorage.getItem('unilms_theme_color') || 'purple';
            const savedMode = localStorage.getItem('unilms_theme_mode') || localStorage.getItem('org_theme') || 'light';
            document.body.setAttribute('data-theme', savedColor);
            if (savedMode === 'dark' || savedMode === 'dark-mode') {
                document.body.classList.add('dark-mode');
            }
        })();
    </script>
<?php
    $uri = uri_string();
    $activeModule = 'dashboard';
    if (strpos($uri, 'org/admissions') !== false) {
        $activeModule = 'admissions';
    } elseif (strpos($uri, 'org/academics') !== false || strpos($uri, 'org/faculty') !== false || strpos($uri, 'org/attendance') !== false || strpos($uri, 'org/obe') !== false || strpos($uri, 'org/marks') !== false || strpos($uri, 'org/assignments') !== false || strpos($uri, 'org/timetable') !== false || strpos($uri, 'org/materials') !== false || strpos($uri, 'org/quizzes') !== false || strpos($uri, 'org/assessments') !== false || strpos($uri, 'org/students') !== false) {
        $activeModule = 'academics';
    } elseif (strpos($uri, 'org/examinations') !== false) {
        $activeModule = 'examinations';
    } elseif (strpos($uri, 'org/fee-config') !== false || strpos($uri, 'org/fee-payments') !== false) {
        $activeModule = 'fees';
    } elseif (strpos($uri, 'org/hr') !== false || strpos($uri, 'org/payroll') !== false) {
        $activeModule = 'hr';
    } elseif (strpos($uri, 'org/accounts') !== false) {
        $activeModule = 'accounts';
    } elseif (strpos($uri, 'org/placements') !== false || strpos($uri, 'org/internships') !== false) {
        $activeModule = 'placements';
    } elseif (strpos($uri, 'org/transport') !== false) {
        $activeModule = 'transport';
    } elseif (strpos($uri, 'org/library') !== false) {
        $activeModule = 'library';
    } elseif (strpos($uri, 'org/staff') !== false) {
        $activeModule = 'staff';
    } elseif (strpos($uri, 'org/correspondence') !== false) {
        $activeModule = 'correspondence';
    } elseif (strpos($uri, 'org/front-office') !== false) {
        $activeModule = 'front_office';
    } elseif (strpos($uri, 'org/hostel') !== false) {
        $activeModule = 'hostel';
    } elseif (strpos($uri, 'org/administration') !== false || strpos($uri, 'org/certificates') !== false) {
        $activeModule = 'administration';
    } elseif (strpos($uri, 'org/lms') !== false) {
        $activeModule = 'lms';
    } elseif (strpos($uri, 'org/systems') !== false) {
        $activeModule = 'systems';
    }
?>

    <div class="app-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="<?= base_url('org/dashboard') ?>" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 10px;">
                    <div class="logo-icon-box">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div style="display: flex; flex-direction: column; overflow: hidden;">
                        <span style="font-size: 19px; font-weight: 700; color: #FFFFFF; line-height: 1.15; letter-spacing: -0.3px;">UniLMS</span>
                        <span style="font-size: 11px; font-weight: 500; color: rgba(255,255,255,0.85); margin-top: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 130px;"><?= esc(session()->get('org_name') ?? 'Apex Innovations') ?></span>
                    </div>
                </a>
            </div>
            
            <nav class="sidebar-nav">
                <?php if($activeModule === 'dashboard'): ?>
                    <div class="sidebar-section-title">Overview</div>
                    <a href="<?= base_url('org/dashboard') ?>" class="nav-item active">
                        <i class="fa-solid fa-table-cells-large"></i> <span>Dashboard</span>
                    </a>
                    <a href="<?= base_url('org/admissions') ?>" class="nav-item">
                        <i class="fa-solid fa-user-plus"></i> <span>Admissions</span>
                    </a>
                    <a href="<?= base_url('org/academics') ?>" class="nav-item">
                        <i class="fa-solid fa-graduation-cap"></i> <span>Academics</span>
                    </a>
                    <a href="<?= base_url('org/examinations') ?>" class="nav-item">
                        <i class="fa-solid fa-file-pen"></i> <span>Examinations</span>
                    </a>
                    <a href="<?= base_url('org/fee-payments/dues') ?>" class="nav-item">
                        <i class="fa-solid fa-indian-rupee-sign"></i> <span>Fees</span>
                    </a>
                    <a href="<?= base_url('org/hr/employees') ?>" class="nav-item">
                        <i class="fa-solid fa-users-viewfinder"></i> <span>HR & Payroll</span>
                    </a>
                    <a href="<?= base_url('org/accounts') ?>" class="nav-item">
                        <i class="fa-solid fa-chart-pie"></i> <span>Accounts</span>
                    </a>
                    <a href="<?= base_url('org/placements') ?>" class="nav-item">
                        <i class="fa-solid fa-briefcase"></i> <span>Placements</span>
                    </a>
                <?php elseif($activeModule === 'admissions'): ?>
                    <div class="sidebar-section-title">CMS — Admissions</div>
                    <a href="<?= base_url('org/admissions') ?>" class="nav-item <?= $uri == 'org/admissions' ? 'active' : '' ?>">
                        <i class="fa-solid fa-table-cells-large"></i> <span>Dashboard</span>
                    </a>
                    <a href="<?= base_url('org/admissions/leads') ?>" class="nav-item <?= strpos($uri, 'org/admissions/leads') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-users"></i> <span>Leads (CRM)</span>
                    </a>
                    <a href="<?= base_url('org/admissions/applications') ?>" class="nav-item <?= strpos($uri, 'org/admissions/applications') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-file-lines"></i> <span>Applications</span>
                    </a>
                    <a href="<?= base_url('org/admissions/documents') ?>" class="nav-item <?= strpos($uri, 'org/admissions/documents') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-shield-halved"></i> <span>Verification</span>
                    </a>
                    <a href="<?= base_url('org/admissions/offers') ?>" class="nav-item <?= strpos($uri, 'org/admissions/offers') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-envelope-open-text"></i> <span>Offers</span>
                    </a>
                    <a href="<?= base_url('org/admissions/payments') ?>" class="nav-item <?= strpos($uri, 'org/admissions/payments') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-indian-rupee-sign"></i> <span>Payments</span>
                    </a>
                    <a href="<?= base_url('org/admissions/enrollment') ?>" class="nav-item <?= strpos($uri, 'org/admissions/enrollment') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-user-plus"></i> <span>Enrollment</span>
                    </a>
                    <div class="sidebar-section-title">Admissions Extras</div>
                    <a href="<?= base_url('org/admissions/college-strength') ?>" class="nav-item <?= strpos($uri, 'org/admissions/college-strength') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-column"></i> <span>College Strength</span>
                    </a>
                    <a href="<?= base_url('org/admissions/categories') ?>" class="nav-item <?= strpos($uri, 'org/admissions/categories') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-tags"></i> <span>Categories/Castes</span>
                    </a>
                    <a href="<?= base_url('org/admissions/scholarships') ?>" class="nav-item <?= strpos($uri, 'org/admissions/scholarships') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-award"></i> <span>Scholarships</span>
                    </a>
                    <a href="<?= base_url('org/admissions/detained') ?>" class="nav-item <?= strpos($uri, 'org/admissions/detained') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-user-lock"></i> <span>Detained Students</span>
                    </a>
                <?php elseif($activeModule === 'academics'): ?>
                    <div class="sidebar-section-title">CMS — Academics</div>
                    <a href="<?= base_url('org/academics') ?>" class="nav-item <?= $uri == 'org/academics' ? 'active' : '' ?>">
                        <i class="fa-solid fa-graduation-cap"></i> <span>Overview</span>
                    </a>
                    <a href="<?= base_url('org/academics/departments') ?>" class="nav-item <?= strpos($uri, 'org/academics/departments') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-building"></i> <span>Departments</span>
                    </a>
                    <a href="<?= base_url('org/academics/programs') ?>" class="nav-item <?= strpos($uri, 'org/academics/programs') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-certificate"></i> <span>Programs</span>
                    </a>
                    <a href="<?= base_url('org/academics/regulations') ?>" class="nav-item <?= strpos($uri, 'org/academics/regulations') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-scroll"></i> <span>Academic Regulations</span>
                    </a>
                    <a href="<?= base_url('org/academics/academic_years') ?>" class="nav-item <?= strpos($uri, 'org/academics/academic_years') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-calendar"></i> <span>Academic Years</span>
                    </a>
                    <a href="<?= base_url('org/academics/semesters') ?>" class="nav-item <?= strpos($uri, 'org/academics/semesters') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-layer-group"></i> <span>Semesters</span>
                    </a>
                    <a href="<?= base_url('org/academics/cohorts') ?>" class="nav-item <?= strpos($uri, 'org/academics/cohorts') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-users"></i> <span>Batches (Cohorts)</span>
                    </a>
                    <a href="<?= base_url('org/academics/sections') ?>" class="nav-item <?= strpos($uri, 'org/academics/sections') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-people-line"></i> <span>Class Sections</span>
                    </a>
                    <a href="<?= base_url('org/academics/subjects') ?>" class="nav-item <?= strpos($uri, 'org/academics/subjects') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-book-open"></i> <span>Subjects</span>
                    </a>
                    <a href="<?= base_url('org/students') ?>" class="nav-item <?= strpos($uri, 'org/students') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-user-graduate"></i> <span>Student Directory</span>
                    </a>
                    <a href="<?= base_url('org/faculty/allocations') ?>" class="nav-item <?= strpos($uri, 'org/faculty/allocations') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-user-tie"></i> <span>Faculty Allocations</span>
                    </a>
                    <a href="<?= base_url('org/timetable') ?>" class="nav-item <?= strpos($uri, 'org/timetable') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-calendar-days"></i> <span>Timetable & Periods</span>
                    </a>
                    <a href="<?= base_url('org/attendance') ?>" class="nav-item <?= strpos($uri, 'org/attendance') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-clipboard-user"></i> <span>Attendance</span>
                    </a>
                    <a href="<?= base_url('org/marks/components') ?>" class="nav-item <?= strpos($uri, 'org/marks') !== false || strpos($uri, 'org/assessments') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-list-check"></i> <span>Marks & Assessments</span>
                    </a>
                    <a href="<?= base_url('org/materials') ?>" class="nav-item <?= strpos($uri, 'org/materials') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-book"></i> <span>Study Materials</span>
                    </a>
                    <div class="sidebar-section-title">OBE & NAAC Metric</div>
                    <a href="<?= base_url('org/obe/po-setup') ?>" class="nav-item <?= strpos($uri, 'org/obe/po-setup') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-award"></i> <span>POs & PSOs Setup</span>
                    </a>
                    <a href="<?= base_url('org/obe/matrix') ?>" class="nav-item <?= strpos($uri, 'org/obe/matrix') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-table-cells"></i> <span>CO-PO Matrix</span>
                    </a>
                    <a href="<?= base_url('org/obe/attainment') ?>" class="nav-item <?= strpos($uri, 'org/obe/attainment') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-line"></i> <span>Attainment Report</span>
                    </a>
                    <a href="<?= base_url('org/obe/gap-analysis') ?>" class="nav-item <?= strpos($uri, 'org/obe/gap-analysis') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-triangle-exclamation"></i> <span>Gap Analysis</span>
                    </a>
                <?php elseif($activeModule === 'examinations'): ?>
                    <div class="sidebar-section-title">CMS — Examinations</div>
                    <a href="<?= base_url('org/examinations') ?>" class="nav-item <?= $uri == 'org/examinations' ? 'active' : '' ?>">
                        <i class="fa-solid fa-graduation-cap"></i> <span>Exam Setup</span>
                    </a>
                    <a href="<?= base_url('org/examinations/schedules') ?>" class="nav-item <?= strpos($uri, 'org/examinations/schedules') !== false ? 'active' : '' ?>">
                        <i class="fa-regular fa-calendar-days"></i> <span>Schedules & Timetable</span>
                    </a>
                    <a href="<?= base_url('org/examinations/applications') ?>" class="nav-item <?= strpos($uri, 'org/examinations/applications') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-address-card"></i> <span>Student Applications</span>
                    </a>
                    <a href="<?= base_url('org/examinations/hall-tickets') ?>" class="nav-item <?= strpos($uri, 'org/examinations/hall-tickets') !== false || strpos($uri, 'hall_tickets') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-ticket"></i> <span>Hall Tickets</span>
                    </a>
                    <a href="<?= base_url('org/examinations/invigilation') ?>" class="nav-item <?= strpos($uri, 'org/examinations/invigilation') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-user-shield"></i> <span>Invigilation Duties</span>
                    </a>
                    <a href="<?= base_url('org/examinations/marks') ?>" class="nav-item <?= strpos($uri, 'org/examinations/marks') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-marker"></i> <span>Marks Entry</span>
                    </a>
                    <a href="<?= base_url('org/examinations/backlogs') ?>" class="nav-item <?= strpos($uri, 'org/examinations/backlogs') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-clock-rotate-left"></i> <span>Backlogs</span>
                    </a>
                <?php elseif($activeModule === 'fees'): ?>
                    <div class="sidebar-section-title">CMS — Fee Management</div>
                    <a href="<?= base_url('org/fee-payments/dues') ?>" class="nav-item <?= strpos($uri, 'org/fee-payments') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-indian-rupee-sign"></i> <span>Dues & Collection</span>
                    </a>
                    <a href="<?= base_url('org/fee-config/structures') ?>" class="nav-item <?= strpos($uri, 'org/fee-config/structures') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-layer-group"></i> <span>Fee Structures</span>
                    </a>
                    <a href="<?= base_url('org/fee-config/manual-assign') ?>" class="nav-item <?= strpos($uri, 'org/fee-config/manual-assign') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-user-tag"></i> <span>Manual Assign</span>
                    </a>
                    <a href="<?= base_url('org/fee-config/types') ?>" class="nav-item <?= strpos($uri, 'org/fee-config/types') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-tags"></i> <span>Fee Types</span>
                    </a>
                <?php elseif($activeModule === 'hr'): ?>
                    <div class="sidebar-section-title">CMS — HR & Payroll</div>
                    <a href="<?= base_url('org/hr/employees') ?>" class="nav-item <?= strpos($uri, 'org/hr/employees') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-users-viewfinder"></i> <span>Directory</span>
                    </a>
                    <a href="<?= base_url('org/hr/appraisals') ?>" class="nav-item <?= strpos($uri, 'org/hr/appraisals') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-line"></i> <span>Appraisals</span>
                    </a>
                    <a href="<?= base_url('org/hr/leaves') ?>" class="nav-item <?= strpos($uri, 'org/hr/leaves') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-person-walking-arrow-right"></i> <span>Leaves</span>
                    </a>
                    <a href="<?= base_url('org/payroll/payslips') ?>" class="nav-item <?= strpos($uri, 'org/payroll/payslips') !== false || $uri === 'org/hr/payroll' ? 'active' : '' ?>">
                        <i class="fa-solid fa-file-invoice-dollar"></i> <span>Payslips</span>
                    </a>
                    <a href="<?= base_url('org/payroll/structures') ?>" class="nav-item <?= strpos($uri, 'org/payroll/structures') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-network-wired"></i> <span>Salary Structures</span>
                    </a>
                    <a href="<?= base_url('org/payroll/components') ?>" class="nav-item <?= strpos($uri, 'org/payroll/components') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-calculator"></i> <span>Pay Components</span>
                    </a>
                <?php elseif($activeModule === 'accounts'): ?>
                    <div class="sidebar-section-title">CMS — Accounts & Finance</div>
                    <a href="<?= base_url('org/accounts') ?>" class="nav-item <?= $uri == 'org/accounts' ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-pie"></i> <span>Dashboard</span>
                    </a>
                    <a href="<?= base_url('org/accounts/heads') ?>" class="nav-item <?= strpos($uri, 'org/accounts/heads') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-tags"></i> <span>Chart of Accounts</span>
                    </a>
                    <a href="<?= base_url('org/accounts/banks') ?>" class="nav-item <?= strpos($uri, 'org/accounts/banks') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-building-columns"></i> <span>Bank & Cash A/c</span>
                    </a>
                    <a href="<?= base_url('org/accounts/transactions') ?>" class="nav-item <?= strpos($uri, 'org/accounts/transactions') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-money-bill-transfer"></i> <span>Transactions</span>
                    </a>
                    <a href="<?= base_url('org/accounts/daybook') ?>" class="nav-item <?= strpos($uri, 'org/accounts/daybook') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-book"></i> <span>Day Book</span>
                    </a>
                    <a href="<?= base_url('org/accounts/pl-statement') ?>" class="nav-item <?= strpos($uri, 'org/accounts/pl-statement') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-line"></i> <span>Profit & Loss</span>
                    </a>
                <?php elseif($activeModule === 'placements'): ?>
                    <div class="sidebar-section-title">CMS — Placements & TPO</div>
                    <a href="<?= base_url('org/placements') ?>" class="nav-item <?= $uri == 'org/placements' ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-pie"></i> <span>Dashboard</span>
                    </a>
                    <a href="<?= base_url('org/internships') ?>" class="nav-item <?= strpos($uri, 'org/internships') !== false && strpos($uri, 'tpoAudit') === false ? 'active' : '' ?>">
                        <i class="fa-solid fa-map-signs"></i> <span>Internship Roadmaps</span>
                    </a>
                    <a href="<?= base_url('org/placements/companies') ?>" class="nav-item <?= strpos($uri, 'org/placements/companies') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-building"></i> <span>Partners / Companies</span>
                    </a>
                    <a href="<?= base_url('org/placements/drives') ?>" class="nav-item <?= strpos($uri, 'org/placements/drives') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-bullhorn"></i> <span>Recruitment Drives</span>
                    </a>
                    <a href="<?= base_url('org/placements/offers') ?>" class="nav-item <?= strpos($uri, 'org/placements/offers') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-briefcase"></i> <span>Job Offers</span>
                    </a>
                <?php elseif($activeModule === 'transport'): ?>
                    <div class="sidebar-section-title">CMS — Transport Services</div>
                    <a href="<?= base_url('org/transport/vehicles') ?>" class="nav-item <?= strpos($uri, 'org/transport/vehicles') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-van-shuttle"></i> <span>Vehicles Fleet</span>
                    </a>
                    <a href="<?= base_url('org/transport/routes') ?>" class="nav-item <?= strpos($uri, 'org/transport/routes') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-route"></i> <span>Routes Planner</span>
                    </a>
                    <a href="<?= base_url('org/transport/halts') ?>" class="nav-item <?= strpos($uri, 'org/transport/halts') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-map-pin"></i> <span>Stops & Pricing</span>
                    </a>
                    <a href="<?= base_url('org/transport/subscriptions') ?>" class="nav-item <?= strpos($uri, 'org/transport/subscriptions') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-id-card"></i> <span>Subscriptions</span>
                    </a>
                <?php elseif($activeModule === 'library'): ?>
                    <div class="sidebar-section-title">CMS — Library</div>
                    <a href="<?= base_url('org/library') ?>" class="nav-item <?= $uri == 'org/library' ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-pie"></i> <span>Dashboard</span>
                    </a>
                    <a href="<?= base_url('org/library/books') ?>" class="nav-item <?= strpos($uri, 'org/library/books') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-book"></i> <span>Books Catalog</span>
                    </a>
                    <a href="<?= base_url('org/library/members') ?>" class="nav-item <?= strpos($uri, 'org/library/members') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-users"></i> <span>Members</span>
                    </a>
                    <a href="<?= base_url('org/library/issues') ?>" class="nav-item <?= strpos($uri, 'org/library/issues') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-hand-holding-hand"></i> <span>Issue / Return</span>
                    </a>
                    <a href="<?= base_url('org/library/categories') ?>" class="nav-item <?= strpos($uri, 'org/library/categories') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-tags"></i> <span>Categories</span>
                    </a>
                <?php elseif($activeModule === 'staff'): ?>
                    <div class="sidebar-section-title">CMS — Staff (Academic)</div>
                    <a href="<?= base_url('org/staff/directory') ?>" class="nav-item <?= strpos($uri, 'org/staff/directory') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-address-book"></i> <span>Directory Lookup</span>
                    </a>
                    <a href="<?= base_url('org/staff/duties') ?>" class="nav-item <?= strpos($uri, 'org/staff/duties') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-list-check"></i> <span>Academic Duties</span>
                    </a>
                    <a href="<?= base_url('org/staff/certificates') ?>" class="nav-item <?= strpos($uri, 'org/staff/certificates') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-certificate"></i> <span>Certificates</span>
                    </a>
                <?php elseif($activeModule === 'correspondence'): ?>
                    <div class="sidebar-section-title">CMS — Correspondence</div>
                    <a href="<?= base_url('org/correspondence') ?>" class="nav-item <?= $uri == 'org/correspondence' ? 'active' : '' ?>">
                        <i class="fa-solid fa-bell"></i> <span>Dashboard</span>
                    </a>
                    <a href="<?= base_url('org/correspondence/templates') ?>" class="nav-item <?= strpos($uri, 'org/correspondence/templates') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-layer-group"></i> <span>Message Templates</span>
                    </a>
                    <a href="<?= base_url('org/correspondence/festivals') ?>" class="nav-item <?= strpos($uri, 'org/correspondence/festivals') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-calendar-check"></i> <span>Festival Greetings</span>
                    </a>
                <?php elseif($activeModule === 'front_office'): ?>
                    <div class="sidebar-section-title">CMS — Front Office</div>
                    <a href="<?= base_url('org/front-office') ?>" class="nav-item <?= $uri == 'org/front-office' ? 'active' : '' ?>">
                        <i class="fa-solid fa-headset"></i> <span>Hub Overview</span>
                    </a>
                    <a href="<?= base_url('org/front-office/visitors') ?>" class="nav-item <?= strpos($uri, 'org/front-office/visitors') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-person-walking"></i> <span>Visitors Book</span>
                    </a>
                    <a href="<?= base_url('org/front-office/calls') ?>" class="nav-item <?= strpos($uri, 'org/front-office/calls') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-phone"></i> <span>Call Logs</span>
                    </a>
                    <a href="<?= base_url('org/front-office/postal') ?>" class="nav-item <?= strpos($uri, 'org/front-office/postal') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-envelopes-bulk"></i> <span>Postal Dispatch</span>
                    </a>
                    <a href="<?= base_url('org/front-office/enquiries') ?>" class="nav-item <?= strpos($uri, 'org/front-office/enquiries') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-clipboard-question"></i> <span>Admission Enquiries</span>
                    </a>
                <?php elseif($activeModule === 'hostel'): ?>
                    <div class="sidebar-section-title">CMS — Hostel</div>
                    <a href="<?= base_url('org/hostel') ?>" class="nav-item <?= $uri == 'org/hostel' ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-pie"></i> <span>Dashboard</span>
                    </a>
                    <a href="<?= base_url('org/hostel/rooms') ?>" class="nav-item <?= strpos($uri, 'org/hostel/rooms') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-bed"></i> <span>Rooms Setup</span>
                    </a>
                    <a href="<?= base_url('org/hostel/registrations') ?>" class="nav-item <?= strpos($uri, 'org/hostel/registrations') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-address-card"></i> <span>Registrations</span>
                    </a>
                    <a href="<?= base_url('org/hostel/outings') ?>" class="nav-item <?= strpos($uri, 'org/hostel/outings') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-person-walking-arrow-right"></i> <span>Outings / Passes</span>
                    </a>
                <?php elseif($activeModule === 'administration'): ?>
                    <div class="sidebar-section-title">CMS — Administration</div>
                    <a href="<?= base_url('org/administration') ?>" class="nav-item <?= $uri == 'org/administration' ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-pie"></i> <span>Dashboard</span>
                    </a>
                    <a href="<?= base_url('org/administration/college-details') ?>" class="nav-item <?= strpos($uri, 'org/administration/college-details') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-building"></i> <span>College Details</span>
                    </a>
                    <a href="<?= base_url('org/administration/lecture-halls') ?>" class="nav-item <?= strpos($uri, 'org/administration/lecture-halls') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-chalkboard"></i> <span>Lecture Halls</span>
                    </a>
                    <a href="<?= base_url('org/administration/locations') ?>" class="nav-item <?= strpos($uri, 'org/administration/locations') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-map-location-dot"></i> <span>Locations</span>
                    </a>
                    <a href="<?= base_url('org/administration/agents') ?>" class="nav-item <?= strpos($uri, 'org/administration/agents') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-users-viewfinder"></i> <span>Agents/Counselors</span>
                    </a>
                    <a href="<?= base_url('org/administration/holidays') ?>" class="nav-item <?= strpos($uri, 'org/administration/holidays') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-umbrella-beach"></i> <span>Holidays</span>
                    </a>
                <?php elseif($activeModule === 'lms'): ?>
                    <div class="sidebar-section-title">CMS — LMS</div>
                    <a href="<?= base_url('org/lms/courses') ?>" class="nav-item <?= strpos($uri, 'org/lms') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-chalkboard-user"></i> <span>Courses</span>
                    </a>
                <?php elseif($activeModule === 'systems'): ?>
                    <div class="sidebar-section-title">CMS — System Settings</div>
                    <?php if(session()->get('is_org_admin')): ?>
                    <a href="<?= base_url('org/systems/settings') ?>" class="nav-item <?= strpos($uri, 'org/systems/settings') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-building"></i> <span>Global Settings</span>
                    </a>
                    <?php endif; ?>
                    <a href="<?= base_url('org/systems/users') ?>" class="nav-item <?= strpos($uri, 'org/systems/users') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-users-gear"></i> <span>Users</span>
                    </a>
                    <a href="<?= base_url('org/systems/access-groups') ?>" class="nav-item <?= strpos($uri, 'org/systems/access-groups') !== false ? 'active' : '' ?>">
                        <i class="fa-solid fa-shield-halved"></i> <span>Access Groups</span>
                    </a>
                <?php endif; ?>
            </nav>

            <div class="sidebar-footer">
                <button class="theme-toggle" id="theme-toggle-btn" style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 20px; height: 20px; background: #FFFFFF; color: #FF5722; border-radius: 6px; font-size: 10px;"><i class="fa-solid fa-chevron-left"></i></span>
                        <span>Toggle Menu</span>
                    </div>
                </button>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <header class="topbar multi-row">
                <div class="topbar-main-row">
                    <div class="topbar-left">
                        <h1 id="page-title"><?= $this->renderSection('page_title') ?></h1>
                    </div>

                    <!-- Global Search Bar -->
                    <div class="topbar-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Search anything..." id="globalSearchInput" autocomplete="off">
                    </div>

                    <div class="topbar-right" style="display: flex; align-items: center; gap: 16px;">
                        <!-- Theme & Color Picker Trigger -->
                        <div class="theme-palette-picker" style="position: relative;">
                            <button type="button" onclick="togglePaletteMenu(event)" id="paletteBtn" style="background: var(--card-bg, #fff); border: 1px solid var(--border-color, #e2e8f0); border-radius: 50%; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-primary); transition: all 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.08); font-size: 18px;" title="Theme & Color Customizer">
                                🎨
                            </button>
                            <div id="paletteDropdown" style="display: none; position: absolute; right: 0; top: 44px; z-index: 9999; background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 14px; padding: 14px 18px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); width: 220px; animation: modalFadeIn 0.2s ease;">
                                <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 10px; letter-spacing: 0.5px;">Theme Accent Color</div>
                                <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 8px; margin-bottom: 14px;">
                                    <button type="button" onclick="setThemeColor('purple')" style="width: 24px; height: 24px; border-radius: 50%; background: #7C3AED; border: 2px solid #fff; box-shadow: 0 0 0 1px #7C3AED; cursor: pointer;" title="Royal Purple"></button>
                                    <button type="button" onclick="setThemeColor('blue')" style="width: 24px; height: 24px; border-radius: 50%; background: #2563EB; border: 2px solid #fff; box-shadow: 0 0 0 1px #2563EB; cursor: pointer;" title="Sapphire Blue"></button>
                                    <button type="button" onclick="setThemeColor('emerald')" style="width: 24px; height: 24px; border-radius: 50%; background: #059669; border: 2px solid #fff; box-shadow: 0 0 0 1px #059669; cursor: pointer;" title="Emerald Green"></button>
                                    <button type="button" onclick="setThemeColor('amber')" style="width: 24px; height: 24px; border-radius: 50%; background: #D97706; border: 2px solid #fff; box-shadow: 0 0 0 1px #D97706; cursor: pointer;" title="Sunset Amber"></button>
                                    <button type="button" onclick="setThemeColor('rose')" style="width: 24px; height: 24px; border-radius: 50%; background: #E11D48; border: 2px solid #fff; box-shadow: 0 0 0 1px #E11D48; cursor: pointer;" title="Crimson Rose"></button>
                                    <button type="button" onclick="setThemeColor('teal')" style="width: 24px; height: 24px; border-radius: 50%; background: #0D9488; border: 2px solid #fff; box-shadow: 0 0 0 1px #0D9488; cursor: pointer;" title="Deep Teal"></button>
                                </div>
                                <div style="border-top: 1px solid var(--border-color); padding-top: 10px; display: flex; align-items: center; justify-content: space-between;">
                                    <span style="font-size: 12px; font-weight: 600; color: var(--text-primary);">Dark Theme</span>
                                    <button type="button" onclick="toggleDarkMode()" id="darkModeToggleBtn" style="background: var(--bg-main); border: 1px solid var(--border-color); border-radius: 20px; padding: 4px 10px; font-size: 11px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 4px; color: var(--text-primary);">
                                        <i class="fa-solid fa-moon"></i> <span>Toggle</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <?php
                            $db = \Config\Database::connect();
                            $unreadCount = 0;
                            if (session('org_user_id')) {
                                $unreadCount = $db->table('inapp_notifications')
                                    ->where('user_id', session('org_user_id'))
                                    ->where('is_read', 0)
                                    ->countAllResults();
                            }
                        ?>
                        <a href="<?= base_url('org/correspondence') ?>" class="notifications-bell" style="position: relative; cursor: pointer; color: var(--text-secondary); text-decoration: none;" title="Notifications">
                            <i class="fa-solid fa-bell" style="font-size: 20px;"></i>
                            <span id="global_unread_badge" style="display: <?= $unreadCount > 0 ? 'inline-flex' : 'none' ?>; position: absolute; top: -6px; right: -8px; background: #7C3AED; color: white; font-size: 11px; font-weight: 700; width: 18px; height: 18px; border-radius: 50%; align-items: center; justify-content: center; line-height: 1; border: 2px solid #ffffff;"><?= $unreadCount ?: 3 ?></span>
                        </a>
                        <div class="user-profile">
                            <div class="avatar" style="background: #7C3AED; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;">
                                <?= strtoupper(substr(session()->get('org_user_name') ?: session()->get('org_user_email') ?: 'A', 0, 1)) ?>
                            </div>
                            <div class="user-info">
                                <span class="user-name" style="font-weight: 600;"><?= esc(session()->get('org_user_email') ?: session()->get('org_user_name') ?: 'admin@apexinnovations.com') ?></span>
                                <span class="user-role" style="font-size: 12px;"><?= session()->get('is_org_admin') ? 'Organization Admin' : 'Staff' ?> | <a href="#" onclick="document.getElementById('logoutModal').style.display='flex'" style="color: #ef4444; text-decoration: none; font-weight: 500;">Logout</a></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Single-Row Modern Module Navigation Card -->
                <div class="org-modules-card">
                    <div class="module-nav-grid">
                        <!-- 1. Admissions -->
                        <a href="<?= base_url('org/admissions') ?>" class="module-nav-item <?= $activeModule === 'admissions' ? 'active' : '' ?>" title="Admissions">
                            <div class="module-icon-circle" style="background: #EDE9FE; color: #7C3AED;">
                                <i class="fa-solid fa-user-plus"></i>
                            </div>
                            <span class="module-nav-label">Admissions</span>
                        </a>

                        <!-- 2. Academics -->
                        <a href="<?= base_url('org/academics') ?>" class="module-nav-item <?= $activeModule === 'academics' ? 'active' : '' ?>" title="Academics">
                            <div class="module-icon-circle" style="background: #E0F2FE; color: #0284C7;">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>
                            <span class="module-nav-label">Academics</span>
                        </a>

                        <!-- 3. Examinations -->
                        <a href="<?= base_url('org/examinations') ?>" class="module-nav-item <?= $activeModule === 'examinations' ? 'active' : '' ?>" title="Examinations">
                            <div class="module-icon-circle" style="background: #DCFCE7; color: #16A34A;">
                                <i class="fa-solid fa-file-signature"></i>
                            </div>
                            <span class="module-nav-label">Examinations</span>
                        </a>

                        <!-- 4. Fees -->
                        <a href="<?= base_url('org/fee-payments/dues') ?>" class="module-nav-item <?= $activeModule === 'fees' ? 'active' : '' ?>" title="Fees">
                            <div class="module-icon-circle" style="background: #FEF3C7; color: #D97706;">
                                <i class="fa-solid fa-indian-rupee-sign"></i>
                            </div>
                            <span class="module-nav-label">Fees</span>
                        </a>

                        <!-- 5. HR & Payroll -->
                        <a href="<?= base_url('org/hr/employees') ?>" class="module-nav-item <?= $activeModule === 'hr' ? 'active' : '' ?>" title="HR & Payroll">
                            <div class="module-icon-circle" style="background: #FCE7F3; color: #DB2777;">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <span class="module-nav-label">HR & Payroll</span>
                        </a>

                        <!-- 6. Accounts -->
                        <a href="<?= base_url('org/accounts') ?>" class="module-nav-item <?= $activeModule === 'accounts' ? 'active' : '' ?>" title="Accounts">
                            <div class="module-icon-circle" style="background: #E0E7FF; color: #4F46E5;">
                                <i class="fa-solid fa-credit-card"></i>
                            </div>
                            <span class="module-nav-label">Accounts</span>
                        </a>

                        <!-- 7. Placements -->
                        <a href="<?= base_url('org/placements') ?>" class="module-nav-item <?= $activeModule === 'placements' ? 'active' : '' ?>" title="Placements">
                            <div class="module-icon-circle" style="background: #FFEDD5; color: #EA580C;">
                                <i class="fa-solid fa-briefcase"></i>
                            </div>
                            <span class="module-nav-label">Placements</span>
                        </a>

                        <!-- 8. Transport -->
                        <a href="<?= base_url('org/transport/vehicles') ?>" class="module-nav-item <?= $activeModule === 'transport' ? 'active' : '' ?>" title="Transport">
                            <div class="module-icon-circle" style="background: #CCFBF1; color: #0D9488;">
                                <i class="fa-solid fa-bus"></i>
                            </div>
                            <span class="module-nav-label">Transport</span>
                        </a>

                        <!-- 9. More (Dropdown Trigger ▦) -->
                        <div class="more-modules-container" id="moreModulesContainer">
                            <button type="button" class="module-nav-item <?= in_array($activeModule, ['library','staff','correspondence','front_office','hostel','administration','lms','systems']) ? 'active' : '' ?>" id="moreModulesBtn" onclick="toggleMoreModules(event)" aria-expanded="false" aria-haspopup="true" title="More Modules">
                                <div class="module-icon-circle" style="background: #F1F5F9; color: #475569;">
                                    <i class="fa-solid fa-table-cells-large"></i>
                                </div>
                                <span class="module-nav-label">More</span>
                            </button>

                            <!-- Attractive More Dropdown Menu with all other modules -->
                            <div class="more-modules-dropdown" id="moreModulesDropdown">
                                <div class="more-dropdown-arrow"></div>
                                
                                <div class="more-dropdown-list">
                                    <!-- 1. 📚 Library -->
                                    <a href="<?= base_url('org/library') ?>" class="more-dropdown-item <?= $activeModule === 'library' ? 'active' : '' ?>">
                                        <div class="more-dropdown-icon" style="background: #ECFDF5; color: #059669;">
                                            <i class="fa-solid fa-book-bookmark"></i>
                                        </div>
                                        <div class="more-dropdown-info">
                                            <div class="more-dropdown-title">📚 Library</div>
                                            <div class="more-dropdown-sub">Books catalog, issues & members</div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right more-dropdown-chevron"></i>
                                    </a>

                                    <!-- 2. 👨‍🏫 Staff (Academic) -->
                                    <a href="<?= base_url('org/staff') ?>" class="more-dropdown-item <?= $activeModule === 'staff' ? 'active' : '' ?>">
                                        <div class="more-dropdown-icon" style="background: #F5F3FF; color: #7C3AED;">
                                            <i class="fa-solid fa-chalkboard-user"></i>
                                        </div>
                                        <div class="more-dropdown-info">
                                            <div class="more-dropdown-title">👨‍🏫 Staff (Academic)</div>
                                            <div class="more-dropdown-sub">Directory, duties & checklists</div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right more-dropdown-chevron"></i>
                                    </a>

                                    <!-- 3. 📢 Correspondence -->
                                    <a href="<?= base_url('org/correspondence') ?>" class="more-dropdown-item <?= $activeModule === 'correspondence' ? 'active' : '' ?>">
                                        <div class="more-dropdown-icon" style="background: #FFF1F2; color: #E11D48;">
                                            <i class="fa-solid fa-bullhorn"></i>
                                        </div>
                                        <div class="more-dropdown-info">
                                            <div class="more-dropdown-title">📢 Correspondence</div>
                                            <div class="more-dropdown-sub">Circulars, notices & SMS</div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right more-dropdown-chevron"></i>
                                    </a>

                                    <!-- 4. 🎧 Front Office -->
                                    <a href="<?= base_url('org/front-office') ?>" class="more-dropdown-item <?= $activeModule === 'front_office' ? 'active' : '' ?>">
                                        <div class="more-dropdown-icon" style="background: #F0F9FF; color: #0284C7;">
                                            <i class="fa-solid fa-headset"></i>
                                        </div>
                                        <div class="more-dropdown-info">
                                            <div class="more-dropdown-title">🎧 Front Office</div>
                                            <div class="more-dropdown-sub">Visitors, calls & enquiries</div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right more-dropdown-chevron"></i>
                                    </a>

                                    <!-- 5. 🏨 Hostel Management -->
                                    <a href="<?= base_url('org/hostel') ?>" class="more-dropdown-item <?= $activeModule === 'hostel' ? 'active' : '' ?>">
                                        <div class="more-dropdown-icon" style="background: #FEF3C7; color: #D97706;">
                                            <i class="fa-solid fa-bed"></i>
                                        </div>
                                        <div class="more-dropdown-info">
                                            <div class="more-dropdown-title">🏨 Hostel</div>
                                            <div class="more-dropdown-sub">Rooms, passes & outings</div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right more-dropdown-chevron"></i>
                                    </a>

                                    <!-- 6. 🏛️ Administration -->
                                    <a href="<?= base_url('org/administration') ?>" class="more-dropdown-item <?= $activeModule === 'administration' ? 'active' : '' ?>">
                                        <div class="more-dropdown-icon" style="background: #F1F5F9; color: #475569;">
                                            <i class="fa-solid fa-building-user"></i>
                                        </div>
                                        <div class="more-dropdown-info">
                                            <div class="more-dropdown-title">🏛️ Administration</div>
                                            <div class="more-dropdown-sub">College profile, halls & certs</div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right more-dropdown-chevron"></i>
                                    </a>

                                    <!-- 7. 🎓 LMS -->
                                    <a href="<?= base_url('org/lms/courses') ?>" class="more-dropdown-item <?= $activeModule === 'lms' ? 'active' : '' ?>">
                                        <div class="more-dropdown-icon" style="background: #EEF2FF; color: #4F46E5;">
                                            <i class="fa-solid fa-graduation-cap"></i>
                                        </div>
                                        <div class="more-dropdown-info">
                                            <div class="more-dropdown-title">🎓 LMS</div>
                                            <div class="more-dropdown-sub">Courses, lessons & quizzes</div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right more-dropdown-chevron"></i>
                                    </a>

                                    <!-- 8. ⚙️ System Settings -->
                                    <a href="<?= base_url('org/systems/users') ?>" class="more-dropdown-item <?= $activeModule === 'systems' ? 'active' : '' ?>">
                                        <div class="more-dropdown-icon" style="background: #F8FAFC; color: #334155;">
                                            <i class="fa-solid fa-gear"></i>
                                        </div>
                                        <div class="more-dropdown-info">
                                            <div class="more-dropdown-title">⚙️ System Settings</div>
                                            <div class="more-dropdown-sub">Users, access groups & config</div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right more-dropdown-chevron"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div style="padding: 0 32px;">
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="flash-msg" style="padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; background: rgba(5,205,153,0.1); color: var(--success); border: 1px solid rgba(5,205,153,0.2); transition: opacity 0.5s ease;">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>
                <?php if(session()->getFlashdata('error')): ?>
                    <div class="flash-msg" style="padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; background: rgba(238,93,80,0.1); color: var(--danger); border: 1px solid rgba(238,93,80,0.2); transition: opacity 0.5s ease;">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <script>
            document.addEventListener("DOMContentLoaded", function() {
                setTimeout(function() {
                    const flashes = document.querySelectorAll('.flash-msg');
                    flashes.forEach(flash => {
                        flash.style.opacity = '0';
                        setTimeout(() => flash.remove(), 500); // Wait for fade out to complete before removing from DOM
                    });
                }, 3000);
            });
            </script>

            <div class="content-body" style="padding: 0 16px 20px;">
                <?= $this->renderSection('content') ?>
            </div>

        </main>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px); align-items:center; justify-content:center;">
        <div style="background:var(--card-bg, #fff); border-radius:16px; padding:32px 36px; max-width:400px; width:90%; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,0.3); animation: modalFadeIn 0.25s ease;">
            <div style="width:56px; height:56px; margin:0 auto 16px; border-radius:50%; background:rgba(239,68,68,0.12); display:flex; align-items:center; justify-content:center;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </div>
            <h3 style="margin:0 0 8px; font-size:20px; font-weight:700; color:var(--text-primary, #1a1a2e);">Confirm Logout</h3>
            <p style="margin:0 0 24px; font-size:14px; color:var(--text-secondary, #6b7280); line-height:1.5;">Are you sure you want to log out? You will need to sign in again to access the dashboard.</p>
            <div style="display:flex; gap:12px; justify-content:center;">
                <button onclick="document.getElementById('logoutModal').style.display='none'" style="flex:1; padding:10px 20px; border-radius:10px; border:1px solid var(--border-color, #e5e7eb); background:transparent; color:var(--text-primary, #374151); font-size:14px; font-weight:600; cursor:pointer; transition:all 0.2s;">Cancel</button>
                <a href="<?= base_url('org/logout') ?>" style="flex:1; padding:10px 20px; border-radius:10px; border:none; background:linear-gradient(135deg, #ef4444, #dc2626); color:#fff; font-size:14px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.2s;">Logout</a>
            </div>
        </div>
    </div>
    <style>
        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.9) translateY(10px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
    </style>
    
    <script>
        // Toggle More Modules dropdown popover
        function toggleMoreModules(event) {
            if (event) {
                event.stopPropagation();
                event.preventDefault();
            }
            const dropdown = document.getElementById('moreModulesDropdown');
            const btn = document.getElementById('moreModulesBtn');
            if (!dropdown) return;
            
            const isOpen = dropdown.classList.contains('show');
            if (isOpen) {
                dropdown.classList.remove('show');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            } else {
                dropdown.classList.add('show');
                if (btn) btn.setAttribute('aria-expanded', 'true');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('moreModulesDropdown');
            const btn = document.getElementById('moreModulesBtn');
            if (dropdown && dropdown.classList.contains('show')) {
                if (!dropdown.contains(e.target) && (!btn || !btn.contains(e.target))) {
                    dropdown.classList.remove('show');
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                }
            }
        });

        // Keyboard navigation: Escape to close dropdown, Ctrl+K to focus search
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const dropdown = document.getElementById('moreModulesDropdown');
                const btn = document.getElementById('moreModulesBtn');
                if (dropdown && dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                    if (btn) {
                        btn.setAttribute('aria-expanded', 'false');
                        btn.focus();
                    }
                }
            }
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                const searchInput = document.getElementById('globalSearchInput');
                if (searchInput) {
                    e.preventDefault();
                    searchInput.focus();
                    searchInput.select();
                }
            }
        });
    </script>
    
    <script>
        const themeToggleBtn = document.getElementById('theme-toggle-btn');
        const body = document.body;
        const savedTheme = localStorage.getItem('org_theme');
        if (savedTheme === 'dark-mode') { body.classList.add('dark-mode'); }
        themeToggleBtn.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('org_theme', 'dark-mode');
            } else {
                localStorage.setItem('org_theme', '');
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.querySelector('.sidebar-nav');
            if (sidebar) {
                const savedScroll = sessionStorage.getItem('orgSidebarScroll');
                if (savedScroll) {
                    sidebar.scrollTop = savedScroll;
                }
                
                window.addEventListener('beforeunload', () => {
                    sessionStorage.setItem('orgSidebarScroll', sidebar.scrollTop);
                });
            }
        });
        
        function openDrawer(id) {
            document.getElementById(id).classList.add('active');
        }
        function closeDrawer(id) {
            document.getElementById(id).classList.remove('active');
        }

        // Global Real-Time Notification Poller
        setInterval(function() {
            fetch('<?= base_url('org/notifications/unread-count') ?>')
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        const badge = document.getElementById('global_unread_badge');
                        if (badge) {
                            if (data.unread > 0) {
                                badge.innerText = data.unread;
                                badge.style.display = 'inline-block';
                            } else {
                                badge.style.display = 'none';
                            }
                        }
                    }
                })
                .catch(err => console.debug('Notif poller idle'));
        }, 30000); // 30s background sync

        // =========================================================================
        // UNIVERSAL INPUT MASKING & CONSTRAINTS (Phone: 10 digits, Aadhaar: 12, PIN: 6)
        // =========================================================================
        document.addEventListener('DOMContentLoaded', function() {
            applyUniversalInputConstraints();
        });

        document.addEventListener('input', function(e) {
            handleUniversalInputField(e.target);
        });

        document.addEventListener('keypress', function(e) {
            if (!isUniversalCharAllowed(e.target, e)) {
                e.preventDefault();
            }
        });

        function applyUniversalInputConstraints() {
            document.querySelectorAll('input').forEach(input => {
                const name = (input.name || '').toLowerCase();
                const id = (input.id || '').toLowerCase();
                const type = (input.type || '').toLowerCase();

                // Explicitly ignore email, password, and search inputs
                if (type === 'email' || name.includes('email') || id.includes('email') || type === 'password' || type === 'search') {
                    return;
                }

                // Phone / Mobile numbers: exactly 10 digits numeric
                if (type === 'tel' || name.includes('phone') || name.includes('mobile') || (name.includes('contact') && !name.includes('email') && !name.includes('name')) || id.includes('phone') || id.includes('mobile')) {
                    input.setAttribute('maxlength', '10');
                    input.setAttribute('inputmode', 'numeric');
                    input.setAttribute('pattern', '[0-9]{10}');
                }
                // Aadhaar / National ID: exactly 12 digits numeric
                else if (name.includes('aadhar') || name.includes('aadhaar') || id.includes('aadhar')) {
                    input.setAttribute('maxlength', '12');
                    input.setAttribute('inputmode', 'numeric');
                    input.setAttribute('pattern', '[0-9]{12}');
                }
                // PIN Code: exactly 6 digits numeric
                else if (name.includes('pincode') || name.includes('pin_code') || name.includes('postal_code') || id.includes('pincode')) {
                    input.setAttribute('maxlength', '6');
                    input.setAttribute('inputmode', 'numeric');
                    input.setAttribute('pattern', '[0-9]{6}');
                }
            });
        }

        function handleUniversalInputField(input) {
            if (!input || input.tagName !== 'INPUT') return;
            const name = (input.name || '').toLowerCase();
            const id = (input.id || '').toLowerCase();
            const type = (input.type || '').toLowerCase();

            // Explicitly ignore email, password, and search inputs
            if (type === 'email' || name.includes('email') || id.includes('email') || type === 'password' || type === 'search') {
                return;
            }

            // 1. Phone / Mobile: strictly digits only, max 10
            if (type === 'tel' || name.includes('phone') || name.includes('mobile') || (name.includes('contact') && !name.includes('email') && !name.includes('name')) || id.includes('phone') || id.includes('mobile')) {
                input.value = input.value.replace(/[^0-9]/g, '').slice(0, 10);
            }
            // 2. Aadhaar: strictly digits only, max 12
            else if (name.includes('aadhar') || name.includes('aadhaar') || id.includes('aadhar')) {
                input.value = input.value.replace(/[^0-9]/g, '').slice(0, 12);
            }
            // 3. PIN Code: strictly digits only, max 6
            else if (name.includes('pincode') || name.includes('pin_code') || name.includes('postal_code') || id.includes('pincode')) {
                input.value = input.value.replace(/[^0-9]/g, '').slice(0, 6);
            }
            // 4. Percentage: max 100, max 1 decimal point
            else if (name.includes('percentage') || id.includes('percentage')) {
                input.value = input.value.replace(/[^0-9.]/g, '');
                if ((input.value.match(/\./g) || []).length > 1) {
                    input.value = input.value.slice(0, -1);
                }
                if (parseFloat(input.value) > 100) {
                    input.value = '100';
                }
            }
            // 5. CGPA: max 10.0
            else if (name.includes('cgpa') || id.includes('cgpa')) {
                input.value = input.value.replace(/[^0-9.]/g, '');
                if ((input.value.match(/\./g) || []).length > 1) {
                    input.value = input.value.slice(0, -1);
                }
                if (parseFloat(input.value) > 10) {
                    input.value = '10';
                }
            }
        }

        function isUniversalCharAllowed(input, e) {
            if (!input || input.tagName !== 'INPUT') return true;
            const name = (input.name || '').toLowerCase();
            const id = (input.id || '').toLowerCase();
            const type = (input.type || '').toLowerCase();

            // Explicitly allow all characters for email, passwords, text, search, etc.
            if (type === 'email' || name.includes('email') || id.includes('email') || type === 'password' || type === 'search') {
                return true;
            }

            // Strictly numeric restrictions
            if (type === 'tel' || name.includes('phone') || name.includes('mobile') || (name.includes('contact') && !name.includes('email') && !name.includes('name')) || id.includes('phone') || id.includes('mobile')
                || name.includes('aadhar') || name.includes('aadhaar') || name.includes('pincode') || name.includes('pin_code') || name.includes('postal_code')) {
                const charCode = e.which ? e.which : e.keyCode;
                // Allow control keys (backspace, tab, enter, delete, arrows)
                if (e.ctrlKey || e.altKey || charCode === 8 || charCode === 9 || charCode === 13 || charCode === 0) {
                    return true;
                }
                if (charCode < 48 || charCode > 57) {
                    return false; // Block any letter, space, or special character
                }
            }
            return true;
        }

        // Theme Palette Functions
        function togglePaletteMenu(e) {
            if (e) e.stopPropagation();
            const dropdown = document.getElementById('paletteDropdown');
            dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
        }

        function setThemeColor(color) {
            document.documentElement.setAttribute('data-theme', color);
            document.body.setAttribute('data-theme', color);
            document.body.className = document.body.className.replace(/\btheme-\S+/g, '');
            document.body.classList.add('theme-' + color);
            localStorage.setItem('org_theme_color', color);
            applyDynamicThemeStyle(color);
            const dropdown = document.getElementById('paletteDropdown');
            if (dropdown) dropdown.style.display = 'none';
        }

        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark-mode');
            document.body.classList.toggle('dark-mode', isDark);
            const mode = isDark ? 'dark' : 'light';
            localStorage.setItem('org_theme_mode', mode);
            localStorage.setItem('org_theme', isDark ? 'dark-mode' : 'light');
        }

        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('paletteDropdown');
            const btn = document.getElementById('paletteBtn');
            if (dropdown && dropdown.style.display === 'block' && !dropdown.contains(e.target) && !btn.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });

        // Universal Flatpickr Datepicker Initialization (Strict DD/MM/YYYY)
        function initGlobalDatepickers() {
            if (typeof flatpickr !== 'undefined') {
                flatpickr("input[type='date']:not(.no-flatpickr)", {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d/m/Y",
                    altInputClass: "form-control flatpickr-input-custom",
                    allowInput: true
                });
                flatpickr("input[type='datetime-local']:not(.no-flatpickr)", {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                    altInput: true,
                    altFormat: "d/m/Y, h:i K",
                    altInputClass: "form-control flatpickr-input-custom",
                    allowInput: true
                });
            }
        }

        document.addEventListener('DOMContentLoaded', initGlobalDatepickers);

        // Prevent browser back button showing cached dashboard after logout
        window.addEventListener('pageshow', function(event) {
            if (event.persisted || (window.performance && window.performance.navigation && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>
