<?= $this->extend('lms/layout') ?>
<?= $this->section('page_title') ?>Student Dashboard<?= $this->endSection() ?>
<?= $this->section('content') ?>

<style>
    .welcome-hero {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
        border-radius: 24px;
        padding: 32px 36px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        margin-bottom: 32px;
        box-shadow: 0 16px 36px -10px rgba(79, 70, 229, 0.35);
    }

    .welcome-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.12) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.5;
    }

    .hero-inner {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .hero-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: #38bdf8;
        margin-bottom: 12px;
    }

    .hero-greeting {
        font-family: 'Outfit', sans-serif;
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.4px;
        margin-bottom: 8px;
    }

    .hero-sub {
        font-size: 14.5px;
        color: #cbd5e1;
        max-width: 520px;
        line-height: 1.5;
    }

    .hero-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .hero-btn {
        padding: 10px 18px;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .hero-btn-primary {
        background: #ffffff;
        color: #312e81;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
    }
    .hero-btn-primary:hover {
        background: #f1f5f9;
        transform: translateY(-2px);
    }

    .hero-btn-secondary {
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.25);
    }
    .hero-btn-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    /* KPI Grid */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .kpi-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 22px 24px;
        display: flex;
        align-items: center;
        gap: 18px;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px -4px rgba(0, 0, 0, 0.08);
    }

    .kpi-icon-box {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .kpi-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .kpi-value {
        font-family: 'Outfit', sans-serif;
        font-size: 26px;
        font-weight: 800;
        line-height: 1.1;
        color: var(--text-main);
    }

    .kpi-sub {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    /* Course Quick Play Card */
    .quick-course-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 18px;
        overflow: hidden;
        display: flex;
        gap: 20px;
        padding: 16px;
        margin-bottom: 16px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .quick-course-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
    }

    .quick-course-thumb {
        width: 140px;
        height: 95px;
        border-radius: 12px;
        background: linear-gradient(135deg, #1e1b4b, #4338ca);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 28px;
        flex-shrink: 0;
    }

    /* Timetable Timeline */
    .timeline-item {
        position: relative;
        padding-left: 20px;
        margin-bottom: 18px;
        border-left: 2.5px solid var(--border);
    }

    .timeline-item.active {
        border-left-color: var(--primary);
    }

    .timeline-item.active::before {
        content: '';
        position: absolute;
        left: -6.5px;
        top: 2px;
        width: 11px;
        height: 11px;
        border-radius: 50%;
        background: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-glow);
    }
</style>

<!-- Welcome Hero Banner -->
<div class="welcome-hero">
    <div class="hero-inner">
        <div>
            <div class="hero-badge-pill">
                <i class="fa-solid fa-graduation-cap"></i>
                <span><?= esc(session('program_name') ?: (session('program_code') ?: 'B.Tech Computer Science & Engineering')) ?> • <?= esc(session('semester_name') ?: 'Semester 1') ?></span>
            </div>
            <h1 class="hero-greeting">Welcome back, <?= esc(session('first_name') ?: (session('user_name') ?: 'Student')) ?>! 👋</h1>
            <p class="hero-sub">
                You have <strong><?= count($timetable) ?> lecture<?= count($timetable) === 1 ? '' : 's' ?></strong> scheduled today and <strong><?= count($pending_assignments) ?> active assignment<?= count($pending_assignments) === 1 ? '' : 's' ?></strong> to complete.
            </p>
        </div>

        <div class="hero-actions">
            <a href="<?= base_url('lms/materials/watch/1') ?>" class="hero-btn hero-btn-primary">
                <i class="fa-solid fa-play"></i> Continue Learning
            </a>
            <a href="<?= base_url('lms/timetable') ?>" class="hero-btn hero-btn-secondary">
                <i class="fa-solid fa-calendar-days"></i> Full Timetable
            </a>
        </div>
    </div>
</div>

<!-- 4 High-Impact KPI Metrics Grid -->
<div class="kpi-grid">
    <!-- Card 1: Attendance -->
    <div class="kpi-card">
        <div class="kpi-icon-box" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
            <i class="fa-solid fa-clipboard-check"></i>
        </div>
        <div>
            <div class="kpi-title">Semester Attendance</div>
            <div class="kpi-value" style="color: #10b981;">
                <?= $attendance_percent ?>%
            </div>
            <div class="kpi-sub">
                <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: #059669; font-weight: 700; font-size: 11px; padding: 2px 8px; border-radius: 12px;">
                    <i class="fa-solid fa-circle-check me-1"></i> <?= $attendance_percent >= 75 ? 'Good Standing' : 'Low Attendance' ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Card 2: Pending Tasks -->
    <div class="kpi-card">
        <div class="kpi-icon-box" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
            <i class="fa-solid fa-file-signature"></i>
        </div>
        <div>
            <div class="kpi-title">Active Assignments</div>
            <div class="kpi-value">
                <?= count($pending_assignments) ?>
            </div>
            <div class="kpi-sub" style="color: #f59e0b; font-weight: 600;">
                <i class="fa-solid fa-clock me-1"></i> <?= count($pending_assignments) > 0 ? count($pending_assignments) . ' Pending' : 'All Clear' ?>
            </div>
        </div>
    </div>

    <!-- Card 3: Classes Today -->
    <div class="kpi-card">
        <div class="kpi-icon-box" style="background: rgba(99, 102, 241, 0.12); color: var(--primary);">
            <i class="fa-solid fa-chalkboard-user"></i>
        </div>
        <div>
            <div class="kpi-title">Classes Today</div>
            <div class="kpi-value"><?= count($timetable) ?></div>
            <div class="kpi-sub" style="color: var(--primary); font-weight: 600;">
                <i class="fa-solid fa-calendar-day me-1"></i> <?= date('l, M d') ?>
            </div>
        </div>
    </div>

    <!-- Card 4: Fee Ledger in Rupees -->
    <div class="kpi-card">
        <div class="kpi-icon-box" style="background: rgba(239, 68, 68, 0.12); color: #ef4444;">
            <i class="fa-solid fa-wallet"></i>
        </div>
        <div>
            <div class="kpi-title">Term Fee Balance</div>
            <div class="kpi-value" style="color: <?= $fee_dues > 0 ? '#ef4444' : '#10b981' ?>;">
                ₹<?= number_format($fee_dues, 2) ?>
            </div>
            <div class="kpi-sub">
                <a href="<?= base_url('lms/fees') ?>" style="color: <?= $fee_dues > 0 ? '#ef4444' : '#10b981' ?>; font-weight: 700; text-decoration: none; font-size: 11.5px;">
                    View Itemized Ledger <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- 2-Column Split Content -->
<div style="display: grid; grid-template-columns: 1.85fr 1.15fr; gap: 28px;">
    
    <!-- Left Column: Video Masterclasses & Assignments -->
    <div>
        <!-- Active Video Modules Section -->
        <div class="card" style="margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div>
                    <h2 style="font-family: 'Outfit', sans-serif; font-size: 19px; font-weight: 800; margin-bottom: 2px;">
                        <i class="fa-solid fa-play-circle me-2" style="color: var(--primary);"></i> Active Video Courses (Blended Learning)
                    </h2>
                    <p style="color: var(--text-muted); font-size: 13px; margin: 0;">Resume video masterclasses and download modular notes</p>
                </div>
                <a href="<?= base_url('lms/materials') ?>" class="btn btn-outline" style="font-size: 12.5px; padding: 6px 14px; border-radius: 20px;">
                    All Courses <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>

            <?php if (!empty($publishedCourses)): ?>
                <?php foreach ($publishedCourses as $c): ?>
                    <div class="quick-course-card">
                        <div class="quick-course-thumb">
                            <i class="fa-solid fa-play"></i>
                        </div>
                        <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                    <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 12px;">
                                        <?= esc($c['pedagogy_tag'] ?? 'Blended Learning') ?>
                                    </span>
                                    <span style="font-size: 12px; color: var(--text-muted);"><i class="fa-solid fa-layer-group me-1"></i> <?= $c['lesson_count'] ?? 1 ?> Lesson<?= ($c['lesson_count'] ?? 1) === 1 ? '' : 's' ?></span>
                                </div>
                                <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; margin-bottom: 4px;">
                                    <?= esc($c['title']) ?>
                                </h3>
                                <p style="font-size: 12.5px; color: var(--text-muted); margin: 0; line-height: 1.4;">
                                    <?= esc(substr(strip_tags($c['description']), 0, 110)) ?>...
                                </p>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 10px; border-top: 1px solid var(--border); margin-top: 8px;">
                                <div style="font-size: 12px; color: #10b981; font-weight: 700;">
                                    <i class="fa-solid fa-circle-check me-1"></i> Published Course
                                </div>
                                <a href="<?= base_url('lms/materials/watch/' . $c['id']) ?>" class="btn btn-primary" style="padding: 6px 16px; font-size: 12.5px; border-radius: 20px;">
                                    Resume Video <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 24px; color: var(--text-muted);">
                    <p>No video courses currently published for your institution.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Assignments & Evaluations Card -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                <h2 style="font-family: 'Outfit', sans-serif; font-size: 19px; font-weight: 800; margin: 0;">
                    <i class="fa-solid fa-file-pen me-2" style="color: #f59e0b;"></i> Assignments & OBE Tasks
                </h2>
                <a href="<?= base_url('lms/assignments') ?>" class="btn btn-outline" style="font-size: 12.5px; padding: 6px 14px; border-radius: 20px;">
                    View All
                </a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 14px;">
                <?php if (!empty($assignments)): ?>
                    <?php foreach (array_slice($assignments, 0, 3) as $a): ?>
                        <?php 
                            $sub = isset($submission_map[$a['id']]) ? $submission_map[$a['id']] : null;
                            $is_overdue = !$sub && strtotime($a['due_date']) < time();
                        ?>
                        <div style="padding: 16px; border: 1px solid var(--border); border-radius: 14px; display: flex; justify-content: space-between; align-items: center; background: var(--surface);">
                            <div>
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                    <?php if ($sub && $sub['status'] === 'graded'): ?>
                                        <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: #059669; font-weight: 700; font-size: 11px; padding: 2px 8px; border-radius: 10px;">
                                            <i class="fa-solid fa-check-circle me-1"></i> Graded: <?= esc($sub['marks_obtained']) ?> / <?= esc($a['max_marks']) ?>
                                        </span>
                                    <?php elseif ($sub): ?>
                                        <span class="badge" style="background: rgba(245, 158, 11, 0.12); color: #d97706; font-weight: 700; font-size: 11px; padding: 2px 8px; border-radius: 10px;">
                                            <i class="fa-solid fa-clock me-1"></i> Submitted
                                        </span>
                                    <?php elseif ($is_overdue): ?>
                                        <span class="badge" style="background: rgba(239, 68, 68, 0.12); color: #ef4444; font-weight: 700; font-size: 11px; padding: 2px 8px; border-radius: 10px;">
                                            <i class="fa-solid fa-circle-exclamation me-1"></i> Overdue
                                        </span>
                                    <?php else: ?>
                                        <span class="badge" style="background: rgba(99, 102, 241, 0.12); color: var(--primary); font-weight: 700; font-size: 11px; padding: 2px 8px; border-radius: 10px;">
                                            <i class="fa-solid fa-clock me-1"></i> Open for Submission
                                        </span>
                                    <?php endif; ?>
                                    <span style="font-size: 12px; color: var(--text-muted);"><?= esc($a['subject_code'] ?? 'CS') ?> &bull; <?= esc($a['subject_name'] ?? 'Subject') ?></span>
                                </div>
                                <div style="font-weight: 700; font-size: 14px; margin-bottom: 2px;">
                                    <?= esc($a['title']) ?>
                                </div>
                                <div style="font-size: 12px; color: <?= $is_overdue ? 'var(--danger)' : 'var(--text-muted)' ?>;">
                                    Due: <?= date('d M Y, h:i A', strtotime($a['due_date'])) ?> &bull; Max Marks: <?= esc($a['max_marks']) ?>
                                </div>
                            </div>
                            <a href="<?= base_url('lms/assignments/view/' . $a['id']) ?>" class="btn btn-outline" style="font-size: 12px; padding: 6px 14px; border-radius: 10px;">
                                <?= $sub ? 'View Review' : 'Submit Task' ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 24px; color: var(--text-muted);">
                        <p>No active assignments pending for your cohort.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Today's Timetable & Campus Notices -->
    <div>
        <!-- Today's Schedule -->
        <div class="card" style="margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; margin: 0;">
                    <i class="fa-solid fa-calendar-day me-2" style="color: var(--primary);"></i> Today's Schedule
                </h2>
                <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 10px;">
                    <?= date('l') ?>
                </span>
            </div>

            <div style="display: flex; flex-direction: column;">
                <?php if (!empty($timetable)): ?>
                    <?php foreach ($timetable as $idx => $slot): ?>
                        <div class="timeline-item <?= $idx === 0 ? 'active' : '' ?>">
                            <div style="font-size: 11.5px; color: <?= $idx === 0 ? 'var(--primary)' : 'var(--text-muted)' ?>; font-weight: 700; margin-bottom: 2px;">
                                <?= date('h:i A', strtotime($slot['start_time'])) ?> - <?= date('h:i A', strtotime($slot['end_time'])) ?> • <?= esc($slot['period_name']) ?>
                            </div>
                            <div style="font-weight: 700; font-size: 14px; margin-bottom: 2px;">
                                <?= esc($slot['subject_code'] ? $slot['subject_code'] . ': ' : '') ?><?= esc($slot['subject_name']) ?>
                            </div>
                            <div style="font-size: 12px; color: var(--text-muted);">
                                <?php if (!empty($slot['faculty_name'])): ?>
                                    <i class="fa-solid fa-user-tie me-1"></i> <?= esc($slot['faculty_name']) ?> &bull;
                                <?php endif; ?>
                                <i class="fa-solid fa-door-open me-1"></i> <?= esc($slot['room_number'] ?? $slot['room'] ?? 'LH-301') ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 24px 10px; color: var(--text-muted);">
                        <i class="fa-solid fa-calendar-check" style="font-size: 32px; opacity: 0.3; margin-bottom: 8px;"></i>
                        <p style="margin: 0; font-size: 13px;">No lectures scheduled for today.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div style="margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--border); text-align: center;">
                <a href="<?= base_url('lms/timetable') ?>" class="btn btn-outline" style="width: 100%; font-size: 13px; border-radius: 12px;">
                    View Complete Week Timetable <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <!-- Academic Accreditation Notice Card -->
        <div class="card" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(56, 189, 248, 0.08)); border-color: rgba(99, 102, 241, 0.2);">
            <div style="display: flex; gap: 14px; align-items: flex-start;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
                <div>
                    <h4 style="font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                        NBA OBE Attainment Matrix Live
                    </h4>
                    <p style="font-size: 12.5px; color: var(--text-muted); line-height: 1.4; margin-bottom: 10px;">
                        Your continuous assessment marks are automatically aggregated for PO1–PO12 NBA Graduate Attributes.
                    </p>
                    <a href="<?= base_url('lms/obe') ?>" style="font-size: 12px; font-weight: 700; color: var(--primary); text-decoration: none;">
                        View My Attainment Scorecard <i class="fa-solid fa-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
