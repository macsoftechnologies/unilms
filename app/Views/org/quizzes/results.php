<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Quiz Results<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div style="margin-bottom: 24px;">
    <a href="<?= base_url('org/quizzes') ?>" style="color: var(--primary); text-decoration: none; font-size: 14px;"><i class="fa-solid fa-arrow-left"></i> Back to Quizzes</a>
</div>

<div class="header-banner">
    <div>
        <h1 class="header-title">Results: <?= esc($quiz['title']) ?></h1>
        <p class="header-subtitle"><?= esc($quiz['subject_name']) ?> | <?= esc($quiz['cohort_name']) ?></p>
    </div>
</div>

<div class="card">
    <?php if(empty($attempts)): ?>
        <div style="text-align: center; padding: 40px; color: var(--text-muted);">
            <i class="fa-solid fa-graduation-cap" style="font-size: 48px; opacity: 0.5; margin-bottom: 16px;"></i>
            <p>No students have attempted this quiz yet.</p>
        </div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Roll Number</th>
                    <th>Attempt Started</th>
                    <th>Attempt Ended</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: right;">Score</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($attempts as $a): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= esc($a['full_name']) ?></td>
                        <td style="font-size: 13px; color: var(--text-muted);"><?= esc($a['roll_number']) ?></td>
                        <td><?= date('d/m/Y, h:i A', strtotime($a['start_time'])) ?></td>
                        <td><?= $a['end_time'] ? date('d/m/Y, h:i A', strtotime($a['end_time'])) : '-' ?></td>
                        <td style="text-align: center;">
                            <?php if($a['status'] === 'completed' || $a['status'] === 'auto_submitted'): ?>
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">Completed</span>
                            <?php else: ?>
                                <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">In Progress</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right; font-weight: 700; color: var(--primary);">
                            <?php if($a['status'] !== 'in_progress'): ?>
                                <?= floatval($a['score_obtained']) ?> / <?= floatval($a['max_score']) ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
