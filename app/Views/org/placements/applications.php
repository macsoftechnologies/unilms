<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Drive Applications & Selection<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <div>
            <h2><i class="fa-solid fa-users-viewfinder"></i> <?= esc($drive['title']) ?></h2>
            <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 13px;">
                <strong><?= esc($drive['company_name']) ?></strong> | Role: <?= esc($drive['job_role']) ?> | Package: <?= esc($drive['ctc_details']) ?> | Drive Date: <?= date('d/m/Y', strtotime($drive['drive_date'])) ?>
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="<?= base_url('org/placements/drives') ?>" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Back to Drives</a>
        </div>
    </div>

    <!-- Applicants Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Candidate Student</th>
                    <th>Roll Number</th>
                    <th>Cohort</th>
                    <th>Resume</th>
                    <th>Current Round</th>
                    <th>Status</th>
                    <th>Evaluation Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($applications)): foreach($applications as $app): ?>
                <tr>
                    <td>
                        <strong><?= esc($app['first_name'] . ' ' . $app['last_name']) ?></strong>
                        <div style="font-size: 11px; color: var(--text-muted);"><?= esc($app['email']) ?></div>
                    </td>
                    <td><?= esc($app['roll_number']) ?></td>
                    <td><?= esc($app['cohort_name'] ?? 'N/A') ?></td>
                    <td>
                        <?php if(!empty($app['resume_file'])): ?>
                            <a href="<?= base_url($app['resume_file']) ?>" target="_blank" class="badge" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; text-decoration: none;">
                                <i class="fa-solid fa-file-pdf me-1"></i> View CV
                            </a>
                        <?php else: ?>
                            <span style="color: var(--text-muted); font-size: 12px;">No file</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge" style="background: rgba(14, 165, 233, 0.1); color: #0ea5e9; font-weight: 600;">
                            <?= esc($app['current_round']) ?>
                        </span>
                    </td>
                    <td>
                        <?php
                            $stCol = '#3b82f6';
                            if ($app['status'] === 'Selected') $stCol = '#10b981';
                            elseif ($app['status'] === 'Rejected') $stCol = '#ef4444';
                            elseif ($app['status'] === 'Shortlisted') $stCol = '#f59e0b';
                        ?>
                        <span class="badge" style="background: <?= $stCol ?>15; color: <?= $stCol ?>; font-weight: 600;">
                            <?= esc($app['status']) ?>
                        </span>
                    </td>
                    <td style="font-size: 12px; color: var(--text-muted);"><?= esc($app['remarks'] ?: '-') ?></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-icon" title="Advance Round / Update Status" onclick="openEvalModal(<?= $app['id'] ?>, '<?= esc($app['first_name'] . ' ' . $app['last_name']) ?>', '<?= esc($app['current_round']) ?>', '<?= esc($app['status']) ?>', '<?= esc($app['remarks']) ?>')">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <?php if($app['status'] !== 'Selected'): ?>
                                <a href="<?= base_url('org/placements/applications/issue-offer/' . ($app['uuid'] ?? $app['id'])) ?>" class="btn-icon text-success" title="Select & Issue Job Offer" onclick="return confirm('Confirm selection and generate placement offer record?')">
                                    <i class="fa-solid fa-award"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="8" style="text-align: center; padding: 30px; color: var(--text-muted);">No student candidates have applied for this drive yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Evaluation Modal -->
<div class="drawer-overlay" id="evalModal" style="display: none;">
    <div class="drawer-content" style="max-width: 460px;">
        <form action="<?= base_url('org/placements/applications/update') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="application_id" id="eval_app_id">

            <div class="drawer-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 700;"><i class="fa-solid fa-pen-to-square me-2" style="color: #4f46e5;"></i> Evaluate Candidate</h3>
                <button type="button" class="btn-close" onclick="closeEvalModal()" style="background: none; border: none; font-size: 18px; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 20px;">
                <div style="background: #EEF2FF; padding: 10px 14px; border-radius: 6px; margin-bottom: 14px;">
                    <span style="font-size: 12px; color: var(--text-muted);">Candidate:</span>
                    <strong id="eval_cand_name" style="color: #4F46E5; display: block;"></strong>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Current Recruitment Round</label>
                    <input type="text" name="current_round" id="eval_current_round" class="form-control" required placeholder="e.g. Round 2: Technical Interview">
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Candidate Status</label>
                    <select name="status" id="eval_status" class="form-control" required>
                        <option value="Applied">Applied</option>
                        <option value="Shortlisted">Shortlisted</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Selected">Selected</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>

                <div class="form-group">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Interviewer Remarks</label>
                    <textarea name="remarks" id="eval_remarks" class="form-control" rows="3" placeholder="Technical score, interview comments..."></textarea>
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeEvalModal()">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Save Evaluation</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEvalModal(id, name, round, status, remarks) {
        document.getElementById('eval_app_id').value = id;
        document.getElementById('eval_cand_name').innerText = name;
        document.getElementById('eval_current_round').value = round;
        document.getElementById('eval_status').value = status;
        document.getElementById('eval_remarks').value = remarks;
        document.getElementById('evalModal').style.display = 'flex';
    }
    function closeEvalModal() {
        document.getElementById('evalModal').style.display = 'none';
    }
</script>
<?= $this->endSection() ?>
