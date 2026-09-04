<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Hostel Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Hostel Management Dashboard</h2>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Add Hostel</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Hostel Name</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Warden</th>
                    <th>Contact</th>
                    <th>Capacity</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($hostels)): foreach($hostels as $h): ?>
                <tr>
                    <td><strong><?= esc($h['name']) ?></strong></td>
                    <td><span class="badge badge-secondary"><?= esc(ucfirst($h['type'])) ?></span></td>
                    <td><?= esc($h['location']) ?></td>
                    <td><?= esc($h['warden_name']) ?></td>
                    <td><?= esc($h['warden_contact']) ?></td>
                    <td><?= esc($h['capacity']) ?></td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6">No hostels found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Modal -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/hostel/save') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="form_id">
            <div class="drawer-header">
                <h3 id="modal_title">Add Hostel</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Hostel Name</label>
                    <input type="text" name="name" id="form_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" id="form_type" class="form-control" required>
                        <option value="boys">Boys</option>
                        <option value="girls">Girls</option>
                        <option value="co-ed">Co-ed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" id="form_location" class="form-control">
                </div>
                <div class="form-group">
                    <label>Warden Name</label>
                    <input type="text" name="warden_name" id="form_warden_name" class="form-control">
                </div>
                <div class="form-group">
                    <label>Warden Contact</label>
                    <input type="tel" pattern="[0-9]{10}" maxlength="10" name="warden_contact" id="form_warden_contact" class="form-control">
                </div>
                <div class="form-group">
                    <label>Total Capacity</label>
                    <input type="number" min="0" name="capacity" id="form_capacity" class="form-control" value="0">
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Hostel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    $('#form_id').val('');
    $('#form_name').val('');
    $('#form_type').val('boys');
    $('#form_location').val('');
    $('#form_warden_name').val('');
    $('#form_warden_contact').val('');
    $('#form_capacity').val('0');
    $('#modal_title').text('Add Hostel');
    $('#addModal').addClass('active');
}
function closeModal() {
    $('#addModal').removeClass('active');
}
</script>
<?= $this->endSection() ?>
