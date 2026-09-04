<?= $this->extend('super_admin/layout') ?>
<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Super Admins</h2>
        <button class="btn btn-primary" onclick="openAdminModal()"><i class="fa-solid fa-plus"></i> Add Admin</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($admins)): ?>
                    <?php foreach($admins as $admin): ?>
                    <tr>
                        <td>
                            <?= esc($admin['email']) ?> 
                            <?php if($admin['email'] == session()->get('admin_email')) echo '<span class="badge badge-success" style="margin-left:8px;">You</span>'; ?>
                        </td>
                        <td>
                            <?php if($admin['is_root']): ?>
                                <span class="badge badge-primary">Root Admin</span>
                            <?php else: ?>
                                <span class="badge" style="background:#e2e8f0; color:#475569;">Sub Admin</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d/m/Y, h:i A', strtotime($admin['created_at'])) ?></td>
                        <td>
                            <div class="action-buttons">
                                <?php if(!$admin['is_root'] || session()->get('admin_email') == $admin['email']): ?>
                                    <button class="btn-icon text-primary" onclick="editAdmin(<?= $admin['id'] ?>, '<?= esc($admin['email']) ?>', '<?= esc($admin['permissions'] ?? '[]', 'js') ?>')" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                <?php endif; ?>
                                
                                <?php if(!$admin['is_root']): ?>
                                    <button class="btn-icon text-danger" onclick="deleteAdmin(<?= $admin['id'] ?>)" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">No admins found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Admin Form Modal -->
<div class="modal-overlay" id="modal-admin">
    <div class="modal-content" style="max-width: 500px;">
        <form action="<?= base_url('superadmin/users/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h3 id="admin-modal-title">Add Super Admin</h3>
                <button type="button" class="btn-close btn-cancel-modal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="admin_id" id="admin_id">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" id="admin_email" class="form-control" required>
                </div>
                <div class="form-group" style="margin-top: 16px;">
                    <label>Password <small id="admin-pw-hint" style="color:var(--text-muted); font-weight:normal;"></small></label>
                    <input type="password" name="password" id="admin_password" class="form-control">
                </div>
                
                <div class="form-group" style="margin-top: 24px;">
                    <label style="display:block; margin-bottom:12px; font-weight:600;">Sub Admin Permissions</label>
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                            <input type="checkbox" name="permissions[]" value="organizations" class="perm-checkbox"> Organizations
                        </label>
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                            <input type="checkbox" name="permissions[]" value="plans" class="perm-checkbox"> Plans
                        </label>
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                            <input type="checkbox" name="permissions[]" value="payments" class="perm-checkbox"> Payments
                        </label>
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                            <input type="checkbox" name="permissions[]" value="settings" class="perm-checkbox"> Settings & Logs
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-cancel-modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Admin</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-admin-form" action="<?= base_url('superadmin/users/delete') ?>" method="POST" style="display:none;">
    <?= csrf_field() ?>
    <input type="hidden" name="admin_id" id="delete_admin_id">
</form>

<script>
$(document).ready(function() {
    $('.btn-cancel-modal').click(function() {
        $('.modal-overlay').removeClass('active');
    });
});

function openAdminModal() {
    $('#admin_id').val('');
    $('#admin_email').val('');
    $('#admin_password').val('').prop('required', true);
    $('#admin-pw-hint').text('');
    $('#admin-modal-title').text('Add Super Admin');
    $('.perm-checkbox').prop('checked', false);
    $('#modal-admin').addClass('active');
}

function editAdmin(id, email, permissionsJSON) {
    $('#admin_id').val(id);
    $('#admin_email').val(email);
    $('#admin_password').val('').prop('required', false);
    $('#admin-pw-hint').text('(Leave blank to keep current password)');
    $('#admin-modal-title').text('Edit Super Admin');
    
    $('.perm-checkbox').prop('checked', false);
    try {
        let perms = JSON.parse(permissionsJSON);
        if (Array.isArray(perms)) {
            perms.forEach(function(p) {
                $('input[value="'+p+'"]').prop('checked', true);
            });
        }
    } catch(e) {}
    
    $('#modal-admin').addClass('active');
}

function deleteAdmin(id) {
    if(confirm('Are you sure you want to delete this super admin?')) {
        document.getElementById('delete_admin_id').value = id;
        document.getElementById('delete-admin-form').submit();
    }
}
</script>
<?= $this->endSection() ?>
