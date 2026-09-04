<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Academic Years<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Manage Academic Years</h2>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Add Year</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($academic_years)): foreach($academic_years as $ay): ?>
                <tr>
                    <td><strong><?= esc($ay['name']) ?></strong></td>
                    <td><?= !empty($ay['start_date']) ? date('d/m/Y', strtotime($ay['start_date'])) : '-' ?></td>
                    <td><?= !empty($ay['end_date']) ? date('d/m/Y', strtotime($ay['end_date'])) : '-' ?></td>
                    <td>
                        <?php if($ay['status'] == 'active'): ?>
                            <span class="badge badge-success">Active</span>
                        <?php else: ?>
                            <span class="badge badge-warning">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-icon" onclick='editAy(<?= json_encode($ay) ?>)'><i class="fa-solid fa-pen"></i></button>
                            <a href="<?= base_url('org/academics/academic_years/delete/'.$ay['id']) ?>" class="btn-icon text-danger" onclick="return confirm('Are you sure you want to delete this year? Blocks if cohorts exist.')"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5">No academic years found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Modal -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/academics/academic_years/save') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="form_id">
            <div class="drawer-header">
                <h3 id="modal_title">Add Academic Year</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" id="form_name" class="form-control" required placeholder="e.g. 2026-2027">
                </div>
                <div class="form-group" style="display: flex; gap: 16px;">
                    <div style="flex: 1;">
                        <label>Start Date</label>
                        <input type="date" name="start_date" id="form_start_date" class="form-control" required>
                    </div>
                    <div style="flex: 1;">
                        <label>End Date</label>
                        <input type="date" name="end_date" id="form_end_date" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="form_status" class="form-control" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Academic Year</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    $('#form_id').val('');
    $('#form_name').val('');
    $('#form_start_date').val('');
    $('#form_end_date').val('');
    const s = document.getElementById('form_start_date');
    const e = document.getElementById('form_end_date');
    if (s && s._flatpickr) s._flatpickr.clear();
    if (e && e._flatpickr) e._flatpickr.clear();
    $('#form_status').val('active');
    $('#modal_title').text('Add Academic Year');
    $('#addModal').addClass('active');
}
function editAy(data) {
    $('#form_id').val(data.id);
    $('#form_name').val(data.name);
    $('#form_start_date').val(data.start_date);
    $('#form_end_date').val(data.end_date);
    const s = document.getElementById('form_start_date');
    const e = document.getElementById('form_end_date');
    if (s && s._flatpickr) s._flatpickr.setDate(data.start_date);
    if (e && e._flatpickr) e._flatpickr.setDate(data.end_date);
    $('#form_status').val(data.status);
    $('#modal_title').text('Edit Academic Year');
    $('#addModal').addClass('active');
}
function closeModal() {
    $('#addModal').removeClass('active');
}
</script>
<?= $this->endSection() ?>
