<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Hostel Registrations<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Hostel Registrations</h2>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> New Registration</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Hostel</th>
                    <th>Room & Bed</th>
                    <th>Joining Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($registrations)): foreach($registrations as $r): ?>
                <tr>
                    <td><strong><?= esc($r['first_name'].' '.$r['last_name']) ?></strong><br><small><?= esc($r['roll_number']) ?></small></td>
                    <td><?= esc($r['hostel_name']) ?></td>
                    <td>Room <?= esc($r['room_no']) ?> (Bed: <?= esc($r['bed_no']) ?>)</td>
                    <td><?= esc(date('d/m/Y', strtotime($r['joining_date']))) ?></td>
                    <td>
                        <?php if($r['status'] == 'active'): ?>
                            <span class="badge badge-success">Active</span>
                        <?php else: ?>
                            <span class="badge badge-secondary"><?= esc(ucfirst($r['status'])) ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5">No registrations found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Modal -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/hostel/save_registration') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="form_id">
            <div class="drawer-header">
                <h3 id="modal_title">New Registration</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Select Student</label>
                    <select name="student_id" id="form_student_id" class="form-control" required>
                        <?php foreach($students as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= esc($s['first_name'].' '.$s['last_name'].' - '.$s['roll_number']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Select Hostel</label>
                    <select name="hostel_id" id="form_hostel_id" class="form-control" required>
                        <?php foreach($hostels as $h): ?>
                        <option value="<?= $h['id'] ?>"><?= esc($h['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Select Room</label>
                    <select name="room_id" id="form_room_id" class="form-control" required>
                        <?php foreach($rooms as $rm): ?>
                        <option value="<?= $rm['id'] ?>">Room <?= esc($rm['room_no']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Bed No.</label>
                    <input type="text" name="bed_no" id="form_bed_no" class="form-control">
                </div>
                <div class="form-group">
                    <label>Joining Date</label>
                    <input type="date" name="joining_date" id="form_joining_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Emergency Contact</label>
                    <input type="tel" pattern="[0-9]{10}" maxlength="10" name="emergency_contact" id="form_emergency_contact" class="form-control">
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Registration</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    $('#form_id').val('');
    $('#form_bed_no').val('');
    $('#form_joining_date').val('<?= date('Y-m-d') ?>');
    $('#form_emergency_contact').val('');
    $('#modal_title').text('New Registration');
    $('#addModal').addClass('active');
}
function closeModal() {
    $('#addModal').removeClass('active');
}
</script>
<?= $this->endSection() ?>
