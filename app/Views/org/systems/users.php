<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>User Management<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
<div class="view-header" style="flex-wrap: wrap; gap: 12px;">
    <div>
        <h2 style="margin-bottom: 4px;">User Directory & Access</h2>
        <p style="color: var(--text-muted); font-size: 13px; margin: 0;">Manage institutional staff, faculty, student, and parent user credentials.</p>
    </div>
    <div style="display: flex; gap: 8px; align-items: center;">
        <button class="btn btn-primary" onclick="openAddUser()"><i class="fa-solid fa-plus"></i> Add User</button>
    </div>
</div>

<!-- Filter Tabs -->
<div style="display: flex; gap: 8px; margin-bottom: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
    <a href="<?= base_url('org/systems/users?type=all') ?>" class="btn <?= ($current_type ?? 'all') === 'all' ? 'btn-primary' : 'btn-outline' ?>" style="font-size: 12.5px; padding: 6px 14px; border-radius: 20px;">
        All Accounts <span class="badge" style="background: rgba(0,0,0,0.1); margin-left: 4px;"><?= $counts['all'] ?? 0 ?></span>
    </a>
    <a href="<?= base_url('org/systems/users?type=staff') ?>" class="btn <?= ($current_type ?? '') === 'staff' ? 'btn-primary' : 'btn-outline' ?>" style="font-size: 12.5px; padding: 6px 14px; border-radius: 20px;">
        Staff & Faculty <span class="badge" style="background: rgba(0,0,0,0.1); margin-left: 4px;"><?= $counts['staff'] ?? 0 ?></span>
    </a>
    <a href="<?= base_url('org/systems/users?type=student') ?>" class="btn <?= ($current_type ?? '') === 'student' ? 'btn-primary' : 'btn-outline' ?>" style="font-size: 12.5px; padding: 6px 14px; border-radius: 20px;">
        Students <span class="badge" style="background: rgba(0,0,0,0.1); margin-left: 4px;"><?= $counts['student'] ?? 0 ?></span>
    </a>
    <a href="<?= base_url('org/systems/users?type=parent') ?>" class="btn <?= ($current_type ?? '') === 'parent' ? 'btn-primary' : 'btn-outline' ?>" style="font-size: 12.5px; padding: 6px 14px; border-radius: 20px;">
        Parents <span class="badge" style="background: rgba(0,0,0,0.1); margin-left: 4px;"><?= $counts['parent'] ?? 0 ?></span>
    </a>
