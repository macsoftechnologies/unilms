<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniLMS — Unified Enterprise Learning & Campus Management Platform</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4F46E5;
            --primary-dark: #3730A3;
            --primary-light: #EEF2FF;
            --bg-page: #0B0F19;
            --card-bg: rgba(17, 24, 39, 0.75);
            --card-border: rgba(255, 255, 255, 0.08);
            --card-hover-border: rgba(99, 102, 241, 0.4);
            --text-main: #F9FAFB;
            --text-muted: #9CA3AF;
            --text-dim: #6B7280;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 15% 15%, rgba(79, 70, 229, 0.18) 0%, transparent 40%),
                radial-gradient(circle at 85% 20%, rgba(14, 165, 233, 0.15) 0%, transparent 45%),
                radial-gradient(circle at 50% 80%, rgba(168, 85, 247, 0.12) 0%, transparent 50%);
            background-attachment: fixed;
        }

        /* Top Navigation Header */
        .header-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 48px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: white;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #4F46E5, #06B6D4);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.4);
        }

        .brand-title {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #FFFFFF 40%, #A5B4FC 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.25);
            border-radius: 9999px;
            color: #34D399;
            font-size: 13px;
            font-weight: 600;
        }

        .header-badge .dot {
            width: 8px;
            height: 8px;
            background: #10B981;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* Hero Section */
        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 64px 24px 32px;
            text-align: center;
        }

        .hero-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            background: rgba(99, 102, 241, 0.12);
            border: 1px solid rgba(99, 102, 241, 0.25);
            border-radius: 9999px;
            color: #A5B4FC;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 24px;
        }

        .hero-title {
            font-size: clamp(32px, 5vw, 56px);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1px;
            margin-bottom: 18px;
        }

        .hero-title span {
            background: linear-gradient(135deg, #818CF8, #38BDF8, #C084FC);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: clamp(16px, 2vw, 19px);
            color: var(--text-muted);
            max-width: 720px;
            margin: 0 auto 48px;
            line-height: 1.6;
            font-weight: 400;
        }

        /* Portals Grid */
        .portals-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 24px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px 64px;
            width: 100%;
        }

        .portal-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 32px;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            backdrop-filter: blur(16px);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }

        .portal-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--accent-gradient, linear-gradient(90deg, #4F46E5, #06B6D4));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .portal-card:hover {
            transform: translateY(-6px);
            border-color: var(--card-hover-border);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5), 0 0 30px -10px var(--accent-glow, rgba(99, 102, 241, 0.2));
        }

        .portal-card:hover::before {
            opacity: 1;
        }

        .portal-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
        }

        .portal-icon-box {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            background: var(--icon-bg, rgba(79, 70, 229, 0.15));
            color: var(--icon-color, #818CF8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            transition: transform 0.3s ease;
        }

        .portal-card:hover .portal-icon-box {
            transform: scale(1.08);
        }

        .portal-tag {
            font-size: 12px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.06);
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .portal-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 10px;
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .portal-desc {
            font-size: 14.5px;
            color: var(--text-muted);
            line-height: 1.55;
            margin-bottom: 24px;
        }

        .portal-features {
            list-style: none;
            margin-bottom: 28px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .portal-features li {
            font-size: 13.5px;
            color: #D1D5DB;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .portal-features li i {
            color: var(--icon-color, #818CF8);
            font-size: 12px;
        }

        .portal-cta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 14.5px;
            font-weight: 600;
            color: var(--icon-color, #818CF8);
            padding-top: 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            transition: gap 0.2s ease;
        }

        .portal-card:hover .portal-cta i {
            transform: translateX(4px);
        }

        .portal-cta i {
            transition: transform 0.2s ease;
        }

        /* Specific Portal Themes */
        .portal-student {
            --icon-bg: rgba(99, 102, 241, 0.15);
            --icon-color: #818CF8;
            --accent-gradient: linear-gradient(90deg, #6366F1, #818CF8);
            --accent-glow: rgba(99, 102, 241, 0.3);
        }

        .portal-org {
            --icon-bg: rgba(2, 132, 199, 0.15);
            --icon-color: #38BDF8;
            --accent-gradient: linear-gradient(90deg, #0284C7, #38BDF8);
            --accent-glow: rgba(2, 132, 199, 0.3);
        }

        .portal-parent {
            --icon-bg: rgba(16, 185, 129, 0.15);
            --icon-color: #34D399;
            --accent-gradient: linear-gradient(90deg, #10B981, #34D399);
            --accent-glow: rgba(16, 185, 129, 0.3);
        }

        .portal-creator {
            --icon-bg: rgba(168, 85, 247, 0.15);
            --icon-color: #C084FC;
            --accent-gradient: linear-gradient(90deg, #9333EA, #C084FC);
            --accent-glow: rgba(168, 85, 247, 0.3);
        }

        .portal-admissions {
            --icon-bg: rgba(245, 158, 11, 0.15);
            --icon-color: #FBBF24;
            --accent-gradient: linear-gradient(90deg, #D97706, #FBBF24);
            --accent-glow: rgba(245, 158, 11, 0.3);
        }

        .portal-superadmin {
            --icon-bg: rgba(244, 63, 94, 0.15);
            --icon-color: #FB7185;
            --accent-gradient: linear-gradient(90deg, #E11D48, #FB7185);
            --accent-glow: rgba(244, 63, 94, 0.3);
        }

        /* Platform Stats Banner */
        .platform-highlights {
            background: rgba(17, 24, 39, 0.5);
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            padding: 40px 24px;
            margin-top: auto;
        }

        .highlights-container {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
            text-align: center;
        }

        .highlight-item h4 {
            font-size: 26px;
            font-weight: 800;
            color: white;
            margin-bottom: 6px;
        }

        .highlight-item p {
            font-size: 13.5px;
            color: var(--text-muted);
        }

        /* Footer */
        .footer-bar {
            padding: 24px 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: var(--text-dim);
            flex-wrap: wrap;
            gap: 16px;
        }

        .footer-links {
            display: flex;
            gap: 20px;
        }

        .footer-links a {
            color: var(--text-dim);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--text-muted);
        }

        @media (max-width: 768px) {
            .header-nav { padding: 16px 20px; }
            .portals-grid { grid-template-columns: 1fr; padding: 0 16px 48px; }
            .footer-bar { padding: 20px; flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>

    <!-- Top Navigation -->
    <header class="header-nav">
        <a href="<?= base_url() ?>" class="brand-logo">
            <div class="brand-icon">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div class="brand-title">UniLMS</div>
        </a>
        <div class="header-badge">
            <span class="dot"></span>
            <span>Enterprise Cloud Active</span>
        </div>
    </header>

    <!-- Main Hero -->
    <div class="hero-container">
        <div class="hero-pill">
            <i class="fa-solid fa-sparkles"></i>
            Next-Generation Academic ERP & Learning Experience
        </div>
        <h1 class="hero-title">
            Unified Cloud for Higher Education & <span>Intelligent Learning</span>
        </h1>
        <p class="hero-subtitle">
            Welcome to the centralized gateway. Please select your dedicated portal below to access courses, academic operations, fee management, or governance suites.
        </p>
    </div>

    <!-- Portals Grid (6 Portals) -->
    <div class="portals-grid">
        
        <!-- 1. Student LMS Portal -->
        <a href="<?= base_url('lms/login') ?>" class="portal-card portal-student">
            <div>
                <div class="portal-header">
                    <div class="portal-icon-box">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <span class="portal-tag">Student</span>
                </div>
                <h3 class="portal-title">Student LMS Portal</h3>
                <p class="portal-desc">Access multimedia courses, interactive lessons, auto-graded CBT quizzes, assignments, and exam hall tickets.</p>
                <ul class="portal-features">
                    <li><i class="fa-solid fa-circle-check"></i> Interactive Video & Lesson Player</li>
                    <li><i class="fa-solid fa-circle-check"></i> Live Timetable & Attendance Log</li>
                    <li><i class="fa-solid fa-circle-check"></i> Internship Workspace & Certifications</li>
                </ul>
            </div>
            <div class="portal-cta">
                <span>Access Student LMS</span>
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>

        <!-- 2. Institution CMS & Faculty Portal -->
        <a href="<?= base_url('org/login') ?>" class="portal-card portal-org">
            <div>
                <div class="portal-header">
                    <div class="portal-icon-box">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <span class="portal-tag">Staff & Admin</span>
                </div>
                <h3 class="portal-title">Institution CMS & ERP</h3>
                <p class="portal-desc">Complete campus operations: Admissions CRM, academic structure, attendance, examinations, fee collection & HR payroll.</p>
                <ul class="portal-features">
                    <li><i class="fa-solid fa-circle-check"></i> Admissions, Cohorts & Timetable Builder</li>
                    <li><i class="fa-solid fa-circle-check"></i> Marks Entry, OBE Matrix & Report Cards</li>
                    <li><i class="fa-solid fa-circle-check"></i> Fees Collection, Daybook & Staff Payroll</li>
                </ul>
            </div>
            <div class="portal-cta">
                <span>Open Institution Portal</span>
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>

        <!-- 3. Parent Guardian Portal -->
        <a href="<?= base_url('parent/login') ?>" class="portal-card portal-parent">
            <div>
                <div class="portal-header">
                    <div class="portal-icon-box">
                        <i class="fa-solid fa-user-group"></i>
                    </div>
                    <span class="portal-tag">Guardians</span>
                </div>
                <h3 class="portal-title">Parent Guardian Portal</h3>
                <p class="portal-desc">Dedicated dashboard for parents to monitor academic progress, daily attendance, fee dues, and teacher correspondence.</p>
                <ul class="portal-features">
                    <li><i class="fa-solid fa-circle-check"></i> Real-Time Attendance & Leave Status</li>
                    <li><i class="fa-solid fa-circle-check"></i> Fee Invoices, Dues & Payment Receipts</li>
                    <li><i class="fa-solid fa-circle-check"></i> Multi-Child Switching with Single Login</li>
                </ul>
            </div>
            <div class="portal-cta">
                <span>Login as Parent</span>
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>

        <!-- 4. Content Creator Studio -->
        <a href="<?= base_url('creator/login') ?>" class="portal-card portal-creator">
            <div>
                <div class="portal-header">
                    <div class="portal-icon-box">
                        <i class="fa-solid fa-clapperboard"></i>
                    </div>
                    <span class="portal-tag">Instructional</span>
                </div>
                <h3 class="portal-title">Content Creator Studio</h3>
                <p class="portal-desc">Author modern courses, build modular chapters, upload high-definition video lessons, and attach study notes/slides.</p>
                <ul class="portal-features">
                    <li><i class="fa-solid fa-circle-check"></i> Modular Chapter & Video Lesson Builder</li>
                    <li><i class="fa-solid fa-circle-check"></i> PDF Lecture Notes & Slides Embedding</li>
                    <li><i class="fa-solid fa-circle-check"></i> Student Enrollment & Progress Analytics</li>
                </ul>
            </div>
            <div class="portal-cta">
                <span>Launch Creator Studio</span>
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>

        <!-- 5. Online Public Admissions -->
        <a href="<?= base_url('admissions/apply') ?>" class="portal-card portal-admissions">
            <div>
                <div class="portal-header">
                    <div class="portal-icon-box">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                    <span class="portal-tag">Applicants</span>
                </div>
                <h3 class="portal-title">Online Admissions Portal</h3>
                <p class="portal-desc">Prospective students can apply for degree programs, submit certificates, and track application admission status.</p>
                <ul class="portal-features">
                    <li><i class="fa-solid fa-circle-check"></i> Instant Online Application Submission</li>
                    <li><i class="fa-solid fa-circle-check"></i> Automated Application Number Generation</li>
                    <li><i class="fa-solid fa-circle-check"></i> Document Upload & Verification Intake</li>
                </ul>
            </div>
            <div class="portal-cta">
                <span>Apply for Admission</span>
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>

        <!-- 6. Super Admin Operations -->
        <a href="<?= base_url('superadmin/login') ?>" class="portal-card portal-superadmin">
            <div>
                <div class="portal-header">
                    <div class="portal-icon-box">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <span class="portal-tag">Governance</span>
                </div>
                <h3 class="portal-title">Super Admin Platform</h3>
                <p class="portal-desc">Multi-tenant management, institution provisioning, plan licensing, subscription renewals, and system audit logs.</p>
                <ul class="portal-features">
                    <li><i class="fa-solid fa-circle-check"></i> Tenant Provisioning & Module Toggles</li>
                    <li><i class="fa-solid fa-circle-check"></i> Subscription Lifecycle & Billing Logs</li>
                    <li><i class="fa-solid fa-circle-check"></i> Role-Based Super Administrator Access</li>
                </ul>
            </div>
            <div class="portal-cta">
                <span>Super Admin Console</span>
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>

    </div>

    <!-- Platform Highlights Section -->
    <div class="platform-highlights">
        <div class="highlights-container">
            <div class="highlight-item">
                <h4>16+ Modules</h4>
                <p>Comprehensive ERP, Academics & LMS</p>
            </div>
            <div class="highlight-item">
                <h4>Multi-Tenant</h4>
                <p>Complete Institutional Data Isolation</p>
            </div>
            <div class="highlight-item">
                <h4>OBE & NBA Ready</h4>
                <p>Outcome Attainment & CO-PO Matrices</p>
            </div>
            <div class="highlight-item">
                <h4>99.9% Uptime</h4>
                <p>Secure Enterprise Architecture</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-bar">
        <div>
            &copy; <?= date('Y') ?> UniLMS Cloud Platform. All rights reserved.
        </div>
        <div class="footer-links">
            <a href="<?= base_url('org/login') ?>">Faculty Login</a>
            <a href="<?= base_url('lms/login') ?>">Student Portal</a>
            <a href="<?= base_url('parent/login') ?>">Parent Portal</a>
            <a href="<?= base_url('creator/login') ?>">Creator Studio</a>
        </div>
    </footer>

</body>
</html>
