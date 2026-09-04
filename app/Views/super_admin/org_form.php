<?= $this->extend('super_admin/layout') ?>
<?= $this->section('content') ?>

<section class="view-section active">
<div class="view-header" style="margin-bottom: 20px;">
    <h2><?= $org ? 'Edit Organization' : 'Create New Organization' ?></h2>
    <a href="<?= base_url('superadmin/organizations') ?>" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Back</a>
</div>

<form action="<?= base_url('superadmin/save_organization') ?>" method="POST" class="org-form-container" id="org_form">
    <?= csrf_field() ?>
    <?php if($org): ?>
        <input type="hidden" name="org_id" value="<?= $org['id'] ?>">
    <?php endif; ?>
    
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        
        <!-- Left Column: Details & Settings -->
        <div>
            <!-- Section 1: Company Details -->
            <div class="card" style="background: var(--bg-card); padding: 24px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
                <h3 style="margin-top: 0; color: var(--text-main);">1. Company Details</h3>
                <hr style="border: 0; border-top: 1px solid var(--border-color); margin-bottom: 20px;">
                
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500;">Organization Name</label>
                        <input type="text" name="name" id="org_name_input" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px;" value="<?= $org ? esc($org['name']) : '' ?>" placeholder="e.g. Apex Institute of Technology">
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500;">Institution Code / Prefix</label>
                        <input type="text" name="code" id="org_code_input" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; font-weight: 700; text-transform: uppercase;" value="<?= $org ? esc($org['code'] ?? '') : '' ?>" placeholder="e.g. AIT">
                    </div>
                </div>
                <div style="font-size: 12px; color: var(--primary); font-weight: 600; margin-top: -6px; margin-bottom: 16px; background: rgba(124,58,237,0.08); padding: 8px 12px; border-radius: 6px; border: 1px solid rgba(124,58,237,0.15);">
                    🔑 Initial Admin Login Employee ID: <strong id="admin_emp_preview" style="font-family: monospace; font-size: 13px;"><?= $org && !empty($org['code']) ? esc($org['code']).'-ADM-1001' : 'AIT-ADM-1001' ?></strong>
                </div>
                
                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Admin Email</label>
                    <input type="email" name="admin_email" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px;" value="<?= $org ? esc($org['admin_email']) : '' ?>">
                </div>
                
                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;"><?= $org ? 'New Admin Password (Leave blank to keep current)' : 'Admin Password (Required)' ?></label>
                    <input type="text" name="admin_password" class="form-control" <?= $org ? '' : 'required' ?> style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px;">
                </div>
            </div>
            
            <!-- Section 2: Module Access -->
            <div class="card" style="background: var(--bg-card); padding: 24px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
                <h3 style="margin-top: 0; color: var(--text-main);">2. Module Access</h3>
                <hr style="border: 0; border-top: 1px solid var(--border-color); margin-bottom: 20px;">
                
                <div style="display: flex; gap: 40px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <input type="hidden" name="cms_enabled" value="1">
                        <span class="badge badge-success" style="font-size: 14px; padding: 6px 12px; border-radius: 6px; font-weight: 600; background: rgba(34,197,94,0.1); color: #22c55e;">CMS: Included</span>
                    </div>
                    
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="lms_enabled" id="lms_enabled_checkbox" value="1" <?= ($org && $org['lms_enabled']) ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: var(--primary);">
                        <span style="font-weight: 500;">Enable LMS Module</span>
                    </label>
                </div>
            </div>
            
            <!-- Section 3: Plan Selection -->
            <div class="card" style="background: var(--bg-card); padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
                <h3 style="margin-top: 0; color: var(--text-main);">3. Assign Subscription Plan</h3>
                <hr style="border: 0; border-top: 1px solid var(--border-color); margin-bottom: 20px;">
                
                <div class="form-group">
                    <input type="hidden" name="plan_id" id="plan_id_input" value="<?= $org ? $org['plan_id'] : '' ?>">
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                        <?php foreach($plans as $plan): ?>
                            <div class="plan-card <?= ($org && $org['plan_id'] == $plan['id']) ? 'selected' : '' ?>" 
                                 data-id="<?= $plan['id'] ?>" 
                                 data-price="<?= $plan['price'] ?>" 
                                 data-duration="<?= $plan['duration_months'] ?>"
                                 data-name="<?= esc($plan['name']) ?>"
                                 style="border: 2px solid <?= ($org && $org['plan_id'] == $plan['id']) ? 'var(--primary)' : 'var(--border-color)' ?>; border-radius: 8px; padding: 16px; cursor: pointer; transition: all 0.2s; position: relative;">
                                
                                <div style="font-weight: 700; font-size: 18px; margin-bottom: 8px; color: var(--text-main);"><?= esc($plan['name']) ?></div>
                                <div style="font-size: 20px; font-weight: 800; color: var(--primary); margin-bottom: 12px;">₹<?= number_format($plan['price'], 2) ?> <span style="font-size: 14px; font-weight: 500; color: var(--text-muted);">/ <?= $plan['duration_months'] ?>mo</span></div>
                                <div style="font-size: 13px; color: var(--text-muted); line-height: 1.5;"><?= esc($plan['features']) ?></div>
                                
                                <?php if($org && $org['plan_id'] == $plan['id']): ?>
                                <div class="selected-indicator" style="position: absolute; top: 12px; right: 12px; color: var(--primary);"><i class="fa-solid fa-circle-check"></i></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
        </div>
        
        <!-- Right Column: Payment Gateway / Summary -->
        <div>
            <div class="card" style="background: var(--bg-card); padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); position: sticky; top: 20px;">
                <h3 style="margin-top: 0; color: var(--text-main);">Payment Gateway</h3>
                <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 20px;">Order Summary & Confirmation</div>
                
                <div style="background: #f8fafc; padding: 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e2e8f0;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="color: var(--text-muted);">Selected Plan:</span>
                        <span id="summary_plan_name" style="font-weight: 600;">-</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="color: var(--text-muted);">Billing Cycle:</span>
                        <span id="summary_duration" style="font-weight: 600;">-</span>
                    </div>
                    <hr style="border: 0; border-top: 1px dashed #cbd5e1; margin: 15px 0;">
                    <div style="display: flex; justify-content: space-between; font-size: 18px;">
                        <span style="font-weight: 700;">Total Due:</span>
                        <span id="summary_total" style="font-weight: 700; color: var(--primary);">₹0.00</span>
                    </div>
                </div>
                
                <?php if(!$org): ?>
                <div style="margin-bottom: 20px; background: rgba(30,58,138,0.05); padding: 12px; border-radius: 6px; border: 1px solid rgba(30,58,138,0.1);">
                    <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="confirm_payment" value="1" checked style="width: 16px; height: 16px; margin-top: 2px;">
                        <span style="font-size: 14px;">Record this as a paid transaction in the Payment History.</span>
                    </label>
                </div>
                <?php endif; ?>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 16px; font-weight: 600; border-radius: 8px; border: none; cursor: pointer; color: white; background: var(--primary);">
                    <?= $org ? 'Update Organization' : 'Confirm & Process Payment' ?>
                </button>
            </div>
        </div>
        
    </div>