</div>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID / Code</th>
                <th>Name</th>
                <th>Email</th>
                <th>Designation / Group</th>
                <th>Access Groups</th>
                <th>Account Role</th>
                <?php if(session()->get('is_org_admin')): ?>
                <th>Audit Info</th>
                <?php endif; ?>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($users)): ?>
                <?php foreach($users as $user): ?>
                <tr>
                    <td><span class="badge badge-primary"><?= esc($user['employee_code'] ?? '—') ?></span></td>
                    <td><strong><?= esc($user['full_name'] ?? '-') ?></strong></td>
                    <td style="color: var(--text-secondary);"><?= esc($user['email']) ?></td>
                    <td><?= esc(empty($user['designation']) ? '—' : $user['designation']) ?></td>
                    <td>
                        <?php if(!empty($user_groups[$user['id']])): ?>
                            <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                <?php foreach($user_groups[$user['id']] as $gname): ?>
                                    <span class="badge badge-secondary"><?= esc($gname) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <span style="color: var(--text-muted); font-size: 12px;">None</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($user['is_org_admin']): ?>
                            <span class="badge badge-success"><i class="fa-solid fa-shield-halved me-1"></i> Org Admin</span>
                        <?php elseif(($user['user_type'] ?? '') === 'student' || strtolower($user['role'] ?? '') === 'student'): ?>
                            <span class="badge" style="background: rgba(124, 58, 237, 0.12); color: #7c3aed; font-weight: 600;"><i class="fa-solid fa-graduation-cap me-1"></i> Student</span>
                        <?php elseif(($user['user_type'] ?? '') === 'parent' || strtolower($user['role'] ?? '') === 'parent'): ?>
                            <span class="badge" style="background: rgba(14, 165, 233, 0.12); color: #0284c7; font-weight: 600;"><i class="fa-solid fa-user-group me-1"></i> Parent</span>
                        <?php elseif(strtolower($user['role'] ?? '') === 'faculty'): ?>
                            <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: #059669; font-weight: 600;"><i class="fa-solid fa-chalkboard-user me-1"></i> Faculty</span>
                        <?php else: ?>
                            <span class="badge badge-warning"><i class="fa-solid fa-user-tie me-1"></i> Staff</span>
                        <?php endif; ?>
                    </td>
                    <?php if(session()->get('is_org_admin')): ?>
                    <td style="font-size: 11px; color: var(--text-muted); line-height: 1.3;">
                        <?php if(!empty($user['created_at'])): ?>
                            <div><i class="fa-solid fa-asterisk" style="font-size:9px; margin-right:3px;"></i> <?= esc($user['created_by_name'] ?? 'System') ?> (<?= date('M d', strtotime($user['created_at'])) ?>)</div>
                        <?php endif; ?>
                        <?php if(!empty($user['updated_by_name'])): ?>
                            <div style="margin-top:2px;"><i class="fa-solid fa-pen" style="font-size:9px; margin-right:3px;"></i> <?= esc($user['updated_by_name']) ?></div>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                    <td>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <button class="btn btn-outline btn-icon" onclick="editUser(<?= htmlspecialchars(json_encode($user), ENT_QUOTES) ?>)" title="Edit User" style="width: 28px; height: 28px; font-size: 11px;"><i class="fa-solid fa-pen"></i></button>
                            <?php if($user['id'] != session()->get('org_user_id')): ?>
                            <form action="<?= base_url('org/systems/users/delete') ?>" method="POST" style="display:inline; margin: 0;" onsubmit="return confirm('Delete this user?')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn btn-outline btn-icon" title="Delete User" style="width: 28px; height: 28px; font-size: 11px; color: var(--danger); border-color: rgba(239, 68, 68, 0.3);"><i class="fa-solid fa-trash"></i></button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="<?= session()->get('is_org_admin') ? '7' : '6' ?>" style="text-align:center; padding: 40px; color: var(--text-muted);">No users found. Add your first team member!</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.side-panel-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(4px);
    z-index: 9998;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}
.side-panel-overlay.active {
    opacity: 1;
    visibility: visible;
}
.side-panel {
    position: fixed;
    top: 0;
    right: -450px;
    width: 420px;
    max-width: 100%;
    height: 100vh;
    background: var(--card-bg, #fff);
    box-shadow: -10px 0 40px rgba(0,0,0,0.15);
    z-index: 9999;
    transition: right 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    display: flex;
    flex-direction: column;
}
.side-panel.active {
    right: 0;
}
.side-panel-header {
    padding: 24px 32px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--bg-main);
}
.side-panel-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: var(--text-main);
}
.side-panel-header .btn-close {
    background: none;
    border: none;
    font-size: 20px;
    color: var(--text-muted);
    cursor: pointer;
    transition: color 0.2s;
}
.side-panel-header .btn-close:hover {
    color: var(--danger);
}
.side-panel-body {
    padding: 32px;
    flex: 1;
    overflow-y: auto;
}
.side-panel-footer {
    padding: 24px 32px;
    border-top: 1px solid var(--border-color);
    display: flex;
    gap: 12px;
    background: var(--bg-main);
}
.side-panel-footer button {
    flex: 1;
}
</style>

