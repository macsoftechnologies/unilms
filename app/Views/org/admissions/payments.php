<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Payments Ledger<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Payments Ledger</h2>
        <button class="btn btn-primary" onclick="openPayModal()"><i class="fa-solid fa-plus"></i> Record Manual Payment</button>
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
                <tr><td colspan="7">No payment records found. Record a manual payment above to add.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Record Manual Payment Modal -->
<div class="drawer-overlay" id="payModal">
    <div class="drawer-content" style="max-width: 520px;">
        <form action="<?= base_url('org/admissions/payments/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header" style="background: linear-gradient(135deg, #10B981 0%, #047857 100%); color: #fff; padding: 18px 22px;">
                <h3 style="margin: 0; font-size: 17px; color: #fff;"><i class="fa-solid fa-receipt me-2"></i> Record Admission Payment</h3>
                <button type="button" class="btn-close" onclick="closePayModal()" style="color: #fff;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body" style="padding: 22px;">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 13px;">Select Admission Offer / Student <span class="text-danger">*</span></label>
                    <select name="offer_id" id="pay_offer_id" class="form-control" required onchange="updatePayAmount(this)">
                        <option value="">-- Choose Candidate --</option>
                        <?php if(!empty($offers)): foreach($offers as $o): ?>
                            <option value="<?= $o['id'] ?>" data-amount="<?= $o['fee_amount'] ?>">
                                <?= esc($o['adm_number']) ?> — <?= esc($o['full_name']) ?> (Due: ₹<?= number_format($o['fee_amount'], 2) ?>)
                            </option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 13px;">Amount Paid (₹) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="1" name="amount" id="pay_amount" class="form-control" required placeholder="e.g. 50000">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 13px;">Payment Mode <span class="text-danger">*</span></label>
                        <select name="payment_mode" class="form-control" required>
                            <option value="Cash">Cash</option>
                            <option value="UPI / QR">UPI / QR</option>
                            <option value="Bank Transfer">Bank Transfer / NEFT</option>
                            <option value="Cheque / DD">Cheque / Demand Draft</option>
                            <option value="Debit/Credit Card">Debit / Credit Card</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 13px;">Reference / Txn ID</label>
                        <input type="text" name="gateway_reference" class="form-control" placeholder="e.g. UTR123456 / CHQ-998">
                    </div>
                </div>
            </div>
            <div class="drawer-footer" style="padding: 16px 22px; border-top: 1px solid var(--border-color); display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn btn-outline" onclick="closePayModal()">Cancel</button>
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-check me-1"></i> Save Payment & Issue Receipt</button>
            </div>
        </form>
    </div>
</div>

<script>
function openPayModal() {
    document.getElementById('payModal').classList.add('active');
}
function closePayModal() {
    document.getElementById('payModal').classList.remove('active');
}
function updatePayAmount(select) {
    const selected = select.options[select.selectedIndex];
    const amt = selected.getAttribute('data-amount');
    if (amt) {
        document.getElementById('pay_amount').value = amt;
    }
}
</script>
<?= $this->endSection() ?>
