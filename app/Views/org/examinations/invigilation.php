<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Invigilation & Duties<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Invigilation & Duty Assignments</h2>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Assign Duty</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Exam & Date</th>
                    <th>Invigilator</th>
                    <th>Charge per Session</th>
                    <th>Total Charge</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($duties)): foreach($duties as $d): ?>
                <tr>
                    <td><strong><?= esc($d['exam_name']) ?></strong><br><small><?= esc(date('d/m/Y', strtotime($d['exam_date']))) ?></small></td>
                    <td><?= esc($d['invigilator_name']) ?></td>
                    <td><?= esc($d['charge_per_session']) ?></td>
                    <td><strong><?= esc($d['total_charge']) ?></strong></td>
                    <td>
                        <?php if($d['paid']): ?>
                            <span class="badge badge-success">Paid</span>
                        <?php else: ?>
                            <span class="badge badge-warning">Pending Payment</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5">No invigilation duties assigned.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Modal -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/examinations/save_invigilation') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header">
                <h3 id="modal_title">Assign Invigilation Duty</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Select Exam Schedule</label>
                    <select name="exam_schedule_id" class="form-control" required>
                        <?php foreach($schedules as $sch): ?>
                        <option value="<?= $sch['id'] ?>"><?= esc($sch['exam_name'].' - '.$sch['exam_date'].' ('.$sch['program_name'].')') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Select Staff / Invigilator</label>
                    <select name="invigilator_id" class="form-control" required>
                        <?php foreach($staff as $u): ?>
                        <option value="<?= $u['id'] ?>"><?= esc($u['name'].' ('.$u['role'].')') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Number of Sessions</label>
                    <input type="number" min="0" name="sessions" class="form-control" value="1" required>
                </div>
                <div class="form-group">
                    <label>Charge per Session (₹)</label>
                    <input type="number" min="0" name="charge_per_session" class="form-control" value="0" required>
                </div>
                <div class="form-group">
                    <label><input type="checkbox" name="paid" value="1"> Payment Cleared?</label>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Duty</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    $('#addModal').addClass('active');
}
function closeModal() {
    $('#addModal').removeClass('active');
}
</script>
<?= $this->endSection() ?>
