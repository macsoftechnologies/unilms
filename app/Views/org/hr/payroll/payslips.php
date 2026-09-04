<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Payroll & Payslips<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <div>
            <h2><i class="fa-solid fa-file-invoice-dollar"></i> Monthly Payroll & Payslips</h2>
            <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 13px;">Review salary generation, bank disbursements, and generate official employee monthly payslips.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="<?= base_url('org/payroll/components') ?>" class="btn btn-outline"><i class="fa-solid fa-calculator"></i> Pay Components</a>
            <a href="<?= base_url('org/payroll/structures') ?>" class="btn btn-outline"><i class="fa-solid fa-network-wired"></i> Structures</a>
            <a href="<?= base_url('org/hr/employees') ?>" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Staff Directory</a>
        </div>
    </div>

    <!-- Month Filter -->
    <div class="card" style="padding: 14px 20px; margin-bottom: 20px; border-radius: 10px;">
        <form method="GET" action="<?= base_url('org/payroll/payslips') ?>" style="display: flex; gap: 12px; align-items: center;">
            <div>
                <label style="font-size: 12px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">Select Payroll Month</label>
                <input type="month" name="month" value="<?= esc($month) ?>" class="form-control" style="height: 38px;">
            </div>
            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary" style="height: 38px;"><i class="fa-solid fa-filter"></i> Load Month</button>
            </div>
        </form>
    </div>

    <!-- Payroll Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Emp. Code</th>
                    <th>Employee Name</th>
                    <th>Department & Role</th>
                    <th>Base Salary</th>
                    <th>Bank Account</th>
                    <th>Disbursement Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($employees)): foreach($employees as $e): ?>
                <tr>
                    <td><span class="badge" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; font-weight: 700;"><?= esc($e['employee_code'] ?? 'EMP-' . $e['id']) ?></span></td>
                    <td>
                        <strong><?= esc($e['full_name']) ?></strong>
                        <div style="font-size: 11px; color: var(--text-muted);"><?= esc($e['email']) ?></div>
                    </td>
                    <td>
                        <div><?= esc($e['designation_name'] ?? 'Faculty') ?></div>
                        <span style="font-size: 11px; color: var(--text-muted);"><?= esc($e['department_name'] ?? '') ?></span>
                    </td>
                    <td>
                        <strong style="color: #10b981;">₹<?= number_format($e['base_salary'] ?: 45000, 2) ?></strong>
                    </td>
                    <td>
                        <div><?= esc($e['bank_name'] ?: 'State Bank of India') ?></div>
                        <span style="font-size: 11px; color: var(--text-muted);">A/C: <?= esc($e['bank_account'] ?: '••••4912') ?></span>
                    </td>
                    <td>
                        <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: #10b981; font-weight: 600;">
                            <i class="fa-solid fa-check me-1"></i> Generated
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-outline" style="padding: 4px 10px; font-size: 12px;" onclick="viewPayslip('<?= esc($e['full_name']) ?>', '<?= esc($e['employee_code'] ?? 'EMP-'.$e['id']) ?>', '<?= esc($e['designation_name']) ?>', '<?= esc($e['department_name']) ?>', <?= $e['base_salary'] ?: 45000 ?>, '<?= esc($month) ?>')">
                            <i class="fa-solid fa-file-invoice me-1"></i> View Payslip
                        </button>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="7" style="text-align: center; padding: 30px; color: var(--text-muted);">No employee records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Payslip Modal -->
