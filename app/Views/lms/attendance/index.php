<?= $this->extend('lms/layout') ?>
<?= $this->section('page_title') ?>Attendance Calendar<?= $this->endSection() ?>
<?= $this->section('content') ?>

<style>
    /* Month Header Controls */
    .att-header-banner {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.12) 0%, rgba(139, 92, 246, 0.18) 50%, rgba(236, 72, 153, 0.1) 100%), var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 24px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04);
    }

    .att-month-controls {
        display: flex;
        align-items: center;
        gap: 12px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 6px 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    .btn-month-nav {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: var(--bg-canvas);
        color: var(--text-main);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        transition: all 0.2s;
    }

    .btn-month-nav:hover {
        background: var(--primary);
        color: #ffffff;
        border-color: var(--primary);
        transform: translateY(-1px);
    }

    .current-month-display {
        font-family: 'Outfit', sans-serif;
        font-size: 17px;
        font-weight: 800;
        color: var(--text-main);
        min-width: 170px;
        text-align: center;
        letter-spacing: -0.2px;
    }

    /* KPI Summary Row */
    .att-kpi-grid {
        display: grid;
        grid-template-columns: 280px 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    .att-kpi-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        position: relative;
        overflow: hidden;
    }

    /* Calendar Grid Matrix */
    .calendar-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04);
        margin-bottom: 24px;
    }

    .calendar-weekdays-row {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
        margin-bottom: 12px;
    }

    .calendar-weekday-col {
        text-align: center;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-muted);
        padding: 6px 0;
    }

    .calendar-days-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
    }

    .calendar-day-tile {
        min-height: 105px;
        border-radius: 14px;
        border: 1px solid var(--border);
        background: var(--bg-canvas);
        padding: 10px 12px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .calendar-day-tile:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        border-color: var(--primary);
    }

    .calendar-day-tile.empty {
        background: transparent;
        border-color: transparent;
        cursor: default;
    }
    .calendar-day-tile.empty:hover {
        transform: none;
        box-shadow: none;
    }

    .day-num-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px;
    }

    .day-number {
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        font-weight: 800;
        color: var(--text-main);
    }

    .today-pill {
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: #ffffff;
        padding: 2px 7px;
        border-radius: 6px;
        letter-spacing: 0.4px;
    }

    /* Day State Color Accents */
    .calendar-day-tile.state-present {
        background: rgba(16, 185, 129, 0.06);
        border-color: rgba(16, 185, 129, 0.25);
    }
    .calendar-day-tile.state-present .day-status-pill {
        background: rgba(16, 185, 129, 0.15);
        color: #059669;
    }

    .calendar-day-tile.state-partial {
        background: rgba(245, 158, 11, 0.06);
        border-color: rgba(245, 158, 11, 0.3);
    }
    .calendar-day-tile.state-partial .day-status-pill {
        background: rgba(245, 158, 11, 0.18);
        color: #d97706;
    }

    .calendar-day-tile.state-absent {
        background: rgba(239, 68, 68, 0.06);
        border-color: rgba(239, 68, 68, 0.3);
    }
    .calendar-day-tile.state-absent .day-status-pill {
        background: rgba(239, 68, 68, 0.15);
        color: #dc2626;
    }

    .calendar-day-tile.state-holiday {
        background: rgba(14, 165, 233, 0.07);
        border-color: rgba(14, 165, 233, 0.25);
    }
    .calendar-day-tile.state-holiday .day-status-pill {
        background: rgba(14, 165, 233, 0.15);
        color: #0284c7;
    }

    .calendar-day-tile.state-weekend {
        background: rgba(100, 116, 139, 0.04);
        border-color: rgba(100, 116, 139, 0.15);
        opacity: 0.85;
    }

    .day-status-pill {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        width: fit-content;
        line-height: 1.2;
    }

    .day-session-meta {
        font-size: 10.5px;
        color: var(--text-muted);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 4px;
    }

    /* Modal / Drawer Styles */
    .att-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(6px);
        z-index: 2000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: fadeIn 0.2s ease-out;
    }

    .att-modal-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 22px;
        width: 100%;
        max-width: 580px;
        max-height: 85vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 40px rgba(0,0,0,0.25);
        overflow: hidden;
        animation: scaleUp 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes scaleUp {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-head {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--bg-canvas);
    }

    .modal-body {
        padding: 22px 24px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .period-item-card {
        background: var(--surface-elevated);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        transition: all 0.15s;
    }

    .period-item-card:hover {
        border-color: var(--primary);
        transform: translateX(2px);
    }

    /* Legend Row */
    .calendar-legend-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 20px;
        padding: 14px;
        background: var(--bg-canvas);
        border: 1px solid var(--border);
        border-radius: 14px;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-muted);
        margin-top: 18px;
    }

    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }

    @media (max-width: 900px) {
        .att-kpi-grid {
            grid-template-columns: 1fr;
        }
        .calendar-weekdays-row, .calendar-days-grid {
            gap: 6px;
        }
        .calendar-day-tile {
            min-height: 80px;
            padding: 6px 8px;
        }
        .day-status-pill {
            font-size: 9px;
            padding: 2px 4px;
        }
    }
