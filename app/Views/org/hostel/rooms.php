<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Hostel Rooms<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Hostel Rooms Setup</h2>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Add Room</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Hostel</th>
                    <th>Room No.</th>
                    <th>Floor</th>
                    <th>Type</th>
                    <th>Capacity</th>
                    <th>AC/Non-AC</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($rooms)): foreach($rooms as $r): ?>
                <tr>
                    <td><?= esc($r['hostel_name']) ?></td>
                    <td><strong><?= esc($r['room_no']) ?></strong></td>
                    <td><?= esc($r['floor']) ?></td>
                    <td><?= esc(ucfirst($r['type'])) ?></td>
                    <td><?= esc($r['capacity']) ?> Beds</td>
                    <td><?= $r['has_ac'] ? '<span class="badge badge-success">AC</span>' : '<span class="badge badge-secondary">Non-AC</span>' ?></td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6">No rooms found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Modal -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/hostel/save_room') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="form_id">
            <div class="drawer-header">
                <h3 id="modal_title">Add Room</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Select Hostel</label>
                    <select name="hostel_id" id="form_hostel_id" class="form-control" required>
                        <?php foreach($hostels as $h): ?>
                        <option value="<?= $h['id'] ?>"><?= esc($h['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Room No.</label>
                    <input type="text" name="room_no" id="form_room_no" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Floor</label>
                    <input type="text" name="floor" id="form_floor" class="form-control">
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" id="form_type" class="form-control">
                        <option value="single">Single</option>
                        <option value="double">Double</option>
                        <option value="dormitory">Dormitory</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Capacity (Beds)</label>
                    <input type="number" min="0" name="capacity" id="form_capacity" class="form-control" value="2" required>
                </div>
                <div class="form-group">
                    <label><input type="checkbox" name="has_ac" id="form_has_ac" value="1"> Has AC?</label>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Room</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    $('#form_id').val('');
    $('#form_room_no').val('');
    $('#form_floor').val('');
    $('#form_type').val('double');
    $('#form_capacity').val('2');
    $('#form_has_ac').prop('checked', false);
    $('#modal_title').text('Add Room');
    $('#addModal').addClass('active');
}
function closeModal() {
    $('#addModal').removeClass('active');
}
</script>
<?= $this->endSection() ?>
