<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Salary Structures<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <div>
            <h2><i class="fa-solid fa-network-wired"></i> Salary Structures & Pay Bands</h2>
            <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 13px;">Manage salary packages, pay grades mapped to designations (Professor, Assistant Professor, Lecturer, Non-Teaching).</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-primary" onclick="openStructureModal()"><i class="fa-solid fa-plus"></i> New Salary Structure</button>
            <a href="<?= base_url('org/payroll/components') ?>" class="btn btn-outline"><i class="fa-solid fa-calculator"></i> Pay Components</a>
            <a href="<?= base_url('org/payroll/payslips') ?>" class="btn btn-outline"><i class="fa-solid fa-file-invoice-dollar"></i> Payslips</a>
        </div>
    </div>

    <!-- Structures Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Structure Name</th>
                    <th>Target Designation</th>
                    <th>Basic Pay</th>
                    <th>Total Earnings</th>
                    <th>Total Deductions</th>
                    <th>Net Monthly CTC</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($structures)): foreach($structures as $s): ?>
                <tr>
                    <td><strong><?= esc($s['name']) ?></strong></td>
                    <td><span class="badge" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5;"><?= esc($s['designation_name'] ?: 'All Designations') ?></span></td>
                    <td>₹<?= number_format($s['basic_salary'], 2) ?></td>
                    <td style="color: #10b981; font-weight: 600;">₹<?= number_format($s['total_earnings'], 2) ?></td>
                    <td style="color: #ef4444; font-weight: 600;">₹<?= number_format($s['total_deductions'], 2) ?></td>
                    <td>
                        <strong style="color: #4f46e5; font-size: 15px;">₹<?= number_format($s['net_salary'], 2) ?></strong>
                    </td>
                    <td>
                        <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: #10b981; font-weight: 600;">
                            <?= esc($s['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?= base_url('org/payroll/structures/delete/' . $s['id']) ?>" class="btn-icon text-danger" onclick="return confirm('Delete this salary structure?')" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="8" style="text-align: center; padding: 30px; color: var(--text-muted);">No salary structures defined yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Structure Modal -->
<div class="drawer-overlay" id="structModal" style="display: none;">
    <div class="drawer-content" style="max-width: 520px;">
        <form action="<?= base_url('org/payroll/structures/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 700;"><i class="fa-solid fa-network-wired me-2" style="color: #4f46e5;"></i> Add Salary Structure</h3>
                <button type="button" class="btn-close" onclick="closeStructureModal()" style="background: none; border: none; font-size: 18px; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 20px;">
                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Structure Title / Grade *</label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Associate Professor Level-12 Band">
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Applicable Designation</label>
                    <select name="designation_id" class="form-control">
                        <option value="">Any / General</option>
                        <?php foreach($designations as $d): ?>
                            <option value="<?= $d['id'] ?>"><?= esc($d['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Base / Basic Pay *</label>
                        <input type="number" step="0.01" name="basic_salary" id="st_basic" class="form-control" required value="50000" oninput="calcNet()">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Total Earnings</label>
                        <input type="number" step="0.01" name="total_earnings" id="st_earnings" class="form-control" value="65000" oninput="calcNet()">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Total Deductions (PF/Tax)</label>
                        <input type="number" step="0.01" name="total_deductions" id="st_deductions" class="form-control" value="6000" oninput="calcNet()">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Net Monthly Salary</label>
                        <input type="number" step="0.01" name="net_salary" id="st_net" class="form-control" readonly style="background: #F1F5F9; font-weight: 700; color: #4F46E5;" value="59000">
                    </div>
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeStructureModal()">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Save Structure</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openStructureModal() { document.getElementById('structModal').style.display = 'flex'; }
    function closeStructureModal() { document.getElementById('structModal').style.display = 'none'; }

    function calcNet() {
        const earnings = parseFloat(document.getElementById('st_earnings').value) || 0;
        const deductions = parseFloat(document.getElementById('st_deductions').value) || 0;
        document.getElementById('st_net').value = (earnings - deductions).toFixed(2);
    }
</script>
<?= $this->endSection() ?>
