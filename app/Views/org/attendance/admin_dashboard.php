<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Admin Attendance Dashboard<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
<div class="view-header">
    <h2>Attendance Defaulters</h2>
    <p style="color:var(--text-muted); font-size: 14px; margin: 4px 0 0;">Students who have fallen below the configured minimum threshold.</p>
</div>

<!-- Settings Widget -->
<div class="stat-card" style="margin-bottom: 24px; max-width: 500px;">
    <h3 style="margin: 0 0 16px; font-size: 16px;"><i class="fa-solid fa-gear" style="color:var(--primary); margin-right:8px;"></i> Attendance Settings</h3>
    <form action="<?= base_url('org/attendance/admin/settings') ?>" method="POST" style="display: flex; gap: 12px; align-items: flex-end;">
        <?= csrf_field() ?>
        <div class="form-group" style="margin:0; flex:1;">
            <label style="font-size: 12px;">Minimum Threshold (%)</label>
            <input type="number" min="0" name="attendance_threshold" class="form-control" value="<?= esc($threshold) ?>" min="1" max="100" required>
        </div>
        <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">Save Rule</button>
    </form>
</div>

<div class="table-container">
    <div style="padding: 16px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin:0; font-size:16px;">Students Below <?= esc($threshold) ?>%</h3>
        <button class="btn btn-outline" style="font-size: 12px;"><i class="fa-regular fa-envelope"></i> Email Defaulters</button>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Roll Number</th>
                <th>Student Name</th>
                <th>Total Sessions</th>
                <th>Attended</th>
                <th>Percentage</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($defaulters)): ?>
                <?php foreach($defaulters as $d): ?>
                <tr>
                    <td><strong><?= esc($d['roll_number']) ?></strong></td>
                    <td><?= esc($d['first_name'] . ' ' . $d['last_name']) ?></td>
                    <td><?= esc($d['total_sessions']) ?></td>
                    <td><?= esc($d['attended_sessions']) ?></td>
                    <td>
                        <strong style="color: <?= $d['percentage'] < ($threshold - 10) ? 'var(--danger)' : 'var(--warning)' ?>;">
                            <?= esc($d['percentage']) ?>%
                        </strong>
                    </td>
                    <td>
                        <span class="badge" style="background:#fee2e2; color:#991b1b;">Defaulter</span>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center; padding: 40px; color: var(--text-muted);">No defaulters found below <?= esc($threshold) ?>%. All students are maintaining good attendance.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</section>

<?= $this->endSection() ?>
