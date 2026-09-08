<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>HR Settings<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">

    <!-- Departments -->
    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <h3 style="margin-top: 0;">Departments</h3>
        <form action="<?= base_url('org/hr/settings/save_department') ?>" method="POST" style="display: flex; gap: 8px; margin-bottom: 16px;">
            <?= csrf_field() ?>
            <input type="text" name="name" class="form-control" placeholder="Department Name" required>
            <input type="text" name="description" class="form-control" placeholder="Description (Optional)">
            <button class="btn btn-primary">Add</button>
        </form>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color);">
                    <th style="padding: 12px 8px; text-align: left;">Name</th>
                    <th style="padding: 12px 8px; text-align: left;">Description</th>
                    <th style="padding: 12px 8px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($departments as $d): ?>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 12px 8px;"><?= esc($d['name']) ?></td>
                    <td style="padding: 12px 8px; color: var(--text-muted);"><?= esc($d['description']) ?></td>
                    <td style="padding: 12px 8px; text-align: right;">
                        <form action="<?= base_url('org/hr/settings/delete_department/' . ($d['uuid'] ?? $d['id'])) ?>" method="POST" onsubmit="return confirm('Delete department?');">
                            <?= csrf_field() ?>
                            <button class="btn btn-outline" style="padding: 4px 8px; font-size: 12px; color: var(--danger); border-color: var(--danger);">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($departments)): ?>
                <tr><td colspan="3" style="padding: 16px; text-align: center; color: var(--text-muted);">No departments configured.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Designations -->
    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <h3 style="margin-top: 0;">Designations</h3>
        <form action="<?= base_url('org/hr/settings/save_designation') ?>" method="POST" style="display: flex; gap: 8px; margin-bottom: 16px;">
            <?= csrf_field() ?>
            <input type="text" name="name" class="form-control" placeholder="Designation Name" required>
            <button class="btn btn-primary">Add</button>
        </form>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color);">
                    <th style="padding: 12px 8px; text-align: left;">Name</th>
                    <th style="padding: 12px 8px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($designations as $d): ?>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 12px 8px;"><?= esc($d['name']) ?></td>
                    <td style="padding: 12px 8px; text-align: right;">
                        <form action="<?= base_url('org/hr/settings/delete_designation/' . ($d['uuid'] ?? $d['id'])) ?>" method="POST" onsubmit="return confirm('Delete designation?');">
                            <?= csrf_field() ?>
                            <button class="btn btn-outline" style="padding: 4px 8px; font-size: 12px; color: var(--danger); border-color: var(--danger);">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Employment Types -->
    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <h3 style="margin-top: 0;">Employment Types</h3>
        <form action="<?= base_url('org/hr/settings/save_employment_type') ?>" method="POST" style="display: flex; gap: 8px; margin-bottom: 16px;">
            <?= csrf_field() ?>
            <input type="text" name="name" class="form-control" placeholder="e.g. Full-Time, Contract" required>
            <button class="btn btn-primary">Add</button>
        </form>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color);">
                    <th style="padding: 12px 8px; text-align: left;">Name</th>
                    <th style="padding: 12px 8px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($employment_types as $d): ?>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 12px 8px;"><?= esc($d['name']) ?></td>
                    <td style="padding: 12px 8px; text-align: right;">
                        <form action="<?= base_url('org/hr/settings/delete_employment_type/' . ($d['uuid'] ?? $d['id'])) ?>" method="POST" onsubmit="return confirm('Delete type?');">
                            <?= csrf_field() ?>
                            <button class="btn btn-outline" style="padding: 4px 8px; font-size: 12px; color: var(--danger); border-color: var(--danger);">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Leave Policies -->
    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <h3 style="margin-top: 0;">Leave Policies</h3>
        <form action="<?= base_url('org/hr/settings/save_leave_policy') ?>" method="POST" style="display: flex; gap: 8px; margin-bottom: 16px;">
            <?= csrf_field() ?>
            <select name="employment_type_id" class="form-control" required>
                <option value="">Select Emp Type</option>
                <?php foreach($employment_types as $et): ?>
                    <option value="<?= $et['id'] ?>"><?= esc($et['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="leave_type" class="form-control" placeholder="e.g. Casual Leave" required>
            <input type="number" min="0" name="annual_quota" class="form-control" placeholder="Quota" style="width:80px;" required>
            <button class="btn btn-primary">Add</button>
        </form>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color);">
                    <th style="padding: 12px 8px; text-align: left;">Emp Type</th>
                    <th style="padding: 12px 8px; text-align: left;">Leave Type</th>
                    <th style="padding: 12px 8px; text-align: center;">Quota</th>
                    <th style="padding: 12px 8px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($leave_policies as $lp): 
                    $empTypeName = '';
                    foreach($employment_types as $et) { if($et['id'] == $lp['employment_type_id']) $empTypeName = $et['name']; }
                ?>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 12px 8px;"><?= esc($empTypeName) ?></td>
                    <td style="padding: 12px 8px;"><?= esc($lp['leave_type']) ?></td>
                    <td style="padding: 12px 8px; text-align: center; font-weight: bold;"><?= esc($lp['annual_quota']) ?></td>
                    <td style="padding: 12px 8px; text-align: right;">
                        <form action="<?= base_url('org/hr/settings/delete_leave_policy/' . ($lp['uuid'] ?? $lp['id'])) ?>" method="POST" onsubmit="return confirm('Delete policy?');">
                            <?= csrf_field() ?>
                            <button class="btn btn-outline" style="padding: 4px 8px; font-size: 12px; color: var(--danger); border-color: var(--danger);">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?= $this->endSection() ?>
