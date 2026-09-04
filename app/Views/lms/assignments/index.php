<?= $this->extend('lms/layout') ?>
<?= $this->section('page_title') ?>Assignments<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="lms-page-header">
    <h1>My Assignments</h1>
    <p>View pending assignments, submit solutions, and track your grading status.</p>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    <table class="data-table" style="width: 100%; border-collapse: collapse;">
        <thead style="background: rgba(0,0,0,0.02); border-bottom: 1px solid var(--border);">
            <tr>
                <th style="padding: 12px 16px; text-align: left;">Assignment</th>
                <th style="padding: 12px 16px; text-align: left;">Due Date</th>
                <th style="padding: 12px 16px; text-align: left;">Status</th>
                <th style="padding: 12px 16px; text-align: left;">Score</th>
                <th style="padding: 12px 16px; text-align: right; width: 100px;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($assignments)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <div class="empty-state-box" style="border: none; margin: 0; padding: 20px;">
                            <div class="empty-icon-circle">
                                <i class="fa-solid fa-file-lines"></i>
                            </div>
                            <div class="empty-state-title">No Assignments Found</div>
                            <p class="empty-state-desc">You don't have any assignments pending at this time.</p>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach($assignments as $a): ?>
                    <?php 
                        $sub = isset($submission_map[$a['id']]) ? $submission_map[$a['id']] : null;
                        $is_overdue = !$sub && strtotime($a['due_date']) < time();
                    ?>
                    <tr style="border-bottom: 1px solid var(--border-light);">
                        <td style="padding: 12px 16px;">
                            <div style="font-weight: 600; margin-bottom: 2px; color: var(--text-main); font-size: 13px;"><?= esc($a['title']) ?></div>
                            <div style="font-size: 11.5px; color: var(--text-muted);"><?= esc($a['subject_code']) ?> &bull; <?= esc($a['subject_name']) ?></div>
                        </td>
                        <td style="padding: 12px 16px; font-size: 12.5px;">
                            <div style="color: <?= $is_overdue ? 'var(--danger)' : 'var(--text-main)' ?>;">
                                <?= date('d/m/Y, h:i A', strtotime($a['due_date'])) ?>
                            </div>
                        </td>
                        <td style="padding: 12px 16px;">
                            <?php if(!$sub): ?>
                                <?php if($is_overdue): ?>
                                    <span class="badge" style="background: rgba(239, 68, 68, 0.12); color: var(--danger);">Overdue</span>
                                <?php else: ?>
                                    <span class="badge" style="background: var(--bg-canvas); color: var(--text-muted); border: 1px solid var(--border);">Pending</span>
                                <?php endif; ?>
                            <?php elseif($sub['status'] === 'graded'): ?>
                                <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success);"><i class="fa-solid fa-check me-1"></i> Graded</span>
                            <?php else: ?>
                                <span class="badge" style="background: rgba(245, 158, 11, 0.12); color: var(--warning);"><i class="fa-solid fa-clock me-1"></i> Submitted</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px 16px; font-size: 12.5px;">
                            <?php if($sub && $sub['status'] === 'graded'): ?>
                                <strong style="color: var(--primary);"><?= esc($sub['marks_obtained']) ?></strong> / <?= $a['max_marks'] ?>
                            <?php else: ?>
                                <span style="color: var(--text-muted);">- / <?= $a['max_marks'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px 16px; text-align: right;">
                            <a href="<?= base_url('lms/assignments/view/'.$a['id']) ?>" class="btn btn-sm btn-outline">Open</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
