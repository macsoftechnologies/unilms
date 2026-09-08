<?= $this->extend('parent/layout') ?>
<?= $this->section('page_title') ?>Homework & Assignments<?= $this->endSection() ?>

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

<div class="card">
    <h2 style="margin-top: 0; font-size: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
        <i class="fa-solid fa-file-signature me-2" style="color: #4f46e5;"></i> Coursework & Homework Tasks
    </h2>

    <table class="data-table">
        <thead>
            <tr>
                <th>Assignment Title</th>
                <th>Subject</th>
                <th>Due Date</th>
                <th>Max Marks</th>
                <th>Submission Status</th>
                <th>Score & Feedback</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($assignments)): foreach($assignments as $a): ?>
            <tr>
                <td>
                    <strong><?= esc($a['title']) ?></strong>
                    <?php if(!empty($a['description'])): ?>
                        <div style="font-size: 12px; color: var(--text-muted); max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <?= esc($a['description']) ?>
                        </div>
                    <?php endif; ?>
                </td>
                <td><?= esc($a['subject_name'] ?? 'General') ?></td>
                <td>
                    <span style="font-size: 13px; <?= strtotime($a['due_date']) < time() ? 'color: #ef4444;' : 'color: #10b981;' ?>">
                        <?= date('d/m/Y, h:i A', strtotime($a['due_date'])) ?>
                    </span>
                </td>
                <td><?= esc($a['max_marks']) ?></td>
                <td>
                    <?php if(!empty($a['submission_status'])): ?>
                        <span style="background: rgba(16,185,129,0.12); color: #10b981; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                            Submitted
                        </span>
                    <?php else: ?>
                        <?php if(strtotime($a['due_date']) < time()): ?>
                            <span style="background: rgba(239,68,68,0.12); color: #ef4444; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                Overdue / Missing
                            </span>
                        <?php else: ?>
                            <span style="background: rgba(245,158,11,0.12); color: #f59e0b; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                Pending Submission
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if(isset($a['score']) && $a['score'] !== null): ?>
                        <strong style="color: #4f46e5;"><?= esc($a['score']) ?></strong> / <?= esc($a['max_marks']) ?>
                        <?php if(!empty($a['feedback'])): ?>
                            <div style="font-size: 11px; color: var(--text-muted); font-style: italic;">"<?= esc($a['feedback']) ?>"</div>
                        <?php endif; ?>
                    <?php else: ?>
                        <span style="color: var(--text-muted); font-size: 12px;">Not graded yet</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td colspan="6" style="text-align: center; padding: 30px; color: var(--text-muted);">No assignments posted for this cohort yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
