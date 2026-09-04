<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Transactions Ledger<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Transactions Ledger</h1>
        <p class="header-subtitle">Record and track income, expenditures, and transfers.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 3fr; gap: 24px;">
    
    <div class="card">
        <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">New Transaction</h2>
        <form action="<?= base_url('org/accounts/save-transaction') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Date *</label>
                <input type="date" name="transaction_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="form-group">
                <label>Type *</label>
                <select name="type" id="trans_type" class="form-control" required onchange="toggleHead()">
                    <option value="Income">Income (Sundry Receipt)</option>
                    <option value="Expense">Expense (Payment)</option>
                    <option value="Deposit">Bank Deposit (Cash to Bank)</option>
                    <option value="Withdrawal">Cash Withdrawal (Bank to Cash)</option>
                </select>
            </div>
            <div class="form-group" id="head_div">
                <label>Account Head</label>
                <select name="head_id" class="form-control">
                    <option value="">-- Select Head --</option>
                    <?php foreach($heads as $h): ?>
                        <option value="<?= $h['id'] ?>"><?= esc($h['head_name']) ?> (<?= esc($h['head_type']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Paying / Receiving Account *</label>
                <select name="bank_account_id" class="form-control" required>
                    <?php foreach($banks as $b): ?>
                        <option value="<?= $b['id'] ?>"><?= esc($b['account_name']) ?> (Bal: <?= number_format($b['current_balance'],2) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Amount (₹) *</label>
                <input type="number" min="0" step="0.01" name="amount" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Party Name / Payee</label>
                <input type="text" name="party_name" class="form-control">
            </div>
            <div class="form-group">
                <label>Reference / Cheque No.</label>
                <input type="text" name="reference_no" class="form-control">
            </div>
            <div class="form-group">
                <label>Description / Narration</label>
                <textarea name="description" class="form-control" style="height: 60px;"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Record Transaction</button>
        </form>
    </div>
    
    <div class="card">
        <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Recent Transactions (Last 100)</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Head / Ref</th>
                    <th>Account (Bank/Cash)</th>
                    <th>Debit (Dr.)</th>
                    <th>Credit (Cr.)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($transactions as $t): ?>
                    <tr>
                        <td style="white-space: nowrap;"><?= date('d/m/Y', strtotime($t['transaction_date'])) ?></td>
                        <td>
                            <?php if($t['type'] == 'Income'): ?>
                                <span style="color: green; font-weight: 500;">INCOME</span>
                            <?php elseif($t['type'] == 'Expense'): ?>
                                <span style="color: red; font-weight: 500;">EXPENSE</span>
                            <?php else: ?>
                                <span style="color: blue; font-weight: 500;"><?= strtoupper(esc($t['type'])) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-weight: 600;"><?= esc($t['head_name'] ?? 'Transfer') ?></div>
                            <div style="font-size: 11px; color: var(--text-muted);"><?= esc($t['party_name']) ?> <?= $t['reference_no'] ? '['.esc($t['reference_no']).']' : '' ?></div>
                        </td>
                        <td><?= esc($t['account_name']) ?></td>
                        
                        <!-- If transaction_type is Debit, money leaves bank (Expense, Withdrawal) -->
                        <td style="color: red; font-weight: bold;">
                            <?= $t['transaction_type'] == 'Debit' ? number_format($t['amount'], 2) : '-' ?>
                        </td>
                        <!-- If transaction_type is Credit, money enters bank (Income, Deposit) -->
                        <td style="color: green; font-weight: bold;">
                            <?= $t['transaction_type'] == 'Credit' ? number_format($t['amount'], 2) : '-' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($transactions)): ?>
                    <tr><td colspan="6" style="text-align: center;">No transactions found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
function toggleHead() {
    var type = document.getElementById('trans_type').value;
    if(type === 'Deposit' || type === 'Withdrawal') {
        document.getElementById('head_div').style.display = 'none';
    } else {
        document.getElementById('head_div').style.display = 'block';
    }
}
</script>

<?= $this->endSection() ?>
