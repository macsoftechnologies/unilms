<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('page_title') ?> - Student LMS Portal</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Flatpickr for Universal Date Inputs -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        $(document).ajaxSend(function(e, xhr, options) {
            if (options.type && options.type.toUpperCase() !== 'GET') {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            }
        });

        // Theme initialization
        (function() {
            const savedColor = localStorage.getItem('unilms_student_theme') || 'purple';
            const savedMode = localStorage.getItem('unilms_student_mode') || 'light';
            document.documentElement.setAttribute('data-theme', savedColor);
            if (savedMode === 'dark') {
                document.documentElement.classList.add('dark-mode');
            }
        })();
    </script>

    <style>
        :root, [data-theme="purple"] {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --primary-glow: rgba(99, 102, 241, 0.18);
            --sidebar-bg: #0f172a;
            --sidebar-nav-hover: rgba(255, 255, 255, 0.08);
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #ffffff;
            --bg-canvas: #f8fafc;
            --surface: #ffffff;
            --surface-elevated: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --border-light: #f1f5f9;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
        }

        [data-theme="blue"] {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #60a5fa;
            --primary-glow: rgba(37, 99, 235, 0.18);
            --sidebar-bg: #0b192c;
        }

        [data-theme="emerald"] {
            --primary: #059669;
            --primary-dark: #047857;
            --primary-light: #34d399;
            --primary-glow: rgba(5, 150, 105, 0.18);
            --sidebar-bg: #06261f;
        }

        [data-theme="amber"] {
            --primary: #d97706;
            --primary-dark: #b45309;
            --primary-light: #fbbf24;
            --primary-glow: rgba(217, 119, 6, 0.18);
            --sidebar-bg: #1f1406;
        }

        [data-theme="rose"] {
            --primary: #e11d48;
            --primary-dark: #be123c;
            --primary-light: #fb7185;
            --primary-glow: rgba(225, 29, 72, 0.18);
            --sidebar-bg: #210710;
        }

        [data-theme="teal"] {
            --primary: #0d9488;
            --primary-dark: #0f766e;
            --primary-light: #2dd4bf;
            --primary-glow: rgba(13, 148, 136, 0.18);
            --sidebar-bg: #081d1a;
        }

        /* Dark Mode Engine */
        html.dark-mode, body.dark-mode {
            --bg-canvas: #090d16;
            --surface: #111827;
            --surface-elevated: #1f2937;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border: rgba(255, 255, 255, 0.08);
            --border-light: rgba(255, 255, 255, 0.04);
            --sidebar-bg: #050811;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-canvas);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* =========================================================================
           LEFT SIDEBAR NAVIGATION (ENTERPRISE GRADE - PINNED HEADER & SCROLLABLE NAV)
        ========================================================================= */
        .lms-sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            height: 100vh;
            z-index: 1000;
            transition: transform 0.3s ease;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            overflow: hidden;
        }

        /* Sidebar Header (Permanently Pinned Top) */
        .sidebar-brand {
            padding: 18px 18px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            flex-shrink: 0;
        }

        .brand-logo-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 16px;
            box-shadow: 0 4px 14px var(--primary-glow);
            flex-shrink: 0;
        }

        .brand-text-box {
            overflow: hidden;
        }

        .brand-title {
            font-family: 'Outfit', sans-serif;
            font-size: 13.5px;
            font-weight: 800;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }

        .brand-subtitle {
            font-size: 10px;
            color: var(--primary-light);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-top: 2px;
        }

        /* Mini Student Profile Card in Sidebar (Pinned Top) */
        .sidebar-student-card {
            margin: 12px 14px 6px;
            padding: 9px 12px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .student-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ec4899, #8b5cf6);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 12px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(236, 72, 153, 0.3);
        }

        .student-info {
            overflow: hidden;
        }

        .student-name {
            font-size: 12px;
            font-weight: 700;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .student-roll {
            font-size: 10px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 1px;
        }

        .student-roll-badge {
            background: rgba(99, 102, 241, 0.2);
            color: #a5b4fc;
            padding: 1px 5px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 9px;
        }

        /* Scrollable Navigation Area */
        .sidebar-nav-container {
            padding: 8px 12px 16px;
            flex: 1 1 auto;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }

        .sidebar-nav-container::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-nav-container::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
        .sidebar-nav-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .nav-category-header {
            font-size: 9px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 12px 8px 5px;
            margin-top: 2px;
        }

        .sidebar-nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 8px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 12.5px;
            font-weight: 600;
            margin-bottom: 2px;
            transition: all 0.15s ease;
            position: relative;
            white-space: nowrap;
        }

        .sidebar-nav-link i.nav-icon {
            font-size: 14px;
            width: 18px;
            text-align: center;
            color: #64748b;
            transition: color 0.15s, transform 0.15s;
            flex-shrink: 0;
        }

        .sidebar-nav-link:hover {
            color: #ffffff;
            background: var(--sidebar-nav-hover);
            transform: translateX(2px);
        }

        .sidebar-nav-link:hover i.nav-icon {
            color: var(--primary-light);
        }

        .sidebar-nav-link.active {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            box-shadow: 0 2px 10px var(--primary-glow);
        }

        .sidebar-nav-link.active i.nav-icon {
            color: #ffffff;
        }

        .nav-pill-badge {
            margin-left: auto;
            font-size: 8px;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            letter-spacing: 0.4px;
        }

        /* Sidebar Footer (Permanently Pinned Bottom) */
        .sidebar-footer {
            padding: 12px 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
            background: var(--sidebar-bg);
        }

        .btn-sidebar-logout {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px;
            border-radius: 8px;
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.2);
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-sidebar-logout:hover {
            background: #ef4444;
            color: #ffffff;
        }

        /* =========================================================================
           MAIN WRAPPER & TOP NAVBAR
        ========================================================================= */
        .lms-main-wrapper {
            margin-left: 260px;
            flex-grow: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            width: calc(100% - 260px);
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        /* Top Header */
        .lms-top-header {
            position: sticky;
            top: 0;
            z-index: 900;
            height: 70px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            transition: background 0.3s ease;
        }

        html.dark-mode .lms-top-header {
            background: rgba(17, 24, 39, 0.85);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .btn-mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text-main);
            cursor: pointer;
        }

        .page-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13.5px;
            color: var(--text-muted);
        }

        .page-breadcrumb .current {
            color: var(--text-main);
            font-weight: 700;
            font-size: 15px;
            font-family: 'Outfit', sans-serif;
        }

        /* Top Actions */
        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .term-badge-header {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(99, 102, 241, 0.08);
            border: 1px solid rgba(99, 102, 241, 0.18);
            color: var(--primary);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12.5px;
            font-weight: 700;
        }

        /* Theme Picker & Dark Mode Toggle */
        .theme-control-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 4px 6px;
        }

        .theme-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: none;
            background: transparent;
            color: var(--text-muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: all 0.2s;
        }

        .theme-btn:hover {
            color: var(--primary);
            background: var(--bg-canvas);
        }

        /* =========================================================================
           PAGE BODY CONTENT
        ========================================================================= */
        .lms-page-container {
            padding: 32px;
            flex-grow: 1;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            animation: fadeInUp 0.4s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* =========================================================================
           UNIVERSAL TYPOGRAPHY & UNIFORM SCALE
        ========================================================================= */
        h1, .h1 {
            font-family: 'Outfit', sans-serif !important;
            font-size: 20px !important;
            font-weight: 700 !important;
            color: var(--text-main) !important;
            line-height: 1.25 !important;
            margin-bottom: 4px;
        }

        h2, .h2 {
            font-family: 'Outfit', sans-serif !important;
            font-size: 17px !important;
            font-weight: 700 !important;
            color: var(--text-main) !important;
            line-height: 1.3 !important;
            margin-bottom: 4px;
        }

        h3, .h3 {
            font-family: 'Outfit', sans-serif !important;
            font-size: 15px !important;
            font-weight: 700 !important;
            color: var(--text-main) !important;
            line-height: 1.3 !important;
            margin-bottom: 4px;
        }

        h4, .h4 {
            font-family: 'Outfit', sans-serif !important;
            font-size: 13.5px !important;
            font-weight: 600 !important;
            color: var(--text-main) !important;
            line-height: 1.35 !important;
            margin-bottom: 3px;
        }

        h5, .h5 {
            font-family: 'Outfit', sans-serif !important;
            font-size: 12.5px !important;
            font-weight: 600 !important;
            color: var(--text-main) !important;
        }

        h6, .h6 {
            font-family: 'Outfit', sans-serif !important;
            font-size: 11.5px !important;
            font-weight: 600 !important;
            color: var(--text-muted) !important;
        }

        p, span, div, td, th, li, a, input, select, textarea, button {
            font-size: 13px;
            line-height: 1.5;
        }

        .text-muted {
            color: var(--text-muted) !important;
            font-size: 12.5px;
        }

        .small, small {
            font-size: 11.5px !important;
        }

        /* Universal Page Header Banner */
        .lms-page-header, .header-banner {
            margin-bottom: 24px;
        }

        .lms-page-header h1, .lms-page-header h2, .lms-page-header h3, .header-title {
            font-family: 'Outfit', sans-serif !important;
            font-size: 20px !important;
            font-weight: 700 !important;
            color: var(--text-main) !important;
            margin-bottom: 4px !important;
        }

        .lms-page-header p, .header-subtitle {
            font-size: 12.5px !important;
            color: var(--text-muted) !important;
            margin-bottom: 0 !important;
        }

        /* Universal Empty State Card */
        .empty-state-box {
            background: var(--surface);
            border: 1px dashed var(--border);
            border-radius: 16px;
            padding: 40px 24px;
            text-align: center;
            margin: 16px 0;
        }

        .empty-icon-circle {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: var(--bg-canvas);
            border: 1px solid var(--border);
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 12px;
        }

        .empty-state-title {
            font-family: 'Outfit', sans-serif;
            font-size: 14.5px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 4px;
        }

        .empty-state-desc {
            font-size: 12.5px;
            color: var(--text-muted);
            margin-bottom: 0;
        }

        /* Universal Tables */
        table {
            font-size: 12.5px;
        }

        table th {
            font-size: 11.5px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            color: var(--text-muted) !important;
        }

        table td {
            font-size: 12.5px !important;
        }

        .badge {
            font-size: 11px !important;
            font-weight: 600 !important;
            padding: 3px 8px !important;
            border-radius: 6px !important;
        }

        /* Card System */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card:hover {
            box-shadow: 0 8px 30px -4px rgba(0, 0, 0, 0.08);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 12.5px !important;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            border: none;
        }

        .btn-sm {
            padding: 5px 12px;
            font-size: 11.5px !important;
            border-radius: 6px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #ffffff;
            box-shadow: 0 4px 14px var(--primary-glow);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px var(--primary-glow);
        }

        .btn-outline {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text-main);
        }

        .btn-outline:hover {
            background: var(--bg-canvas);
            border-color: var(--text-muted);
        }

        /* Mobile Sidebar Backdrop */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 999;
        }

        @media (max-width: 1024px) {
            .lms-sidebar {
                transform: translateX(-100%);
            }
            .lms-sidebar.active {
                transform: translateX(0);
            }
            .lms-main-wrapper {
                margin-left: 0;
                width: 100%;
            }
            .btn-mobile-toggle {
                display: block;
            }
            .sidebar-backdrop.active {
                display: block;
            }
            .lms-page-container {
                padding: 16px 12px;
            }
            .lms-top-header {
                padding: 0 16px;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar Backdrop for Mobile -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

    <!-- =========================================================================
         LEFT SIDEBAR NAVIGATION
    ========================================================================= -->
    <aside class="lms-sidebar" id="lmsSidebar">
        <!-- Sidebar Brand Header -->
        <div class="sidebar-brand">
            <div class="brand-logo-icon">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div class="brand-text-box">
                <div class="brand-title"><?= esc(session('org_name') ?: 'Apex Institute of Technology') ?></div>
                <div class="brand-subtitle">Student LMS Cloud</div>
            </div>
        </div>

        <!-- Student Mini Profile Card -->
        <div class="sidebar-student-card">
            <div class="student-avatar">
                <?= strtoupper(substr(session('user_name') ?: 'S', 0, 1)) ?>
            </div>
            <div class="student-info">
                <div class="student-name"><?= esc(session('user_name') ?: 'Student') ?></div>
                <div class="student-roll">
                    <span class="student-roll-badge"><?= esc(session('roll_number') ?: 'STU') ?></span>
                    <span>• <?= esc(session('program_code') ?: (session('cohort_name') ?: 'B.Tech CSE')) ?></span>
                </div>
            </div>
        </div>

        <?php $uri = service('uri')->getPath(); ?>

        <!-- Nav Links Categorized -->
        <div class="sidebar-nav-container">
            
            <!-- Category 1: Academics -->
            <div class="nav-category-header">Academics & Schedule</div>
            <a href="<?= base_url('lms/dashboard') ?>" class="sidebar-nav-link <?= strpos($uri, 'lms/dashboard') !== false || $uri == 'lms' ? 'active' : '' ?>">
                <i class="fa-solid fa-gauge-high nav-icon"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?= base_url('lms/timetable') ?>" class="sidebar-nav-link <?= strpos($uri, 'lms/timetable') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-calendar-days nav-icon"></i>
                <span>Class Timetable</span>
            </a>
            <a href="<?= base_url('lms/attendance') ?>" class="sidebar-nav-link <?= strpos($uri, 'lms/attendance') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-clipboard-user nav-icon"></i>
                <span>Attendance Monitor</span>
            </a>
            <a href="<?= base_url('lms/grade-card') ?>" class="sidebar-nav-link <?= strpos($uri, 'lms/grade-card') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-award nav-icon"></i>
                <span>Semester Grade Card</span>
            </a>
            <a href="<?= base_url('lms/obe') ?>" class="sidebar-nav-link <?= strpos($uri, 'lms/obe') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-bullseye nav-icon"></i>
                <span>OBE Outcomes & Matrix</span>
            </a>

            <!-- Category 2: Learning Hub -->
            <div class="nav-category-header">Learning & Courses</div>
            <a href="<?= base_url('lms/materials') ?>" class="sidebar-nav-link <?= strpos($uri, 'lms/materials') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-play-circle nav-icon"></i>
                <span>Video Masterclasses</span>
                <span class="nav-pill-badge">Blended</span>
            </a>
            <a href="<?= base_url('lms/assignments') ?>" class="sidebar-nav-link <?= strpos($uri, 'lms/assignments') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-file-lines nav-icon"></i>
                <span>Assignments & Tasks</span>
            </a>
            <a href="<?= base_url('lms/quizzes') ?>" class="sidebar-nav-link <?= strpos($uri, 'lms/quizzes') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-laptop-code nav-icon"></i>
                <span>CBT Online Exams</span>
            </a>
            <a href="<?= base_url('lms/assessments') ?>" class="sidebar-nav-link <?= strpos($uri, 'lms/assessments') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-layer-group nav-icon"></i>
                <span>Interactive Assessments</span>
            </a>

            <!-- Category 3: Campus & Career -->
            <div class="nav-category-header">Campus Life & Career</div>
            <a href="<?= base_url('lms/internships') ?>" class="sidebar-nav-link <?= strpos($uri, 'lms/internships') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-briefcase nav-icon"></i>
                <span>Industry Internships</span>
            </a>
            <a href="<?= base_url('lms/placements') ?>" class="sidebar-nav-link <?= strpos($uri, 'lms/placements') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-building-user nav-icon"></i>
                <span>Placements Hub</span>
            </a>
            <a href="<?= base_url('lms/fees') ?>" class="sidebar-nav-link <?= strpos($uri, 'lms/fees') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-indian-rupee-sign nav-icon"></i>
                <span>Fee Ledger & Dues</span>
            </a>
            <a href="<?= base_url('lms/self-service') ?>" class="sidebar-nav-link <?= strpos($uri, 'lms/self-service') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-receipt nav-icon"></i>
                <span>Self Service Desk</span>
            </a>
            <a href="<?= base_url('lms/feedback') ?>" class="sidebar-nav-link <?= strpos($uri, 'lms/feedback') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-comment-dots nav-icon"></i>
                <span>Course Feedback</span>
            </a>
        </div>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <button type="button" class="btn-sidebar-logout" onclick="document.getElementById('lmsLogoutModal').style.display='flex'">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Sign Out</span>
            </button>
        </div>
    </aside>

    <!-- =========================================================================
         MAIN APPLICATION CANVAS
    ========================================================================= -->
    <div class="lms-main-wrapper">
        <!-- Top Navbar -->
        <header class="lms-top-header">
            <div class="header-left">
                <button class="btn-mobile-toggle" onclick="toggleSidebar()">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="page-breadcrumb">
                    <span>Student Portal</span>
                    <i class="fa-solid fa-chevron-right" style="font-size: 11px; opacity: 0.5;"></i>
                    <span class="current"><?= $this->renderSection('page_title') ?></span>
                </div>
            </div>

            <div class="header-right">
                <div class="term-badge-header">
                    <i class="fa-solid fa-graduation-cap"></i>
                    <span><?= esc(session('semester_name') ?: 'Semester 1') ?> (<?= esc(session('academic_year_name') ?: '2026-2027') ?>)</span>
                </div>

                <!-- Theme Color Palette Dropdown & Dark Mode -->
                <div class="theme-control-box">
                    <button type="button" class="theme-btn" onclick="toggleThemeDropdown()" title="Change Color Palette">
                        <i class="fa-solid fa-palette"></i>
                    </button>
                    <button type="button" class="theme-btn" onclick="toggleDarkMode()" title="Toggle Dark/Light Mode">
                        <i class="fa-solid fa-moon"></i>
                    </button>
                </div>

                <!-- Palette Modal Dropdown -->
                <div id="paletteDropdownMenu" style="display: none; position: absolute; right: 80px; top: 65px; z-index: 1000; background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); width: 220px;">
                    <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: var(--text-muted); margin-bottom: 10px; letter-spacing: 0.5px;">Theme Color</div>
                    <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 8px;">
                        <button type="button" onclick="setTheme('purple')" style="width: 24px; height: 24px; border-radius: 50%; background: #6366f1; border: 2px solid #fff; cursor: pointer;" title="Purple"></button>
                        <button type="button" onclick="setTheme('blue')" style="width: 24px; height: 24px; border-radius: 50%; background: #2563eb; border: 2px solid #fff; cursor: pointer;" title="Blue"></button>
                        <button type="button" onclick="setTheme('emerald')" style="width: 24px; height: 24px; border-radius: 50%; background: #059669; border: 2px solid #fff; cursor: pointer;" title="Emerald"></button>
                        <button type="button" onclick="setTheme('amber')" style="width: 24px; height: 24px; border-radius: 50%; background: #d97706; border: 2px solid #fff; cursor: pointer;" title="Amber"></button>
                        <button type="button" onclick="setTheme('rose')" style="width: 24px; height: 24px; border-radius: 50%; background: #e11d48; border: 2px solid #fff; cursor: pointer;" title="Rose"></button>
                        <button type="button" onclick="setTheme('teal')" style="width: 24px; height: 24px; border-radius: 50%; background: #0d9488; border: 2px solid #fff; cursor: pointer;" title="Teal"></button>
                    </div>
                </div>

                <!-- Student Profile Avatar -->
                <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; box-shadow: 0 4px 10px var(--primary-glow);">
                    <?= strtoupper(substr(session('user_name') ?: 'S', 0, 1)) ?>
                </div>
            </div>
        </header>

        <!-- Page Dynamic Canvas -->
        <main class="lms-page-container">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <!-- Sign Out Confirmation Modal -->
    <div id="lmsLogoutModal" style="display:none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(6px); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: var(--surface); border-radius: 20px; padding: 28px; width: 100%; max-width: 380px; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.25); border: 1px solid var(--border);">
            <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(239, 68, 68, 0.1); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 16px;">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </div>
            <h3 style="font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 700; margin-bottom: 8px;">Sign Out of LMS?</h3>
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 24px;">Are you sure you want to end your student session?</p>
            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="document.getElementById('lmsLogoutModal').style.display='none'" class="btn btn-outline" style="flex: 1;">Cancel</button>
                <a href="<?= base_url('lms/logout') ?>" class="btn btn-primary" style="flex: 1; background: #ef4444;">Sign Out</a>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('lmsSidebar').classList.toggle('active');
            document.getElementById('sidebarBackdrop').classList.toggle('active');
        }

        function toggleThemeDropdown() {
            const menu = document.getElementById('paletteDropdownMenu');
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }

        function setTheme(color) {
            document.documentElement.setAttribute('data-theme', color);
            localStorage.setItem('unilms_student_theme', color);
            document.getElementById('paletteDropdownMenu').style.display = 'none';
        }

        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark-mode');
            const isDark = document.documentElement.classList.contains('dark-mode');
            localStorage.setItem('unilms_student_mode', isDark ? 'dark' : 'light');
        }

        // Close dropdown when clicked outside
        window.addEventListener('click', function(e) {
            if (!e.target.closest('.theme-control-box') && !e.target.closest('#paletteDropdownMenu')) {
                const menu = document.getElementById('paletteDropdownMenu');
                if (menu) menu.style.display = 'none';
            }
        });

        // Clear any legacy forced scroll locks so sidebar header stays pinned
        sessionStorage.removeItem('unilms_sidebar_scroll');
    </script>
</body>
</html>
