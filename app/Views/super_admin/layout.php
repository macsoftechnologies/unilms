<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Platform</title>
    <link rel="stylesheet" href="<?= base_url('index.css?v=' . time()) ?>">
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
        (function() {
            const savedMode = localStorage.getItem('superadmin_theme_mode') || 'light';
            document.documentElement.setAttribute('data-theme', 'blue');
            if (savedMode === 'dark' || savedMode === 'dark-mode') {
                document.documentElement.classList.add('dark-mode');
            }
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
    <style>
        .pagination {
            display: flex;
            list-style: none;
            gap: 10px;
            margin-top: 20px;
        }
        .pagination li a, .pagination li span {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            text-decoration: none;
            color: var(--text-main);
        }
        .pagination li.active span {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        .pagination li a:hover {
            background-color: var(--sidebar-hover);
        }
    </style>
</head>
<body class="superadmin-layout">

    <div class="app-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>SuperAdmin</span>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <?php 
                    $is_root = session()->get('admin_is_root') == 1;
                    $perms = session()->get('admin_permissions') ?? [];
                ?>
                <a href="<?= base_url('superadmin') ?>" class="nav-item <?= current_url() == base_url('superadmin') ? 'active' : '' ?>">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
                
                <?php if($is_root || in_array('organizations', $perms)): ?>
                <a href="<?= base_url('superadmin/organizations') ?>" class="nav-item <?= current_url() == base_url('superadmin/organizations') ? 'active' : '' ?>">
                    <i class="fa-solid fa-building"></i>
                    <span>Organizations</span>
                </a>
                <a href="<?= base_url('superadmin/expiring') ?>" class="nav-item <?= current_url() == base_url('superadmin/expiring') ? 'active' : '' ?>">
                    <i class="fa-solid fa-clock"></i>
                    <span>Expiring Soon</span>
                </a>
                <?php endif; ?>
                
                <?php if($is_root || in_array('plans', $perms)): ?>
                <a href="<?= base_url('superadmin/plans') ?>" class="nav-item <?= current_url() == base_url('superadmin/plans') ? 'active' : '' ?>">
                    <i class="fa-solid fa-tags"></i>
                    <span>Plans & Packages</span>
                </a>
                <?php endif; ?>
                
                <?php if($is_root || in_array('payments', $perms)): ?>
                <a href="<?= base_url('superadmin/payments') ?>" class="nav-item <?= current_url() == base_url('superadmin/payments') ? 'active' : '' ?>">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Payments</span>
                </a>
                <?php endif; ?>
                
                <?php if($is_root): ?>
                <a href="<?= base_url('superadmin/users') ?>" class="nav-item <?= current_url() == base_url('superadmin/users') ? 'active' : '' ?>">
                    <i class="fa-solid fa-users-gear"></i>
                    <span>Admins</span>
                </a>
                <a href="<?= base_url('superadmin/creators') ?>" class="nav-item <?= strpos(current_url(), 'superadmin/creators') !== false ? 'active' : '' ?>">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    <span>Content Creators</span>
                </a>
                <?php endif; ?>
                
                <?php if($is_root || in_array('settings', $perms)): ?>
                <a href="<?= base_url('superadmin/logs') ?>" class="nav-item <?= current_url() == base_url('superadmin/logs') ? 'active' : '' ?>">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <span>Activity Logs</span>
                </a>
                <a href="<?= base_url('superadmin/settings') ?>" class="nav-item <?= current_url() == base_url('superadmin/settings') ? 'active' : '' ?>">
                    <i class="fa-solid fa-cogs"></i>
                    <span>Settings</span>
                </a>
                <?php endif; ?>
            </nav>

            <div class="sidebar-footer">
                <button class="theme-toggle" id="theme-toggle-btn">
                    <i class="fa-solid fa-moon dark-icon"></i>
                    <i class="fa-solid fa-sun light-icon"></i>
                    <span>Toggle Theme</span>
                </button>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Header -->
            <header class="topbar">
                <div class="topbar-main-row">
                    <div class="topbar-left">
                        <h1 id="page-title"><?= $title ?? 'Dashboard' ?></h1>
                    </div>
                    <div class="topbar-right">
                        <div class="user-profile">
                            <img src="https://i.pravatar.cc/150?img=11" alt="Super Admin" class="avatar">
                            <div class="user-info">
                                <span class="user-name">Root Admin</span>
                                <span class="user-role">Superuser | <a href="#" onclick="document.getElementById('logoutModal').style.display='flex'" style="color: var(--danger); text-decoration: none;">Logout</a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <?php if(session()->getFlashdata('success')): ?>
                <div id="flash-alert-box" style="margin: 16px 32px 0; padding: 12px 20px; background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.35); border-radius: 10px; color: #16A34A; font-weight: 600; font-size: 14px; display: flex; align-items: center; justify-content: space-between; animation: modalFadeIn 0.3s ease; transition: opacity 0.5s ease, transform 0.5s ease;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-circle-check" style="font-size: 16px;"></i>
                        <span><?= session()->getFlashdata('success') ?></span>
                    </div>
                    <button type="button" onclick="dismissFlashAlert()" style="background: none; border: none; color: #16A34A; font-size: 18px; cursor: pointer; padding: 0 4px;">&times;</button>
                </div>
            <?php endif; ?>

            <?php if(session()->getFlashdata('error')): ?>
                <div id="flash-alert-box" style="margin: 16px 32px 0; padding: 12px 20px; background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.35); border-radius: 10px; color: #DC2626; font-weight: 600; font-size: 14px; display: flex; align-items: center; justify-content: space-between; animation: modalFadeIn 0.3s ease; transition: opacity 0.5s ease, transform 0.5s ease;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-circle-exclamation" style="font-size: 16px;"></i>
                        <span><?= session()->getFlashdata('error') ?></span>
                    </div>
                    <button type="button" onclick="dismissFlashAlert()" style="background: none; border: none; color: #DC2626; font-size: 18px; cursor: pointer; padding: 0 4px;">&times;</button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>

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
                <a href="<?= base_url('superadmin/logout') ?>" style="flex:1; padding:10px 20px; border-radius:10px; border:none; background:linear-gradient(135deg, #ef4444, #dc2626); color:#fff; font-size:14px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.2s;">Logout</a>
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
        (function() {
            const savedColor = localStorage.getItem('unilms_theme_color') || 'purple';
            const savedMode = localStorage.getItem('unilms_theme_mode') || localStorage.getItem('superadmin_theme') || 'light';
            document.body.setAttribute('data-theme', savedColor);
            if (savedMode === 'dark' || savedMode === 'dark-mode') {
                document.body.classList.add('dark-mode');
            }
        })();

        // Theme Toggle Logic
        const themeToggleBtn = document.getElementById('theme-toggle-btn');
        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', () => {
                const isDark = document.body.classList.toggle('dark-mode');
                document.documentElement.classList.toggle('dark-mode', isDark);
                const mode = isDark ? 'dark' : 'light';
                localStorage.setItem('superadmin_theme', isDark ? 'dark-mode' : '');
            });
        }

        // Auto-dismiss Flash Alerts
        function dismissFlashAlert() {
            const alertBox = document.getElementById('flash-alert-box');
            if (alertBox) {
                alertBox.style.opacity = '0';
                alertBox.style.transform = 'translateY(-8px)';
                setTimeout(() => { if (alertBox) alertBox.remove(); }, 400);
            }
        }
        if (document.getElementById('flash-alert-box')) {
            setTimeout(dismissFlashAlert, 3000);
        }

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
