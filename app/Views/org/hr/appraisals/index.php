<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Performance Appraisals<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <div>
            <h2><i class="fa-solid fa-chart-line"></i> Staff Performance Appraisals</h2>
            <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 13px;">Manage faculty self-assessments, HOD evaluations, ratings, and increment/promotion recommendations.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-primary" onclick="openAppraisalModal()"><i class="fa-solid fa-plus"></i> New Appraisal</button>
            <a href="<?= base_url('org/hr/employees') ?>" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Staff Directory</a>
        </div>
    </div>

    <!-- Appraisals Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Employee Name & Dept</th>
                    <th>Appraisal Period</th>
                    <th>Self Score</th>
                    <th>HOD Score</th>
                    <th>Final Score</th>
                    <th>Recommendation</th>
                    <th>Reviewed By</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($appraisals)): foreach($appraisals as $a): ?>
                <tr>
                    <td>
                        <strong><?= esc($a['employee_name']) ?></strong>
                        <div style="font-size: 11px; color: var(--text-muted);"><?= esc($a['designation_name'] ?? '') ?> • <?= esc($a['department_name'] ?? '') ?></div>
                    </td>
                    <td><strong><?= esc($a['appraisal_period']) ?></strong></td>
                    <td><?= $a['self_rating'] ?> / 10</td>
                    <td><?= $a['hod_rating'] ?> / 10</td>
                    <td>
                        <strong style="color: #4f46e5; font-size: 15px;"><?= $a['final_score'] ?></strong> / 10
                    </td>
                    <td>
                        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10b981; font-weight: 600;">
                            <i class="fa-solid fa-circle-arrow-up me-1"></i> <?= esc($a['recommendation']) ?>
                        </span>
                    </td>
                    <td>
                        <div><?= esc($a['reviewer_name'] ?: 'Admin') ?></div>
                        <span style="font-size: 11px; color: var(--text-muted);"><?= date('d/m/Y', strtotime($a['review_date'])) ?></span>
                    </td>
                    <td>
                        <span class="badge" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; font-weight: 600;">
                            <?= esc($a['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?= base_url('org/hr/appraisals/delete/' . $a['id']) ?>" class="btn-icon text-danger" onclick="return confirm('Delete this appraisal record?')" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="9" style="text-align: center; padding: 30px; color: var(--text-muted);">No performance appraisals recorded yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Appraisal Modal -->
<div class="drawer-overlay" id="appraisalModal" style="display: none;">
    <div class="drawer-content" style="max-width: 540px;">
        <form action="<?= base_url('org/hr/appraisals/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 700;"><i class="fa-solid fa-chart-line me-2" style="color: #4f46e5;"></i> Record Performance Appraisal</h3>
                <button type="button" class="btn-close" onclick="closeAppraisalModal()" style="background: none; border: none; font-size: 18px; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div class="form-group" style="grid-column: span 2;">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Select Staff / Employee *</label>
                        <select name="employee_id" class="form-control" required>
                            <option value="">-- Choose Employee --</option>
                            <?php foreach($employees as $e): ?>
                                <option value="<?= $e['id'] ?>"><?= esc($e['full_name']) ?> (<?= esc($e['employee_code'] ?? 'EMP') ?>) - <?= esc($e['department_name'] ?? '') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Appraisal Period *</label>
                        <input type="text" name="appraisal_period" class="form-control" value="<?= date('Y') . '-' . (date('Y') + 1) ?>" required>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Recommendation *</label>
                        <select name="recommendation" class="form-control" required>
                            <option value="Increment">Salary Increment</option>
                            <option value="Promotion">Promotion to Higher Grade</option>
                            <option value="Bonus">Performance Bonus</option>
                            <option value="Retain">Retain Current Scale</option>
                            <option value="Warning">Performance Improvement Plan (PIP)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Self Rating (0-10)</label>
                        <input type="number" step="0.1" max="10" min="0" name="self_rating" class="form-control" value="8.0">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">HOD / Dean Rating (0-10)</label>
                        <input type="number" step="0.1" max="10" min="0" name="hod_rating" class="form-control" value="8.5">
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Final Composite Score (0-10) *</label>
                        <input type="number" step="0.1" max="10" min="0" name="final_score" class="form-control" required value="8.2">
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Key Strengths & Achievements</label>
                        <textarea name="strengths" class="form-control" rows="2" placeholder="Research papers published, student feedback, NAAC documentation..."></textarea>
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Areas for Improvement / Goals</label>
                        <textarea name="areas_for_improvement" class="form-control" rows="2" placeholder="Grant funding proposals, course material updates..."></textarea>
                    </div>
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeAppraisalModal()">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Save Appraisal</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAppraisalModal() { document.getElementById('appraisalModal').style.display = 'flex'; }
    function closeAppraisalModal() { document.getElementById('appraisalModal').style.display = 'none'; }
</script>
<?= $this->endSection() ?>
