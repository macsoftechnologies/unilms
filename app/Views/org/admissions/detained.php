<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Detained Students<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Detained Students</h1>
        <p class="header-subtitle">Track and manage students who have been detained due to attendance or academic shortfalls.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <div>
        <div class="card">
            <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Add Detained Record</h2>
            <form action="<?= base_url('org/admissions/save-detained') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="det_id">
                
                <div class="form-group">
                    <label>Student</label>
                    <select name="student_id" id="det_student" class="form-control" required>
                        <option value="">-- Select Student --</option>
                        <?php foreach($students as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= esc($s['first_name'] . ' ' . $s['last_name']) ?> (<?= esc($s['roll_number']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Date Detained</label>
                    <input type="date" name="date_detained" id="det_date" class="form-control" required value="<?= date('Y-m-d') ?>">
                </div>
                
                <div class="form-group">
                    <label>Reason</label>
                    <textarea name="reason" id="det_reason" class="form-control" required placeholder="e.g. Shortage of attendance"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="det_status" class="form-control">
                        <option value="active">Active (Currently Detained)</option>
                        <option value="resolved">Resolved (Re-joined)</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Save Record</button>
            </form>
        </div>
    </div>
    
    <div>
        <div class="card">
            <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Detained Student List</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Roll No</th>
                        <th>Date Detained</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($detained as $d): ?>
                        <tr>
                            <td><?= esc($d['first_name'] . ' ' . $d['last_name']) ?></td>
                            <td><?= esc($d['roll_number']) ?></td>
                            <td><?= date('d/m/Y', strtotime($d['date_detained'])) ?></td>
                            <td><?= esc($d['reason']) ?></td>
                            <td>
                                <?php if($d['status'] == 'active'): ?>
                                    <span style="color: red; font-weight: bold;">Detained</span>
                                <?php else: ?>
                                    <span style="color: green; font-weight: bold;">Resolved</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px;" onclick="editDetained(<?= $d['id'] ?>, <?= $d['student_id'] ?>, '<?= $d['date_detained'] ?>', '<?= esc($d['reason'], 'js') ?>', '<?= $d['status'] ?>')">Edit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($detained)): ?>
                        <tr><td colspan="6" style="text-align:center;">No detained records found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function editDetained(id, studentId, dateDetained, reason, status) {
    document.getElementById('det_id').value = id;
    document.getElementById('det_student').value = studentId;
    document.getElementById('det_date').value = dateDetained;
    document.getElementById('det_reason').value = reason;
    document.getElementById('det_status').value = status;
}
</script>

<?= $this->endSection() ?>
