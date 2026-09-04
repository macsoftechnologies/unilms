<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Login | UniLMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-main: #f5f3ff;
            --bg-card: #ffffff;
            --text-main: #1a202c;
            --text-muted: #718096;
            --border-color: #ede9fe;
            --primary: #6d28d9;
            --primary-light: rgba(109, 40, 217, 0.08);
            --primary-hover: #5b21b6;
            --danger: #e53e3e;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: var(--bg-main);
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-container {
            display: flex;
            max-width: 900px;
            width: 100%;
            background: var(--bg-card);
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .login-brand {
            background: linear-gradient(135deg, #4c1d95 0%, #6d28d9 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 380px;
            flex-shrink: 0;
        }
        .login-brand h1 { font-size: 28px; font-weight: 700; margin-bottom: 12px; }
        .login-brand p { font-size: 15px; line-height: 1.6; opacity: 0.85; }
        .login-brand .brand-features { margin-top: 30px; }
        .login-brand .brand-features li {
            list-style: none;
            padding: 8px 0;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            opacity: 0.9;
        }
        .login-brand .brand-features li::before {
            content: '✓';
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            font-size: 12px;
            font-weight: bold;
        }
        .login-form-side {
            padding: 60px 40px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-form-side h2 { font-size: 24px; font-weight: 700; margin-bottom: 8px; }
        .login-form-side .subtitle { color: var(--text-muted); font-size: 14px; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px; }
        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }
        .btn-primary {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 8px;
        }
        .btn-primary:hover { background: var(--primary-hover); }
        .error-msg {
            color: var(--danger);
            font-size: 14px;
            margin-bottom: 16px;
            background: rgba(229, 62, 62, 0.08);
            padding: 12px;
            border-radius: 8px;
            border: 1px solid rgba(229, 62, 62, 0.15);
        }
        .login-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
        }
        @media (max-width: 768px) {
            .login-brand { display: none; }
            .login-container { border-radius: 0; max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-brand">
        <h1>UniLMS</h1>
        <p>Your institution's unified platform for College Management and Learning.</p>
        <ul class="brand-features">
            <li>College Management System</li>
            <li>Learning Management System</li>
            <li>Role-Based Access Control</li>
            <li>Real-Time Analytics</li>
        </ul>
    </div>
    
    <div class="login-form-side">
        <h2>Welcome Back</h2>
        <p class="subtitle">Sign in to your organization portal</p>
        
        <?php if(session()->getFlashdata('error')): ?>
            <div class="error-msg"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        
        <form action="<?= base_url('org/authenticate') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Employee ID / Staff Code</label>
                <input type="text" name="employee_code" class="form-control" placeholder="e.g. ADM-1001 or EMP-1001" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-primary">Sign In</button>
        </form>
        
        
    </div>
</div>

</body>
</html>
