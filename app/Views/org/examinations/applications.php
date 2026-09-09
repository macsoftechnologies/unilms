<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Exam Applications<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div>
        <h1 class="header-title">Student Exam Applications</h1>
        <p class="header-subtitle">Review, register, and approve exam applications from students.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary" onclick="openRegisterModal()" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); border: none; padding: 10px 18px; border-radius: 8px; color: #fff; font-weight: 700; cursor: pointer;">
            <i class="fa-solid fa-plus-circle me-1"></i> Register Student Exam
        </button>
    </div>
</div>

<div class="card" style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 12px; padding: 20px;">
    <table class="data-table">
        <thead>
            <tr>
                <th>Student</th>
                <th>Roll No</th>
                <th>Exam Applied For</th>
                <th>Application Date</th>
                <th>Fee Paid</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($applications as $app): ?>
                <tr>
                    <td><?= esc($app['first_name'] . ' ' . $app['last_name']) ?></td>
                    <td><?= esc($app['roll_number']) ?></td>
                    <td style="font-weight: 600;"><?= esc($app['exam_name']) ?></td>
                    <td><?= date('d/m/Y', strtotime($app['created_at'])) ?></td>
                    <td>
                        <?php if($app['fee_paid']): ?>
                            <span style="color: green; font-weight: bold;"><i class="fa-solid fa-check-circle"></i> Yes</span>
                        <?php else: ?>
                            <span style="color: red; font-weight: bold;"><i class="fa-solid fa-xmark-circle"></i> No</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                            $bg = '#e2e8f0';
                            $color = '#334155';
                            if ($app['status'] == 'Approved') { $bg = '#dcfce7'; $color = '#166534'; }
                            if ($app['status'] == 'Rejected') { $bg = '#fee2e2'; $color = '#991b1b'; }
                        ?>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: <?= $bg ?>; color: <?= $color ?>;">
                            <?= esc($app['status']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if($app['status'] == 'Applied'): ?>
                        <form action="<?= base_url('org/examinations/update-application-status/' . ($app['uuid'] ?? $app['id'])) ?>" method="POST" style="display:inline-block;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="Approved">
                            <button type="submit" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px; color: green; border-color: green;">Approve</button>
                        </form>
                        <form action="<?= base_url('org/examinations/update-application-status/' . ($app['uuid'] ?? $app['id'])) ?>" method="POST" style="display:inline-block;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="Rejected">
                            <button type="submit" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px; color: red; border-color: red;">Reject</button>
                        </form>
                        <?php else: ?>
                            <span style="color: var(--text-secondary); font-size: 12.5px;"><i class="fa-solid fa-check-double text-success me-1"></i> Processed</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($applications)): ?>
                <tr><td colspan="7" style="text-align:center; padding: 30px; color: var(--text-secondary);">No applications found. Use 'Register Student Exam' to enroll students for upcoming exams.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal for Registering Exam Application -->
<div class="modal-overlay" id="registerExamModal" style="display:none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div class="modal-card" style="background: #fff; width: 100%; max-width: 480px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden;">
        <div style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); color: #fff; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 16px; font-weight: 700;">Register Student Exam Application</h3>
            <button type="button" onclick="closeRegisterModal()" style="background: none; border: none; color: #fff; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <form action="<?= base_url('org/examinations/save-application') ?>" method="POST" style="padding: 20px;">
            <?= csrf_field() ?>
            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-size: 12.5px; font-weight: 700;">Select Student <span class="text-danger">*</span></label>
                <select name="student_id" class="form-control" required style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid var(--border-color);">
                    <option value="">-- Choose Student --</option>
                    <?php if(!empty($students)): ?>
                        <?php foreach($students as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= esc($s['first_name'] . ' ' . $s['last_name']) ?> (<?= esc($s['roll_number']) ?>)</option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-size: 12.5px; font-weight: 700;">Select Examination <span class="text-danger">*</span></label>
                <select name="exam_id" class="form-control" required style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid var(--border-color);">
                    <option value="">-- Choose Exam --</option>
                    <?php if(!empty($exams)): ?>
                        <?php foreach($exams as $e): ?>
                            <option value="<?= $e['id'] ?>"><?= esc($e['name']) ?> (<?= esc($e['type'] ?? 'Semester Exam') ?>)</option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-size: 12.5px; font-weight: 700;">Application Status</label>
                <select name="status" class="form-control" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid var(--border-color);">
                    <option value="Approved" selected>Approved</option>
                    <option value="Applied">Applied</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                    <input type="checkbox" name="fee_paid" value="1" checked> Exam Fee Paid
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeRegisterModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background: #7C3AED; color: #fff; border: none; padding: 8px 18px; border-radius: 6px; font-weight: 700;">Register Application</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRegisterModal() {
    document.getElementById('registerExamModal').style.display = 'flex';
}
function closeRegisterModal() {
    document.getElementById('registerExamModal').style.display = 'none';
}
</script>

<?= $this->endSection() ?>
