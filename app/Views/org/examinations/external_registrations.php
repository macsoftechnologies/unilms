<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>External Exam Registrations<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>External Exam Registrations (JNTU / Others)</h2>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> New Registration</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Exam Name / Board</th>
                    <th>Registration No.</th>
                    <th>Date</th>
                    <th>Fee Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($registrations)): foreach($registrations as $r): ?>
                <tr>
                    <td><strong><?= esc($r['first_name'].' '.$r['last_name']) ?></strong><br><small><?= esc($r['roll_number']) ?></small></td>
                    <td><?= esc($r['exam_name']) ?></td>
                    <td><?= esc($r['registration_no']) ?></td>
                    <td><?= esc(date('d/m/Y', strtotime($r['registration_date']))) ?></td>
                    <td>
                        <?php if($r['fee_paid']): ?>
                            <span class="badge badge-success">Paid</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Unpaid</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5">No external registrations found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Modal -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/examinations/save_external_registration') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header">
                <h3 id="modal_title">External Exam Registration</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Select Student</label>
                    <select name="student_id" class="form-control" required>
                        <?php foreach($students as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= esc($s['first_name'].' '.$s['last_name'].' - '.$s['roll_number']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Exam Name / University / Board</label>
                    <input type="text" name="exam_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Registration No (if generated)</label>
                    <input type="text" name="registration_no" class="form-control">
                </div>
                <div class="form-group">
                    <label>Registration Date</label>
                    <input type="date" name="registration_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group">
                    <label><input type="checkbox" name="fee_paid" value="1"> Exam Fee Paid by Student?</label>
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
    $('#addModal').addClass('active');
}
function closeModal() {
    $('#addModal').removeClass('active');
}
</script>
<?= $this->endSection() ?>
