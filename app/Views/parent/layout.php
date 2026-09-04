<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('page_title') ?> | Parent Portal</title>
    <!-- Anti-cache meta tags -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Flatpickr for Universal DD/MM/YYYY Date Inputs -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        :root {
            --primary: #4F46E5;
            --primary-hover: #4338CA;
            --bg-color: #F3F4F6;
            --card-bg: #FFFFFF;
            --text-main: #1F2937;
            --text-muted: #6B7280;
            --border-color: #E5E7EB;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: var(--primary);
            color: white;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-right: 16px;
            font-size: 14px;
        }
        .container {
            max-width: 1200px;
            margin: 24px auto;
            padding: 0 16px;
        }
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
        }
        th {
            background-color: #F9FAFB;
            color: var(--text-muted);
            font-weight: 600;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        .badge-success { background: #DEF7EC; color: #03543F; }
        .badge-danger { background: #FDE8E8; color: #9B1C1C; }
        .badge-warning { background: #FEF08A; color: #854D0E; }
    </style>
</head>
<body>

    <?php if(session()->get('parent_logged_in')): ?>
    <?php $currentUri = service('uri')->getPath(); ?>
    <div class="navbar">
        <div style="display: flex; align-items: center; gap: 20px;">
            <span style="font-weight: 700; font-size: 18px; display: flex; align-items: center; gap: 8px;"><i class="fa-solid fa-graduation-cap"></i> UniLMS Parent Portal</span>
            <div style="display: flex; align-items: center; gap: 8px;">
                <a href="<?= base_url('parent/dashboard') ?>" style="margin: 0; padding: 6px 12px; border-radius: 6px; <?= strpos($currentUri, 'parent/dashboard') !== false || $currentUri === 'parent' ? 'background: rgba(255,255,255,0.2); font-weight: 600;' : 'opacity: 0.9;' ?>"><i class="fa-solid fa-house me-1"></i> Dashboard</a>
                <a href="<?= base_url('parent/attendance') ?>" style="margin: 0; padding: 6px 12px; border-radius: 6px; <?= strpos($currentUri, 'parent/attendance') !== false ? 'background: rgba(255,255,255,0.2); font-weight: 600;' : 'opacity: 0.9;' ?>"><i class="fa-solid fa-user-check me-1"></i> Attendance</a>
                <a href="<?= base_url('parent/fees') ?>" style="margin: 0; padding: 6px 12px; border-radius: 6px; <?= strpos($currentUri, 'parent/fees') !== false ? 'background: rgba(255,255,255,0.2); font-weight: 600;' : 'opacity: 0.9;' ?>"><i class="fa-solid fa-indian-rupee-sign me-1"></i> Fees</a>
                <a href="<?= base_url('parent/marks') ?>" style="margin: 0; padding: 6px 12px; border-radius: 6px; <?= strpos($currentUri, 'parent/marks') !== false ? 'background: rgba(255,255,255,0.2); font-weight: 600;' : 'opacity: 0.9;' ?>"><i class="fa-solid fa-award me-1"></i> Marks</a>
                <a href="<?= base_url('parent/assignments') ?>" style="margin: 0; padding: 6px 12px; border-radius: 6px; <?= strpos($currentUri, 'parent/assignments') !== false ? 'background: rgba(255,255,255,0.2); font-weight: 600;' : 'opacity: 0.9;' ?>"><i class="fa-solid fa-file-signature me-1"></i> Homework</a>
                <a href="<?= base_url('parent/timetable') ?>" style="margin: 0; padding: 6px 12px; border-radius: 6px; <?= strpos($currentUri, 'parent/timetable') !== false ? 'background: rgba(255,255,255,0.2); font-weight: 600;' : 'opacity: 0.9;' ?>"><i class="fa-solid fa-calendar-days me-1"></i> Timetable</a>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 14px; font-size: 14px;">
            <span><i class="fa-regular fa-circle-user me-1"></i> <?= esc(session('parent_name')) ?></span>
            <button type="button" onclick="document.getElementById('parentLogoutModal').style.display='flex'" style="background: rgba(239, 68, 68, 0.2); padding: 5px 12px; border-radius: 6px; border: 1px solid rgba(239, 68, 68, 0.4); color: white; cursor: pointer;"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</button>
        </div>
    </div>
    <?php endif; ?>

    <div class="container">
        <?php if(session()->getFlashdata('error')): ?>
            <div class="parent-flash-alert" style="background: #FEE2E2; color: #991B1B; padding: 12px; border-radius: 6px; margin-bottom: 24px; transition: opacity 0.4s ease;">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('success')): ?>
            <div class="parent-flash-alert" style="background: #D1FAE5; color: #065F46; padding: 12px; border-radius: 6px; margin-bottom: 24px; transition: opacity 0.4s ease;">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>

    <!-- Parent Portal Logout Confirmation Modal -->
    <div id="parentLogoutModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5); backdrop-filter:blur(6px); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:16px; padding:32px 36px; max-width:400px; width:90%; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,0.3);">
            <div style="width:56px; height:56px; margin:0 auto 16px; border-radius:50%; background:rgba(239,68,68,0.12); display:flex; align-items:center; justify-content:center;">
                <i class="fa-solid fa-arrow-right-from-bracket fa-2x" style="color: #ef4444;"></i>
            </div>
            <h3 style="margin:0 0 8px; font-size:20px; font-weight:700; color:#1F2937;">Confirm Logout</h3>
            <p style="margin:0 0 24px; font-size:14px; color:#6B7280; line-height:1.5;">Are you sure you want to log out of the Parent Portal?</p>
            <div style="display:flex; gap:12px; justify-content:center;">
                <button onclick="document.getElementById('parentLogoutModal').style.display='none'" style="flex:1; padding:10px 20px; border-radius:10px; border:1px solid #E5E7EB; background:transparent; color:#374151; font-size:14px; font-weight:600; cursor:pointer;">Cancel</button>
                <a href="<?= base_url('parent/logout') ?>" style="flex:1; padding:10px 20px; border-radius:10px; border:none; background:linear-gradient(135deg, #ef4444, #dc2626); color:#fff; font-size:14px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; cursor:pointer;">Logout</a>
            </div>
        </div>
    </div>

    <script>
        // Auto-dismiss Flash Alerts in 3 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.parent-flash-alert');
                alerts.forEach(function(alert) {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 400);
                });
            }, 3000);
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