</style>

<!-- 1. Top Header Banner with Month Navigator -->
<div class="att-header-banner">
    <div>
        <span class="badge" style="background: rgba(99, 102, 241, 0.15); color: var(--primary); font-size: 11px; font-weight: 800; text-transform: uppercase; padding: 4px 10px; border-radius: 20px; margin-bottom: 6px; display: inline-block;">
            Academic Attendance Hub
        </span>
        <h1 style="margin: 0; font-size: 24px; font-weight: 800; font-family: 'Outfit', sans-serif; color: var(--text-main);">
            My Attendance Calendar
        </h1>
        <p style="margin: 4px 0 0; font-size: 13px; color: var(--text-muted);">
            View daily presence, period-by-period class topics, and threshold calculations.
        </p>
    </div>

    <!-- Month Navigation Controls -->
    <div class="att-month-controls">
        <a href="<?= base_url('lms/attendance?month=' . $prevMonth . '&year=' . $prevYear) ?>" class="btn-month-nav" title="Previous Month">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <div class="current-month-display">
            <i class="fa-solid fa-calendar-days text-primary me-2"></i><?= esc($monthName) ?>
        </div>
        <a href="<?= base_url('lms/attendance?month=' . $nextMonth . '&year=' . $nextYear) ?>" class="btn-month-nav" title="Next Month">
            <i class="fa-solid fa-chevron-right"></i>
        </a>
        <?php if ($currentMonth != date('n') || $currentYear != date('Y')): ?>
            <a href="<?= base_url('lms/attendance') ?>" class="btn btn-sm btn-outline" style="font-size: 11px; padding: 5px 10px; border-radius: 8px; margin-left: 4px;">
                Today
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- 2. Top Summary & Safe-Bunk Threshold Cards -->
<div class="att-kpi-grid">
    <!-- Card 1: Semester Overall Radial Progress -->
    <?php 
        $pct = $overall['percentage'];
        $colorHex = '#10b981'; // green
        $statusText = 'Excellent Standing';
        if ($pct < 75) {
            $colorHex = '#ef4444'; // red
            $statusText = 'Shortage Risk';
        } elseif ($pct < 85) {
            $colorHex = '#f59e0b'; // amber
            $statusText = 'Good Standing';
        }
    ?>
    <div class="att-kpi-card" style="align-items: center; text-align: center;">
        <span style="font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
            Semester Overall
        </span>

        <div style="position: relative; width: 130px; height: 130px; margin: 12px auto;">
            <svg viewBox="0 0 36 36" style="width: 100%; height: 100%; transform: rotate(-90deg);">
                <path style="stroke: var(--border); stroke-width: 3.8; fill: none;" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                <path style="stroke: <?= $colorHex ?>; stroke-width: 3.8; fill: none; stroke-dasharray: <?= min(100, $pct) ?>, 100; stroke-linecap: round; transition: stroke-dasharray 1s ease-out;" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
            </svg>
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                <div style="font-family: 'Outfit', sans-serif; font-size: 26px; font-weight: 800; color: <?= $colorHex ?>; line-height: 1;">
                    <?= $pct ?>%
                </div>
                <div style="font-size: 10px; font-weight: 700; color: var(--text-muted); margin-top: 3px;">
                    <?= $statusText ?>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 14px; font-size: 12px; color: var(--text-muted);">
            <span><strong style="color: #10b981;"><?= $overall['present'] ?></strong> Present</span>
            <span>•</span>
            <span><strong style="color: #f59e0b;"><?= $overall['late'] ?></strong> Late</span>
            <span>•</span>
            <span><strong style="color: #ef4444;"><?= $overall['absent'] ?></strong> Absent</span>
        </div>
    </div>

    <!-- Card 2: 75% Mandatory Threshold & Safe-Bunk Buffer -->
    <div class="att-kpi-card">
        <div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                <span style="font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                    Threshold & Buffer Analysis
                </span>
                <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11px; font-weight: 700;">
                    Min 75% Required
                </span>
            </div>

            <?php if ($pct >= 75): ?>
                <div style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.25); border-radius: 12px; padding: 14px; margin-bottom: 12px;">
                    <div style="display: flex; align-items: center; gap: 10px; color: #059669; font-weight: 800; font-size: 14px; margin-bottom: 4px;">
                        <i class="fa-solid fa-shield-halved" style="font-size: 18px;"></i> Safe Zone Active
                    </div>
                    <p style="margin: 0; font-size: 12.5px; color: var(--text-main); line-height: 1.4;">
                        You have a safety cushion! You can miss up to <strong><?= $overall['safe_bunk_count'] ?> more sessions</strong> without falling below the mandatory 75% exam cutoff.
                    </p>
                </div>
            <?php else: ?>
                <div style="background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.25); border-radius: 12px; padding: 14px; margin-bottom: 12px;">
                    <div style="display: flex; align-items: center; gap: 10px; color: #dc2626; font-weight: 800; font-size: 14px; margin-bottom: 4px;">
                        <i class="fa-solid fa-triangle-exclamation" style="font-size: 18px;"></i> Attendance Shortage Alert
                    </div>
                    <p style="margin: 0; font-size: 12.5px; color: var(--text-main); line-height: 1.4;">
                        You are below the 75% requirement. You must attend the next <strong><?= $overall['needed_sessions_count'] ?> consecutive classes</strong> to regain examination eligibility.
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <div style="font-size: 11.5px; color: var(--text-muted); display: flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-circle-info text-primary"></i> Calculated across all recorded semester sessions (<?= $overall['total'] ?> total).
        </div>
    </div>

    <!-- Card 3: Selected Month Overview -->
    <div class="att-kpi-card">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
            <span style="font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                <?= esc($monthName) ?> Summary
            </span>
            <span style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: var(--text-main);">
                <?= $monthStats['percentage'] ?>%
            </span>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 14px;">
            <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 10px; padding: 10px 12px;">
                <div style="font-size: 18px; font-weight: 800; color: #10b981;"><?= $monthStats['present'] ?></div>
                <div style="font-size: 11px; color: var(--text-muted); font-weight: 600;">Present Sessions</div>
            </div>
            <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 10px; padding: 10px 12px;">
                <div style="font-size: 18px; font-weight: 800; color: #ef4444;"><?= $monthStats['absent'] ?></div>
                <div style="font-size: 11px; color: var(--text-muted); font-weight: 600;">Absent Sessions</div>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; font-size: 12px; color: var(--text-muted); border-top: 1px solid var(--border); padding-top: 10px;">
            <span>Total Sessions: <strong><?= $monthStats['total'] ?></strong></span>
            <span>Late Marks: <strong><?= $monthStats['late'] ?></strong></span>
        </div>
    </div>
