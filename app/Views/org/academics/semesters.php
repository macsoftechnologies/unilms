<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Semesters<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Manage Semesters</h2>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Add Semester</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Sequence</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($semesters)): foreach($semesters as $sem): ?>
                <tr>
                    <td><span class="badge badge-primary"><?= esc($sem['sequence']) ?></span></td>
                    <td><?= esc($sem['name']) ?></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-icon" onclick='editSem(<?= json_encode($sem) ?>)'><i class="fa-solid fa-pen"></i></button>
                            <a href="<?= base_url('org/academics/semesters/delete/'.$sem['id']) ?>" class="btn-icon text-danger" onclick="return confirm('Are you sure you want to delete this semester? Blocks if subjects exist.')"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="3">No semesters found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Modal -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/academics/semesters/save') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="form_id">
            <div class="drawer-header">
                <h3 id="modal_title">Add Semester</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Sequence Number (For Sorting)</label>
                    <input type="number" name="sequence" id="form_sequence" class="form-control" required min="1" max="20" placeholder="e.g. 1">
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" id="form_name" class="form-control" required placeholder="e.g. Semester 1">
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Semester</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    $('#form_id').val('');
    $('#form_sequence').val('');
    $('#form_name').val('');
    $('#modal_title').text('Add Semester');
    $('#addModal').addClass('active');
}
function editSem(data) {
    $('#form_id').val(data.id);
    $('#form_sequence').val(data.sequence);
    $('#form_name').val(data.name);
    $('#modal_title').text('Edit Semester');
    $('#addModal').addClass('active');
}
function closeModal() {
    $('#addModal').removeClass('active');
}
</script>
<?= $this->endSection() ?>
