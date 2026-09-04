<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creator Studio Login - UniLMS</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: #0f172a;
            font-size: 13px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 1.25rem;
            max-width: 420px;
            width: 100%;
            padding: 2.25rem 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
        }
        .btn-creator {
            background: var(--primary-gradient);
            color: white;
            font-weight: 700;
            font-size: 13px;
            padding: 0.65rem 1.25rem;
            border-radius: 0.6rem;
            border: none;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            transition: all 0.2s ease;
        }
        .btn-creator:hover {
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
        }
        .form-control {
            font-size: 13px;
        }
        .form-label {
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-3">
        <div class="d-inline-flex align-items-center justify-content-center text-white rounded-3 mb-2" style="width: 46px; height: 46px; background: var(--primary-gradient); font-size: 20px; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);">
            <i class="fa-solid fa-clapperboard"></i>
        </div>
        <h4 class="fw-bold mb-1" style="font-family: 'Outfit', sans-serif; font-size: 18px;">Creator Studio</h4>
        <p class="text-muted mb-0" style="font-size: 12px;">Authoring & Curriculum Publishing Portal</p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger rounded-3 py-2 small mb-3" style="font-size: 12px;">
            <i class="fa-solid fa-circle-exclamation me-1"></i> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success rounded-3 py-2 small mb-3" style="font-size: 12px;">
            <i class="fa-solid fa-circle-check me-1"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('creator/authenticate') ?>" method="POST">
        <?= csrf_field() ?>
        
        <div class="mb-3">
            <label class="form-label text-dark">Creator Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-envelope" style="font-size: 12px;"></i></span>
                <input type="email" id="creatorEmail" name="email" class="form-control" placeholder="creator@unilms.com" required autofocus>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label text-dark">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-lock" style="font-size: 12px;"></i></span>
                <input type="password" id="creatorPass" name="password" class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" class="btn btn-creator w-100 mb-3">
            <i class="fa-solid fa-right-to-bracket me-1"></i> Sign In to Creator Studio
        </button>
    </form>

    <!-- Quick Demo Credential Autofill Helper -->
    <div class="p-2.5 bg-light rounded-3 border text-center mb-3">
        <div class="text-muted" style="font-size: 11px;">Demo Account: <strong>creator@unilms.com</strong></div>
        <button type="button" class="btn btn-sm btn-outline-primary py-0.5 px-2 rounded-pill mt-1" style="font-size: 11px;" onclick="fillDemo()">
            <i class="fa-solid fa-bolt me-1"></i> Autofill Demo Credentials
        </button>
    </div>

    <div class="text-center text-muted" style="font-size: 11.5px;">
        <i class="fa-solid fa-shield-halved me-1 text-primary"></i> Isolated authoring environment
    </div>
</div>

<script>
function fillDemo() {
    document.getElementById('creatorEmail').value = 'creator@unilms.com';
    document.getElementById('creatorPass').value = 'Password@123';
}
</script>

</body>
</html>

