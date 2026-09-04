<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Payments Ledger<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Payments Ledger</h2>
        <button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Record Manual Payment</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Receipt No.</th>
                    <th>App Number</th>
                    <th>Applicant Name</th>
                    <th>Amount Paid</th>
                    <th>Mode</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($payments)): foreach($payments as $payment): ?>
                <tr>
                    <td><strong><?= esc($payment['receipt_number']) ?: 'N/A' ?></strong></td>
                    <td><?= esc($payment['adm_number']) ?></td>
                    <td><?= esc($payment['full_name']) ?></td>
                    <td>₹<?= number_format($payment['amount'], 2) ?></td>
                    <td><?= esc($payment['payment_mode']) ?></td>
                    <td>
                        <span class="badge badge-success"><?= esc($payment['payment_status']) ?></span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($payment['created_at'])) ?></td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="7">No payment records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
