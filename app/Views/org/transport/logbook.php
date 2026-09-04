<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Transport Logbook<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div class="title-area">
        <h1>Transport Logbook</h1>
        <p>Log fuel, maintenance, and toll expenses for vehicles</p>
    </div>
    <div class="action-area">
        <button type="button" class="btn btn-primary" onclick="openModal('addLogModal')">
            <i class="fa-solid fa-plus"></i> Add Log Entry
        </button>
    </div>
</div>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Vehicle No</th>
                <th>Expense Head</th>
                <th>Amount</th>
                <th>Description</th>
                <th style="width: 120px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($logs as $l): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($l['expense_date'])) ?></td>
                    <td style="font-weight: 500; color: var(--primary);"><?= esc($l['vehicle_no']) ?></td>
                    <td><span class="badge" style="background: rgba(107,114,128,0.1); color: var(--text-main);"><?= esc($l['expense_head']) ?></span></td>
                    <td style="font-weight: 600; color: var(--danger);">-₹<?= number_format($l['amount'], 2) ?></td>
                    <td style="font-size: 13px; color: var(--text-muted);"><?= esc($l['description']) ?></td>
                    <td>
                        <button class="btn btn-outline" style="padding: 4px 8px; font-size: 12px;" onclick="editLog(<?= htmlspecialchars(json_encode($l)) ?>)">Edit</button>
                        <a href="<?= base_url('org/transport/delete_logbook/'.$l['id']) ?>" class="btn btn-outline" style="padding: 4px 8px; font-size: 12px; color: var(--danger); border-color: var(--danger);" onclick="return confirm('Delete this log entry?')">Del</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($logs)): ?>
                <tr><td colspan="6" style="text-align: center;">No logbook entries found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="addLogModal" class="modal" style="display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div class="modal-content card" style="width: 500px; max-width: 90%;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
            <h2 id="modalTitle" style="margin: 0;">Add Log Entry</h2>
            <button type="button" style="background: none; border: none; font-size: 20px; cursor: pointer;" onclick="closeModal('addLogModal')">&times;</button>
        </div>
        <form action="<?= base_url('org/transport/logbook') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="log_id" value="">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="expense_date" id="expense_date" class="form-control" required value="<?= date('Y-m-d') ?>">
                </div>

                <div class="form-group">
                    <label>Vehicle</label>
                    <select name="vehicle_id" id="vehicle_id" class="form-control" required>
                        <option value="">Select Vehicle</option>
                        <?php foreach($vehicles as $v): ?>
                            <option value="<?= $v['id'] ?>"><?= esc($v['vehicle_no']) ?> (<?= esc($v['type']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Expense Head</label>
                    <select name="expense_head" id="expense_head" class="form-control" required>
                        <option value="Fuel">Fuel</option>
                        <option value="Maintenance / Repair">Maintenance / Repair</option>
                        <option value="Toll Tax">Toll Tax</option>
                        <option value="Driver Salary / Allowance">Driver Salary / Allowance</option>
                        <option value="Insurance / Permit">Insurance / Permit</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Amount ($)</label>
                    <input type="number" min="0" step="0.01" name="amount" id="amount" class="form-control" required>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label>Description (Optional)</label>
                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="e.g. Regular servicing at dealership"></textarea>
                </div>
            </div>

            <div style="text-align: right; margin-top: 24px;">
                <button type="button" class="btn btn-outline" onclick="closeModal('addLogModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Entry</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
    document.getElementById('log_id').value = '';
    document.getElementById('modalTitle').innerText = 'Add Log Entry';
    document.querySelector('#addLogModal form').reset();
    document.getElementById('expense_date').value = '<?= date('Y-m-d') ?>';
}
function editLog(l) {
    document.getElementById('log_id').value = l.id;
    document.getElementById('expense_date').value = l.expense_date;
    document.getElementById('vehicle_id').value = l.vehicle_id;
    document.getElementById('expense_head').value = l.expense_head;
    document.getElementById('amount').value = l.amount;
    document.getElementById('description').value = l.description;
    document.getElementById('modalTitle').innerText = 'Edit Log Entry';
    openModal('addLogModal');
}
</script>

<?= $this->endSection() ?>
