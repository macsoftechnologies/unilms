<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Certificates Master<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Certificates Master</h2>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Add Certificate</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Certificate Name</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($certificates)): foreach($certificates as $c): ?>
                <tr>
                    <td><strong><?= esc($c['certificate_name']) ?></strong></td>
                    <td><?= esc(date('d/m/Y', strtotime($c['created_at']))) ?></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-icon" onclick='editCert(<?= json_encode($c) ?>)'><i class="fa-solid fa-pen"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="3">No certificates found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Modal -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/administration/save_certificate') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="form_id">
            <div class="drawer-header">
                <h3 id="modal_title">Add Certificate</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Certificate Name</label>
                    <input type="text" name="certificate_name" id="form_certificate_name" class="form-control" required placeholder="e.g. Bonafide Certificate">
                </div>
                <div class="form-group">
                    <label>Template (HTML)</label>
                    <textarea name="template" id="form_template" class="form-control" rows="8" placeholder="Enter HTML template with placeholders like {{student_name}}"></textarea>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Certificate</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    $('#form_id').val('');
    $('#form_certificate_name').val('');
    $('#form_template').val('');
    $('#modal_title').text('Add Certificate');
    $('#addModal').addClass('active');
}
function editCert(data) {
    $('#form_id').val(data.id);
    $('#form_certificate_name').val(data.certificate_name);
    $('#form_template').val(data.template);
    $('#modal_title').text('Edit Certificate');
    $('#addModal').addClass('active');
}
function closeModal() {
    $('#addModal').removeClass('active');
}
</script>
<?= $this->endSection() ?>
