<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Bank Details<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Bank Accounts</h1>
        <p class="header-subtitle">Configure organization bank details for fee collection and payroll.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <div>
        <div class="card">
            <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Add/Edit Bank Details</h2>
            <form action="<?= base_url('org/administration/save-bank') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="bank_id">
                
                <div class="form-group">
                    <label>Bank Name</label>
                    <input type="text" name="bank_name" id="bank_name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Account Name</label>
                    <input type="text" name="account_name" id="account_name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Account Number</label>
                    <input type="text" name="account_number" id="account_number" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>IFSC Code</label>
                    <input type="text" name="ifsc_code" id="ifsc_code" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Branch Name</label>
                    <input type="text" name="branch_name" id="branch_name" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_primary" id="is_primary" value="1"> 
                        Set as Primary Account
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Save Bank Details</button>
            </form>
        </div>
    </div>
    
    <div>
        <div class="card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Bank / Branch</th>
                        <th>Account Details</th>
                        <th>Primary</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($banks as $b): ?>
                        <tr>
                            <td><strong><?= esc($b['bank_name']) ?></strong><br><small><?= esc($b['branch_name']) ?></small></td>
                            <td><?= esc($b['account_name']) ?><br><?= esc($b['account_number']) ?><br><small><?= esc($b['ifsc_code']) ?></small></td>
                            <td>
                                <?php if($b['is_primary']): ?>
                                    <span style="color: green; font-weight: bold;"><i class="fa-solid fa-check"></i> Primary</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px;" onclick='editBank(<?= json_encode($b) ?>)'>Edit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function editBank(data) {
    document.getElementById('bank_id').value = data.id;
    document.getElementById('bank_name').value = data.bank_name;
    document.getElementById('account_name').value = data.account_name;
    document.getElementById('account_number').value = data.account_number;
    document.getElementById('ifsc_code').value = data.ifsc_code;
    document.getElementById('branch_name').value = data.branch_name;
    document.getElementById('is_primary').checked = (data.is_primary == 1);
}
</script>

<?= $this->endSection() ?>
