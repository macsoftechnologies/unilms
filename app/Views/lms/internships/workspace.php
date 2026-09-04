<?= $this->extend('lms/layout') ?>

<?= $this->section('page_title') ?>
Internship Workspace - <?= esc($enrollment['role_title']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
/* =========================================================================
   WORKSPACE HERO & ROADMAP STYLES
========================================================================= */
.ws-hero-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 24px 28px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
}

.ws-hero-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #6366f1, #8b5cf6, #ec4899);
}

.ws-stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.2s ease;
    box-shadow: var(--shadow-sm);
}

.ws-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    border-color: rgba(99, 102, 241, 0.3);
}

.ws-stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

/* Timeline & Milestones */
.milestone-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 18px;
    margin-bottom: 24px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.2s ease;
}

.milestone-card:hover {
    border-color: rgba(99, 102, 241, 0.25);
}

.milestone-header {
    padding: 18px 24px;
    background: var(--bg-canvas);
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.task-item {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    transition: background 0.15s ease;
}

.task-item:last-child {
    border-bottom: none;
}

.task-item:hover {
    background: rgba(99, 102, 241, 0.015);
}

/* Stopwatch Dark Card */
.stopwatch-card {
    background: linear-gradient(145deg, #0f172a 0%, #1e1b4b 100%);
    border: 1px solid rgba(99, 102, 241, 0.3);
    border-radius: 18px;
    padding: 22px;
    color: #ffffff;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.3);
    position: relative;
    overflow: hidden;
}

.stopwatch-card::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
    pointer-events: none;
}

.stopwatch-digit {
    font-family: 'SF Mono', 'Fira Code', 'Roboto Mono', monospace;
    font-size: 32px;
    font-weight: 800;
    letter-spacing: 2px;
    color: #38bdf8;
    text-shadow: 0 0 12px rgba(56, 189, 248, 0.4);
}

.stopwatch-pulse {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #10b981;
    margin-right: 6px;
    animation: timerPulse 1.5s infinite;
}

@keyframes timerPulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { transform: scale(1.1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

/* Status Badges */
.status-pill-approved {
    background: rgba(16, 185, 129, 0.12);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.25);
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.status-pill-pending {
    background: rgba(100, 116, 139, 0.1);
    color: var(--text-muted);
    border: 1px solid var(--border);
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.status-pill-review {
    background: rgba(245, 158, 11, 0.12);
    color: #d97706;
    border: 1px solid rgba(245, 158, 11, 0.25);
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.status-pill-rejected {
    background: rgba(239, 68, 68, 0.12);
    color: #dc2626;
    border: 1px solid rgba(239, 68, 68, 0.25);
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
</style>

<div class="container-fluid p-0" style="max-width: 1360px; margin: 0 auto;">
    
    <!-- =========================================================================
         1. HERO HEADER: ROLE & COMPANY BANNER
    ========================================================================= -->
    <div class="ws-hero-card">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div style="display: flex; align-items: center; gap: 18px;">
                <div style="width: 58px; height: 58px; border-radius: 16px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 800; box-shadow: 0 8px 16px rgba(99, 102, 241, 0.25); flex-shrink: 0;">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; flex-wrap: wrap;">
                        <a href="<?= site_url('lms/internships') ?>" class="btn btn-sm btn-outline" style="padding: 4px 12px; font-size: 11.5px; border-radius: 8px;">
                            <i class="fa-solid fa-arrow-left me-1"></i> All Internships
                        </a>
                        <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                            <?= esc($enrollment['company_name']) ?>
                        </span>
                        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10b981; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                            <i class="fa-solid fa-circle-check me-1"></i> Active Enrollment
                        </span>
                    </div>
                    <h2 style="font-size: 22px; font-weight: 800; margin: 0 0 6px; color: var(--text-main); font-family: 'Outfit', sans-serif;">
                        <?= esc($enrollment['role_title']) ?>
                    </h2>
                    <div style="display: flex; align-items: center; gap: 14px; font-size: 12.5px; color: var(--text-muted); flex-wrap: wrap;">
                        <span><i class="fa-regular fa-calendar me-1 text-primary"></i> <?= date('M d, Y', strtotime($enrollment['start_date'])) ?> &ndash; <?= date('M d, Y', strtotime($enrollment['end_date'])) ?></span>
                        <span>&bull;</span>
                        <span><i class="fa-solid fa-graduation-cap me-1 text-primary"></i> Accredited University Practicum</span>
                    </div>
                </div>
            </div>

            <!-- Progress Dial / Widget -->
            <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 14px; padding: 14px 20px; min-width: 220px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Milestone Progress</span>
                    <span style="font-size: 15px; font-weight: 800; color: var(--primary); font-family: 'Outfit', sans-serif;"><?= $progressPct ?>%</span>
                </div>
                <div class="progress" style="height: 8px; border-radius: 10px; background: var(--border); overflow: hidden;">
                    <div class="progress-bar" role="progressbar" style="width: <?= $progressPct ?>%; background: linear-gradient(90deg, #6366f1, #10b981); border-radius: 10px;" aria-valuenow="<?= $progressPct ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <?php if ($enrollment['status'] === 'completed'): ?>
            <div class="alert alert-success d-flex justify-content-between align-items-center rounded-4 mt-3 mb-0" style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.2); color: #065f46;">
                <div>
                    <h5 class="fw-bold mb-1" style="font-size: 14px;"><i class="fa-solid fa-stamp me-2 text-success"></i> Official University Certificate Issued & Verified!</h5>
                    <small>Certificate ID: <strong><?= esc($enrollment['certificate_number']) ?></strong> &bull; Grade: <strong><?= esc($enrollment['total_weighted_grade']) ?></strong></small>
                </div>
                <a href="<?= site_url('verify-certificate/' . $enrollment['certificate_hash']) ?>" class="btn btn-sm btn-success rounded-pill px-4" target="_blank" style="font-weight: 700;">
                    <i class="fa-solid fa-qrcode me-1"></i> View Verified Certificate
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- =========================================================================
         2. KPI METRICS RIBBON (4 STATS)
    ========================================================================= -->
    <?php
    $totalTasksCount = 0;
    $approvedTasksCount = 0;
    $underReviewCount = 0;
    foreach ($milestones as $m) {
        foreach ($m['tasks'] as $t) {
            $totalTasksCount++;
            if (!empty($t['submission'])) {
                if ($t['submission']['faculty_status'] === 'approved') $approvedTasksCount++;
                elseif ($t['submission']['faculty_status'] === 'pending') $underReviewCount++;
            }
        }
    }
    ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="ws-stat-card">
            <div class="ws-stat-icon" style="background: rgba(99, 102, 241, 0.1); color: var(--primary);">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
            <div>
                <div style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Roadmap Milestones</div>
                <div style="font-size: 18px; font-weight: 800; color: var(--text-main); font-family: 'Outfit', sans-serif;">
                    <?= count($milestones) ?> Phases
                </div>
            </div>
        </div>

        <div class="ws-stat-card">
            <div class="ws-stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <div>
                <div style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Tasks Approved</div>
                <div style="font-size: 18px; font-weight: 800; color: var(--text-main); font-family: 'Outfit', sans-serif;">
                    <?= $approvedTasksCount ?> <span style="font-size: 13px; font-weight: 500; color: var(--text-muted);">/ <?= $totalTasksCount ?> completed</span>
                </div>
            </div>
        </div>

        <div class="ws-stat-card">
            <div class="ws-stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #d97706;">
                <i class="fa-solid fa-spinner"></i>
            </div>
            <div>
                <div style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Under Evaluation</div>
                <div style="font-size: 18px; font-weight: 800; color: var(--text-main); font-family: 'Outfit', sans-serif;">
                    <?= $underReviewCount ?> Deliverables
                </div>
            </div>
        </div>

        <div class="ws-stat-card">
            <div class="ws-stat-icon" style="background: rgba(236, 72, 153, 0.1); color: #ec4899;">
                <i class="fa-solid fa-award"></i>
            </div>
            <div>
                <div style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Evaluation Status</div>
                <div style="font-size: 15px; font-weight: 800; color: var(--text-main); font-family: 'Outfit', sans-serif;">
                    <?= ($enrollment['status'] === 'completed') ? '🏆 Graduated' : '🚀 In Progress' ?>
                </div>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         3. MAIN 2-COLUMN WORKSPACE
    ========================================================================= -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
        
        <!-- =========================================================================
             LEFT COLUMN (70%): SEQUENTIAL MILESTONE ROADMAP
        ========================================================================= -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h4 style="font-size: 16px; font-weight: 800; margin: 0; color: var(--text-main); font-family: 'Outfit', sans-serif;">
                    <i class="fa-solid fa-timeline text-primary me-2"></i> Structured Practicum Deliverables
                </h4>
                <span class="badge" style="background: var(--surface); border: 1px solid var(--border); color: var(--text-muted); font-size: 11.5px;">
                    Sequential Review Gate
                </span>
            </div>

            <?php foreach ($milestones as $mIdx => $m): ?>
                <div class="milestone-card">
                    <!-- Milestone Header -->
                    <div class="milestone-header">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 32px; height: 32px; border-radius: 10px; background: rgba(99, 102, 241, 0.12); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">
                                M<?= $m['milestone_number'] ?>
                            </div>
                            <div>
                                <h5 style="margin: 0; font-size: 15px; font-weight: 800; color: var(--text-main); font-family: 'Outfit', sans-serif;">
                                    <?= esc($m['title']) ?>
                                </h5>
                                <?php if (!empty($m['description'])): ?>
                                    <small style="color: var(--text-muted); font-size: 12px;"><?= esc($m['description']) ?></small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (!empty($m['is_midpoint_gate'])): ?>
                            <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.2); font-size: 11px; padding: 5px 12px; border-radius: 20px; font-weight: 700;">
                                <i class="fa-solid fa-flag-checkered me-1"></i> Midpoint Review Gate
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Tasks List inside Milestone -->
                    <div>
                        <?php foreach ($m['tasks'] as $tIdx => $t): ?>
                            <?php $sub = $t['submission'] ?? null; ?>
                            <div class="task-item">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 10px;">
                                    <div>
                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                            <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); background: var(--bg-canvas); border: 1px solid var(--border); padding: 2px 8px; border-radius: 6px;">
                                                Task <?= $mIdx + 1 ?>.<?= $tIdx + 1 ?>
                                            </span>
                                            <h6 style="margin: 0; font-size: 14px; font-weight: 700; color: var(--text-main);">
                                                <?= esc($t['task_title']) ?>
                                            </h6>
                                        </div>
                                        <p style="margin: 0 0 8px; font-size: 12.5px; color: var(--text-secondary); line-height: 1.5;">
                                            <?= esc($t['description']) ?>
                                        </p>
                                        <div style="display: flex; align-items: center; gap: 14px; font-size: 11.5px; color: var(--text-muted); flex-wrap: wrap;">
                                            <span><i class="fa-solid fa-bullseye me-1 text-primary"></i> <strong>Target Output:</strong> <?= esc($t['expected_output']) ?></span>
                                            <span>&bull;</span>
                                            <span><i class="fa-regular fa-clock me-1 text-primary"></i> <strong>Estimated:</strong> <?= esc($t['estimated_hours']) ?> hrs</span>
                                            <span>&bull;</span>
                                            <span><i class="fa-solid <?= $t['submission_type'] === 'file' ? 'fa-file-arrow-up' : 'fa-link' ?> me-1 text-primary"></i> <?= ucfirst($t['submission_type']) ?> Submission</span>
                                        </div>
                                    </div>

                                    <!-- Status Pill -->
                                    <div style="flex-shrink: 0;">
                                        <?php if (!$sub): ?>
                                            <span class="status-pill-pending"><i class="fa-regular fa-circle"></i> Pending Submission</span>
                                        <?php elseif ($sub['faculty_status'] === 'approved'): ?>
                                            <span class="status-pill-approved"><i class="fa-solid fa-circle-check"></i> Faculty Approved</span>
                                        <?php elseif ($sub['faculty_status'] === 'rejected'): ?>
                                            <span class="status-pill-rejected"><i class="fa-solid fa-circle-exclamation"></i> Changes Requested</span>
                                        <?php else: ?>
                                            <span class="status-pill-review"><i class="fa-solid fa-clock-rotate-left"></i> Under Review</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Submission Area / Completed View -->
                                <?php if (!$sub || $sub['faculty_status'] === 'rejected'): ?>
                                    <div style="background: var(--bg-canvas); border: 1px dashed var(--border); border-radius: 12px; padding: 16px; margin-top: 12px;">
                                        <form action="<?= site_url('lms/internships/submitTask') ?>" method="POST" enctype="multipart/form-data">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="enrollment_id" value="<?= $enrollment['id'] ?>">
                                            <input type="hidden" name="task_id" value="<?= $t['id'] ?>">
                                            <input type="hidden" name="tracked_time_seconds" class="trackedSecondsInput" value="0">

                                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                                                <?php if ($t['submission_type'] === 'file'): ?>
                                                    <div>
                                                        <label style="font-size: 11.5px; font-weight: 700; color: var(--text-main); margin-bottom: 4px; display: block;">
                                                            <i class="fa-solid fa-cloud-arrow-up me-1 text-primary"></i> Upload Deliverable File (PDF / ZIP)
                                                        </label>
                                                        <input type="file" name="submission_file" class="form-control" style="font-size: 12px; padding: 6px 10px;" required>
                                                    </div>
                                                <?php elseif ($t['submission_type'] === 'link'): ?>
                                                    <div>
                                                        <label style="font-size: 11.5px; font-weight: 700; color: var(--text-main); margin-bottom: 4px; display: block;">
                                                            <i class="fa-solid fa-code-branch me-1 text-primary"></i> Repository / Live Project URL
                                                        </label>
                                                        <input type="url" name="submission_link" class="form-control" placeholder="https://github.com/..." style="font-size: 12px; padding: 6px 10px;" required>
                                                    </div>
                                                <?php endif; ?>

                                                <div>
                                                    <label style="font-size: 11.5px; font-weight: 700; color: var(--text-main); margin-bottom: 4px; display: block;">
                                                        <i class="fa-regular fa-comment-dots me-1 text-primary"></i> Work Summary / Notes
                                                    </label>
                                                    <input type="text" name="submission_text" class="form-control" placeholder="Brief summary of implementation..." style="font-size: 12px; padding: 6px 10px;" required>
                                                </div>
                                            </div>

                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                <small style="color: var(--text-muted); font-size: 11px;">
                                                    <i class="fa-solid fa-stopwatch me-1 text-primary"></i> Logged session timer will attach automatically.
                                                </small>
                                                <button type="submit" class="btn btn-sm btn-primary" style="font-size: 12px; padding: 6px 16px; border-radius: 8px; font-weight: 700;">
                                                    <i class="fa-solid fa-paper-plane me-1"></i> Submit Deliverable
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 12px; padding: 12px 16px; margin-top: 10px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                        <div>
                                            <div style="font-size: 12px; color: #10b981; font-weight: 700;">
                                                <i class="fa-solid fa-check-circle me-1"></i> Submitted on <?= date('M d, Y h:i A', strtotime($sub['student_submitted_at'])) ?>
                                            </div>
                                            <?php if (!empty($sub['submission_text'])): ?>
                                                <div style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;">
                                                    <strong>Student Note:</strong> "<?= esc($sub['submission_text']) ?>"
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($sub['faculty_feedback'])): ?>
                                                <div style="font-size: 12px; color: var(--primary); margin-top: 4px; background: rgba(99, 102, 241, 0.08); padding: 4px 10px; border-radius: 6px; display: inline-block;">
                                                    <i class="fa-solid fa-comment me-1"></i> <strong>Faculty Feedback:</strong> <?= esc($sub['faculty_feedback']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <?php if (!empty($sub['submission_link'])): ?>
                                                <a href="<?= esc($sub['submission_link']) ?>" target="_blank" class="btn btn-sm btn-outline" style="font-size: 11px; padding: 4px 10px;">
                                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View URL
                                                </a>
                                            <?php endif; ?>
                                            <?php if (!empty($sub['submission_file'])): ?>
                                                <a href="<?= base_url($sub['submission_file']) ?>" target="_blank" class="btn btn-sm btn-outline" style="font-size: 11px; padding: 4px 10px;">
                                                    <i class="fa-solid fa-download me-1"></i> Download
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- =========================================================================
             RIGHT COLUMN (30%): WORKSPACE TOOLS SIDEBAR
        ========================================================================= -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            
            <!-- 1. LIVE WORK SESSION STOPWATCH -->
            <div class="stopwatch-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span class="stopwatch-pulse" id="timerPulseDot" style="display: none;"></span>
                        <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9;">
                            Active Work Session
                        </span>
                    </div>
                    <span class="badge" style="background: rgba(255, 255, 255, 0.15); color: #fff; font-size: 10.5px;">Live Logger</span>
                </div>

                <div style="text-align: center; padding: 12px 0 16px;">
                    <div class="stopwatch-digit" id="stopwatchDisplay">00:00:00</div>
                    <div style="font-size: 11.5px; opacity: 0.7; margin-top: 4px;">Time will be logged with deliverable submissions</div>
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="button" class="btn btn-sm w-100" id="btnTimerToggle" style="background: #38bdf8; color: #0f172a; font-weight: 800; border-radius: 10px; padding: 10px; font-size: 12.5px; border: none; transition: all 0.2s;">
                        <i class="fa-solid fa-play me-1" id="timerIcon"></i> <span id="timerBtnText">Start Session</span>
                    </button>
                    <button type="button" class="btn btn-sm" id="btnTimerReset" style="background: rgba(255, 255, 255, 0.15); color: #fff; border-radius: 10px; padding: 10px 14px; border: none;" title="Reset Timer">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
            </div>

            <!-- 2. MID-INTERNSHIP REFLECTION CHECKPOINT -->
            <div class="card" style="border-radius: 18px; padding: 20px; border: 1px solid var(--border); background: var(--surface); box-shadow: var(--shadow-sm);">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                    <div style="width: 32px; height: 32px; border-radius: 10px; background: rgba(245, 158, 11, 0.12); color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                        <i class="fa-solid fa-signature"></i>
                    </div>
                    <div>
                        <h5 style="margin: 0; font-size: 14px; font-weight: 800; color: var(--text-main);">Midpoint Reflection</h5>
                        <small style="color: var(--text-muted); font-size: 11px;">Self-Assessment Checkpoint</small>
                    </div>
                </div>

                <?php if (!empty($enrollment['student_mid_review'])): ?>
                    <div style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 12px; padding: 12px; font-size: 12px; color: #065f46;">
                        <div style="font-weight: 700; margin-bottom: 4px;"><i class="fa-solid fa-circle-check me-1 text-success"></i> Review Submitted:</div>
                        <p style="margin: 0; font-style: italic; line-height: 1.4;">"<?= esc($enrollment['student_mid_review']) ?>"</p>
                    </div>
                <?php else: ?>
                    <p style="font-size: 12px; color: var(--text-secondary); margin-bottom: 12px; line-height: 1.4;">
                        Reflect on your key accomplishments, challenges, and support required for the second half of the practicum.
                    </p>
                    <form action="<?= site_url('lms/internships/submitMidReview') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="enrollment_id" value="<?= $enrollment['id'] ?>">
                        <textarea name="student_mid_review" class="form-control" rows="3" placeholder="Key learnings and progress reflection..." style="font-size: 12px; margin-bottom: 10px;" required></textarea>
                        <button type="submit" class="btn btn-sm btn-outline-warning w-100" style="font-weight: 700; font-size: 12px; border-radius: 8px;">
                            <i class="fa-solid fa-paper-plane me-1"></i> Submit Mid-Review
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- 3. MENTORSHIP & GUIDANCE CARD -->
            <div class="card" style="border-radius: 18px; padding: 20px; border: 1px solid var(--border); background: var(--surface); box-shadow: var(--shadow-sm);">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                    <div style="width: 32px; height: 32px; border-radius: 10px; background: rgba(99, 102, 241, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 14px;">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div>
                        <h5 style="margin: 0; font-size: 14px; font-weight: 800; color: var(--text-main);">Faculty & Industry Mentorship</h5>
                        <small style="color: var(--text-muted); font-size: 11px;">Evaluation Framework</small>
                    </div>
                </div>

                <div style="font-size: 12px; color: var(--text-secondary); line-height: 1.5; display: flex; flex-direction: column; gap: 10px;">
                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                        <i class="fa-solid fa-check text-primary mt-1" style="font-size: 11px;"></i>
                        <span>Deliverables undergo evaluation against the <strong>6-Dimension University Rubric</strong>.</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                        <i class="fa-solid fa-check text-primary mt-1" style="font-size: 11px;"></i>
                        <span>Approved tasks count directly towards your accredited <strong>Curricular Practicum Credits</strong>.</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                        <i class="fa-solid fa-check text-primary mt-1" style="font-size: 11px;"></i>
                        <span>Final Grade Card & Official Certificate are unlocked upon 100% roadmap completion.</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- =========================================================================
     STOPWATCH INTERACTIVE JAVASCRIPT
========================================================================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    let timerRunning = false;
    let seconds = 0;
    let interval = null;

    const display = document.getElementById('stopwatchDisplay');
    const toggleBtn = document.getElementById('btnTimerToggle');
    const resetBtn = document.getElementById('btnTimerReset');
    const btnText = document.getElementById('timerBtnText');
    const icon = document.getElementById('timerIcon');
    const pulseDot = document.getElementById('timerPulseDot');

    toggleBtn.addEventListener('click', function () {
        if (!timerRunning) {
            timerRunning = true;
            btnText.textContent = 'Pause Session';
            icon.className = 'fa-solid fa-pause me-1 text-danger';
            toggleBtn.style.background = '#fef08a';
            toggleBtn.style.color = '#854d0e';
            if (pulseDot) pulseDot.style.display = 'inline-block';

            interval = setInterval(function () {
                seconds++;
                const hrs = Math.floor(seconds / 3600);
                const mins = Math.floor((seconds % 3600) / 60);
                const secs = seconds % 60;
                display.textContent = 
                    String(hrs).padStart(2, '0') + ':' +
                    String(mins).padStart(2, '0') + ':' +
                    String(secs).padStart(2, '0');

                document.querySelectorAll('.trackedSecondsInput').forEach(inp => {
                    inp.value = seconds;
                });
            }, 1000);
        } else {
            timerRunning = false;
            btnText.textContent = 'Resume Session';
            icon.className = 'fa-solid fa-play me-1';
            toggleBtn.style.background = '#38bdf8';
            toggleBtn.style.color = '#0f172a';
            if (pulseDot) pulseDot.style.display = 'none';
            clearInterval(interval);
        }
    });

    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            if (confirm('Reset current work session timer to 00:00:00?')) {
                clearInterval(interval);
                timerRunning = false;
                seconds = 0;
                display.textContent = '00:00:00';
                btnText.textContent = 'Start Session';
                icon.className = 'fa-solid fa-play me-1';
                toggleBtn.style.background = '#38bdf8';
                toggleBtn.style.color = '#0f172a';
                if (pulseDot) pulseDot.style.display = 'none';
                document.querySelectorAll('.trackedSecondsInput').forEach(inp => {
                    inp.value = 0;
                });
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
