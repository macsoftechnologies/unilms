<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Campus Recruitment Drives<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <div>
            <h2><i class="fa-solid fa-bullhorn"></i> Campus Recruitment Drives</h2>
            <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 13px;">Manage on-campus and virtual recruitment drives, candidate shortlisting, and selection rounds.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-primary" onclick="openDriveModal()"><i class="fa-solid fa-plus"></i> Schedule Recruitment Drive</button>
            <a href="<?= base_url('org/placements') ?>" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Placements Home</a>
        </div>
    </div>

    <!-- Drives Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Drive Title & Role</th>
                    <th>Hiring Company</th>
                    <th>CTC / Package</th>
                    <th>Drive Date</th>
                    <th>Eligibility Criteria</th>
                    <th>Applicants</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($drives)): foreach($drives as $d): ?>
                <tr>
                    <td>
                        <strong><?= esc($d['title']) ?></strong>
                        <div style="font-size: 12px; color: var(--text-muted);"><?= esc($d['job_role']) ?></div>
                    </td>
                    <td><strong><?= esc($d['company_name'] ?? 'N/A') ?></strong></td>
                    <td>
                        <span style="font-weight: 700; color: #10b981;">
                            <?= esc($d['ctc_details'] ?: 'As per norms') ?>
                        </span>
                    </td>
                    <td>
                        <div><?= date('d/m/Y', strtotime($d['drive_date'])) ?></div>
                        <div style="font-size: 11px; color: var(--text-muted);">Deadline: <?= date('d/m/Y', strtotime($d['deadline_date'])) ?></div>
                    </td>
                    <td>
                        <div style="font-size: 12px;">Min CGPA: <strong><?= $d['required_cgpa'] ?></strong></div>
                        <div style="font-size: 12px; color: var(--text-muted);">Max Backlogs: <strong><?= $d['max_backlogs'] ?></strong></div>
                    </td>
                    <td>
                        <a href="<?= base_url('org/placements/drives/' . ($d['uuid'] ?? $d['id']) . '/applications') ?>" class="badge" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; text-decoration: none; font-weight: 700; padding: 6px 12px; border-radius: 12px;">
                            <i class="fa-solid fa-users me-1"></i> <?= $d['applicant_count'] ?> Candidates (<?= $d['selected_count'] ?> Selected)
                        </a>
                    </td>
                    <td>
                        <?php
                            $stCol = '#3b82f6';
                            if ($d['status'] === 'Active') $stCol = '#10b981';
                            elseif ($d['status'] === 'Completed') $stCol = '#64748b';
                        ?>
                        <span class="badge" style="background: <?= $stCol ?>15; color: <?= $stCol ?>; font-weight: 600;">
                            <?= esc($d['status']) ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="<?= base_url('org/placements/drives/' . ($d['uuid'] ?? $d['id']) . '/applications') ?>" class="btn-icon" title="View Applicants">
                                <i class="fa-solid fa-users-viewfinder"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="8" style="text-align: center; padding: 30px; color: var(--text-muted);">No campus recruitment drives scheduled yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Schedule Drive Modal -->
<div class="drawer-overlay" id="driveModal" style="display: none;">
    <div class="drawer-content" style="max-width: 580px;">
        <form action="<?= base_url('org/placements/drives/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 700;"><i class="fa-solid fa-bullhorn me-2" style="color: #4f46e5;"></i> Schedule Recruitment Drive</h3>
                <button type="button" class="btn-close" onclick="closeDriveModal()" style="background: none; border: none; font-size: 18px; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div class="form-group" style="grid-column: span 2;">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Drive Title / Headline *</label>
                        <input type="text" name="title" class="form-control" required placeholder="e.g. Infosys Campus Drive 2026 - Systems Engineer">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Hiring Partner / Company *</label>
                        <select name="company_id" class="form-control" required>
                            <option value="">-- Choose Company --</option>
                            <?php foreach($companies as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= esc($c['company_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Designation / Job Role *</label>
                        <input type="text" name="job_role" class="form-control" required placeholder="e.g. Graduate Trainee Engineer">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">CTC / Package Offered</label>
                        <input type="text" name="ctc_details" class="form-control" placeholder="e.g. ₹6.5 LPA + Performance Bonus">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Location / Venue</label>
                        <input type="text" name="location" class="form-control" placeholder="Auditorium / Online">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Drive Date *</label>
                        <input type="date" name="drive_date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Application Deadline *</label>
                        <input type="date" name="deadline_date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Min Required CGPA</label>
                        <input type="number" step="0.1" name="required_cgpa" class="form-control" value="6.0">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Max Standing Backlogs</label>
                        <input type="number" name="max_backlogs" class="form-control" value="0">
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Drive Description / Selection Rounds</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Round 1: Online Aptitude, Round 2: Tech Interview, Round 3: HR Discussion"></textarea>
                    </div>
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeDriveModal()">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Publish Drive</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDriveModal() { document.getElementById('driveModal').style.display = 'flex'; }
    function closeDriveModal() { document.getElementById('driveModal').style.display = 'none'; }
</script>
<?= $this->endSection() ?>