</div>

<!-- 3. Interactive Monthly Calendar Matrix -->
<div class="calendar-card">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px;">
        <div>
            <h2 style="margin: 0; font-size: 18px; font-weight: 800; font-family: 'Outfit', sans-serif; color: var(--text-main);">
                <?= esc($monthName) ?> Attendance Grid
            </h2>
            <small style="color: var(--text-muted);">Click on any class day to inspect period timings, topics taught, and faculty sign-offs.</small>
        </div>
    </div>

    <!-- Weekday Header (Mon - Sun) -->
    <div class="calendar-weekdays-row">
        <div class="calendar-weekday-col">Mon</div>
        <div class="calendar-weekday-col">Tue</div>
        <div class="calendar-weekday-col">Wed</div>
        <div class="calendar-weekday-col">Thu</div>
        <div class="calendar-weekday-col">Fri</div>
        <div class="calendar-weekday-col" style="color: #6366f1;">Sat</div>
        <div class="calendar-weekday-col" style="color: #ec4899;">Sun</div>
    </div>

    <!-- Calendar 7-Column Days Grid -->
    <div class="calendar-days-grid">
        <!-- Empty offset tiles before Day 1 -->
        <?php for ($i = 0; $i < $startOffset; $i++): ?>
            <div class="calendar-day-tile empty"></div>
        <?php endfor; ?>

        <!-- Month Day Tiles (1 to DaysInMonth) -->
        <?php foreach ($calendarMap as $dayNum => $dayData): ?>
            <?php 
                $tileClass = 'state-' . $dayData['status'];
                $hasSessions = ($dayData['session_count'] > 0);
            ?>
            <div class="calendar-day-tile <?= $tileClass ?>" 
                 onclick="openDayDrilldown(<?= htmlspecialchars(json_encode($dayData), ENT_QUOTES, 'UTF-8') ?>)"
                 title="<?= esc($dayData['date']) ?> - Click to view periods">
                
                <div class="day-num-row">
                    <span class="day-number"><?= $dayNum ?></span>
                    <?php if ($dayData['is_today']): ?>
                        <span class="today-pill">Today</span>
                    <?php endif; ?>
                </div>

                <!-- Status Pill & Summary -->
                <div>
                    <?php if ($dayData['is_holiday']): ?>
                        <div class="day-status-pill">
                            <i class="fa-solid fa-umbrella-beach"></i> <?= esc(character_limiter($dayData['holiday_title'], 14)) ?>
                        </div>
                    <?php elseif ($dayData['status'] === 'present'): ?>
                        <div class="day-status-pill">
                            <i class="fa-solid fa-circle-check"></i> Present (<?= $dayData['session_count'] ?>/<?= $dayData['session_count'] ?>)
                        </div>
                    <?php elseif ($dayData['status'] === 'partial'): ?>
                        <div class="day-status-pill">
                            <i class="fa-solid fa-circle-exclamation"></i> Partial (<?= $dayData['session_count'] ?> Classes)
                        </div>
                    <?php elseif ($dayData['status'] === 'absent'): ?>
                        <div class="day-status-pill">
                            <i class="fa-solid fa-circle-xmark"></i> Absent
                        </div>
                    <?php elseif ($dayData['is_weekend']): ?>
                        <div style="font-size: 11px; color: var(--text-muted); font-weight: 600;">
                            Weekend
                        </div>
                    <?php else: ?>
                        <div style="font-size: 10.5px; color: var(--text-muted);">
                            No Sessions
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Bottom Mini Period Dot Badges -->
                <?php if ($hasSessions): ?>
                    <div class="day-session-meta">
                        <i class="fa-solid fa-book-open"></i> <?= $dayData['session_count'] ?> Periods Logged
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Calendar Legend Bar -->
    <div class="calendar-legend-bar">
        <span><span class="legend-dot" style="background: #10b981;"></span> 100% Present</span>
        <span><span class="legend-dot" style="background: #f59e0b;"></span> Partial / Late</span>
        <span><span class="legend-dot" style="background: #ef4444;"></span> Full Day Absent</span>
        <span><span class="legend-dot" style="background: #0284c7;"></span> College Holiday</span>
        <span><span class="legend-dot" style="background: #94a3b8;"></span> Weekend / Off</span>
    </div>
