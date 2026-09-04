<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Placement Partners<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Placement Partners</h1>
        <p class="header-subtitle">Manage companies and recruitment partners.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    
    <div class="card">
        <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Add Company</h2>
        <form action="<?= base_url('org/placements/save-company') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="comp_id">
            <div class="form-group">
                <label>Company Name *</label>
                <input type="text" name="company_name" id="company_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Industry</label>
                <input type="text" name="industry" id="industry" class="form-control" placeholder="e.g. IT, Manufacturing">
            </div>
            <div class="form-group">
                <label>HR Contact Name</label>
                <input type="text" name="hr_contact_name" id="hr_contact_name" class="form-control">
            </div>
            <div class="form-group">
                <label>HR Email</label>
                <input type="email" name="hr_email" id="hr_email" class="form-control">
            </div>
            <div class="form-group">
                <label>HR Phone</label>
                <input type="tel" pattern="[0-9]{10}" maxlength="10" name="hr_phone" id="hr_phone" class="form-control">
            </div>
            <div class="form-group" style="display: flex; gap: 8px; align-items: center;">
                <input type="checkbox" name="mou_signed" id="mou_signed" value="1">
                <label style="margin: 0;">MOU Signed with College</label>
            </div>
            
            <div style="display: flex; gap: 12px; margin-top: 16px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Save Partner</button>
                <button type="button" class="btn" onclick="document.getElementById('comp_id').value=''; this.form.reset();" style="background: #F3F4F6; border: 1px solid var(--border-color);">Clear</button>
            </div>
        </form>
    </div>
    
    <div class="card">
        <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Registered Companies</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Industry</th>
                    <th>HR Contact</th>
                    <th>MOU</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($companies as $c): ?>
                    <tr>
                        <td style="font-weight: 600; color: var(--primary);"><?= esc($c['company_name']) ?></td>
                        <td><?= esc($c['industry']) ?></td>
                        <td>
                            <div><?= esc($c['hr_contact_name']) ?></div>
                            <div style="font-size: 11px; color: var(--text-muted);"><?= esc($c['hr_email']) ?></div>
                        </td>
                        <td>
                            <?php if($c['mou_signed']): ?>
                                <span style="background: #D1FAE5; color: #065F46; padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: bold;">SIGNED</span>
                            <?php else: ?>
                                <span style="color: var(--text-muted); font-size: 11px;">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button type="button" class="btn" style="background: #F3F4F6; border: 1px solid var(--border-color); padding: 4px 8px; font-size: 12px;" onclick="editComp(<?= htmlspecialchars(json_encode($c)) ?>)">Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($companies)): ?>
                    <tr><td colspan="5" style="text-align: center;">No companies registered.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
function editComp(data) {
    document.getElementById('comp_id').value = data.id;
    document.getElementById('company_name').value = data.company_name;
    document.getElementById('industry').value = data.industry;
    document.getElementById('hr_contact_name').value = data.hr_contact_name;
    document.getElementById('hr_email').value = data.hr_email;
    document.getElementById('hr_phone').value = data.hr_phone;
    document.getElementById('mou_signed').checked = data.mou_signed == 1;
}
</script>

<?= $this->endSection() ?>
