<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Day Book<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Day Book</h1>
        <p class="header-subtitle">Daily transaction log for all accounts.</p>
    </div>
</div>

<div class="card" style="margin-bottom: 24px; padding: 16px;">
    <form action="<?= base_url('org/accounts/daybook') ?>" method="GET" style="display: flex; gap: 16px; align-items: flex-end;">
        <div class="form-group" style="margin-bottom: 0;">
            <label>Select Date</label>
            <input type="date" name="date" class="form-control" value="<?= esc($selected_date) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Generate Book</button>
    </form>
</div>

<div class="card">
    <h2 style="margin-top: 0; text-align: center; font-size: 20px;">Day Book for <?= date('d F Y', strtotime($selected_date)) ?></h2>
    
    <table class="data-table" style="margin-top: 24px;">
        <thead>
            <tr>
                <th style="width: 60px;">S.No</th>
                <th>Particulars (Narration)</th>
                <th>Account</th>
                <th>Type</th>
                <th style="text-align: right;">Receipts (Cr.)</th>
                <th style="text-align: right;">Payments (Dr.)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_cr = 0;
            $total_dr = 0;
            $i = 1;
            foreach($transactions as $t): 
                if($t['transaction_type'] == 'Credit') {
                    $cr = $t['amount'];
                    $dr = 0;
                    $total_cr += $cr;
                } else {
                    $cr = 0;
                    $dr = $t['amount'];
                    $total_dr += $dr;
                }
            ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td>
                        <strong><?= esc($t['head_name'] ?? 'Transfer / Contra') ?></strong><br>
                        <small style="color: var(--text-muted);"><?= esc($t['description']) ?> (<?= esc($t['party_name']) ?>)</small>
                    </td>
                    <td><?= esc($t['account_name']) ?></td>
                    <td><?= esc($t['type']) ?></td>
                    <td style="text-align: right; color: green; font-weight: 500;"><?= $cr > 0 ? number_format($cr, 2) : '' ?></td>
                    <td style="text-align: right; color: red; font-weight: 500;"><?= $dr > 0 ? number_format($dr, 2) : '' ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($transactions)): ?>
                <tr><td colspan="6" style="text-align: center; padding: 40px;">No transactions found for this date.</td></tr>
            <?php else: ?>
                <tr style="background: #F9FAFB; font-weight: bold; font-size: 16px;">
                    <td colspan="4" style="text-align: right; padding-right: 20px;">TOTALS</td>
                    <td style="text-align: right; color: green;">₹<?= number_format($total_cr, 2) ?></td>
                    <td style="text-align: right; color: red;">₹<?= number_format($total_dr, 2) ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
