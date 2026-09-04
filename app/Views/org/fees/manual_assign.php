<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Manual Fee Assignment<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Manual Fee Assignment</h2>
        <p style="color: #666; font-size: 14px;">Manually assign a fee structure to a specific student (e.g. late admissions, transfers, special cases).</p>
    </div>
    
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); max-width: 600px;">
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" style="margin-bottom: 15px; padding: 10px; background: #fee; color: #c00; border-radius: 4px;"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success" style="margin-bottom: 15px; padding: 10px; background: #efe; color: #080; border-radius: 4px;"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('org/fee-config/save-manual-assign') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px;">Select Student</label>
                <select name="student_id" class="form-control" required style="width: 100%; padding: 10px;">
                    <option value="" disabled selected>-- Select Student --</option>
                    <?php foreach($students as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= esc($s['first_name'] . ' ' . $s['last_name'] . ' (' . $s['roll_number'] . ')') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px;">Select Fee Structure</label>
                <select name="fee_structure_id" class="form-control" required style="width: 100%; padding: 10px;">
                    <option value="" disabled selected>-- Select Fee Structure --</option>
                    <?php foreach($fee_structures as $fs): ?>
                        <option value="<?= $fs['id'] ?>">
                            <?= esc($fs['fee_type_name'] . ' - ' . $fs['program_name'] . ' (' . $fs['semester_name'] . ')') ?> - ₹<?= number_format($fs['amount'], 2) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary" style="padding: 10px 20px; border: none; border-radius: 4px; background: #0056b3; color: white; cursor: pointer;">Assign Fee Structure</button>
            </div>
        </form>
    </div>
</section>
<?= $this->endSection() ?>
