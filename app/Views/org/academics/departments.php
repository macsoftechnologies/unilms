<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Departments<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Manage Departments</h2>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Add Department</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($departments)): foreach($departments as $dept): ?>
                <tr>
                    <td><span class="badge badge-primary"><?= esc($dept['code']) ?></span></td>
                    <td><?= esc($dept['name']) ?></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-icon" onclick='editDept(<?= json_encode($dept) ?>)'><i class="fa-solid fa-pen"></i></button>
                            <a href="<?= base_url('org/academics/departments/delete/' . ($dept['uuid'] ?? $dept['id'])) ?>" class="btn-icon text-danger" onclick="return confirm('Are you sure you want to delete this department? This is blocked if programs exist under it.')"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="3">No departments found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Modal -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/academics/departments/save') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="form_id">
            <div class="drawer-header">
                <h3 id="modal_title">Add Department</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Department Code</label>
                    <input type="text" name="code" id="form_code" class="form-control" required placeholder="e.g. CS">
                </div>
                <div class="form-group">
                    <label>Department Name</label>
                    <input type="text" name="name" id="form_name" class="form-control" required placeholder="e.g. Computer Science">
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Department</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    $('#form_id').val('');
    $('#form_code').val('');
    $('#form_name').val('');
    $('#modal_title').text('Add Department');
    $('#addModal').addClass('active');
}
function editDept(data) {
    $('#form_id').val(data.id);
    $('#form_code').val(data.code);
    $('#form_name').val(data.name);
    $('#modal_title').text('Edit Department');
    $('#addModal').addClass('active');
}
function closeModal() {
    $('#addModal').removeClass('active');
}
</script>
<?= $this->endSection() ?>
