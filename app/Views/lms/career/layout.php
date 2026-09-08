<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('page_title') ?> - Career Suite</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        (function() {
            const savedMode = localStorage.getItem('unilms_student_mode') || 'light';
            if (savedMode === 'dark') {
                document.documentElement.classList.add('dark-mode');
            }
        })();
    </script>

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --primary-glow: rgba(99, 102, 241, 0.18);
            --bg-canvas: #f0f0f7;
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

        html.dark-mode {
            --bg-canvas: #090d16;
            --surface: #111827;
            --surface-elevated: #1f2937;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border: rgba(255, 255, 255, 0.08);
            --border-light: rgba(255, 255, 255, 0.04);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-canvas);
            color: var(--text-main);
            min-height: 100vh;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* =========================================================================
           CAREER SUITE TOP NAVIGATION BAR
        ========================================================================= */
        .career-topbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            padding: 0 40px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ct-left {
            display: flex;
            align-items: center;
            gap: 28px;
        }

        .ct-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .ct-brand-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 14px;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
        }

        .ct-brand-text {
            font-family: 'Outfit', sans-serif;
            font-size: 15px;
            font-weight: 800;
            color: #ffffff;
        }

        .ct-brand-text .accent {
            background: linear-gradient(135deg, #a78bfa, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Navigation Links */
        .ct-nav {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .ct-nav-link {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 12.5px;
            font-weight: 600;
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .ct-nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.06);
        }

        .ct-nav-link.active {
            color: #ffffff;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(139, 92, 246, 0.15));
            box-shadow: inset 0 0 0 1px rgba(99, 102, 241, 0.3);
        }

        .ct-nav-link i {
            font-size: 13px;
            width: 16px;
            text-align: center;
        }

        /* Right Side Actions */
        .ct-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ct-back-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #94a3b8;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .ct-back-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .ct-dark-toggle {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .ct-dark-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #f59e0b;
        }

        .ct-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 13px;
            box-shadow: 0 3px 10px rgba(99, 102, 241, 0.3);
        }

        /* =========================================================================
           PAGE BODY
        ========================================================================= */
        .career-page-body {
            padding: 32px 40px;
            max-width: 1400px;
            margin: 0 auto;
            animation: fadeInUp 0.4s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* =========================================================================
           UNIVERSAL TYPOGRAPHY (Consistent with LMS layout)
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
        }

        h3, .h3 {
            font-family: 'Outfit', sans-serif !important;
            font-size: 15px !important;
            font-weight: 700 !important;
            color: var(--text-main) !important;
        }

        p, span, div, td, th, li, a, input, select, textarea, button {
            font-size: 13px;
            line-height: 1.5;
        }

        .text-muted { color: var(--text-muted) !important; font-size: 12.5px; }
        .small, small { font-size: 11.5px !important; }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card:hover { box-shadow: 0 8px 30px -4px rgba(0, 0, 0, 0.08); }

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

        .btn-sm { padding: 5px 12px; font-size: 11.5px !important; border-radius: 6px; }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #ffffff;
            box-shadow: 0 4px 14px var(--primary-glow);
        }

        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px var(--primary-glow); }

        .btn-outline {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text-main);
        }

        .btn-outline:hover { background: var(--bg-canvas); border-color: var(--text-muted); }

        table { font-size: 12.5px; }
        table th { font-size: 11.5px !important; font-weight: 700 !important; text-transform: uppercase !important; letter-spacing: 0.5px !important; color: var(--text-muted) !important; }
        table td { font-size: 12.5px !important; }
        .badge { font-size: 11px !important; font-weight: 600 !important; padding: 3px 8px !important; border-radius: 6px !important; }

        .text-primary { color: var(--primary) !important; }
        .text-success { color: var(--success) !important; }
        .text-warning { color: var(--warning) !important; }
        .text-danger { color: var(--danger) !important; }
        .text-info { color: var(--info) !important; }
        .fw-bold { font-weight: 700 !important; }
        .me-1 { margin-right: 4px !important; }
        .me-2 { margin-right: 8px !important; }
        .mb-0 { margin-bottom: 0 !important; }
        .mb-2 { margin-bottom: 8px !important; }
        .opacity-50 { opacity: 0.5 !important; }

        /* Mobile Responsive */
        @media (max-width: 1024px) {
            .career-topbar { padding: 0 16px; height: 56px; }
            .ct-nav { display: none; }
            .career-page-body { padding: 16px 12px; }
            .ct-brand-text { font-size: 13px; }
        }

        /* Mobile Nav Toggle */
        .ct-mobile-nav-toggle {
            display: none;
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 18px;
            cursor: pointer;
        }

        @media (max-width: 1024px) {
            .ct-mobile-nav-toggle { display: block; }
        }

        /* Mobile Dropdown Nav */
        .ct-mobile-nav {
            display: none;
            position: fixed;
            top: 56px;
            left: 0;
            right: 0;
            background: rgba(15, 23, 42, 0.98);
            backdrop-filter: blur(20px);
            padding: 12px 16px;
            z-index: 999;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .ct-mobile-nav.open { display: block; }

        .ct-mobile-nav .ct-nav-link {
            display: flex;
            width: 100%;
            padding: 10px 14px;
        }
    </style>
</head>
<body>

    <?php $uri = service('uri')->getPath(); ?>

    <!-- =========================================================================
         CAREER SUITE TOP NAVIGATION BAR
    ========================================================================= -->
    <nav class="career-topbar">
        <div class="ct-left">
            <a href="<?= base_url('lms/learn') ?>" class="ct-brand">
                <div class="ct-brand-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                <div class="ct-brand-text">LMS <span class="accent">Cloud</span></div>
            </a>

            <button class="ct-mobile-nav-toggle" onclick="document.getElementById('mobileCareerNav').classList.toggle('open')">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="ct-nav">
                <a href="<?= base_url('lms/learn') ?>" class="ct-nav-link <?= (strpos($uri, 'lms/learn') !== false || strpos($uri, 'materials') !== false) ? 'active' : '' ?>">
                    <i class="fa-solid fa-play-circle"></i> Masterclasses
                </a>
                <a href="<?= base_url('lms/career/resume') ?>" class="ct-nav-link <?= strpos($uri, 'career/resume') !== false ? 'active' : '' ?>">
                    <i class="fa-solid fa-file-lines"></i> Resume
                </a>
                <a href="<?= base_url('lms/career/mock-interview') ?>" class="ct-nav-link <?= strpos($uri, 'career/mock-interview') !== false ? 'active' : '' ?>">
                    <i class="fa-solid fa-microphone-lines"></i> Interviews
                </a>
                <a href="<?= base_url('lms/career/portfolio') ?>" class="ct-nav-link <?= strpos($uri, 'career/portfolio') !== false ? 'active' : '' ?>">
                    <i class="fa-solid fa-globe"></i> Portfolio
                </a>
                <a href="<?= base_url('lms/career') ?>" class="ct-nav-link <?= $uri == 'lms/career' ? 'active' : '' ?>">
                    <i class="fa-solid fa-th-large"></i> Hub
                </a>
            </div>
        </div>

        <div class="ct-right">
            <a href="<?= base_url('lms/dashboard') ?>" class="ct-back-btn">
                <i class="fa-solid fa-arrow-left"></i> Student Portal
            </a>
            <button class="ct-dark-toggle" onclick="toggleDarkMode()" title="Toggle Dark Mode">
                <i class="fa-solid fa-moon"></i>
            </button>
            <div class="ct-avatar">
                <?= strtoupper(substr(session('user_name') ?: 'S', 0, 1)) ?>
            </div>
        </div>
    </nav>

    <!-- Mobile Nav Dropdown -->
    <div id="mobileCareerNav" class="ct-mobile-nav">
        <a href="<?= base_url('lms/learn') ?>" class="ct-nav-link <?= (strpos($uri, 'lms/learn') !== false || strpos($uri, 'materials') !== false) ? 'active' : '' ?>"><i class="fa-solid fa-play-circle"></i> Masterclasses</a>
        <a href="<?= base_url('lms/career/resume') ?>" class="ct-nav-link <?= strpos($uri, 'career/resume') !== false ? 'active' : '' ?>"><i class="fa-solid fa-file-lines"></i> Resume Builder</a>
        <a href="<?= base_url('lms/career/mock-interview') ?>" class="ct-nav-link <?= strpos($uri, 'career/mock-interview') !== false ? 'active' : '' ?>"><i class="fa-solid fa-microphone-lines"></i> Mock Interviews</a>
        <a href="<?= base_url('lms/career/portfolio') ?>" class="ct-nav-link <?= strpos($uri, 'career/portfolio') !== false ? 'active' : '' ?>"><i class="fa-solid fa-globe"></i> Digital Portfolio</a>
        <a href="<?= base_url('lms/career') ?>" class="ct-nav-link <?= $uri == 'lms/career' ? 'active' : '' ?>"><i class="fa-solid fa-th-large"></i> Career Hub</a>
    </div>

    <!-- =========================================================================
         PAGE CONTENT
    ========================================================================= -->
    <main class="career-page-body">
        <?= $this->renderSection('content') ?>
    </main>

    <script>
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark-mode');
            const isDark = document.documentElement.classList.contains('dark-mode');
            localStorage.setItem('unilms_student_mode', isDark ? 'dark' : 'light');
        }
    </script>
</body>
</html>
