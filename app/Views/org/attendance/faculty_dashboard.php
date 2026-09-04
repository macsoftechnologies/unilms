<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Faculty Attendance Dashboard<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
<div class="view-header">
    <h2>My Subjects & Attendance</h2>
    <p style="color:var(--text-muted); font-size: 14px; margin: 4px 0 0;">Select a subject below to mark attendance.</p>
</div>

<div class="grid-layout" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px; margin-bottom: 40px;">
    <?php if(!empty($allocations)): ?>
        <?php foreach($allocations as $a): ?>
            <div class="stat-card" style="box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                    <div>
                        <h3 style="margin: 0; font-size: 16px; color: var(--primary);"><?= esc($a['subject_code']) ?></h3>
                        <p style="margin: 4px 0 0; font-size: 13px; font-weight: 600;"><?= esc($a['subject_name']) ?></p>
                    </div>
                </div>
                <div style="margin-bottom: 16px; font-size: 13px; color: var(--text-muted);">
                    <i class="fa-solid fa-users"></i> Cohort: <strong><?= esc($a['cohort_name']) ?></strong>
                </div>
                
                <a href="<?= base_url('org/attendance/mark/'.$a['subject_id'].'/'.$a['cohort_id']) ?>" class="btn btn-primary" style="width: 100%; display: block; text-align: center;">Mark Attendance</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted);">
            No subjects have been allocated to you yet. Contact the administration.
        </div>
    <?php endif; ?>
</div>

<div class="view-header">
    <h3>Recent Sessions</h3>
</div>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Subject</th>
                <th>Cohort</th>
                <th>Topic Taught</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($recent_sessions)): ?>
                <?php foreach($recent_sessions as $s): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($s['session_date'])) ?></td>
                    <td><?= esc($s['subject_code']) ?> - <?= esc($s['subject_name']) ?></td>
                    <td><?= esc($s['cohort_name']) ?></td>
                    <td><?= esc($s['topic_taught'] ?? '-') ?></td>
                    <td>
                        <a href="<?= base_url('org/attendance/mark/'.$s['subject_id'].'/'.$s['cohort_id'].'?session_id='.$s['id']) ?>" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px;">Edit Log</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center; padding: 20px; color: var(--text-muted);">No attendance sessions recorded yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</section>

<?= $this->endSection() ?>
