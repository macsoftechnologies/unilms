<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Internal Marks Entry<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
<div class="view-header">
    <h2>Internal Marks Entry</h2>
    <p style="color:var(--text-muted); font-size: 14px; margin: 4px 0 0;">Enter marks for internal components. Marks can be locked by the HOD.</p>
</div>

<div class="stat-card" style="margin-bottom: 24px;">
    <form action="" method="GET" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
        <div class="form-group" style="margin: 0; min-width: 200px;">
            <label>Subject</label>
            <select name="subject_id" class="form-control" onchange="this.form.submit()">
                <?php foreach($subjects as $sub): ?>
                    <option value="<?= $sub['id'] ?>" <?= $sub['id'] == $selected_subject_id ? 'selected' : '' ?>><?= esc($sub['code']) ?> - <?= esc($sub['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="margin: 0; min-width: 200px;">
            <label>Cohort</label>
            <select name="cohort_id" class="form-control" onchange="this.form.submit()">
                <?php foreach($cohorts as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $c['id'] == $selected_cohort_id ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="margin: 0; min-width: 200px;">
            <label>Component</label>
            <select name="component_id" class="form-control" onchange="this.form.submit()">
                <?php foreach($components as $comp): ?>
                    <option value="<?= $comp['id'] ?>" <?= $comp['id'] == $selected_component_id ? 'selected' : '' ?>><?= esc($comp['name']) ?> (Max: <?= $comp['max_marks'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

<?php if($selected_cohort_id && $selected_component_id): ?>
    <div class="widget" style="padding: 0; overflow: hidden; border: 1px solid var(--border-color);">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: var(--bg-main);">
            <h3 style="margin:0; font-size:16px;">Marks Entry Grid</h3>
            <?php if($is_locked): ?>
                <span class="badge" style="background: rgba(238,93,80,0.1); color: var(--danger);"><i class="fa-solid fa-lock"></i> Locked by Admin/HOD</span>
            <?php else: ?>
                <?php if(session('is_org_admin') || session('has_manage_academics')): ?>
                    <form action="<?= base_url('org/marks/entry/lock') ?>" method="POST" onsubmit="return confirm('Are you sure you want to lock these marks? Faculty will no longer be able to edit them.');">
                        <?= csrf_field() ?>
                        <input type="hidden" name="component_id" value="<?= $selected_component_id ?>">
                        <button type="submit" class="btn btn-outline" style="color: var(--danger); border-color: rgba(238,93,80,0.2);"><i class="fa-solid fa-lock"></i> Lock Marks</button>
                    </form>
                <?php else: ?>
                    <span class="badge" style="background: rgba(5,205,153,0.1); color: var(--success);"><i class="fa-solid fa-lock-open"></i> Unlocked</span>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <form action="<?= base_url('org/marks/entry/save') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="subject_id" value="<?= $selected_subject_id ?>">
            <input type="hidden" name="cohort_id" value="<?= $selected_cohort_id ?>">
            <input type="hidden" name="component_id" value="<?= $selected_component_id ?>">
            
            <div class="table-container">
                <table class="data-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Roll No</th>
                            <th>Student Name</th>
                            <th style="width: 200px; text-align: right;">Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($students)): ?>
                            <?php foreach($students as $st): ?>
                            <?php 
                                $score = isset($marks_data[$st['id']]) ? $marks_data[$st['id']]['score'] : '';
                            ?>
                            <tr>
                                <td><strong><?= esc($st['roll_number']) ?></strong></td>
                                <td><?= esc($st['first_name'] . ' ' . $st['last_name']) ?></td>
                                <td style="text-align: right;">
                                    <?php if($is_locked): ?>
                                        <span style="font-weight: 600; font-size: 15px;"><?= $score !== '' ? esc($score) : '-' ?></span>
                                    <?php else: ?>
                                        <input type="number" min="0" step="0.01" name="scores[<?= $st['id'] ?>]" class="form-control" style="width: 120px; display: inline-block; text-align: right;" value="<?= esc($score) ?>" placeholder="-">
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" style="text-align: center; padding: 40px; color: var(--text-muted);">No students found in this cohort.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if(!$is_locked && !empty($students)): ?>
            <div style="padding: 16px 20px; background: var(--bg-main); border-top: 1px solid var(--border-color); text-align: right;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 32px;"><i class="fa-solid fa-save"></i> Save Marks</button>
            </div>
            <?php endif; ?>
        </form>
    </div>
<?php endif; ?>

</section>
<?= $this->endSection() ?>
