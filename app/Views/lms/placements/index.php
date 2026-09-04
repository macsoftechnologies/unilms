<?php
    $stFirst = !empty($student['first_name']) ? $student['first_name'] : (session('first_name') ?: 'Aarav');
    $stLast = !empty($student['last_name']) ? $student['last_name'] : (session('last_name') ?: 'Patel');
    $stRoll = !empty($student['roll_number']) ? $student['roll_number'] : (session('roll_number') ?: '26CSE001');
    $stCohort = session('program_code') ?: (session('cohort_name') ?: 'B.Tech Computer Science');
?>
<?= $this->extend('lms/layout') ?>
<?= $this->section('page_title') ?>Campus Placements & Drives<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="lms-page-header">
    <h1><i class="fa-solid fa-briefcase me-2" style="color: var(--primary);"></i> Campus Placement Drives & Jobs</h1>
    <p>Browse active institutional placement drives, check criteria eligibility, and track your recruitment stages.</p>
</div>

<!-- Candidate Eligibility & Profile Bar -->
<div class="card" style="padding: 18px 22px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
    <div style="display: flex; gap: 28px; align-items: center; flex-wrap: wrap;">
        <div>
            <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Student Candidate</div>
            <div style="font-size: 15px; font-weight: 800; color: var(--text-main); margin-top: 2px;">
                <?= esc($stFirst . ' ' . $stLast) ?> 
                <span style="font-size: 13px; font-weight: 600; color: var(--primary);">(<?= esc($stRoll) ?>)</span>
            </div>
        </div>
        <div>
            <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Standing Backlogs</div>
            <div style="font-size: 15px; font-weight: 800; color: <?= ($backlogs_count ?? 0) == 0 ? 'var(--success)' : 'var(--danger)' ?>; margin-top: 2px;">
                <i class="fa-solid <?= ($backlogs_count ?? 0) == 0 ? 'fa-circle-check' : 'fa-circle-exclamation' ?> me-1"></i>
                <?= $backlogs_count ?? 0 ?> Active <?= ($backlogs_count ?? 0) === 1 ? 'Backlog' : 'Backlogs' ?>
            </div>
        </div>
        <div>
            <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Program & Batch</div>
            <div style="font-size: 14px; font-weight: 700; color: var(--text-main); margin-top: 2px;">
                <?= esc($stCohort) ?> • Class of 2026
            </div>
        </div>
    </div>
    <div>
        <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700;">
            <i class="fa-solid fa-shield-check me-1"></i> TPO Placement Eligible
        </span>
    </div>
</div>