</form>
</section>

<script>
$(document).ready(function() {
    var initialLmsState = $('#lms_enabled_checkbox').is(':checked');
    
    $('#org_form').submit(function(e) {
        var currentLmsState = $('#lms_enabled_checkbox').is(':checked');
        if (initialLmsState !== currentLmsState) {
            var orgName = $('input[name="name"]').val() || 'this organization';
            if (!confirm('This will change what ' + orgName + '\'s staff can access. Continue?')) {
                e.preventDefault();
                return false;
            }
        }
        
        if (!$('#plan_id_input').val()) {
            alert('Please select a subscription plan.');
            e.preventDefault();
            return false;
        }
    });

    $('.plan-card').click(function() {
        // UI updates
        $('.plan-card').css('border-color', 'var(--border-color)').removeClass('selected');
        $('.plan-card .selected-indicator').remove();
        
        $(this).css('border-color', 'var(--primary)').addClass('selected');
        $(this).append('<div class="selected-indicator" style="position: absolute; top: 12px; right: 12px; color: var(--primary);"><i class="fa-solid fa-circle-check"></i></div>');
        
        // Data updates
        $('#plan_id_input').val($(this).data('id'));
        
        var name = $(this).data('name');
        var price = parseFloat($(this).data('price'));
        var duration = $(this).data('duration');
        
        $('#summary_plan_name').text(name);
        $('#summary_duration').text(duration + (duration > 1 ? ' Months' : ' Month'));
        $('#summary_total').text('₹' + price.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    });
    
    // Real-time Institution Code Acronym Generator & Preview
    $('#org_name_input').on('input', function() {
        var codeInput = $('#org_code_input');
        if (!codeInput.data('manual')) {
            var words = $(this).val().trim().split(/\s+/).filter(function(w) { return w.length > 0; });
            var acronym = '';
            if (words.length === 1) {
                acronym = words[0].substring(0, 4).toUpperCase();
            } else if (words.length > 1) {
                acronym = words.map(function(w) { return w[0]; }).join('').toUpperCase().substring(0, 5);
            }
            codeInput.val(acronym);
            $('#admin_emp_preview').text((acronym || 'ORG') + '-ADM-1001');
        }
    });

    $('#org_code_input').on('input', function() {
        $(this).data('manual', true);
        var code = $(this).val().toUpperCase().trim();
        $(this).val(code);
        $('#admin_emp_preview').text((code || 'ORG') + '-ADM-1001');
    });

    // Initial call in case of edit mode
    if ($('.plan-card.selected').length > 0) {
        $('.plan-card.selected').trigger('click');
    }
});
</script>

<?= $this->endSection() ?>
