<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Study Materials (CMS)<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
<div class="view-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2>Study Materials</h2>
        <p style="color:var(--text-muted); font-size: 14px; margin: 4px 0 0;">Upload and manage content library for your students.</p>
    </div>
    <a href="<?= base_url('org/materials/create') ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Upload Material</a>
</div>

<div class="widget" style="padding: 0; overflow: hidden; border: 1px solid var(--border-color);">
    <table class="data-table" style="width: 100%;">
        <thead>
            <tr>
                <th>Title</th>
                <th>Subject & Cohort</th>
                <th>Type</th>
                <th>Status</th>
                <?php if(session('is_org_admin') || session('has_manage_academics_global')): ?>
                    <th>Uploaded By</th>
                <?php endif; ?>
                <th style="width: 130px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($materials)): ?>
                <?php foreach($materials as $m): ?>
                <tr>
                    <td>
                        <strong><?= esc($m['title']) ?></strong>
                        <?php if($m['description']): ?>
                            <br><small style="color: var(--text-muted);"><?= esc(substr($m['description'], 0, 50)) ?>...</small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span style="font-weight: 500;"><?= esc($m['subject_code']) ?></span><br>
                        <small style="color: var(--text-muted);"><?= esc($m['cohort_name']) ?></small>
                    </td>
                    <td>
                        <?php if($m['type'] === 'file'): ?>
                            <span style="color: var(--primary);"><i class="fa-solid fa-file-pdf"></i> File</span>
                        <?php elseif($m['type'] === 'youtube'): ?>
                            <span style="color: #ef4444;"><i class="fa-brands fa-youtube"></i> YouTube</span>
                        <?php else: ?>
                            <span style="color: var(--text-primary);"><i class="fa-solid fa-link"></i> Link</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($m['is_active']): ?>
                            <span class="badge" style="background: rgba(5,205,153,0.1); color: var(--success);">Visible</span>
                        <?php else: ?>
                            <span class="badge" style="background: rgba(107,114,128,0.1); color: var(--text-muted);">Hidden</span>
                        <?php endif; ?>
                    </td>
                    <?php if(session('is_org_admin') || session('has_manage_academics_global')): ?>
                        <td><?= esc($m['full_name']) ?></td>
                    <?php endif; ?>
                    <td>
                        <?php if($m['type'] === 'file' && $m['file_path']): ?>
                            <a href="<?= base_url($m['file_path']) ?>" target="_blank" class="btn btn-outline" style="padding: 4px 10px;" title="View File"><i class="fa-solid fa-eye"></i></a>
                        <?php elseif($m['external_url']): ?>
                            <a href="<?= esc($m['external_url']) ?>" target="_blank" class="btn btn-outline" style="padding: 4px 10px;" title="Open Link"><i class="fa-solid fa-external-link"></i></a>
                        <?php endif; ?>
                        
                        <a href="<?= base_url('org/materials/edit/' . ($m['uuid'] ?? $m['id'])) ?>" class="btn btn-outline" style="padding: 4px 10px; margin-left: 4px;"><i class="fa-solid fa-pen"></i></a>
                        <form action="<?= base_url('org/materials/delete/' . ($m['uuid'] ?? $m['id'])) ?>" method="POST" style="display:inline;" onsubmit="return confirm('Delete this material?');">
                            <?= csrf_field() ?>
                            <button class="btn btn-outline" style="padding: 4px 10px; color: var(--danger); border-color: rgba(238,93,80,0.2);"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">No study materials found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</section>
<?= $this->endSection() ?>
