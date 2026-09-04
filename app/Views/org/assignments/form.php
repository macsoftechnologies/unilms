<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?><?= $assignment ? 'Edit' : 'Create' ?> Assignment<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
<div class="view-header">
    <a href="<?= base_url('org/assignments') ?>" style="color: var(--primary); text-decoration: none; font-size: 14px; margin-bottom: 8px; display: inline-block;"><i class="fa-solid fa-arrow-left"></i> Back to Assignments</a>
    <h2><?= $assignment ? 'Edit' : 'Create' ?> Assignment</h2>
</div>

<div class="widget" style="max-width: 800px; margin: 0 auto; border: 1px solid var(--border-color);">
    <form action="<?= base_url('org/assignments/save') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <?php if($assignment): ?>
            <input type="hidden" name="assignment_id" value="<?= $assignment['id'] ?>">
        <?php endif; ?>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Subject</label>
                <select name="subject_id" class="form-control" required>
                    <option value="">-- Select Subject --</option>
                    <?php foreach($subjects as $sub): ?>
                        <option value="<?= $sub['id'] ?>" <?= ($assignment && $assignment['subject_id'] == $sub['id']) ? 'selected' : '' ?>><?= esc($sub['code']) ?> - <?= esc($sub['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Cohort</label>
                <select name="cohort_id" class="form-control" required>
                    <option value="">-- Select Cohort --</option>
                    <?php foreach($cohorts as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($assignment && $assignment['cohort_id'] == $c['id']) ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Assignment Title</label>
            <input type="text" name="title" class="form-control" value="<?= $assignment ? esc($assignment['title']) : '' ?>" required>
        </div>

        <div class="form-group">
            <label>Instructions / Description</label>
            <textarea name="description" class="form-control" rows="6"><?= $assignment ? esc($assignment['description']) : '' ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Due Date & Time</label>
                <?php 
                    $due = $assignment && $assignment['due_date'] ? date('Y-m-d\TH:i', strtotime($assignment['due_date'])) : '';
                ?>
                <input type="datetime-local" name="due_date" class="form-control" value="<?= $due ?>" required>
            </div>
            
            <div class="form-group">
                <label>Maximum Marks</label>
                <input type="number" min="0" step="0.01" name="max_marks" class="form-control" value="<?= $assignment ? esc($assignment['max_marks']) : '100' ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>Attachment File (Optional)</label>
            <?php if($assignment && $assignment['file_path']): ?>
                <div style="margin-bottom: 8px; font-size: 14px;">
                    Current file: <a href="<?= base_url($assignment['file_path']) ?>" target="_blank" style="color: var(--primary);">View Attachment</a>
                </div>
            <?php endif; ?>
            <input type="file" name="attachment" class="form-control" style="padding: 8px;">
            <small style="color: var(--text-muted);">PDF, Word, Images, Zip files allowed. Max size: 10MB.</small>
        </div>

        <div class="form-group" style="margin-top: 24px;">
            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 500;">
                <input type="checkbox" name="is_active" value="1" <?= (!$assignment || $assignment['is_active']) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                Publish Assignment Immediately
            </label>
            <p style="margin: 4px 0 0 28px; font-size: 13px; color: var(--text-muted);">If unchecked, this will be saved as a draft and students won't see it.</p>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 30px 0;">

        <div style="text-align: right;">
            <a href="<?= base_url('org/assignments') ?>" class="btn btn-outline" style="margin-right: 12px;">Cancel</a>
            <button type="submit" class="btn btn-primary" style="padding: 12px 32px;"><i class="fa-solid fa-save"></i> <?= $assignment ? 'Update' : 'Create' ?> Assignment</button>
        </div>
    </form>
</div>

</section>
<?= $this->endSection() ?>
