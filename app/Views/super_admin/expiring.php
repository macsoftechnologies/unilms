<?= $this->extend('super_admin/layout') ?>
<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Expiring Organizations (Next 20 Days)</h2>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Organization</th>
                    <th>Plan</th>
                    <th>Subscription End Date</th>
                    <th>Days Remaining</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($expiring_orgs)): ?>
                    <?php foreach($expiring_orgs as $org): ?>
                    <?php
                        $endDate = new DateTime($org['subscription_end_date']);
                        $now = new DateTime(date('Y-m-d'));
                        $diff = $now->diff($endDate)->days;
                    ?>
                    <tr>
                        <td><?= esc($org['name']) ?></td>
                        <td><?= esc($org['plan_name']) ?></td>
                        <td><span class="badge badge-warning"><?= date('d/m/Y', strtotime($org['subscription_end_date'])) ?></span></td>
                        <td style="color: var(--warning); font-weight: bold;"><?= $diff ?> days</td>
                        <td>
                            <button class="btn btn-primary btn-renew" data-org="<?= $org['id'] ?>">Renew Now</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">No organizations expiring soon!</td></tr>
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
    <div class="modal-content">
        <form action="<?= base_url('superadmin/renew_organization') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="org_id" id="renew_org_id">
            <div class="modal-header">
                <h3>Record Payment / Renew</h3>
                <button type="button" class="btn-close btn-cancel-modal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Payment Amount (₹)</label>
                    <input type="number" min="0" name="amount" class="form-control" required placeholder="e.g. 4999">
                </div>
                <p style="font-size: 13px; color: var(--text-muted);">This will log a new payment and extend the subscription by 1 month.</p>
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
    $('.btn-renew').click(function() {
        $('#renew_org_id').val($(this).data('org'));
        $('#modal-renew').addClass('active');
    });
    
    $('.btn-cancel-modal').click(function() {
        $('.modal-overlay').removeClass('active');
    });
});
</script>
<?= $this->endSection() ?>
