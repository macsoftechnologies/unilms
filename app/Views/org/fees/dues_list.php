<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Fee Dues & Collection<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Fee Dues List</h1>
        <p class="header-subtitle">View pending dues and collect payments.</p>
    </div>
</div>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Roll No</th>
                <th>Fee Type</th>
                <th>Total Due</th>
                <th>Paid</th>
                <th>Balance</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($dues as $d): ?>
                <tr>
                    <td><?= esc($d['first_name'] . ' ' . $d['last_name']) ?></td>
                    <td><?= esc($d['roll_number']) ?></td>
                    <td><?= esc($d['fee_type_name']) ?></td>
                    <td>₹<?= esc($d['total_amount']) ?></td>
                    <td style="color: green;">₹<?= esc($d['amount_paid']) ?></td>
                    <td style="color: red; font-weight: bold;">₹<?= esc($d['balance']) ?></td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; <?= $d['status'] == 'partial' ? 'background: #fff3cd; color: #856404;' : 'background: #f8d7da; color: #721c24;' ?>">
                            <?= strtoupper($d['status']) ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-primary" style="padding: 4px 10px; font-size: 12px;" onclick="openPayModal(<?= $d['id'] ?>, <?= $d['student_id'] ?>, <?= $d['balance'] ?>)">Pay</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($dues)): ?>
                <tr><td colspan="8" style="text-align:center;">No pending dues found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Simple Modal for Payment -->
<div id="payModal" style="display:none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 24px; border-radius: 8px; width: 400px; max-width: 90%;">
        <h2 style="margin-top: 0;">Collect Payment</h2>
        <form action="<?= base_url('org/fee-payments/pay') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="ledger_id" id="mod_ledger_id">
            <input type="hidden" name="student_id" id="mod_student_id">
            
            <div class="form-group">
                <label>Amount Paying (₹)</label>
                <input type="number" min="0" step="0.01" name="amount" id="mod_amount" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Payment Mode</label>
                <select name="mode" class="form-control" required>
                    <option value="cash">Cash</option>
                    <option value="online">Online/UPI</option>
                    <option value="dd">Demand Draft</option>
                    <option value="cheque">Cheque</option>
                </select>
            </div>
            <div class="form-group">
                <label>Bank Ref / Cheque No (Optional)</label>
                <input type="text" name="bank_ref" class="form-control">
            </div>
            <div class="form-group">
                <label>Remarks</label>
                <input type="text" name="remarks" class="form-control">
            </div>
            
            <div style="display: flex; gap: 10px; margin-top: 16px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Confirm Payment</button>
                <button type="button" class="btn btn-outline" style="flex: 1;" onclick="document.getElementById('payModal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openPayModal(ledgerId, studentId, balance) {
    document.getElementById('mod_ledger_id').value = ledgerId;
    document.getElementById('mod_student_id').value = studentId;
    document.getElementById('mod_amount').value = balance;
    document.getElementById('mod_amount').max = balance;
    document.getElementById('payModal').style.display = 'flex';
}
</script>

<?= $this->endSection() ?>
