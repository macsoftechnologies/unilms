<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?><?= $quiz ? 'Edit Quiz' : 'Create Quiz' ?><?= $this->endSection() ?>
<?= $this->section('content') ?>

<div style="margin-bottom: 24px;">
    <a href="<?= base_url('org/quizzes') ?>" style="color: var(--primary); text-decoration: none; font-size: 14px;"><i class="fa-solid fa-arrow-left"></i> Back to Quizzes</a>
</div>

<div class="card" style="max-width: 800px;">
    <h2 style="margin-top: 0; margin-bottom: 24px; font-size: 20px;"><?= $quiz ? 'Edit Quiz Settings' : 'Create New Quiz' ?></h2>
    
    <form action="<?= base_url('org/quizzes/save') ?>" method="POST">
        <?= csrf_field() ?>
        <?php if($quiz): ?>
            <input type="hidden" name="quiz_id" value="<?= esc($quiz['uuid'] ?? $quiz['id']) ?>">
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Subject</label>
                <select name="subject_id" class="form-control" required>
                    <option value="">-- Select Subject --</option>
                    <?php foreach($subjects as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= ($quiz && $quiz['subject_id'] == $s['id']) ? 'selected' : '' ?>><?= esc($s['name']) ?> (<?= esc($s['code']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Cohort (Class)</label>
                <select name="cohort_id" class="form-control" required>
                    <option value="">-- Select Cohort --</option>
                    <?php foreach($cohorts as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($quiz && $quiz['cohort_id'] == $c['id']) ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Quiz Title</label>
            <input type="text" name="title" class="form-control" value="<?= $quiz ? esc($quiz['title']) : '' ?>" required>
        </div>

        <div class="form-group">
            <label>Description / Instructions</label>
            <textarea name="description" class="form-control" rows="4"><?= $quiz ? esc($quiz['description']) : '' ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Time Limit (Minutes)</label>
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 6px;">Set 0 for unlimited time.</div>
                <input type="number" min="0" name="time_limit_minutes" class="form-control" value="<?= $quiz ? $quiz['time_limit_minutes'] : '0' ?>" min="0">
            </div>
            <div class="form-group">
                <label>Max Attempts</label>
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 6px;">Number of times a student can take the quiz.</div>
                <input type="number" min="0" name="max_attempts" class="form-control" value="<?= $quiz ? $quiz['max_attempts'] : '1' ?>" min="1">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Start Date (Optional)</label>
                <input type="datetime-local" name="start_date" class="form-control" value="<?= $quiz && $quiz['start_date'] ? date('Y-m-d\TH:i', strtotime($quiz['start_date'])) : '' ?>">
            </div>
            <div class="form-group">
                <label>End Date (Optional)</label>
                <input type="datetime-local" name="end_date" class="form-control" value="<?= $quiz && $quiz['end_date'] ? date('Y-m-d\TH:i', strtotime($quiz['end_date'])) : '' ?>">
            </div>
        </div>

        <div class="form-group" style="margin-top: 16px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" name="is_published" value="1" <?= ($quiz && $quiz['is_published']) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                <span style="font-weight: 600;">Publish Quiz (Visible to Students)</span>
            </label>
            <div style="font-size: 13px; color: var(--text-muted); margin-left: 26px;">If unchecked, the quiz remains a draft and students cannot see it.</div>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 24px 0;">

        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> <?= $quiz ? 'Update Settings' : 'Create Quiz & Add Questions' ?></button>
    </form>
</div>

<?= $this->endSection() ?>

