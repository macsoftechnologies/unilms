<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Mark Components & Assessment Mapping<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
<div class="view-header">
    <h2>Mark Components & Assessment Mapping</h2>
    <p style="color:var(--text-muted); font-size: 14px; margin: 4px 0 0;">Define internal exams/components and map them to COs.</p>
</div>

<div class="stat-card" style="margin-bottom: 24px;">
    <form action="" method="GET" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
        <div class="form-group" style="margin: 0; min-width: 200px;">
            <label>Program</label>
            <select name="program_id" class="form-control" onchange="this.form.submit()">
                <?php foreach($programs as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $p['id'] == $selected_program_id ? 'selected' : '' ?>><?= esc($p['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="margin: 0; min-width: 200px;">
            <label>Semester</label>
            <select name="semester_id" class="form-control" onchange="this.form.submit()">
                <?php foreach($semesters as $sem): ?>
                    <option value="<?= $sem['id'] ?>" <?= $sem['id'] == $selected_semester_id ? 'selected' : '' ?>><?= esc($sem['name'] ?? ('Semester ' . ($sem['sequence'] ?? ''))) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="margin: 0; min-width: 200px;">
            <label>Subject</label>
            <select name="subject_id" class="form-control" onchange="this.form.submit()">
                <?php foreach($subjects as $sub): ?>
                    <option value="<?= $sub['id'] ?>" <?= $sub['id'] == $selected_subject_id ? 'selected' : '' ?>><?= esc($sub['code']) ?> - <?= esc($sub['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

<?php if($selected_subject_id): ?>
<div class="grid-layout" style="display: grid; grid-template-columns: 350px 1fr; gap: 24px;">
    <div class="widget" style="border: 1px solid var(--border-color);">
        <h3 style="margin:0 0 16px; font-size: 16px;">Add Component</h3>
        <form action="<?= base_url('org/marks/components/save') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="program_id" value="<?= $selected_program_id ?>">
            <input type="hidden" name="semester_id" value="<?= $selected_semester_id ?>">
            <input type="hidden" name="subject_id" value="<?= $selected_subject_id ?>">
            
            <div class="form-group">
                <label>Component Name (e.g. Mid-Term 1)</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Maximum Marks</label>
                <input type="number" min="0" step="0.01" name="max_marks" class="form-control" required>
            </div>
            
            <div class="form-group" style="margin-top: 24px;">
                <label style="margin-bottom: 8px;">Maps to COs (Optional)</label>
                <?php if(empty($cos)): ?>
                    <div style="font-size: 12px; color: var(--warning);">No COs defined for this subject yet.</div>
                <?php else: ?>
                    <div style="display: flex; flex-direction: column; gap: 8px; border: 1px solid var(--border-color); padding: 12px; border-radius: 6px; background: var(--bg-main);">
                        <?php foreach($cos as $co): ?>
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 500; cursor: pointer;">
                                <input type="checkbox" name="co_ids[]" value="<?= $co['id'] ?>"> <?= esc($co['code']) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 16px;">Save Component</button>
        </form>
    </div>
    
    <div class="widget" style="border: 1px solid var(--border-color); padding: 0;">
        <table class="data-table" style="width: 100%;">
            <thead>
                <tr>
                    <th>Component Name</th>
                    <th>Max Marks</th>
                    <th>Mapped COs</th>
                    <th style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($components)): ?>
                    <?php foreach($components as $comp): ?>
                    <tr>
                        <td><strong><?= esc($comp['name']) ?></strong></td>
                        <td><?= esc($comp['max_marks']) ?></td>
                        <td>
                            <?php 
                                $mapped = isset($mappings[$comp['id']]) ? $mappings[$comp['id']] : [];
                                if (empty($mapped)) {
                                    echo '<span style="color: var(--text-muted); font-size: 12px;">None</span>';
                                } else {
                                    foreach($mapped as $cid) {
                                        // find CO code
                                        $code = '';
                                        foreach($cos as $c) { if($c['id'] == $cid) { $code = $c['code']; break; } }
                                        echo '<span class="badge" style="background: rgba(109, 40, 217, 0.1); color: var(--primary); margin-right: 4px;">'.$code.'</span>';
                                    }
                                }
                            ?>
                        </td>
                        <td>
                            <form action="<?= base_url('org/marks/components/delete/'.$comp['id']) ?>" method="POST" onsubmit="return confirm('Delete this component? All associated marks will be lost.');">
                                <?= csrf_field() ?>
                                <button class="btn btn-outline" style="padding: 4px 10px; color: var(--danger); border-color: rgba(238,93,80,0.2);"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align: center; padding: 20px; color: var(--text-muted);">No components defined for this subject.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

</section>
<?= $this->endSection() ?>