</div>

<!-- 4. Subject-wise Progress & Breakdown Section -->
<div class="card" style="border-radius: 20px; padding: 24px; border: 1px solid var(--border); background: var(--surface); box-shadow: 0 4px 20px -2px rgba(0,0,0,0.04);">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px;">
        <div>
            <h2 style="margin: 0; font-size: 18px; font-weight: 800; font-family: 'Outfit', sans-serif; color: var(--text-main);">
                Subject-wise Attendance Distribution
            </h2>
            <small style="color: var(--text-muted);">Continuous assessment tracking against academic thresholds.</small>
        </div>
    </div>

    <?php if (empty($subject_stats)): ?>
        <div style="text-align: center; padding: 36px; color: var(--text-muted);">
            <i class="fa-solid fa-clipboard-user" style="font-size: 40px; opacity: 0.3; margin-bottom: 12px;"></i>
            <p style="font-size: 14px;">No subject-wise session records enrolled for this semester.</p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px;">
            <?php foreach ($subject_stats as $stat): ?>
                <?php 
                    $spct = $stat['percentage'];
                    $scolor = '#10b981';
                    if ($spct < 75) $scolor = '#ef4444';
                    elseif ($spct < 85) $scolor = '#f59e0b';
                ?>
                <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 16px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                            <div>
                                <span style="font-size: 11px; font-weight: 700; color: var(--primary); text-transform: uppercase;">
                                    <?= esc($stat['subject']['code'] ?? 'SUB') ?>
                                </span>
                                <h4 style="margin: 2px 0 0; font-size: 15px; font-weight: 800; color: var(--text-main);">
                                    <?= esc($stat['subject']['name']) ?>
                                </h4>
                            </div>
                            <span style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; color: <?= $scolor ?>;">
                                <?= $spct ?>%
                            </span>
                        </div>

                        <!-- Progress Bar with 75% target marker -->
                        <div style="position: relative; width: 100%; height: 8px; background: var(--border); border-radius: 4px; overflow: hidden; margin: 12px 0 8px;">
                            <div style="width: <?= min(100, $spct) ?>%; height: 100%; background: <?= $scolor ?>; border-radius: 4px; transition: width 0.6s ease;"></div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; font-size: 11.5px; color: var(--text-muted); border-top: 1px solid var(--border); padding-top: 10px; margin-top: 8px;">
                        <span>Total: <strong><?= $stat['total'] ?></strong></span>
                        <span style="color: #10b981;">Present: <strong><?= $stat['present'] ?></strong></span>
                        <span style="color: #f59e0b;">Late: <strong><?= $stat['late'] ?></strong></span>
                        <span style="color: #ef4444;">Absent: <strong><?= $stat['absent'] ?></strong></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- =========================================================================
     5. INTERACTIVE DAY DRILLDOWN MODAL
