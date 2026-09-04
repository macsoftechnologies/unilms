<?= $this->extend('super_admin/layout') ?>
<?= $this->section('content') ?>

<?php if(session()->getFlashdata('error')): ?>
    <div style="padding: 10px 32px; color: red; font-weight: bold;">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<section class="view-section active">
    <div class="view-header">
        <h2>Subscription Plans</h2>
        <button class="btn btn-primary" id="btn-add-plan"><i class="fa-solid fa-plus"></i> Create Plan</button>
    </div>
    <div class="plans-grid">
        <?php foreach($plans as $plan): ?>
            <div class="plan-card <?= $plan['name'] == 'Professional' ? 'premium' : '' ?>">
                <h3><?= esc($plan['name']) ?></h3>
                <div class="plan-price">₹<?= number_format($plan['price'], 2) ?><span> / <?= $plan['duration_months'] ?>mo</span></div>
                <ul class="plan-features">
                    <?php 
                        $features = explode(',', $plan['features']);
                        foreach($features as $feat): 
                    ?>
                        <li><i class="fa-solid fa-check"></i> <?= esc(trim($feat)) ?></li>
                    <?php endforeach; ?>
                </ul>
                <div class="action-buttons" style="justify-content: center; margin-top: 20px;">
                    <button class="btn btn-outline btn-edit-plan" data-id="<?= $plan['id'] ?>" data-name="<?= esc($plan['name']) ?>" data-price="<?= $plan['price'] ?>" data-duration="<?= $plan['duration_months'] ?>" data-features="<?= esc($plan['features']) ?>">Edit</button>
                    <button class="btn btn-outline btn-delete-plan text-danger" data-id="<?= $plan['id'] ?>" style="border-color: var(--danger); color: var(--danger);">Delete</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Add Plan Modal -->
<div class="modal-overlay" id="modal-add-plan">
    <div class="modal-content">
        <form action="<?= base_url('superadmin/add_plan') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h3>Create New Plan</h3>
                <button type="button" class="btn-close btn-cancel-modal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Plan Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Starter">
                </div>
                <div class="form-group">
                    <label>Price (₹)</label>
                    <input type="number" min="0" name="price" step="0.01" class="form-control" required placeholder="e.g. 1999">
                </div>
                <div class="form-group">
                    <label>Duration (Months)</label>
                    <input type="number" min="0" name="duration_months" class="form-control" required value="1">
                </div>
                <div class="form-group">
                    <label>Features (comma separated)</label>
                    <textarea name="features" class="form-control" required placeholder="CRM Module, Up to 10 Users"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-cancel-modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Plan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Plan Modal -->
<div class="modal-overlay" id="modal-edit-plan">
    <div class="modal-content">
        <form action="<?= base_url('superadmin/edit_plan') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="plan_id" id="edit_plan_id">
            <div class="modal-header">
                <h3>Edit Plan</h3>
                <button type="button" class="btn-close btn-cancel-modal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Plan Name</label>
                    <input type="text" name="name" id="edit_plan_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Price (₹)</label>
                    <input type="number" min="0" name="price" id="edit_plan_price" step="0.01" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Duration (Months)</label>
                    <input type="number" min="0" name="duration_months" id="edit_plan_duration" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Features (comma separated)</label>
                    <textarea name="features" id="edit_plan_features" class="form-control" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-cancel-modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Plan Modal -->
<div class="modal-overlay" id="modal-delete-plan">
    <div class="modal-content">
        <form action="<?= base_url('superadmin/delete_plan') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="plan_id" id="delete_plan_id">
            <div class="modal-header">
                <h3>Delete Plan</h3>
                <button type="button" class="btn-close btn-cancel-modal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this plan? This action cannot be undone.</p>
                <p style="font-size:13px; color:var(--text-muted);">Note: You cannot delete a plan if organizations are currently subscribed to it.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-cancel-modal">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background-color: var(--danger);">Delete Plan</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#btn-add-plan').click(function() {
        $('#modal-add-plan').addClass('active');
    });
    
    $('.btn-edit-plan').click(function() {
        $('#edit_plan_id').val($(this).data('id'));
        $('#edit_plan_name').val($(this).data('name'));
        $('#edit_plan_price').val($(this).data('price'));
        $('#edit_plan_duration').val($(this).data('duration'));
        $('#edit_plan_features').val($(this).data('features'));
        $('#modal-edit-plan').addClass('active');
    });
    
    $('.btn-delete-plan').click(function() {
        $('#delete_plan_id').val($(this).data('id'));
        $('#modal-delete-plan').addClass('active');
    });
    
    $('.btn-cancel-modal').click(function() {
        $('.modal-overlay').removeClass('active');
    });
});
</script>
<?= $this->endSection() ?>
