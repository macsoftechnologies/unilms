<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Examinations Setup<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Examinations Setup</h1>
        <p class="header-subtitle">Define examination instances (Regular, Supplementary, Midterm) across the organization.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <div>
        <div class="card">
            <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Create New Exam</h2>
            <form action="<?= base_url('org/examinations/save-exam') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="exam_id">
                <div class="form-group">
                    <label>Exam Name</label>
                    <input type="text" name="name" id="exam_name" class="form-control" required placeholder="e.g. End Semester Exam 2026">
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" id="exam_type" class="form-control" required>
                        <option value="Regular">Regular</option>
                        <option value="Supplementary">Supplementary</option>
                        <option value="Midterm">Midterm</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Description (Optional)</label>
                    <textarea name="description" id="exam_desc" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Save Exam</button>
            </form>
        </div>
    </div>
    
    <div>
        <div class="card">
            <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Exam List</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Exam Name</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($exams as $e): ?>
                        <tr>
                            <td style="font-weight: 600;"><?= esc($e['name']) ?></td>
                            <td><?= esc($e['type']) ?></td>
                            <td><?= esc($e['description']) ?></td>
                            <td>
                                <button type="button" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px;" onclick="editExam(<?= $e['id'] ?>, '<?= esc($e['name'], 'js') ?>', '<?= esc($e['type'], 'js') ?>', '<?= esc($e['description'], 'js') ?>')">Edit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($exams)): ?>
                        <tr><td colspan="4" style="text-align:center;">No exams created yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function editExam(id, name, type, desc) {
    document.getElementById('exam_id').value = id;
    document.getElementById('exam_name').value = name;
    document.getElementById('exam_type').value = type;
    document.getElementById('exam_desc').value = desc;
}
</script>

<?= $this->endSection() ?>
