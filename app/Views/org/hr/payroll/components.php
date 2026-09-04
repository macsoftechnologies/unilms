<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Salary Components<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <div>
            <h2><i class="fa-solid fa-calculator"></i> Salary Components (Earnings & Deductions)</h2>
            <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 13px;">Configure pay items like Basic Salary, Dearness Allowance (DA), HRA, Provident Fund (PF), ESI, and Tax Deductions.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-primary" onclick="openComponentModal()"><i class="fa-solid fa-plus"></i> Add Pay Component</button>
            <a href="<?= base_url('org/payroll/structures') ?>" class="btn btn-outline"><i class="fa-solid fa-network-wired"></i> Salary Structures</a>
            <a href="<?= base_url('org/hr/employees') ?>" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> HR Hub</a>
        </div>
    </div>

    <!-- Components Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Component Name</th>
                    <th>Code</th>
                    <th>Classification Type</th>
                    <th>Calculation Mode</th>
                    <th>Default / Base Value</th>
                    <th>Taxable</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($components)): foreach($components as $c): ?>
                <tr>
                    <td><strong><?= esc($c['name']) ?></strong></td>
                    <td><span class="badge" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; font-weight: 700;"><?= esc($c['code']) ?></span></td>
                    <td>
                        <span class="badge" style="background: <?= $c['type'] === 'Earning' ? 'rgba(16, 185, 129, 0.12)' : 'rgba(239, 68, 68, 0.12)' ?>; color: <?= $c['type'] === 'Earning' ? '#10b981' : '#ef4444' ?>; font-weight: 600;">
                            <?= esc($c['type']) ?>
                        </span>
                    </td>
                    <td>
                        <?= esc($c['calculation_type']) ?>
                        <?php if($c['calculation_type'] === 'Percentage' && !empty($c['percentage_of'])): ?>
                            <span style="font-size: 11px; color: var(--text-muted);">(% of <?= esc($c['percentage_of']) ?>)</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?= $c['calculation_type'] === 'Percentage' ? $c['default_amount'] . '%' : '₹' . number_format($c['default_amount'], 2) ?></strong>
                    </td>
                    <td>
                        <?= $c['is_taxable'] ? '<span class="badge" style="background: #10b98115; color: #10b981;">Yes</span>' : '<span style="color: var(--text-muted); font-size: 12px;">No</span>' ?>
                    </td>
                    <td>
                        <a href="<?= base_url('org/payroll/components/delete/' . $c['id']) ?>" class="btn-icon text-danger" onclick="return confirm('Delete this component?')" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="7" style="text-align: center; padding: 30px; color: var(--text-muted);">No salary components defined yet. Click "Add Pay Component" to create Basic, DA, HRA, PF.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Component Modal -->
<div class="drawer-overlay" id="compModal" style="display: none;">
    <div class="drawer-content" style="max-width: 480px;">
        <form action="<?= base_url('org/payroll/components/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 700;"><i class="fa-solid fa-calculator me-2" style="color: #4f46e5;"></i> Add Pay Component</h3>
                <button type="button" class="btn-close" onclick="closeComponentModal()" style="background: none; border: none; font-size: 18px; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 20px;">
                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Component Name *</label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Basic Salary, House Rent Allowance (HRA)">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Code *</label>
                        <input type="text" name="code" class="form-control" required placeholder="BASIC, HRA, PF">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Type *</label>
                        <select name="type" class="form-control" required>
                            <option value="Earning">Earning (+)</option>
                            <option value="Deduction">Deduction (-)</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Calculation Mode</label>
                        <select name="calculation_type" class="form-control">
                            <option value="Fixed">Fixed Amount</option>
                            <option value="Percentage">Percentage (%)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Default Value</label>
                        <input type="number" step="0.01" name="default_amount" class="form-control" placeholder="Amount or %">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Percentage Calculated On</label>
                    <input type="text" name="percentage_of" class="form-control" placeholder="BASIC (if calculation is Percentage)">
                </div>

                <div class="form-group">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_taxable" value="1" checked>
                        Subject to Income Tax Calculation (Taxable)
                    </label>
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeComponentModal()">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Save Component</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openComponentModal() { document.getElementById('compModal').style.display = 'flex'; }
    function closeComponentModal() { document.getElementById('compModal').style.display = 'none'; }
</script>
<?= $this->endSection() ?>
