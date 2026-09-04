<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-main: #f4f7fe;
            --bg-card: #ffffff;
            --text-main: #2b3674;
            --border-color: #e0e5f2;
            --primary: #1E3A8A;
            --primary-hover: #172A45;
        }
        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            background: var(--bg-card);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-card h2 {
            margin-top: 0;
            margin-bottom: 24px;
            font-weight: 700;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }
        .btn-primary {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background: var(--primary-hover);
        }
        .error-msg {
            color: #EE5D50;
            font-size: 14px;
            margin-bottom: 16px;
            background: rgba(238, 93, 80, 0.1);
            padding: 10px;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Super Admin Login</h2>
    
    <?php if(session()->getFlashdata('error')): ?>
        <div class="error-msg"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('superadmin/authenticate') ?>" method="POST">
        <?= csrf_field() ?>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="admin@superadmin.com" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-primary">Sign In</button>
    </form>
</div>

</body>
</html>
