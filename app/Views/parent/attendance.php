<?= $this->extend('parent/layout') ?>
<?= $this->section('page_title') ?>Attendance History<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Student Selector -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
    <div style="display: flex; gap: 10px;">
        <?php foreach($students as $s): ?>
            <a href="<?= base_url('parent/dashboard/select_student/' . ($s['uuid'] ?? $s['id'])) ?>" 
               style="text-decoration: none; padding: 8px 18px; border-radius: 6px; font-weight: 600; font-size: 14px;
                      <?= $s['id'] == $selected_student['id'] ? 'background: var(--primary); color: white;' : 'background: white; color: var(--text-main); border: 1px solid var(--border-color);' ?>">
                <i class="fa-solid fa-graduation-cap me-1"></i> <?= esc($s['first_name'] . ' ' . $s['last_name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
    <div style="font-size: 13px; color: var(--text-muted);">
        Roll Number: <strong><?= esc($selected_student['roll_number']) ?></strong>
    </div>
</div>

<!-- Attendance Highlights -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="card" style="padding: 16px; margin: 0; border-left: 4px solid #4f46e5;">
        <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Overall Percentage</div>
        <div style="font-size: 26px; font-weight: 700; color: #4f46e5; margin-top: 4px;"><?= $percentage ?>%</div>
        <div style="font-size: 12px; color: <?= $percentage >= 75 ? '#10b981' : '#ef4444' ?>; margin-top: 4px;">
            <?= $percentage >= 75 ? 'Satisfies minimum 75% requirement' : 'Low attendance alert!' ?>
        </div>
    </div>

    <div class="card" style="padding: 16px; margin: 0; border-left: 4px solid #10b981;">
        <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Present Sessions</div>
        <div style="font-size: 26px; font-weight: 700; color: #10b981; margin-top: 4px;"><?= $present ?></div>
        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Classes attended</div>
    </div>

    <div class="card" style="padding: 16px; margin: 0; border-left: 4px solid #ef4444;">
        <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Absent Sessions</div>
        <div style="font-size: 26px; font-weight: 700; color: #ef4444; margin-top: 4px;"><?= $absent ?></div>
        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Periods missed</div>
    </div>

    <div class="card" style="padding: 16px; margin: 0; border-left: 4px solid #64748b;">
        <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Total Sessions</div>
        <div style="font-size: 26px; font-weight: 700; color: #1e293b; margin-top: 4px;"><?= $total ?></div>
        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Class periods conducted</div>
    </div>
</div>

<!-- Detailed Records -->
<div class="card">
    <h2 style="margin-top: 0; font-size: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
        <i class="fa-solid fa-calendar-days me-2" style="color: #4f46e5;"></i> Session-wise Attendance Log
    </h2>

    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time / Period</th>
                <th>Subject</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($records)): foreach($records as $r): ?>
            <tr>
                <td><strong><?= date('d/m/Y', strtotime($r['session_date'])) ?></strong></td>
                <td><?= esc(!empty($r['session_time']) ? $r['session_time'] : (!empty($r['period_name']) ? $r['period_name'] : 'Regular Period')) ?></td>
                <td>
                    <strong><?= esc($r['subject_name'] ?? 'General') ?></strong>
                    <?php if(!empty($r['subject_code'])): ?>
                        <span style="font-size: 11px; color: var(--text-muted);">(<?= esc($r['subject_code']) ?>)</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($r['status'] === 'Present'): ?>
                        <span style="background: rgba(16, 185, 129, 0.12); color: #10b981; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                            <i class="fa-solid fa-check me-1"></i> Present
                        </span>
                    <?php elseif($r['status'] === 'Absent'): ?>
                        <span style="background: rgba(239, 68, 68, 0.12); color: #ef4444; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                            <i class="fa-solid fa-xmark me-1"></i> Absent
                        </span>
                    <?php else: ?>
                        <span style="background: rgba(245, 158, 11, 0.12); color: #f59e0b; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                            <?= esc($r['status']) ?>
                        </span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td colspan="4" style="text-align: center; padding: 30px; color: var(--text-muted);">No attendance records logged yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
