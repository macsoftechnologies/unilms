<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Scholarships<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Scholarship Tracking</h1>
        <p class="header-subtitle">Manage government and institutional scholarships for students.</p>
    </div>
</div>

<div class="card" style="margin-bottom: 24px;">
    <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Add Scholarship Entry</h2>
    <form action="<?= base_url('org/admissions/save-scholarship') ?>" method="POST" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; align-items: end;">
        <?= csrf_field() ?>
        
        <div class="form-group" style="margin: 0;">
            <label>Student</label>
            <select name="student_id" class="form-control" required>
                <option value="">-- Select Student --</option>
                <?php foreach($students as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= esc($s['first_name'] . ' ' . $s['last_name']) ?> (<?= esc($s['roll_number']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group" style="margin: 0;">
            <label>Scholarship Name</label>
            <input type="text" name="scholarship_name" class="form-control" required placeholder="e.g. Merit Scholarship">
        </div>
        
        <div class="form-group" style="margin: 0;">
            <label>Donor / Source</label>
            <input type="text" name="donor_name" class="form-control" placeholder="e.g. State Govt">
        </div>
        
        <div class="form-group" style="margin: 0;">
            <label>Amount (₹)</label>
            <input type="number" min="0" step="0.01" name="amount" class="form-control" required>
        </div>
        
        <div class="form-group" style="margin: 0;">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="applied">Applied</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="disbursed">Disbursed</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Save Entry</button>
    </form>
</div>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Roll No</th>
                <th>Scholarship Name</th>
                <th>Source</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($scholarships as $sc): ?>
                <tr>
                    <td><?= esc($sc['first_name'] . ' ' . $sc['last_name']) ?></td>
                    <td><?= esc($sc['roll_number']) ?></td>
                    <td><?= esc($sc['scholarship_name']) ?></td>
                    <td><?= esc($sc['donor_name']) ?></td>
                    <td>₹<?= esc($sc['amount']) ?></td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: #e2e8f0;">
                            <?= strtoupper($sc['status']) ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($scholarships)): ?>
                <tr><td colspan="6" style="text-align:center;">No scholarships recorded yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
