<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Course Outcomes (CO) Setup<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
<div class="view-header">
    <h2>Course Outcomes</h2>
    <p style="color:var(--text-muted); font-size: 14px; margin: 4px 0 0;">Define the learning outcomes for the subjects you teach.</p>
</div>

<div class="stat-card" style="margin-bottom: 24px;">
    <form action="" method="GET" style="display: flex; gap: 16px; align-items: flex-end;">
        <div class="form-group" style="margin: 0; flex: 1; max-width: 300px;">
            <label>Select Subject</label>
            <select name="subject_id" class="form-control" onchange="this.form.submit()">
                <?php foreach($subjects as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= $s['id'] == $selected_subject_id ? 'selected' : '' ?>><?= esc($s['code']) ?> - <?= esc($s['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

<?php if($selected_subject_id): ?>
<div class="grid-layout" style="display: grid; grid-template-columns: 350px 1fr; gap: 24px;">
    <div class="widget" style="border: 1px solid var(--border-color);">
        <h3 style="margin:0 0 16px; font-size: 16px;">Add Course Outcome</h3>
        <form action="<?= base_url('org/obe/co/save') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="subject_id" value="<?= $selected_subject_id ?>">
            <div class="form-group">
                <label>Code (e.g. CO1)</label>
                <input type="text" name="code" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="4" required placeholder="What will the student be able to do?"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Save Outcome</button>
        </form>
    </div>
    
    <div class="widget" style="border: 1px solid var(--border-color); padding: 0;">
        <table class="data-table" style="width: 100%;">
            <thead>
                <tr>
                    <th style="width:80px;">Code</th>
                    <th>Description</th>
                    <th style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($cos)): ?>
                    <?php foreach($cos as $co): ?>
                    <tr>
                        <td><strong><?= esc($co['code']) ?></strong></td>
                        <td><?= esc($co['description']) ?></td>
                        <td>
                            <form action="<?= base_url('org/obe/co/delete/' . ($co['uuid'] ?? $co['id'])) ?>" method="POST" onsubmit="return confirm('Delete this outcome?');">
                                <?= csrf_field() ?>
                                <button class="btn btn-outline" style="padding: 4px 10px; color: var(--danger); border-color: rgba(238,93,80,0.2);"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" style="text-align: center; padding: 20px; color: var(--text-muted);">No COs defined for this subject.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

</section>
<?= $this->endSection() ?>
