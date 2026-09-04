<?= $this->extend('super_admin/layout') ?>
<?= $this->section('content') ?>

<section class="view-section active">
    <div class="view-header" style="margin-bottom: 20px;">
        <h2>Organization Details: <?= esc($org['name']) ?></h2>
        <a href="<?= base_url('superadmin/organizations') ?>" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
        
        <!-- Organization Info Card -->
        <div class="card" style="background: var(--bg-card); padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); align-self: start;">
            <h3 style="margin-top: 0; color: var(--text-main); margin-bottom: 20px;">Overview</h3>
            <hr style="border: 0; border-top: 1px solid var(--border-color); margin-bottom: 20px;">
            
            <div style="margin-bottom: 16px;">
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Organization Name</div>
                <div style="font-weight: 600; font-size: 16px;"><?= esc($org['name']) ?></div>
            </div>
            
            <div style="margin-bottom: 16px;">
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Admin Email</div>
                <div style="font-weight: 500;"><?= esc($org['admin_email']) ?></div>
            </div>
            
            <div style="margin-bottom: 16px;">
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Current Plan</div>
                <div><span class="badge badge-primary"><?= esc($org['plan_name']) ?></span></div>
            </div>
            
            <div style="margin-bottom: 16px;">
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Modules Enabled</div>
                <div style="display: flex; gap: 8px;">
                    <?php if(isset($org['cms_enabled']) && $org['cms_enabled']): ?><span class="badge badge-success">CMS</span><?php elseif(!isset($org['cms_enabled'])): ?><span class="badge badge-success">CMS</span><?php endif; ?>
                    <?php if($org['lms_enabled']): ?><span class="badge badge-success">LMS</span><?php endif; ?>
                </div>
            </div>
            
            <div style="margin-bottom: 16px;">
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Status</div>
                <div>
                    <?php if($org['status'] == 'active'): ?>
                        <span class="badge badge-success">Active</span>
                    <?php else: ?>
                        <span class="badge badge-danger">Suspended</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div style="margin-bottom: 16px;">
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Subscription Ends</div>
                <div style="font-weight: 500; color: <?= strtotime($org['subscription_end_date']) < time() ? 'var(--danger)' : 'inherit' ?>;">
                    <?= date('d/m/Y', strtotime($org['subscription_end_date'])) ?>
                </div>
            </div>
            
            <div style="margin-bottom: 16px;">
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Joined On</div>
                <div><?= date('d/m/Y', strtotime($org['created_at'])) ?></div>
            </div>
            
            <div style="margin-top: 24px;">
                <a href="<?= base_url('superadmin/edit_organization/'.$org['id']) ?>" class="btn btn-primary" style="width: 100%; text-align: center;"><i class="fa-solid fa-pen"></i> Edit Organization</a>
            </div>
        </div>
        
        <!-- Organization Users List -->
        <div class="card" style="background: var(--bg-card); padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; color: var(--text-main);">Organization Users</h3>
                <span class="badge" style="background: var(--primary); color: white;"><?= count($users) ?> Users</span>
            </div>
            <hr style="border: 0; border-top: 1px solid var(--border-color); margin-bottom: 20px;">
            
            <div class="table-container" style="box-shadow: none; padding: 0;">
                <table class="data-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($users)): ?>
                            <?php foreach($users as $user): ?>
                            <tr>
                                <td style="font-weight: 500;"><?= esc($user['email']) ?></td>
                                <td>
                                    <?php if($user['role'] == 'admin'): ?>
                                        <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">Admin</span>
                                    <?php else: ?>
                                        <span class="badge" style="background: rgba(100, 116, 139, 0.1); color: #64748b;"><?= esc(ucfirst($user['role'])) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" style="text-align: center; padding: 20px; color: var(--text-muted);">No users found for this organization.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</section>

<?= $this->endSection() ?>