========================================================================= -->
<div class="att-modal-backdrop" id="dayDrilldownModal" onclick="closeDayDrilldown(event)">
    <div class="att-modal-card" onclick="event.stopPropagation()">
        <!-- Modal Head -->
        <div class="modal-head">
            <div>
                <span class="badge" id="modalDayPill" style="font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 20px;">
                    Class Log
                </span>
                <h3 id="modalDateTitle" style="margin: 4px 0 0; font-size: 18px; font-weight: 800; font-family: 'Outfit', sans-serif; color: var(--text-main);">
                    Day Details
                </h3>
            </div>
            <button type="button" onclick="closeDayDrilldownDirect()" style="border: none; background: transparent; color: var(--text-muted); font-size: 18px; cursor: pointer; padding: 4px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Modal Body (Period List) -->
        <div class="modal-body" id="modalPeriodList">
            <!-- Dynamic periods injected via JS -->
        </div>

        <!-- Modal Footer -->
        <div style="padding: 14px 24px; background: var(--bg-canvas); border-top: 1px solid var(--border); text-align: right;">
            <button type="button" onclick="closeDayDrilldownDirect()" class="btn btn-sm btn-primary" style="padding: 8px 18px; border-radius: 8px;">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    function openDayDrilldown(dayData) {
        const modal = document.getElementById('dayDrilldownModal');
        const titleEl = document.getElementById('modalDateTitle');
        const pillEl = document.getElementById('modalDayPill');
        const listEl = document.getElementById('modalPeriodList');

        titleEl.innerText = dayData.day_name + ', ' + dayData.date;

        // Configure pill
        if (dayData.is_holiday) {
            pillEl.style.background = 'rgba(14, 165, 233, 0.15)';
            pillEl.style.color = '#0284c7';
            pillEl.innerText = 'College Holiday: ' + (dayData.holiday_title || 'Official Holiday');
        } else if (dayData.status === 'present') {
            pillEl.style.background = 'rgba(16, 185, 129, 0.15)';
            pillEl.style.color = '#059669';
            pillEl.innerText = '100% Present';
        } else if (dayData.status === 'absent') {
            pillEl.style.background = 'rgba(239, 68, 68, 0.15)';
            pillEl.style.color = '#dc2626';
            pillEl.innerText = 'Full Day Absent';
        } else if (dayData.status === 'partial') {
            pillEl.style.background = 'rgba(245, 158, 11, 0.18)';
            pillEl.style.color = '#d97706';
            pillEl.innerText = 'Partial Attendance';
        } else if (dayData.is_weekend) {
            pillEl.style.background = 'rgba(100, 116, 139, 0.15)';
            pillEl.style.color = '#64748b';
            pillEl.innerText = 'Weekend';
        } else {
            pillEl.style.background = 'rgba(100, 116, 139, 0.15)';
            pillEl.style.color = '#64748b';
            pillEl.innerText = 'No Sessions Scheduled';
        }

        // Render Periods
        listEl.innerHTML = '';
        if (dayData.sessions && dayData.sessions.length > 0) {
            dayData.sessions.forEach((s, idx) => {
                let statusBg = 'rgba(16, 185, 129, 0.15)';
                let statusCol = '#059669';
                let icon = 'fa-circle-check';

                if (s.status === 'Absent') {
                    statusBg = 'rgba(239, 68, 68, 0.15)';
                    statusCol = '#dc2626';
                    icon = 'fa-circle-xmark';
                } else if (s.status === 'Late') {
                    statusBg = 'rgba(245, 158, 11, 0.18)';
                    statusCol = '#d97706';
                    icon = 'fa-circle-exclamation';
                }

                const periodHtml = `
                    <div class="period-item-card">
                        <div style="display: flex; align-items: flex-start; gap: 12px;">
                            <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(99, 102, 241, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; flex-shrink: 0;">
                                P${idx + 1}
                            </div>
                            <div>
                                <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); margin-bottom: 2px;">
                                    ${s.start_time ? s.start_time + ' - ' + s.end_time : (s.period_name || 'Period ' + (idx + 1))}
                                </div>
                                <div style="font-size: 14.5px; font-weight: 800; color: var(--text-main);">
                                    ${s.subject_code ? '<span style="color:var(--primary);">' + s.subject_code + ' - </span>' : ''}${s.subject_name}
                                </div>
                                ${s.topic_taught ? '<div style="font-size: 12px; color: var(--text-muted); margin-top: 3px;"><i class="fa-solid fa-chalkboard-user me-1"></i> Topic: ' + s.topic_taught + '</div>' : ''}
                                ${s.faculty_name ? '<div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;"><i class="fa-solid fa-user-tie me-1"></i> Faculty: ' + s.faculty_name + '</div>' : ''}
                            </div>
                        </div>
                        <span class="badge" style="background: ${statusBg}; color: ${statusCol}; font-size: 12px; font-weight: 800; padding: 6px 12px; border-radius: 8px;">
                            <i class="fa-solid ${icon} me-1"></i> ${s.status}
                        </span>
                    </div>
                `;
                listEl.innerHTML += periodHtml;
            });
        } else {
            listEl.innerHTML = `
                <div style="text-align: center; padding: 30px; color: var(--text-muted);">
                    <i class="fa-solid fa-calendar-xmark" style="font-size: 38px; opacity: 0.3; margin-bottom: 12px;"></i>
                    <p style="margin: 0; font-size: 13.5px;">No individual lecture periods were recorded on this date.</p>
                </div>
            `;
        }

        modal.style.display = 'flex';
    }

    function closeDayDrilldown(e) {
        if (e.target.id === 'dayDrilldownModal') {
            document.getElementById('dayDrilldownModal').style.display = 'none';
        }
    }

    function closeDayDrilldownDirect() {
        document.getElementById('dayDrilldownModal').style.display = 'none';
    }
</script>

<?= $this->endSection() ?>
