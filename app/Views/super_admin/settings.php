<?= $this->extend('super_admin/layout') ?>
<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>System Settings</h2>
    </div>
    
    <div style="background: var(--card-bg, #fff); padding: 24px; border-radius: 12px; border: 1px solid var(--border-color); max-width: 800px;">
        <form action="<?= base_url('superadmin/settings/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display:block; font-weight: 500; margin-bottom:8px;">Site Name</label>
                <input type="text" name="site_name" class="form-control" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px;" value="<?= esc($settings_kv['site_name'] ?? '') ?>" required>
                <small style="color: var(--text-muted); margin-top:4px; display:block;">The global name of the platform</small>
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display:block; font-weight: 500; margin-bottom:8px;">Support Email</label>
                <input type="email" name="support_email" class="form-control" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px;" value="<?= esc($settings_kv['support_email'] ?? '') ?>" required>
                <small style="color: var(--text-muted); margin-top:4px; display:block;">The contact email for support inquiries</small>
            </div>
            
            <div class="form-group" style="margin-bottom: 24px;">
                <label style="display:block; font-weight: 500; margin-bottom:8px;">Maintenance Mode</label>
                <select name="maintenance_mode" class="form-control" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px;">
                    <option value="0" <?= (isset($settings_kv['maintenance_mode']) && $settings_kv['maintenance_mode'] == '0') ? 'selected' : '' ?>>Disabled</option>
                    <option value="1" <?= (isset($settings_kv['maintenance_mode']) && $settings_kv['maintenance_mode'] == '1') ? 'selected' : '' ?>>Enabled</option>
                </select>
                <small style="color: var(--text-muted); margin-top:4px; display:block;">If enabled, the public site will show a maintenance page</small>
            </div>
            
            <button type="submit" class="btn btn-primary" style="padding: 10px 24px; background: var(--primary); color: white; border: none; border-radius: 6px; cursor: pointer;">Save Settings</button>
        </form>
    </div>
</section>
<?= $this->endSection() ?>
