<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Manage Quizzes<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 class="header-title">Online Examination Engine</h1>
            <p class="header-subtitle">Create and manage MCQ quizzes and exams.</p>
        </div>
        <a href="<?= base_url('org/quizzes/create') ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Create Quiz</a>
    </div>
</div>

<div class="card">
    <?php if(empty($quizzes)): ?>
        <p style="color: var(--text-muted);">No quizzes found. Click 'Create Quiz' to get started.</p>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>Cohort</th>
                    <th>Faculty</th>
                    <th style="text-align: center;">Duration</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($quizzes as $q): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= esc($q['title']) ?></td>
                        <td><?= esc($q['subject_name']) ?> <span style="font-size: 11px; color: var(--text-muted);">(<?= esc($q['subject_code']) ?>)</span></td>
                        <td><?= esc($q['cohort_name']) ?></td>
                        <td><?= esc($q['full_name']) ?></td>
                        <td style="text-align: center;">
                            <?= $q['time_limit_minutes'] > 0 ? $q['time_limit_minutes'] . ' mins' : 'Unlimited' ?>
                        </td>
                        <td style="text-align: center;">
                            <?php if($q['is_published']): ?>
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">Published</span>
                            <?php else: ?>
                                <span class="badge" style="background: rgba(0,0,0,0.05); color: var(--text-muted);">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right;">
                            <a href="<?= base_url('org/quizzes/results/' . ($q['uuid'] ?? $q['id'])) ?>" class="btn btn-primary" style="padding: 4px 10px; font-size: 12px; margin-right: 4px; background: var(--success); border-color: var(--success);"><i class="fa-solid fa-chart-line"></i> Results</a>
                            <a href="<?= base_url('org/quizzes/questions/' . ($q['uuid'] ?? $q['id'])) ?>" class="btn btn-primary" style="padding: 4px 10px; font-size: 12px; margin-right: 4px;"><i class="fa-solid fa-list-ul"></i> Builder</a>
                            <a href="<?= base_url('org/quizzes/edit/' . ($q['uuid'] ?? $q['id'])) ?>" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px; margin-right: 4px;"><i class="fa-solid fa-pen"></i> Settings</a>
                            
                            <form action="<?= base_url('org/quizzes/delete/' . ($q['uuid'] ?? $q['id'])) ?>" method="POST" style="display:inline;" onsubmit="return confirm('Delete this quiz completely?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px; color: var(--danger); border-color: var(--danger);"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