<div class="drawer-overlay" id="payslipModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; max-width: 550px; width: 90%; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #E2E8F0; padding-bottom: 12px; margin-bottom: 16px;">
            <div>
                <h3 style="margin: 0; font-size: 18px; color: #1E293B; font-weight: 700;">SALARY PAYSLIP</h3>
                <div style="font-size: 12px; color: #64748B;" id="ps_month"></div>
            </div>
            <button type="button" onclick="closePayslip()" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>

        <div style="background: #F8FAFC; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 13px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                <span>Employee Name:</span>
                <strong id="ps_name" style="color: #4F46E5;"></strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                <span>Employee Code:</span>
                <strong id="ps_code"></strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Department / Role:</span>
                <strong id="ps_dept"></strong>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 16px;">
            <tr style="border-bottom: 1px solid #E2E8F0;">
                <th style="text-align: left; padding: 8px 0; color: #64748B;">Earnings</th>
                <th style="text-align: right; padding: 8px 0; color: #64748B;">Amount</th>
                <th style="text-align: left; padding: 8px 0 8px 16px; color: #64748B;">Deductions</th>
                <th style="text-align: right; padding: 8px 0; color: #64748B;">Amount</th>
            </tr>
            <tr style="border-bottom: 1px solid #F1F5F9;">
                <td style="padding: 6px 0;">Basic Pay</td>
                <td style="text-align: right; font-weight: 600;" id="ps_basic"></td>
                <td style="padding: 6px 0 6px 16px;">Provident Fund (PF)</td>
                <td style="text-align: right; font-weight: 600;" id="ps_pf"></td>
            </tr>
            <tr style="border-bottom: 1px solid #F1F5F9;">
                <td style="padding: 6px 0;">Dearness Allowance (DA)</td>
                <td style="text-align: right; font-weight: 600;" id="ps_da"></td>
                <td style="padding: 6px 0 6px 16px;">Professional Tax</td>
                <td style="text-align: right; font-weight: 600;">₹200.00</td>
            </tr>
            <tr style="border-bottom: 1px solid #E2E8F0;">
                <td style="padding: 6px 0;">HRA Allowance</td>
                <td style="text-align: right; font-weight: 600;" id="ps_hra"></td>
                <td style="padding: 6px 0 6px 16px;">TDS / Income Tax</td>
                <td style="text-align: right; font-weight: 600;" id="ps_tax"></td>
            </tr>
            <tr style="font-weight: 700; border-bottom: 2px solid #E2E8F0;">
                <td style="padding: 10px 0;">Total Earnings</td>
                <td style="text-align: right; color: #10B981;" id="ps_tot_earn"></td>
                <td style="padding: 10px 0 10px 16px;">Total Deductions</td>
                <td style="text-align: right; color: #EF4444;" id="ps_tot_ded"></td>
            </tr>
            <tr style="background: #EEF2FF; font-weight: 700; font-size: 15px;">
                <td colspan="2" style="padding: 12px; color: #1E293B;">NET TAKE-HOME SALARY</td>
                <td colspan="2" style="text-align: right; padding: 12px; color: #4F46E5;" id="ps_net"></td>
            </tr>
        </table>

        <div style="display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" class="btn btn-outline" onclick="closePayslip()">Close</button>
            <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa-solid fa-print me-1"></i> Print Payslip</button>
        </div>
    </div>
</div>

<script>
    function viewPayslip(name, code, desig, dept, basic, month) {
        document.getElementById('ps_name').innerText = name;
        document.getElementById('ps_code').innerText = code;
        document.getElementById('ps_dept').innerText = desig + ' (' + dept + ')';
        document.getElementById('ps_month').innerText = 'Pay Period: ' + month;

        const da = basic * 0.20;
        const hra = basic * 0.15;
        const totEarn = basic + da + hra;
        const pf = basic * 0.12;
        const pt = 200;
        const tax = basic * 0.05;
        const totDed = pf + pt + tax;
        const net = totEarn - totDed;

        document.getElementById('ps_basic').innerText = '₹' + basic.toFixed(2);
        document.getElementById('ps_da').innerText = '₹' + da.toFixed(2);
        document.getElementById('ps_hra').innerText = '₹' + hra.toFixed(2);
        document.getElementById('ps_tot_earn').innerText = '₹' + totEarn.toFixed(2);

        document.getElementById('ps_pf').innerText = '₹' + pf.toFixed(2);
        document.getElementById('ps_tax').innerText = '₹' + tax.toFixed(2);
        document.getElementById('ps_tot_ded').innerText = '₹' + totDed.toFixed(2);

        document.getElementById('ps_net').innerText = '₹' + net.toFixed(2);

        document.getElementById('payslipModal').style.display = 'flex';
    }

    function closePayslip() {
        document.getElementById('payslipModal').style.display = 'none';
    }
</script>
<?= $this->endSection() ?>
