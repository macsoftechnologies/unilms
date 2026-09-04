<?= $this->extend('super_admin/layout') ?>
<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
        <div>
            <h2>Content Creators</h2>
        </div>
        <button class="btn btn-primary" onclick="openCreatorModal()"><i class="fa-solid fa-plus"></i> Add Content Creator</button>
    </div>

    <!-- Stats Quick Cards -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="stat-card" style="padding: 16px 20px; border-radius: 12px; background: var(--card-bg); border: 1px solid var(--border-color);">
            <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Total Content Creators</div>
            <div style="font-size: 24px; font-weight: 700; color: var(--primary); margin-top: 4px;"><?= count($creators) ?></div>
        </div>
        <div class="stat-card" style="padding: 16px 20px; border-radius: 12px; background: var(--card-bg); border: 1px solid var(--border-color);">
            <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Active Creators</div>
            <div style="font-size: 24px; font-weight: 700; color: #16A34A; margin-top: 4px;">
                <?= count(array_filter($creators, fn($c) => $c['status'] === 'active')) ?>
            </div>
        </div>
        <div class="stat-card" style="padding: 16px 20px; border-radius: 12px; background: var(--card-bg); border: 1px solid var(--border-color);">
            <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Creator Studio Login URL</div>
            <div style="font-size: 13px; font-weight: 600; color: var(--text-primary); margin-top: 8px;">
                <a href="<?= base_url('creator/login') ?>" target="_blank" style="color: var(--primary); text-decoration: none;">
                    <?= base_url('creator/login') ?> <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 11px;"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Creator Name & Email</th>
                    <th>Assigned Organization</th>
                    <th>Published Courses</th>
                    <th>Status</th>
                    <th>Created On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($creators)): ?>
                    <?php foreach($creators as $creator): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 700; font-size: 14px;"><?= esc($creator['name']) ?></div>
                            <div style="font-size: 12px; color: var(--text-muted);"><?= esc($creator['email']) ?></div>
                        </td>
                        <td>
                            <?php if(empty($creator['org_id'])): ?>
                                <span class="badge" style="background: rgba(124,58,237,0.12); color: #7C3AED; font-weight: 600;">
                                    <i class="fa-solid fa-globe" style="margin-right: 4px;"></i> Global (All Orgs)
                                </span>
                            <?php else: ?>
                                <span class="badge badge-primary">
                                    <i class="fa-solid fa-building" style="margin-right: 4px;"></i> <?= esc($creator['org_name']) ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span style="font-weight: 600;"><?= $creator['course_count'] ?></span> courses
                        </td>
                        <td>
                            <?php if($creator['status'] === 'active'): ?>
                                <span class="badge badge-success">Active</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td><?= !empty($creator['created_at']) ? date('d/m/Y', strtotime($creator['created_at'])) : '—' ?></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon text-primary" onclick="editCreator(<?= htmlspecialchars(json_encode($creator), ENT_QUOTES, 'UTF-8') ?>)" title="Edit Creator">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="<?= base_url('superadmin/creators/delete/'.$creator['id']) ?>" method="POST" onsubmit="return confirm('Delete creator <?= esc($creator['name']) ?>?');" style="display:inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-icon text-danger" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center; padding:30px; color:var(--text-muted);">No content creators found. Click "+ Add Content Creator" to create one.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Standard Creator Form Modal -->
<div class="modal-overlay" id="modal-creator">
    <div class="modal-content" style="max-width: 520px;">
        <form action="<?= base_url('superadmin/creators/save') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="creator_id" id="creator_id" value="">
            
            <div class="modal-header">
                <h3 id="creatorModalTitle">Add Content Creator</h3>
                <button type="button" class="btn-close btn-cancel-modal"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <div class="modal-body">
                <div class="form-group" style="margin-bottom: 14px;">
                    <label>Full Name *</label>
                    <input type="text" name="name" id="creator_name" class="form-control" placeholder="e.g. Prof. Rajesh Sharma" required>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label>Email Address *</label>
                    <input type="email" name="email" id="creator_email" class="form-control" placeholder="rajesh@ait.edu" required>
                </div>
                
                <div class="form-group" style="margin-bottom: 14px;">
                    <label id="passwordLabel">Password *</label>
                    <input type="password" name="password" id="creator_password" class="form-control" placeholder="Enter secure password">
                    <small id="passwordHelp" style="color:var(--text-muted); font-size:11px; display:none;">Leave blank to keep existing password.</small>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label>Assigned Institution / Scope *</label>
                    <select name="org_id" id="creator_org_id" class="form-control">
                        <option value="">🌐 Global (Master Courses for All Organizations)</option>
                        <?php foreach($organizations as $org): ?>
                            <option value="<?= $org['id'] ?>">🏢 <?= esc($org['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color:var(--text-muted); font-size:11px; margin-top:4px; display:block;">Select an organization to assign authoring to that college, or leave Global.</small>
                </div>

                <div class="form-group" style="margin-bottom: 10px;">
                    <label>Account Status</label>
                    <select name="status" id="creator_status" class="form-control">
                        <option value="active">Active (Can log in & publish)</option>
                        <option value="inactive">Inactive / Suspended</option>
                    </select>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-cancel-modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Creator</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.btn-cancel-modal').click(function() {
        $('#modal-creator').removeClass('active');
    });
});

function openCreatorModal() {
    $('#creatorModalTitle').text('Add Content Creator');
    $('#creator_id').val('');
    $('#creator_name').val('');
    $('#creator_email').val('');
    $('#creator_password').val('').prop('required', true);
    $('#passwordLabel').text('Password *');
    $('#passwordHelp').hide();
    $('#creator_org_id').val('');
    $('#creator_status').val('active');
    $('#modal-creator').addClass('active');
}

function editCreator(creator) {
    $('#creatorModalTitle').text('Edit Content Creator');
    $('#creator_id').val(creator.id);
    $('#creator_name').val(creator.name);
    $('#creator_email').val(creator.email);
    $('#creator_password').val('').prop('required', false);
    $('#passwordLabel').text('Change Password (Optional)');
    $('#passwordHelp').show();
    $('#creator_org_id').val(creator.org_id || '');
    $('#creator_status').val(creator.status || 'active');
    $('#modal-creator').addClass('active');
}
</script>

<?= $this->endSection() ?>
