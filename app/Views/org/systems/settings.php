<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Global Settings<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
<div class="view-header">
    <h2>Organization Profile</h2>
    <p style="color: var(--text-muted); font-size: 13px;">Manage the core contact details and identity of your institution.</p>
</div>

<div class="card" style="max-width: 600px;">
    <form action="<?= base_url('org/systems/settings/save') ?>" method="POST">
        <?= csrf_field() ?>
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Organization Name <span style="color:var(--danger)">*</span></label>
            <input type="text" name="name" class="form-control" value="<?= esc($org['name']) ?>" required style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px;">
        </div>
        
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Official Email Address <span style="color:var(--danger)">*</span></label>
            <input type="email" name="admin_email" class="form-control" value="<?= esc($org['admin_email']) ?>" required style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px;">
            <small style="color: var(--text-muted); display: block; margin-top: 4px;">This is your institution's public contact email. It will NOT change your personal login email.</small>
        </div>
        
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Contact Phone Number</label>
            <input type="tel" pattern="[0-9]{10}" maxlength="10" name="org_phone" class="form-control" value="<?= esc($settings['org_phone'] ?? '') ?>" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px;">
        </div>
        
        <div class="form-group" style="margin-bottom: 24px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Campus Address</label>
            <textarea name="org_address" class="form-control" rows="4" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; resize: vertical;"><?= esc($settings['org_address'] ?? '') ?></textarea>
        </div>
        
        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Profile</button>
        </div>
    </form>
</div>
</section>

<?= $this->endSection() ?>
