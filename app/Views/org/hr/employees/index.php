<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Employee Directory<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 20px;">
    <h3 style="margin-top: 0; margin-bottom: 14px; font-size: 15px; font-weight: 700; color: #1E293B;">Onboard New Employee</h3>
    <form action="<?= base_url('org/hr/employees/save') ?>" method="POST" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; align-items: end;">
        <?= csrf_field() ?>
        
        <div>
            <label class="form-label" style="font-size: 11.5px; font-weight: 600; color: #64748B; margin-bottom: 5px; display: block;">System User (Unmapped Staff)</label>
            <select name="org_user_id" class="form-control" required style="font-size: 12.5px; height: 38px;">
                <option value="">Select User...</option>
                <?php foreach($unmapped_users as $u): ?>
                    <option value="<?= $u['id'] ?>"><?= esc($u['full_name']) ?> (<?= esc($u['email']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="form-label" style="font-size: 11.5px; font-weight: 600; color: #64748B; margin-bottom: 5px; display: block;">Employee Code</label>
            <input type="text" name="employee_code" class="form-control" placeholder="e.g. EMP001" required style="font-size: 12.5px; height: 38px;">
        </div>

        <div>
            <label class="form-label" style="font-size: 11.5px; font-weight: 600; color: #64748B; margin-bottom: 5px; display: block;">Department</label>
            <select name="department_id" class="form-control" required style="font-size: 12.5px; height: 38px;">
                <option value="">Select Dept...</option>
                <?php foreach($departments as $d): ?>
                    <option value="<?= $d['id'] ?>"><?= esc($d['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="form-label" style="font-size: 11.5px; font-weight: 600; color: #64748B; margin-bottom: 5px; display: block;">Designation</label>
            <select name="designation_id" class="form-control" required style="font-size: 12.5px; height: 38px;">
                <option value="">Select Designation...</option>
                <?php foreach($designations as $d): ?>
                    <option value="<?= $d['id'] ?>"><?= esc($d['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="form-label" style="font-size: 11.5px; font-weight: 600; color: #64748B; margin-bottom: 5px; display: block;">Employment Type</label>
            <select name="employment_type_id" class="form-control" required style="font-size: 12.5px; height: 38px;">
                <option value="">Select Type...</option>
                <?php foreach($employment_types as $d): ?>
                    <option value="<?= $d['id'] ?>"><?= esc($d['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="form-label" style="font-size: 11.5px; font-weight: 600; color: #64748B; margin-bottom: 5px; display: block;">Joining Date</label>
            <input type="date" name="joining_date" class="form-control" required style="font-size: 12.5px; height: 38px;">
        </div>

        <div>
            <label class="form-label" style="font-size: 11.5px; font-weight: 600; color: #64748B; margin-bottom: 5px; display: block;">Base Salary (Monthly)</label>
            <input type="number" min="0" step="0.01" name="base_salary" class="form-control" placeholder="0.00" required style="font-size: 12.5px; height: 38px;">
        </div>

        <div>
            <button class="btn btn-primary" style="width: 100%; height: 38px; font-size: 12.5px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 6px;"><i class="fa-solid fa-user-plus"></i> Onboard Employee</button>
        </div>

    </form>
</div>

<div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
    <h3 style="margin-top: 0; font-size: 15px; font-weight: 700; color: #1E293B;">Employee Directory</h3>
    
    <table style="width: 100%; border-collapse: collapse; margin-top: 14px;">
        <thead>
            <tr style="border-bottom: 2px solid var(--border-color);">
                <th style="padding: 10px 8px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748B;">Emp Code</th>
                <th style="padding: 10px 8px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748B;">Name</th>
                <th style="padding: 10px 8px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748B;">Department & Role</th>
                <th style="padding: 10px 8px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748B;">Joined</th>
                <th style="padding: 10px 8px; text-align: right; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748B;">Base Salary</th>
                <th style="padding: 10px 8px; text-align: right; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748B;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($employees as $e): ?>
            <tr style="border-bottom: 1px solid var(--border-color);">
                <td style="padding: 12px 8px; font-weight: 600;"><?= esc($e['employee_code']) ?></td>
                <td style="padding: 12px 8px;">
                    <div style="font-weight: 600;"><?= esc($e['full_name']) ?></div>
                    <div style="font-size: 12px; color: var(--text-muted);"><?= esc($e['email']) ?></div>
                </td>
                <td style="padding: 12px 8px;">
                    <div><?= esc($e['department_name']) ?></div>
                    <div style="font-size: 12px; color: var(--primary); font-weight: bold;"><?= esc($e['designation_name']) ?></div>
                </td>
                <td style="padding: 12px 8px;"><?= esc(date('d/m/Y', strtotime($e['joining_date']))) ?></td>
                <td style="padding: 12px 8px; text-align: right; font-weight: bold;">₹<?= number_format($e['base_salary'], 2) ?></td>
                <td style="padding: 12px 8px; text-align: right;">
                    <form action="<?= base_url('org/hr/employees/delete/'.$e['id']) ?>" method="POST" onsubmit="return confirm('Remove employee? This does not delete the user account, but removes their HR profile.');">
                        <?= csrf_field() ?>
                        <button class="btn btn-outline" style="padding: 4px 8px; font-size: 12px; color: var(--danger); border-color: var(--danger);">Remove</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($employees)): ?>
            <tr><td colspan="6" style="padding: 32px; text-align: center; color: var(--text-muted);">No employees found in directory. Onboard a user above.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
