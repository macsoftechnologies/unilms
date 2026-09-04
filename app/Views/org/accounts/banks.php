<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Bank & Cash Accounts<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Bank & Cash Accounts</h1>
        <p class="header-subtitle">Manage organization bank accounts and cash-in-hand ledgers.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    
    <div class="card">
        <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Add Account</h2>
        <form action="<?= base_url('org/accounts/save-bank') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="acc_id">
            <div class="form-group">
                <label>Account Name (e.g. Petty Cash, Main HDFC) *</label>
                <input type="text" name="account_name" id="account_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Type *</label>
                <select name="type" id="acc_type" class="form-control" required>
                    <option value="Bank">Bank Account</option>
                    <option value="Cash">Cash in Hand</option>
                </select>
            </div>
            <div class="form-group">
                <label>Account Number (For Banks)</label>
                <input type="text" name="account_no" id="account_no" class="form-control">
            </div>
            <div class="form-group">
                <label>IFSC Code (For Banks)</label>
                <input type="text" name="ifsc_code" id="ifsc_code" class="form-control">
            </div>
            <div class="form-group" id="opening_div">
                <label>Opening Balance (₹) *</label>
                <input type="number" min="0" step="0.01" name="opening_balance" id="opening_balance" class="form-control" value="0.00" required>
                <small style="color: var(--text-muted);">Can only be set upon creation.</small>
            </div>
            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Save Account</button>
                <button type="button" class="btn" onclick="resetForm()" style="background: #F3F4F6; border: 1px solid var(--border-color);">Clear</button>
            </div>
        </form>
    </div>
    
    <div class="card">
        <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Accounts List</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Account Name</th>
                    <th>Type</th>
                    <th>A/C No</th>
                    <th>Current Balance (₹)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($banks as $b): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= esc($b['account_name']) ?></td>
                        <td>
                            <?php if($b['type'] == 'Cash'): ?>
                                <span style="background: #FEF3C7; color: #92400E; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;"><i class="fa-solid fa-wallet"></i> CASH</span>
                            <?php else: ?>
                                <span style="background: #DBEAFE; color: #1E40AF; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;"><i class="fa-solid fa-building-columns"></i> BANK</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($b['account_no']) ?></td>
                        <td style="font-weight: bold; font-size: 16px; color: <?= $b['current_balance'] < 0 ? 'red' : 'green' ?>;">
                            <?= number_format($b['current_balance'], 2) ?>
                        </td>
                        <td>
                            <button type="button" class="btn" style="background: #F3F4F6; border: 1px solid var(--border-color); padding: 4px 8px; font-size: 12px;" onclick="editAcc(<?= htmlspecialchars(json_encode($b)) ?>)">Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($banks)): ?>
                    <tr><td colspan="5" style="text-align: center;">No accounts found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
function editAcc(data) {
    document.getElementById('acc_id').value = data.id;
    document.getElementById('account_name').value = data.account_name;
    document.getElementById('acc_type').value = data.type;
    document.getElementById('account_no').value = data.account_no;
    document.getElementById('ifsc_code').value = data.ifsc_code;
    
    // Hide opening balance when editing
    document.getElementById('opening_div').style.display = 'none';
    document.getElementById('opening_balance').removeAttribute('required');
}

function resetForm() {
    document.getElementById('acc_id').value = '';
    document.querySelector('form').reset();
    document.getElementById('opening_div').style.display = 'block';
    document.getElementById('opening_balance').setAttribute('required', 'required');
}
</script>

<?= $this->endSection() ?>
