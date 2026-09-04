<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Issue Official Certificate<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <div>
            <h2><i class="fa-solid fa-print"></i> Issue Official Certificate</h2>
            <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 13px;">Select a student and certificate template to generate, preview, and print an official university certificate.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="<?= base_url('org/certificates/templates') ?>" class="btn btn-outline"><i class="fa-solid fa-stamp"></i> Manage Templates</a>
        </div>
    </div>

    <div class="card" style="max-width: 600px; padding: 24px;">
        <div class="form-group" style="margin-bottom: 16px;">
            <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">1. Select Certificate Template *</label>
            <select id="sel_template" class="form-control" style="height: 42px;">
                <option value="">-- Choose Template --</option>
                <?php foreach($templates as $t): ?>
                    <option value="<?= $t['id'] ?>"><?= esc($t['name']) ?> (<?= esc($t['category']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">2. Select Student Recipient *</label>
            <select id="sel_student" class="form-control" style="height: 42px;">
                <option value="">-- Choose Student --</option>
                <?php foreach($students as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= esc($s['roll_number']) ?> - <?= esc($s['first_name'] . ' ' . $s['last_name']) ?> (<?= esc($s['program_name']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="button" class="btn btn-primary" onclick="launchPrint()" style="width: 100%; height: 42px; font-size: 15px;">
            <i class="fa-solid fa-file-invoice me-2"></i> Generate & Print Certificate
        </button>
    </div>
</section>

<script>
    function launchPrint() {
        const tId = document.getElementById('sel_template').value;
        const sId = document.getElementById('sel_student').value;
        if (!tId || !sId) {
            alert('Please select both a template and a student.');
            return;
        }
        window.open('<?= base_url('org/certificates/print/') ?>' + tId + '/' + sId, '_blank');
    }
</script>
<?= $this->endSection() ?>
