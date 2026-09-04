<?php $this->extend('org/layout'); ?>

<?php $this->section('content'); ?>
<div class="view-header">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Organization Settings</h2>
    </div>
</div>

<div class="content-body" style="padding: 24px;">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card" style="background: var(--bg-surface); padding: 24px; border-radius: 12px; max-width: 800px; margin: 0 auto; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <p><em>General settings for the organization (Timezones, API Keys for SMS/Email, Default Academic Year, etc.) will be configured here.</em></p>
        
        <form action="<?= base_url('org/administration/save-settings') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Default Academic Year</label>
                    <select name="default_academic_year" class="form-control">
                        <option value="">Select Year</option>
                        <!-- Dynamic options here -->
                    </select>
                </div>
                <div class="form-group">
                    <label>Timezone</label>
                    <select name="timezone" class="form-control">
                        <option value="Asia/Kolkata">Asia/Kolkata (IST)</option>
                        <option value="UTC">UTC</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>SMS Gateway API Key</label>
                    <input type="text" name="sms_api_key" class="form-control" placeholder="Optional">
                </div>
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Payment Gateway Key (Razorpay/Stripe)</label>
                    <input type="text" name="pg_api_key" class="form-control" placeholder="Optional">
                </div>
            </div>

            <div style="margin-top: 24px; text-align: right;">
                <button type="submit" class="btn btn-primary" style="background: var(--primary-color); border: none; padding: 10px 20px; border-radius: 6px; color: white; cursor: pointer;">Save Settings</button>
            </div>
        </form>
    </div>
</div>
<?php $this->endSection(); ?>
