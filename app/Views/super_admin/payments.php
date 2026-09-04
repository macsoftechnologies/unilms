<?= $this->extend('super_admin/layout') ?>
<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Payment History</h2>
        <a href="<?= base_url('superadmin/export_payments') ?>" class="btn btn-outline" style="border:1px solid var(--border-color); padding:8px 16px; border-radius:6px; text-decoration:none; color:var(--text-main);"><i class="fa-solid fa-download"></i> Export CSV</a>
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Invoice ID</th>
                    <th>Organization</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($payments)): ?>
                    <?php foreach($payments as $payment): ?>
                    <tr>
                        <td><?= esc($payment['invoice_id']) ?></td>
                        <td><?= esc($payment['org_name']) ?></td>
                        <td>₹<?= number_format($payment['amount'], 2) ?></td>
                        <td><?= date('d/m/Y', strtotime($payment['payment_date'])) ?></td>
                        <td><span class="badge badge-success">Paid</span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">No payments found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if($pager): ?>
            <?= $pager->links() ?>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>