<!-- Add/Edit User Side Panel -->
<div class="side-panel-overlay" id="panel-overlay" onclick="closePanel()"></div>
<div class="side-panel" id="user-panel">
    <form action="<?= base_url('org/systems/users/save') ?>" method="POST" style="display: flex; flex-direction: column; height: 100%;">
        <?= csrf_field() ?>
        <input type="hidden" name="user_id" id="user_id">
        <div class="side-panel-header">
            <h3 id="modal-user-title">Add New User</h3>
            <button type="button" class="btn-close" onclick="closePanel()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="side-panel-body">
            <div class="form-group">
                <label>Employee ID / Staff Code</label>
                <input type="text" name="employee_code" id="user_employee_code" class="form-control" placeholder="Leave blank to auto-generate (e.g. EMP-1001)">
            </div>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" id="user_full_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" id="user_email" class="form-control" required>
            </div>
            <div class="form-group">
                <label id="password_label">Password</label>
                <input type="text" name="password" id="user_password" class="form-control">
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" pattern="[0-9]{10}" maxlength="10" name="phone" id="user_phone" class="form-control">
            </div>
            <div class="form-group">
                <label>Designation</label>
                <input type="text" name="designation" id="user_designation" class="form-control" placeholder="e.g. Professor, Admin">
            </div>
            
            <div style="margin: 32px 0 24px; border-top: 1px dashed var(--border-color);"></div>
            
            <?php if(session()->get('is_org_admin')): ?>
            <div class="form-group" style="background: rgba(109, 40, 217, 0.05); padding: 16px; border-radius: 8px; border: 1px solid rgba(109, 40, 217, 0.1);">
                <label style="margin: 0; display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 600; color: var(--primary);">
                    <input type="checkbox" name="is_org_admin" id="user_is_admin" value="1" style="width: 18px; height: 18px; accent-color: var(--primary);"> 
                    Grant Organization Admin
                </label>
                <p style="margin: 6px 0 0 28px; font-size: 12px; color: var(--text-muted);">Admins have full, unrestricted access to all modules and settings.</p>
            </div>
            <?php endif; ?>

            
            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 12px;">Assign Access Groups</label>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <?php if(!empty($access_groups)): ?>
                        <?php foreach($access_groups as $ag): ?>
                            <label style="display: flex; align-items: center; gap: 10px; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border-color)'">
                                <input type="checkbox" name="group_ids[]" value="<?= $ag['id'] ?>" class="group-checkbox" style="width: 16px; height: 16px; accent-color: var(--primary);"> 
                                <span style="font-weight: 500; font-size: 14px;"><?= esc($ag['name']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="padding: 16px; background: var(--bg-main); border-radius: 8px; text-align: center;">
                            <span style="color: var(--text-muted); font-size: 13px;">No Access Groups created yet.</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="side-panel-footer">
            <button type="button" class="btn btn-outline" onclick="closePanel()">Cancel</button>
            <button type="submit" class="btn btn-primary">Save User</button>
        </div>
    </form>
</div>

<script>
function openAddUser() {
    $('#user_id').val('');
    $('#user_employee_code').val('');
    $('#user_full_name').val('');
    $('#user_email').val('');
    $('#user_password').val('').attr('required', true);
    $('#password_label').text('Password');
    $('#user_phone').val('');
    $('#user_designation').val('');
    $('#user_is_admin').prop('checked', false);
    $('.group-checkbox').prop('checked', false);
    $('#modal-user-title').text('Add New User');
    
    $('#panel-overlay').addClass('active');
    $('#user-panel').addClass('active');
}

function editUser(user) {
    $('#user_id').val(user.id);
    $('#user_employee_code').val(user.employee_code || '');
    $('#user_full_name').val(user.full_name || '');
    $('#user_email').val(user.email);
    $('#user_password').val('').removeAttr('required');
    $('#password_label').text('New Password (leave blank to keep)');
    $('#user_phone').val(user.phone || '');
    $('#user_designation').val(user.designation || '');
    $('#user_is_admin').prop('checked', user.is_org_admin == 1);
    
    $('.group-checkbox').prop('checked', false);
    $('#modal-user-title').text('Edit User');
    
    $('#panel-overlay').addClass('active');
    $('#user-panel').addClass('active');
}

function closePanel() {
    $('#panel-overlay').removeClass('active');
    $('#user-panel').removeClass('active');
}
</script>

</section>

<?= $this->endSection() ?>
