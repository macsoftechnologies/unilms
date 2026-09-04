<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Message Templates<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Message Templates</h1>
        <p class="header-subtitle">Manage reusable correspondence templates for greetings and alerts.</p>
    </div>
    <div>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> New Template</button>
    </div>
</div>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Template Name</th>
                <th>Type</th>
                <th>Preview</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($templates as $t): ?>
                <tr>
                    <td style="font-weight: 600; color: var(--primary);"><?= esc($t['name']) ?></td>
                    <td><span class="badge badge-secondary"><?= esc($t['type']) ?></span></td>
                    <td style="font-size: 13px; color: var(--text-muted); max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <?= esc($t['body_text']) ?>
                    </td>
                    <td>
                        <button class="btn btn-outline" style="padding: 4px 10px; font-size: 12px;" onclick='editData(<?= json_encode($t) ?>)'>Edit</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($templates)): ?>
                <tr><td colspan="4" style="text-align: center;">No templates found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Drawer -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/correspondence/save_template') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="form_id">
            
            <div class="drawer-header">
                <h3 id="modal_title">Add Template</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <div class="drawer-body">
                <div class="form-group">
                    <label>Template Name</label>
                    <input type="text" name="name" id="form_name" class="form-control" required placeholder="e.g. Diwali Greeting">
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" id="form_type" class="form-control" required>
                        <option value="Festival">Festival Greeting</option>
                        <option value="Birthday">Birthday Greeting</option>
                        <option value="General">General Alert</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Message Body</label>
                    <textarea name="body_text" id="form_body" class="form-control" rows="8" required placeholder="Use {name} as a placeholder..."></textarea>
                    <small style="color: var(--text-muted); display: block; margin-top: 8px;"><i class="fa-solid fa-circle-info"></i> You can use placeholders like <strong>{name}</strong> to automatically insert the recipient's name.</small>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Template</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    $('#form_id').val('');
    $('#form_name').val('');
    $('#form_type').val('Festival');
    $('#form_body').val('');
    $('#modal_title').text('Add Template');
    $('#addModal').addClass('active');
}
function editData(data) {
    $('#form_id').val(data.id);
    $('#form_name').val(data.name);
    $('#form_type').val(data.type);
    $('#form_body').val(data.body_text);
    $('#modal_title').text('Edit Template');
    $('#addModal').addClass('active');
}
function closeModal() {
    $('#addModal').removeClass('active');
}
</script>

<?= $this->endSection() ?>
