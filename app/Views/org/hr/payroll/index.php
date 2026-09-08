<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Payroll & Payslips<?= $this->endSection() ?>
<?= $this->section('content') ?>

<?php if($is_admin): ?>
<div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 24px;">
    <h3 style="margin-top: 0; margin-bottom: 16px;">Generate Payslip</h3>
    <form action="<?= base_url('org/hr/payroll/generate') ?>" method="POST" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; align-items: end;">
        <?= csrf_field() ?>
        
        <div style="grid-column: span 2;">
            <label class="form-label" style="font-size: 12px; font-weight: bold; color: var(--text-muted);">Employee</label>
            <select name="employee_id" class="form-control" required>
                <option value="">Select Employee...</option>
                <?php foreach($employees as $e): ?>
                    <option value="<?= $e['id'] ?>"><?= esc($e['full_name']) ?> (<?= esc($e['employee_code']) ?>) - Base: $<?= number_format($e['base_salary'], 2) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="form-label" style="font-size: 12px; font-weight: bold; color: var(--text-muted);">Month</label>
            <select name="month" class="form-control" required>
                <?php 
                    $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                    foreach($months as $m) echo "<option value=\"$m\">$m</option>";
                ?>
            </select>
        </div>

        <div>
            <label class="form-label" style="font-size: 12px; font-weight: bold; color: var(--text-muted);">Year</label>
            <input type="number" min="0" name="year" class="form-control" value="<?= date('Y') ?>" required>
        </div>

        <div>
            <label class="form-label" style="font-size: 12px; font-weight: bold; color: var(--text-muted);">Additional Allowances ($)</label>
            <input type="number" min="0" step="0.01" name="allowances" class="form-control" placeholder="0.00">
        </div>

        <div>
            <label class="form-label" style="font-size: 12px; font-weight: bold; color: var(--text-muted);">Deductions (₹)</label>
            <input type="number" min="0" step="0.01" name="deductions" class="form-control" placeholder="0.00">
        </div>

        <div style="grid-column: span 2;">
            <button class="btn btn-primary" style="width: 100%;"><i class="fa-solid fa-file-invoice-dollar"></i> Generate Payslip</button>
        </div>

    </form>
</div>
<?php endif; ?>

<div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
    <h3 style="margin-top: 0;"><?= $is_admin ? 'Organization Payroll History' : 'My Payslips' ?></h3>
    
    <table style="width: 100%; border-collapse: collapse; margin-top: 16px;">
        <thead>
            <tr style="border-bottom: 2px solid var(--border-color);">
                <?php if($is_admin): ?>
                <th style="padding: 12px 8px; text-align: left;">Employee</th>
                <th style="padding: 12px 8px; text-align: left;">Department</th>
                <?php endif; ?>
                <th style="padding: 12px 8px; text-align: left;">Period</th>
                <th style="padding: 12px 8px; text-align: right;">Base</th>
                <th style="padding: 12px 8px; text-align: right;">Net Salary</th>
                <th style="padding: 12px 8px; text-align: center;">Status</th>
                <th style="padding: 12px 8px; text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($payslips as $p): ?>
            <tr style="border-bottom: 1px solid var(--border-color);">
                <?php if($is_admin): ?>
                <td style="padding: 12px 8px; font-weight: 600;"><?= esc($p['full_name']) ?> <span style="font-size: 11px; color: var(--text-muted);">(<?= esc($p['employee_code']) ?>)</span></td>
                <td style="padding: 12px 8px; font-size: 13px; color: var(--text-muted);"><?= esc($p['dept_name']) ?></td>
                <?php endif; ?>
                
                <td style="padding: 12px 8px; font-weight: bold;"><?= esc($p['month'].' '.$p['year']) ?></td>
                <td style="padding: 12px 8px; text-align: right; color: var(--text-muted);">₹<?= number_format($p['base_salary'], 2) ?></td>
                <td style="padding: 12px 8px; text-align: right; font-weight: bold; color: var(--primary);">₹<?= number_format($p['net_salary'], 2) ?></td>
                
                <td style="padding: 12px 8px; text-align: center;">
                    <?php if($p['status'] == 'Draft'): ?>
                        <span style="background: rgba(245, 158, 11, 0.1); color: var(--warning); padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">Draft</span>
                    <?php else: ?>
                        <span style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">Paid on <?= date('d/m/Y', strtotime($p['payment_date'])) ?></span>
                    <?php endif; ?>
                </td>
                
                <td style="padding: 12px 8px; text-align: right;">
                    <?php if($is_admin && $p['status'] == 'Draft'): ?>
                        <form action="<?= base_url('org/hr/payroll/mark_paid/' . ($p['uuid'] ?? $p['id'])) ?>" method="POST" style="display: inline;">
                            <?= csrf_field() ?>
                            <button class="btn btn-outline" style="padding: 4px 10px; font-size: 12px; color: var(--success); border-color: var(--success);">Mark Paid</button>
                        </form>
                    <?php endif; ?>
                    <button class="btn btn-outline" style="padding: 4px 10px; font-size: 12px;" onclick="alert('PDF generation not implemented in demo')"><i class="fa-solid fa-download"></i></button>
                </td>
            </tr>
            <?php endforeach; ?>
            
            <?php if(empty($payslips)): ?>
            <tr><td colspan="<?= $is_admin ? 7 : 5 ?>" style="padding: 32px; text-align: center; color: var(--text-muted);">No payslips found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
