<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Assignments (CMS)<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
<div class="view-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2>Manage Assignments</h2>
        <p style="color:var(--text-muted); font-size: 14px; margin: 4px 0 0;">Create, publish, and grade student assignments.</p>
    </div>
    <a href="<?= base_url('org/assignments/create') ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Create Assignment</a>
</div>

<div class="widget" style="padding: 0; overflow: hidden; border: 1px solid var(--border-color);">
    <table class="data-table" style="width: 100%;">
        <thead>
            <tr>
                <th>Title</th>
                <th>Subject & Cohort</th>
                <th>Due Date</th>
                <th>Status</th>
                <?php if(session('is_org_admin') || session('has_manage_academics_global')): ?>
                    <th>Faculty</th>
                <?php endif; ?>
                <th style="width: 180px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($assignments)): ?>
                <?php foreach($assignments as $a): ?>
                <tr>
                    <td>
                        <strong><?= esc($a['title']) ?></strong><br>
                        <small style="color: var(--text-muted);">Max Marks: <?= esc($a['max_marks']) ?></small>
                    </td>
                    <td>
                        <span style="font-weight: 500;"><?= esc($a['subject_code']) ?></span><br>
                        <small style="color: var(--text-muted);"><?= esc($a['cohort_name']) ?></small>
                    </td>
                    <td>
                        <?php 
                            $dueDate = new \DateTime($a['due_date']);
                            $now = new \DateTime();
                            $color = ($dueDate < $now) ? 'var(--danger)' : 'var(--text-primary)';
                        ?>
                        <span style="color: <?= $color ?>;"><?= $dueDate->format('d M Y, h:i A') ?></span>
                    </td>
                    <td>
                        <?php if($a['is_active']): ?>
                            <span class="badge" style="background: rgba(5,205,153,0.1); color: var(--success);">Published</span>
                        <?php else: ?>
                            <span class="badge" style="background: rgba(107,114,128,0.1); color: var(--text-muted);">Draft</span>
                        <?php endif; ?>
                    </td>
                    <?php if(session('is_org_admin') || session('has_manage_academics_global')): ?>
                        <td><?= esc($a['full_name']) ?></td>
                    <?php endif; ?>
                    <td>
                        <a href="<?= base_url('org/assignments/submissions/'.$a['id']) ?>" class="btn btn-outline" style="padding: 4px 10px; font-size: 13px;"><i class="fa-solid fa-list-check"></i> Submissions</a>
                        <a href="<?= base_url('org/assignments/edit/'.$a['id']) ?>" class="btn btn-outline" style="padding: 4px 10px; margin-left: 4px;"><i class="fa-solid fa-pen"></i></a>
                        <form action="<?= base_url('org/assignments/delete/'.$a['id']) ?>" method="POST" style="display:inline;" onsubmit="return confirm('Delete this assignment and all its submissions?');">
                            <?= csrf_field() ?>
                            <button class="btn btn-outline" style="padding: 4px 10px; color: var(--danger); border-color: rgba(238,93,80,0.2);"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">No assignments found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</section>
<?= $this->endSection() ?>
