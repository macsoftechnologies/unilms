<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Access Groups<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
<div class="view-header">
    <h2>Access Groups</h2>
    <a href="<?= base_url('org/systems/access-groups/create') ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Create Access Group</a>
</div>

<?php if(!empty($groups)): ?>
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
    <?php foreach($groups as $group): ?>
    <div class="card" style="display: flex; flex-direction: column;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
            <div>
                <h3 style="font-size: 17px; margin-bottom: 4px;"><?= esc($group['name']) ?></h3>
                <p style="font-size: 13px; color: var(--text-muted);"><?= esc($group['description'] ?? 'No description') ?></p>
            </div>
        </div>
        <div style="display: flex; gap: 16px; margin-bottom: 16px;">
            <div>
                <span style="font-size: 22px; font-weight: 700;"><?= $group_stats[$group['id']]['perms'] ?></span>
                <span style="font-size: 12px; color: var(--text-muted); display: block;">Permissions</span>
            </div>
            <div>
                <span style="font-size: 22px; font-weight: 700;"><?= $group_stats[$group['id']]['users'] ?></span>
                <span style="font-size: 12px; color: var(--text-muted); display: block;">Users</span>
            </div>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="<?= base_url('org/systems/access-groups/edit/' . $group['id']) ?>" class="btn btn-outline" style="flex: 1; justify-content: center;"><i class="fa-solid fa-pen"></i> Edit</a>
            <form action="<?= base_url('org/systems/access-groups/delete') ?>" method="POST" style="flex: 1;" onsubmit="return confirm('Delete this group? Users in it will lose these permissions.')">
                <?= csrf_field() ?>
                <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
                <button type="submit" class="btn btn-outline" style="width: 100%; justify-content: center; color: var(--danger); border-color: var(--danger);"><i class="fa-solid fa-trash"></i> Delete</button>
            </form>
        </div>
        
        <?php if(session()->get('is_org_admin')): ?>
        <div style="margin-top: 16px; padding-top: 12px; border-top: 1px dashed var(--border-color); font-size: 11px; color: var(--text-muted); display: flex; flex-direction: column; gap: 4px;">
            <div><i class="fa-solid fa-asterisk" style="margin-right:4px;"></i>Created by <strong><?= esc($group['created_by_name'] ?? 'System') ?></strong> <?php if(!empty($group['created_at'])) echo '(' . date('d/m/Y', strtotime($group['created_at'])) . ')'; ?></div>
            <?php if(!empty($group['updated_by_name'])): ?>
            <div><i class="fa-solid fa-pen" style="margin-right:4px;"></i>Updated by <strong><?= esc($group['updated_by_name']) ?></strong> <?php if(!empty($group['updated_at'])) echo '(' . date('d/m/Y', strtotime($group['updated_at'])) . ')'; ?></div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="table-container" style="padding: 60px; text-align: center;">
    <i class="fa-solid fa-shield-halved" style="font-size: 48px; color: var(--text-muted); margin-bottom: 16px;"></i>
    <h3 style="margin-bottom: 8px;">No Access Groups Yet</h3>
    <p style="color: var(--text-muted); margin-bottom: 24px;">Create your first Access Group to start defining custom roles for your team.</p>
    <a href="<?= base_url('org/systems/access-groups/create') ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Create Access Group</a>
</div>
<?php endif; ?>

</section>
<?= $this->endSection() ?>
