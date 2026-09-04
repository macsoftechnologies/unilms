<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Chart of Accounts<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Chart of Accounts</h1>
        <p class="header-subtitle">Manage Income, Expense, Asset, and Liability heads.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    
    <div class="card">
        <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Add Account Head</h2>
        <form action="<?= base_url('org/accounts/save-head') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="head_id">
            <div class="form-group">
                <label>Head Name *</label>
                <input type="text" name="head_name" id="head_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Type *</label>
                <select name="head_type" id="head_type" class="form-control" required>
                    <option value="Income">Income</option>
                    <option value="Expense">Expense</option>
                    <option value="Asset">Asset</option>
                    <option value="Liability">Liability</option>
                </select>
            </div>
            <div class="form-group">
                <label>GL Code (Optional)</label>
                <input type="text" name="gl_code" id="gl_code" class="form-control">
            </div>
            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Save Head</button>
                <button type="button" class="btn" onclick="document.getElementById('head_id').value=''; this.form.reset();" style="background: #F3F4F6; border: 1px solid var(--border-color);">Clear</button>
            </div>
        </form>
    </div>
    
    <div class="card">
        <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Account Heads List</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>GL Code</th>
                    <th>Head Name</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($heads as $h): ?>
                    <tr>
                        <td><?= esc($h['gl_code']) ?></td>
                        <td style="font-weight: 600;"><?= esc($h['head_name']) ?></td>
                        <td>
                            <?php if($h['head_type'] == 'Income'): ?>
                                <span style="background: #D1FAE5; color: #065F46; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">INCOME</span>
                            <?php elseif($h['head_type'] == 'Expense'): ?>
                                <span style="background: #FEE2E2; color: #991B1B; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">EXPENSE</span>
                            <?php else: ?>
                                <span style="background: #E5E7EB; color: #374151; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;"><?= strtoupper(esc($h['head_type'])) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button type="button" class="btn" style="background: #F3F4F6; border: 1px solid var(--border-color); padding: 4px 8px; font-size: 12px;" onclick="editHead(<?= htmlspecialchars(json_encode($h)) ?>)">Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($heads)): ?>
                    <tr><td colspan="4" style="text-align: center;">No account heads found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
function editHead(data) {
    document.getElementById('head_id').value = data.id;
    document.getElementById('head_name').value = data.head_name;
    document.getElementById('head_type').value = data.head_type;
    document.getElementById('gl_code').value = data.gl_code;
}
</script>

<?= $this->endSection() ?>
