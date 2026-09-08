<?= $this->extend('super_admin/layout') ?>
<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Organizations</h2>
        <div style="display:flex; gap:10px;">
            <a href="<?= base_url('superadmin/export_organizations') ?>" class="btn btn-outline" style="border:1px solid var(--border-color); padding:8px 16px; border-radius:6px; text-decoration:none; color:var(--text-main);"><i class="fa-solid fa-download"></i> Export CSV</a>
            <a href="<?= base_url('superadmin/create_organization') ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Organization</a>
        </div>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Plan</th>
                    <th>Modules Enabled</th>
                    <th>Subscription End</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($organizations)): ?>
                    <?php foreach($organizations as $org): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: var(--text-main);"><?= esc($org['name']) ?></div>
                            <?php if(!empty($org['code'])): ?>
                                <span style="display: inline-block; background: rgba(124,58,237,0.1); color: var(--primary); font-weight: 700; padding: 2px 7px; border-radius: 4px; font-size: 11px; margin-top: 3px; font-family: monospace;">CODE: <?= esc($org['code']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($org['plan_name']) ?></td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <?php if(isset($org['cms_enabled']) && $org['cms_enabled']): ?>
                                    <span class="badge badge-success">CMS: On</span>
                                <?php else: ?>
                                    <span class="badge" style="background: #e2e8f0; color: #64748b;">CMS: Off</span>
                                <?php endif; ?>
                                <?php if($org['lms_enabled']): ?>
                                    <span class="badge badge-primary">LMS: On</span>
                                <?php else: ?>
                                    <span class="badge" style="background: #e2e8f0; color: #64748b;">LMS: Off</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><?= date('d/m/Y', strtotime($org['subscription_end_date'])) ?></td>
                        <td>
                            <?php if($org['status'] == 'active'): ?>
                                <span class="badge badge-success">Active</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Suspended</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-buttons" style="display: flex; gap: 6px; align-items: center;">
                                <a href="<?= base_url('superadmin/view_organization/'.($org['uuid'] ?? $org['id'])) ?>" class="btn-icon text-info" title="View Details"><i class="fa-solid fa-eye"></i></a>
                                <a href="<?= base_url('superadmin/edit_organization/'.($org['uuid'] ?? $org['id'])) ?>" class="btn-icon text-primary" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <button type="button" class="btn-icon btn-renew" data-org="<?= $org['id'] ?>" title="Renew / Add Payment"><i class="fa-solid fa-arrows-rotate"></i></button>
                                <?php if($org['status'] == 'active'): ?>
                                    <button type="button" class="btn-icon text-danger btn-toggle-status" data-org="<?= $org['id'] ?>" data-status="suspended" title="Suspend"><i class="fa-solid fa-ban"></i></button>
                                <?php else: ?>
                                    <button type="button" class="btn-icon text-success btn-toggle-status" data-org="<?= $org['id'] ?>" data-status="active" title="Activate"><i class="fa-solid fa-check"></i></button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No organizations found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if($pager): ?>
            <?= $pager->links() ?>
        <?php endif; ?>
    </div>
</section>

<!-- Renew Modal -->
<div class="modal-overlay" id="modal-renew">
    <div class="modal-content" style="max-width: 600px;">
        <form action="<?= base_url('superadmin/renew_organization') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="org_id" id="renew_org_id">
            <input type="hidden" name="plan_id" id="renew_plan_id">
            <div class="modal-header">
                <h3>Record Payment / Renew</h3>
                <button type="button" class="btn-close btn-cancel-modal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 10px; font-weight: 500;">Select a Plan to Renew / Upgrade</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px;">
                        <?php foreach($plans as $plan): ?>
                            <div class="renew-plan-card" 
                                 data-id="<?= $plan['id'] ?>" 
                                 data-price="<?= $plan['price'] ?>" 
                                 style="border: 2px solid var(--border-color); border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.2s; position: relative;">
                                
                                <div style="font-weight: 700; font-size: 15px; margin-bottom: 6px; color: var(--text-main);"><?= esc($plan['name']) ?></div>
                                <div style="font-size: 16px; font-weight: 800; color: var(--primary);">₹<?= number_format($plan['price'], 2) ?> <span style="font-size: 12px; font-weight: 500; color: var(--text-muted);">/ <?= $plan['duration_months'] ?>mo</span></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Payment Amount (₹)</label>
                    <input type="number" min="0" name="amount" id="renew_amount" class="form-control" required placeholder="e.g. 4999">
                </div>
                <p style="font-size: 13px; color: var(--text-muted);">This will log a new payment and extend the subscription by the selected plan's duration.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-cancel-modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Log Payment</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Renew Modal
    $('.btn-renew').click(function() {
        $('#renew_org_id').val($(this).data('org'));
        // Reset state
        $('.renew-plan-card').css('border-color', 'var(--border-color)').removeClass('selected');
        $('.renew-plan-card .selected-indicator').remove();
        $('#renew_plan_id').val('');
        $('#renew_amount').val('');
        
        $('#modal-renew').addClass('active');
    });
    
    // Select Plan in Renew Modal
    $('.renew-plan-card').click(function() {
        $('.renew-plan-card').css('border-color', 'var(--border-color)').removeClass('selected');
        $('.renew-plan-card .selected-indicator').remove();
        
        $(this).css('border-color', 'var(--primary)').addClass('selected');
        $(this).append('<div class="selected-indicator" style="position: absolute; top: 8px; right: 8px; color: var(--primary); font-size: 14px;"><i class="fa-solid fa-circle-check"></i></div>');
        
        $('#renew_plan_id').val($(this).data('id'));
        $('#renew_amount').val($(this).data('price'));
    });
    
    // Close Modals
    $('.btn-cancel-modal').click(function() {
        $('.modal-overlay').removeClass('active');
    });
    
    // Suspend / Activate Action
    $('.btn-toggle-status').click(function() {
        var orgId = $(this).data('org');
        var newStatus = $(this).data('status');
        
        if(confirm('Are you sure you want to change the status of this organization?')) {
            $.post('<?= base_url('superadmin/toggle_status') ?>', {
                org_id: orgId,
                status: newStatus
            }, function(response) {
                if(response.success) {
                    location.reload();
                } else {
                    alert('Failed to update status.');
                }
            });
        }
    });


});
</script>
<?= $this->endSection() ?>
