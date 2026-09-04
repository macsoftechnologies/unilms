<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?><?= $material ? 'Edit' : 'Upload' ?> Study Material<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
<div class="view-header">
    <a href="<?= base_url('org/materials') ?>" style="color: var(--primary); text-decoration: none; font-size: 14px; margin-bottom: 8px; display: inline-block;"><i class="fa-solid fa-arrow-left"></i> Back to Materials</a>
    <h2><?= $material ? 'Edit' : 'Upload' ?> Study Material</h2>
</div>

<div class="widget" style="max-width: 800px; margin: 0 auto; border: 1px solid var(--border-color);">
    <form action="<?= base_url('org/materials/save') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <?php if($material): ?>
            <input type="hidden" name="material_id" value="<?= $material['id'] ?>">
        <?php endif; ?>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Subject</label>
                <select name="subject_id" class="form-control" required>
                    <option value="">-- Select Subject --</option>
                    <?php foreach($subjects as $sub): ?>
                        <option value="<?= $sub['id'] ?>" <?= ($material && $material['subject_id'] == $sub['id']) ? 'selected' : '' ?>><?= esc($sub['code']) ?> - <?= esc($sub['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Cohort</label>
                <select name="cohort_id" class="form-control" required>
                    <option value="">-- Select Cohort --</option>
                    <?php foreach($cohorts as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($material && $material['cohort_id'] == $c['id']) ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="<?= $material ? esc($material['title']) : '' ?>" required>
        </div>

        <div class="form-group">
            <label>Description (Optional)</label>
            <textarea name="description" class="form-control" rows="3"><?= $material ? esc($material['description']) : '' ?></textarea>
        </div>

        <div class="form-group">
            <label>Content Type</label>
            <select name="type" id="content_type" class="form-control" onchange="toggleContentInputs()" required>
                <option value="file" <?= ($material && $material['type'] === 'file') ? 'selected' : '' ?>>File Upload (PDF, Word, PPT)</option>
                <option value="youtube" <?= ($material && $material['type'] === 'youtube') ? 'selected' : '' ?>>YouTube Video</option>
                <option value="link" <?= ($material && $material['type'] === 'link') ? 'selected' : '' ?>>External Link</option>
            </select>
        </div>

        <div class="form-group" id="file_input_group">
            <label>File Attachment</label>
            <?php if($material && $material['type'] === 'file' && $material['file_path']): ?>
                <div style="margin-bottom: 8px; font-size: 14px;">
                    Current file: <a href="<?= base_url($material['file_path']) ?>" target="_blank" style="color: var(--primary);">View File</a>
                </div>
            <?php endif; ?>
            <input type="file" name="attachment" id="attachment" class="form-control" style="padding: 8px;" <?= (!$material) ? 'required' : '' ?>>
        </div>

        <div class="form-group" id="url_input_group" style="display: none;">
            <label>URL / Link</label>
            <input type="url" name="external_url" id="external_url" class="form-control" value="<?= $material ? esc($material['external_url']) : '' ?>" placeholder="https://...">
        </div>

        <div class="form-group" style="margin-top: 24px;">
            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 500;">
                <input type="checkbox" name="is_active" value="1" <?= (!$material || $material['is_active']) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                Make Visible to Students Immediately
            </label>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 30px 0;">

        <div style="text-align: right;">
            <a href="<?= base_url('org/materials') ?>" class="btn btn-outline" style="margin-right: 12px;">Cancel</a>
            <button type="submit" class="btn btn-primary" style="padding: 12px 32px;"><i class="fa-solid fa-save"></i> <?= $material ? 'Update' : 'Upload' ?> Material</button>
        </div>
    </form>
</div>

<script>
function toggleContentInputs() {
    var type = document.getElementById('content_type').value;
    var fileGroup = document.getElementById('file_input_group');
    var urlGroup = document.getElementById('url_input_group');
    var fileInput = document.getElementById('attachment');
    var urlInput = document.getElementById('external_url');

    if (type === 'file') {
        fileGroup.style.display = 'block';
        urlGroup.style.display = 'none';
        urlInput.removeAttribute('required');
        <?php if(!$material): ?>
            fileInput.setAttribute('required', 'required');
        <?php endif; ?>
    } else {
        fileGroup.style.display = 'none';
        urlGroup.style.display = 'block';
        fileInput.removeAttribute('required');
        urlInput.setAttribute('required', 'required');
    }
}
// Run on load
toggleContentInputs();
</script>

</section>
<?= $this->endSection() ?>
