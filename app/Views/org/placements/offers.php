<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Job Offers<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Job Offers</h1>
        <p class="header-subtitle">Track placement offers and student acceptances.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 3fr; gap: 24px;">
    
    <div class="card">
        <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Log Offer</h2>
        <form action="<?= base_url('org/placements/save-offer') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Student *</label>
                <select name="student_id" class="form-control" required>
                    <option value="">-- Select Student --</option>
                    <?php foreach($students as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= esc($s['first_name']) ?> <?= esc($s['last_name']) ?> (<?= esc($s['roll_number']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Company *</label>
                <select name="company_id" class="form-control" required>
                    <option value="">-- Select Company --</option>
                    <?php foreach($companies as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= esc($c['company_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Job Role</label>
                <input type="text" name="job_role" class="form-control" required>
            </div>
            <div class="form-group">
                <label>CTC (₹ Total)</label>
                <input type="number" min="0" step="0.01" name="ctc" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Offer Date</label>
                <input type="date" name="offer_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="form-group">
                <label>Student Status</label>
                <select name="status" class="form-control">
                    <option value="Pending">Decision Pending</option>
                    <option value="Accepted">Accepted Offer</option>
                    <option value="Rejected">Rejected Offer</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Record Offer</button>
        </form>
    </div>
    
    <div class="card">
        <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">All Offers</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Student</th>
                    <th>Company</th>
                    <th>Role</th>
                    <th>CTC (LPA)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($offers as $o): ?>
                    <tr>
                        <td style="white-space: nowrap; font-size: 13px;"><?= date('d/m/Y', strtotime($o['offer_date'])) ?></td>
                        <td>
                            <strong><?= esc($o['first_name']) ?> <?= esc($o['last_name']) ?></strong><br>
                            <small style="color: var(--text-muted);"><?= esc($o['roll_number']) ?></small>
                        </td>
                        <td style="font-weight: 500; color: var(--primary);"><?= esc($o['company_name']) ?></td>
                        <td><?= esc($o['job_role']) ?></td>
                        <td style="font-weight: bold; color: green;"><?= number_format($o['ctc'] / 100000, 2) ?> L</td>
                        <td>
                            <?php if($o['status'] == 'Accepted'): ?>
                                <span style="background: #D1FAE5; color: #065F46; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">ACCEPTED</span>
                            <?php elseif($o['status'] == 'Rejected'): ?>
                                <span style="background: #FEE2E2; color: #991B1B; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">REJECTED</span>
                            <?php else: ?>
                                <span style="background: #FEF3C7; color: #92400E; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">PENDING</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($offers)): ?>
                    <tr><td colspan="6" style="text-align: center;">No job offers recorded.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?= $this->endSection() ?>