<!-- Active Drives Grid -->
<div class="card" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; margin: 0;">
            <i class="fa-solid fa-building me-2" style="color: var(--primary);"></i> Upcoming Recruitment Drives
        </h2>
        <span class="badge" style="background: rgba(99, 102, 241, 0.08); color: var(--primary); font-size: 11.5px; padding: 4px 10px; border-radius: 12px; font-weight: 700;">
            <?= count($drives ?? []) ?> Active Openings
        </span>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 20px;">
        <?php if(!empty($drives)): foreach($drives as $d): ?>
        <div class="card" style="border: 1px solid var(--border); border-radius: 14px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; background: var(--surface); box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; gap: 12px;">
                    <div>
                        <h3 style="font-family: 'Outfit', sans-serif; margin: 0; font-size: 16px; font-weight: 800; color: var(--text-main);"><?= esc($d['title']) ?></h3>
                        <div style="font-size: 13px; color: var(--primary); font-weight: 700; margin-top: 3px;">
                            <i class="fa-solid fa-building-circle-check me-1"></i> <?= esc($d['company_name']) ?>
                        </div>
                    </div>
                    <?php if(!empty($d['ctc_details'])): ?>
                        <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); font-weight: 800; font-size: 12px; padding: 4px 10px; border-radius: 12px; white-space: nowrap;">
                            <?= esc($d['ctc_details']) ?>
                        </span>
                    <?php endif; ?>
                </div>

                <p style="font-size: 13px; color: var(--text-muted); margin: 10px 0 14px; line-height: 1.5;">
                    <?= esc($d['description'] ?? 'Global campus hiring drive for engineering candidates.') ?>
                </p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 12px; margin-bottom: 16px; background: var(--bg-canvas); padding: 12px; border-radius: 10px; border: 1px solid var(--border);">
                    <div><span style="color: var(--text-muted);">Drive Date:</span> <strong><?= date('d M Y', strtotime($d['drive_date'])) ?></strong></div>
                    <div><span style="color: var(--text-muted);">Location:</span> <strong><?= esc($d['location'] ?: 'Bengaluru / Hybrid') ?></strong></div>
                    <div><span style="color: var(--text-muted);">Deadline:</span> <strong><?= date('d M Y', strtotime($d['deadline_date'])) ?></strong></div>
                    <div><span style="color: var(--text-muted);">Min CGPA:</span> <strong><?= esc($d['required_cgpa'] ?? '7.00') ?>+</strong></div>
                </div>
            </div>

            <div>
                <?php if($d['has_applied']): ?>
                    <button class="btn btn-outline" disabled style="width: 100%; cursor: default; opacity: 0.9; border-color: var(--success); color: var(--success); font-weight: 700;">
                        <i class="fa-solid fa-circle-check me-1"></i> Application Submitted
                    </button>
                <?php elseif(!$d['is_eligible']): ?>
                    <button class="btn btn-outline" disabled style="width: 100%; cursor: not-allowed; opacity: 0.6; border-color: var(--danger); color: var(--danger); font-weight: 600;">
                        <i class="fa-solid fa-ban me-1"></i> Ineligible (Backlog Criteria)
                    </button>
                <?php else: ?>
                    <button class="btn btn-primary" style="width: 100%; font-weight: 700;" onclick="openApplyModal(<?= $d['id'] ?>, '<?= esc($d['title']) ?>', '<?= esc($d['company_name']) ?>')">
                        <i class="fa-solid fa-paper-plane me-1"></i> Submit Campus Application
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; else: ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 36px; color: var(--text-muted);">
            <i class="fa-solid fa-briefcase" style="font-size: 32px; opacity: 0.3; margin-bottom: 10px;"></i>
            <p>No upcoming placement drives scheduled at this moment.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- My Applications -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; margin: 0;">
            <i class="fa-solid fa-clock-rotate-left me-2" style="color: var(--success);"></i> My Recruitment Status
        </h2>
    </div>

    <div style="overflow-x: auto;">
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="padding: 12px 16px;">Company</th>
                    <th style="padding: 12px 16px;">Role / Drive Title</th>
                    <th style="padding: 12px 16px;">Current Round</th>
                    <th style="padding: 12px 16px;">Remarks</th>
                    <th style="padding: 12px 16px;">Applied On</th>
                    <th style="padding: 12px 16px; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($my_applications)): foreach($my_applications as $app): ?>
                <tr>
                    <td style="padding: 14px 16px;">
                        <strong style="color: var(--text-main);"><i class="fa-solid fa-building me-1 text-primary"></i> <?= esc($app['company_name']) ?></strong>
                    </td>
                    <td style="padding: 14px 16px; font-weight: 600;"><?= esc($app['drive_title']) ?></td>
                    <td style="padding: 14px 16px;">
                        <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); padding: 4px 10px; border-radius: 12px; font-weight: 700;">
                            <?= esc($app['current_round']) ?>
                        </span>
                    </td>
                    <td style="padding: 14px 16px; font-size: 12.5px; color: var(--text-muted); max-width: 260px;">
                        <?= esc($app['remarks'] ?? 'Application verified by TPO.') ?>
                    </td>
                    <td style="padding: 14px 16px; font-size: 12.5px;"><?= date('d M Y', strtotime($app['created_at'])) ?></td>
                    <td style="padding: 14px 16px; text-align: center;">
                        <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: 800;">
                            <i class="fa-solid fa-circle-check me-1"></i> <?= esc($app['status']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6" style="text-align: center; padding: 24px; color: var(--text-muted);">You have not applied for any placement drives yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Apply Modal -->
<div id="applyModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card" style="max-width: 480px; width: 90%; padding: 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.25);">
        <form action="<?= base_url('lms/placements/apply') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="drive_id" id="modal_drive_id">

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-family: 'Outfit', sans-serif; margin: 0; font-size: 18px; font-weight: 800; color: var(--text-main);">Apply for Campus Placement</h3>
                <button type="button" onclick="closeApplyModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>

            <div style="background: rgba(99, 102, 241, 0.08); padding: 12px 14px; border-radius: 10px; margin-bottom: 16px; border: 1px solid rgba(99, 102, 241, 0.15);">
                <div style="font-size: 11px; color: var(--primary); font-weight: 700; text-transform: uppercase;">Selected Recruitment Drive:</div>
                <strong id="modal_drive_title" style="font-size: 13.5px; color: var(--text-main);"></strong>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="font-size: 13px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-main);">Upload Resume / CV (PDF) *</label>
                <input type="file" name="resume" accept=".pdf,.doc,.docx" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; font-size: 12.5px; background: var(--surface);">
                <small style="color: var(--text-muted); font-size: 11px; display: block; margin-top: 4px;">Accepted formats: PDF or DOCX (Max 10MB)</small>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeApplyModal()">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane me-1"></i> Submit Application</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openApplyModal(id, title, company) {
        document.getElementById('modal_drive_id').value = id;
        document.getElementById('modal_drive_title').innerText = title + ' • ' + company;
        document.getElementById('applyModal').style.display = 'flex';
    }
    function closeApplyModal() {
        document.getElementById('applyModal').style.display = 'none';
    }
</script>
<?= $this->endSection() ?>
