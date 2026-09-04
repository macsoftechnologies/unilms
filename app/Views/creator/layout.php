<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('page_title') ?> - Creator Studio</title>
    <!-- Anti-cache meta tags -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --secondary-gradient: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            --accent-gradient: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
            --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --bg-body: #f8fafc;
            --card-bg: #ffffff;
            --text-dark: #0f172a;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --card-shadow-hover: 0 12px 24px -4px rgba(79, 70, 229, 0.12), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
            --card-radius: 16px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            letter-spacing: -0.01em;
            font-size: 13px;
        }

        /* =========================================================================
           UNIVERSAL UNILMS TYPOGRAPHY & COMPACT SCALE
        ========================================================================= */
        h1, .h1 {
            font-family: 'Outfit', sans-serif !important;
            font-size: 20px !important;
            font-weight: 700 !important;
            color: var(--text-dark) !important;
            line-height: 1.25 !important;
            margin-bottom: 4px;
        }

        h2, .h2 {
            font-family: 'Outfit', sans-serif !important;
            font-size: 17px !important;
            font-weight: 700 !important;
            color: var(--text-dark) !important;
            line-height: 1.3 !important;
            margin-bottom: 4px;
        }

        h3, .h3 {
            font-family: 'Outfit', sans-serif !important;
            font-size: 15px !important;
            font-weight: 700 !important;
            color: var(--text-dark) !important;
            line-height: 1.3 !important;
            margin-bottom: 4px;
        }

        h4, .h4 {
            font-family: 'Outfit', sans-serif !important;
            font-size: 13.5px !important;
            font-weight: 600 !important;
            color: var(--text-dark) !important;
            line-height: 1.35 !important;
            margin-bottom: 3px;
        }

        h5, .h5 {
            font-family: 'Outfit', sans-serif !important;
            font-size: 12.5px !important;
            font-weight: 600 !important;
            color: var(--text-dark) !important;
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

        .badge {
            font-size: 11px !important;
            font-weight: 600 !important;
            padding: 3px 8px !important;
            border-radius: 6px !important;
        }

        .form-control, .form-select {
            font-size: 13px !important;
            padding: 0.5rem 0.85rem !important;
        }

        .form-label {
            font-size: 12.5px !important;
            font-weight: 600 !important;
            margin-bottom: 0.35rem !important;
        }

        .form-control-lg {
            font-size: 14px !important;
            padding: 0.65rem 1rem !important;
        }

        .btn {
            font-size: 12.5px !important;
            font-weight: 600 !important;
        }

        .btn-sm {
            font-size: 11.5px !important;
            padding: 0.3rem 0.65rem !important;
        }

        table {
            font-size: 12.5px !important;
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

        /* Top Creator Navigation Bar */
        .creator-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04);
        }

        .creator-brand-badge {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: var(--primary-gradient);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
            transition: transform 0.2s ease;
        }
        
        .creator-brand:hover .creator-brand-badge {
            transform: scale(1.05);
        }

        .creator-brand-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.02em;
            line-height: 1.1;
        }
        
        .creator-brand-subtitle {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .creator-nav-link {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-secondary);
            text-decoration: none;
            padding: 0.55rem 1.1rem;
            border-radius: 10px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
        }

        .creator-nav-link:hover {
            color: var(--primary);
            background-color: var(--primary-light);
        }

        .creator-nav-link.active {
            color: var(--primary);
            background-color: var(--primary-light);
            font-weight: 700;
        }

        .creator-user-pill {
            background: #ffffff;
            border: 1px solid var(--border-color);
            padding: 0.4rem 0.85rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .creator-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .content-container {
            max-width: 1240px;
            width: 100%;
            margin: 0 auto;
            padding: 2rem 1.5rem 4rem;
            flex: 1;
        }

        /* Modern UI Card Defaults */
        .modern-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--card-radius);
            box-shadow: var(--card-shadow);
            transition: all 0.25s ease;
        }

        .modern-card:hover {
            box-shadow: var(--card-shadow-hover);
        }

        /* Custom Buttons */
        .btn-gradient-primary {
            background: var(--primary-gradient);
            color: #ffffff !important;
            border: none;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            transition: all 0.2s ease;
        }

        .btn-gradient-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35);
        }

        .btn-gradient-primary:active {
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .content-container {
                padding: 1.25rem 1rem 3rem;
            }
        }
    </style>
</head>
<body>

<?php $uri = service('uri')->getPath(); ?>

