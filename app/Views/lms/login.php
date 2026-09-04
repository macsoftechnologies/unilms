<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal Login | UniLMS</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 Pro / Free -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #818cf8;
            --accent: #06b6d4;
            --surface: #ffffff;
            --bg-canvas: #0b0f19;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #090d16;
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Glow Backdrop */
        .ambient-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            filter: blur(140px);
            opacity: 0.25;
            pointer-events: none;
            z-index: 0;
        }
        .glow-1 {
            top: -100px;
            left: -100px;
            background: radial-gradient(circle, #4f46e5, #06b6d4);
        }
        .glow-2 {
            bottom: -150px;
            right: -100px;
            background: radial-gradient(circle, #8b5cf6, #ec4899);
        }

        /* Master Container */
        .auth-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1060px;
            background: #ffffff;
            border-radius: 28px;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.1);
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            overflow: hidden;
            min-height: 620px;
        }

        @media (max-width: 900px) {
            .auth-card {
                grid-template-columns: 1fr;
                max-width: 480px;
            }
            .hero-side {
                display: none !important;
            }
        }

        /* Left Hero Showcase */
        .hero-side {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4338ca 100%);
            padding: 48px 44px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }

        .hero-side::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.12) 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.6;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(12px);
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.4px;
            color: #ffffff;
            margin-bottom: 28px;
        }

        .brand-badge i {
            color: #38bdf8;
            font-size: 15px;
        }

        .hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 34px;
            font-weight: 800;
            line-height: 1.25;
            letter-spacing: -0.5px;
            margin-bottom: 16px;
        }

        .hero-title span {
            background: linear-gradient(120deg, #38bdf8, #a5b4fc, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 15px;
            color: #cbd5e1;
            line-height: 1.6;
            margin-bottom: 32px;
            max-width: 440px;
        }

        .features-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 14px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            padding: 12px 18px;
            border-radius: 14px;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateX(4px);
        }

        .feature-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.25), rgba(79, 70, 229, 0.4));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #38bdf8;
            font-size: 15px;
            flex-shrink: 0;
        }

        .feature-text {
            font-size: 13.5px;
            font-weight: 600;
            color: #f1f5f9;
        }

        .feature-sub {
            font-size: 11.5px;
            color: #94a3b8;
            font-weight: 400;
            display: block;
            margin-top: 2px;
        }

        .hero-footer {
            position: relative;
            z-index: 2;
            padding-top: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 12px;
            color: #94a3b8;
        }

        /* Right Form Side */
        .form-side {
            padding: 48px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .form-header {
            margin-bottom: 28px;
        }

        .form-header h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.4px;
            margin-bottom: 6px;
        }

        .form-header p {
            color: var(--text-muted);
            font-size: 14px;
        }

        .input-group-custom {
            margin-bottom: 20px;
        }

        .input-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i.leading-icon {
            position: absolute;
            left: 16px;
            color: #94a3b8;
            font-size: 15px;
            transition: color 0.2s;
        }

        .form-control-custom {
            width: 100%;
            padding: 13px 16px 13px 44px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14.5px;
            color: #0f172a;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .form-control-custom:focus {
            outline: none;
            background: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
        }

        .form-control-custom:focus + i.leading-icon,
        .input-wrapper:focus-within i.leading-icon {
            color: var(--primary);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-family: 'Outfit', sans-serif;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.2px;
            cursor: pointer;
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #4338ca 0%, #3730a3 100%);
            transform: translateY(-2px);
            box-shadow: 0 14px 24px -5px rgba(79, 70, 229, 0.5);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .alert-error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        /* Demo Quick Pill Login Switcher */
        .demo-pills {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px dashed #e2e8f0;
        }

        .demo-pills-label {
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #94a3b8;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .pills-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .demo-pill-btn {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            text-align: left;
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
        }

        .demo-pill-btn:hover {
            background: #e0e7ff;
            border-color: #c7d2fe;
            color: #4338ca;
        }

        .demo-pill-btn span {
            font-size: 10px;
            font-weight: 400;
            color: #64748b;
        }

        .portal-switch {
            margin-top: 24px;
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
        }

        .portal-switch a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .portal-switch a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Ambient Glow Effect -->
    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>

    <div class="auth-card">
        <!-- Left Hero Side -->
        <div class="hero-side">
            <div class="hero-content">
                <div class="brand-badge">
                    <i class="fa-solid fa-building-columns"></i>
                    <span>V Apex Institute of Technology</span>
                </div>

                <h1 class="hero-title">
                    Empowering Next-Gen <span>Engineering & Learning</span>
                </h1>
                
                <p class="hero-subtitle">
                    Autonomous Engineering & Management Campus • Approved by AICTE, New Delhi • NAAC 'A+' Grade & NBA Accredited Institution
                </p>

                <div class="features-list">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fa-solid fa-play"></i>
                        </div>
                        <div>
                            <span class="feature-text">Blended Learning Video Hub</span>
                            <span class="feature-sub">Modular chapters & downloadable study notes</span>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fa-solid fa-code-branch"></i>
                        </div>
                        <div>
                            <span class="feature-text">OBE-Mapped Assessments</span>
                            <span class="feature-sub">CO/PO mapping & rubric-based grading</span>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                        <div>
                            <span class="feature-text">Grade Card & Smart Attendance</span>
                            <span class="feature-sub">Live semester progress & timetable tracking</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hero-footer">
                <span><i class="fa-solid fa-location-dot me-1" style="color: #38bdf8;"></i> Cyber Valley Campus, Bengaluru</span>
                <span><i class="fa-solid fa-shield-halved me-1" style="color: #38bdf8;"></i> AICTE / NBA Certified</span>
            </div>
        </div>

        <!-- Right Login Form Side -->
        <div class="form-side">
            <div class="form-header">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                    <div style="width: 28px; height: 28px; border-radius: 7px; background: rgba(79, 70, 229, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 14px;">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <span style="font-size: 12px; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.5px;">V Apex Institute of Technology</span>
                </div>
                <h2>Student Portal Sign In</h2>
                <p>Access your personalized semester courses, lectures & assignments</p>
            </div>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert-error-box">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('lms/authenticate') ?>" method="POST" id="studentLoginForm">
                <?= csrf_field() ?>

                <div class="input-group-custom">
                    <div class="input-label">
                        <span>Student Roll Number</span>
                        <span style="font-size: 11px; color: #94a3b8; font-weight: 500;">e.g. 26CSE001</span>
                    </div>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-id-card leading-icon"></i>
                        <input type="text" name="roll_number" id="rollNumberInput" class="form-control-custom" placeholder="Enter student roll number" required autofocus value="26CSE001">
                    </div>
                </div>

                <div class="input-group-custom">
                    <div class="input-label">
                        <span>Password</span>
                        <a href="javascript:void(0)" style="font-size: 12px; color: var(--primary); text-decoration: none; font-weight: 600;">Forgot?</a>
                    </div>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock leading-icon"></i>
                        <input type="password" name="password" id="passwordInput" class="form-control-custom" placeholder="••••••••" required value="Password@123">
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <span>Sign In to Student Portal</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            <!-- Quick Demo Auto-Fill Pills -->
            <div class="demo-pills">
                <div class="demo-pills-label">
                    <i class="fa-solid fa-bolt text-warning" style="color: #f59e0b;"></i> Quick Demo Fill
                </div>
                <div class="pills-grid">
                    <button type="button" class="demo-pill-btn" onclick="fillCreds('26CSE001', 'Password@123')">
                        <strong>Aarav Patel</strong>
                        <span>26CSE001 (Sem 1)</span>
                    </button>
                    <button type="button" class="demo-pill-btn" onclick="fillCreds('26CSE002', 'Password@123')">
                        <strong>Diya Reddy</strong>
                        <span>26CSE002 (Sem 1)</span>
                    </button>
                </div>
            </div>

            <div class="portal-switch">
                <span>Faculty or Administrator? <a href="<?= base_url('org/login') ?>">Staff Login Portal <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 11px;"></i></a></span>
            </div>
        </div>
    </div>

    <script>
        function fillCreds(roll, pwd) {
            document.getElementById('rollNumberInput').value = roll;
            document.getElementById('passwordInput').value = pwd;
            document.getElementById('rollNumberInput').focus();
        }
    </script>
</body>
</html>