<!-- Clean Creator-Only Top Navigation -->
<nav class="creator-navbar">
    <div class="container-fluid d-flex justify-content-between align-items-center" style="max-width: 1240px; padding: 0 1.5rem;">
        <div class="d-flex align-items-center gap-4">
            <a href="<?= base_url('creator/courses') ?>" class="creator-brand d-flex align-items-center gap-3 text-decoration-none">
                <div class="creator-brand-badge">
                    <i class="fa-solid fa-clapperboard"></i>
                </div>
                <div>
                    <div class="creator-brand-title">UniLMS</div>
                    <div class="creator-brand-subtitle">Creator Studio</div>
                </div>
            </a>

            <!-- Navigation Links -->
            <div class="d-none d-md-flex align-items-center gap-2">
                <a href="<?= base_url('creator/courses') ?>" class="creator-nav-link <?= (strpos($uri, 'creator/courses/create') === false && strpos($uri, 'creator/courses/builder') === false && strpos($uri, 'creator/courses/preview') === false) ? 'active' : '' ?>">
                    <i class="fa-solid fa-layer-group"></i> My Courses
                </a>
                <a href="<?= base_url('creator/courses/create') ?>" class="creator-nav-link <?= strpos($uri, 'creator/courses/create') !== false ? 'active' : '' ?>">
                    <i class="fa-solid fa-circle-plus"></i> + Create New Course
                </a>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="d-none d-sm-flex align-items-center">
                <div class="creator-user-pill">
                    <div class="creator-avatar">
                        <?= strtoupper(substr(session('creator_name') ?: 'C', 0, 1)) ?>
                    </div>
                    <div class="text-start pe-1">
                        <div class="fw-bold fs-6 text-dark lh-1" style="font-size: 0.85rem !important;"><?= esc(session('creator_name') ?: 'Course Instructor') ?></div>
                        <small class="text-muted" style="font-size: 10px; font-weight: 600;">VERIFIED CREATOR</small>
                    </div>
                </div>
            </div>
            <button type="button" 
                    onclick="showConfirmAction({ title: 'Confirm Logout', message: 'Are you sure you want to log out of Creator Studio? You will need to sign in again to create or edit courses.', icon: 'fa-solid fa-arrow-right-from-bracket fa-2x', iconColor: '#EF4444', iconBg: 'rgba(239, 68, 68, 0.12)', btnText: 'Yes, Logout', btnClass: 'btn-danger', actionUrl: '<?= base_url('creator/logout') ?>' })" 
                    class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.85rem;">
                <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Logout
            </button>
        </div>
    </div>
</nav>

<div class="content-container">
    <?php if (session()->getFlashdata('success')): ?>
        <div id="flash-alert-box" class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 flash-msg d-flex align-items-center" role="alert" style="background: #ecfdf5; border-left: 4px solid #10b981 !important; color: #065f46; transition: opacity 0.4s ease, transform 0.4s ease;">
            <div class="p-2 rounded-circle bg-success text-white me-3 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 12px;">
                <i class="fa-solid fa-check"></i>
            </div>
            <div>
                <strong>Success:</strong> <?= session()->getFlashdata('success') ?>
            </div>
            <button type="button" class="btn-close ms-auto" onclick="dismissFlashAlert()"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div id="flash-alert-box" class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 flash-msg d-flex align-items-center" role="alert" style="background: #fef2f2; border-left: 4px solid #ef4444 !important; color: #991b1b; transition: opacity 0.4s ease, transform 0.4s ease;">
            <div class="p-2 rounded-circle bg-danger text-white me-3 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 12px;">
                <i class="fa-solid fa-exclamation"></i>
            </div>
            <div>
                <strong>Notice:</strong> <?= session()->getFlashdata('error') ?>
            </div>
            <button type="button" class="btn-close ms-auto" onclick="dismissFlashAlert()"></button>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</div>

<!-- Universal Action Confirmation Modal -->
<div class="modal fade" id="universalConfirmModal" tabindex="-1" aria-hidden="true" style="backdrop-filter: blur(8px);">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content border-0 shadow-lg rounded-4 p-4 text-center bg-white" style="animation: modalFadeIn 0.25s ease;">
            <div id="confirmModalIconWrapper" class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 68px; height: 68px; background: rgba(79, 70, 229, 0.12);">
                <i id="confirmModalIcon" class="fa-solid fa-rocket fa-2x" style="color: var(--primary);"></i>
            </div>
            <h5 class="fw-bold text-dark mb-2 brand-font" id="confirmModalTitle">Confirm Action</h5>
            <p class="text-muted small mb-4" id="confirmModalMessage">Are you sure you want to proceed?</p>
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-light rounded-pill px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmModalActionBtn" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold shadow-sm">Confirm</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-dismiss Flash Alerts in 3.5 seconds
function dismissFlashAlert() {
    const alerts = document.querySelectorAll('.flash-msg, #flash-alert-box');
    alerts.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(-6px)';
        setTimeout(() => { if (el) el.remove(); }, 400);
    });
}
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelectorAll('.flash-msg, #flash-alert-box').length > 0) {
        setTimeout(dismissFlashAlert, 3500);
    }
});

function showConfirmAction(options) {
    document.getElementById('confirmModalTitle').innerText = options.title || 'Confirm Action';
    document.getElementById('confirmModalMessage').innerText = options.message || 'Are you sure you want to proceed?';
    
    const icon = document.getElementById('confirmModalIcon');
    const iconWrapper = document.getElementById('confirmModalIconWrapper');
    icon.className = options.icon || 'fa-solid fa-circle-question fa-2x';
    icon.style.color = options.iconColor || 'var(--primary)';
    iconWrapper.style.background = options.iconBg || 'rgba(79, 70, 229, 0.12)';
    
    const btn = document.getElementById('confirmModalActionBtn');
    btn.className = 'btn rounded-pill px-4 py-2 fw-semibold shadow-sm ' + (options.btnClass || 'btn-primary');
    btn.innerText = options.btnText || 'Confirm';
    btn.href = options.actionUrl || '#';
    
    const modal = new bootstrap.Modal(document.getElementById('universalConfirmModal'));
    modal.show();
}

// Prevent browser back button showing cached page after logout
window.addEventListener('pageshow', function(event) {
    if (event.persisted || (window.performance && window.performance.navigation && window.performance.navigation.type === 2)) {
        window.location.reload();
    }
});
</script>
</body>
</html>

