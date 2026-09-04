<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Attendance Reports<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div style="margin-bottom: 24px;">
    <a href="<?= base_url('org/attendance') ?>" style="color: var(--primary); text-decoration: none; font-size: 14px;"><i class="fa-solid fa-arrow-left"></i> Back to Sessions</a>
</div>

<div class="header-banner">
    <h1 class="header-title">Attendance Reports</h1>
    <p class="header-subtitle">View aggregated attendance metrics across cohorts.</p>
</div>

<div class="card" style="margin-bottom: 24px;">
    <form method="GET" action="" style="display: flex; gap: 16px; align-items: flex-end;">
        <div class="form-group" style="flex: 1; margin: 0;">
            <label>Select Cohort to View Report</label>
            <select name="cohort_id" class="form-control" onchange="this.form.submit()">
                <option value="">-- Choose Cohort --</option>
                <?php foreach($cohorts as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $selected_cohort_id == $c['id'] ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

<?php if($selected_cohort_id): ?>
    <div class="card" style="padding: 0; overflow: hidden;">
        <table class="data-table" style="border: none;">
            <thead>
                <tr>
                    <th style="width: 80px; text-align: center;">Roll No</th>
                    <th>Student Name</th>
                    <th style="text-align: center;">Present</th>
                    <th style="text-align: center;">Late</th>
                    <th style="text-align: center;">Absent</th>
                    <th style="text-align: center;">Total Sessions</th>
                    <th style="text-align: right; padding-right: 32px;">Attendance %</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($report_data as $row): ?>
                    <?php 
                        $pct = $row['percentage'];
                        $color_class = 'success';
                        if ($pct < 75) $color_class = 'danger';
                        else if ($pct < 85) $color_class = 'warning';
                    ?>
                    <tr>
                        <td style="text-align: center; color: var(--text-muted); font-size: 13px; font-weight: 600;"><?= esc($row['student']['roll_number']) ?></td>
                        <td style="font-weight: 600;"><?= esc($row['student']['first_name'] . ' ' . $row['student']['last_name']) ?></td>
                        <td style="text-align: center; color: var(--success); font-weight: 600;"><?= $row['present'] ?></td>
                        <td style="text-align: center; color: var(--warning); font-weight: 600;"><?= $row['late'] ?></td>
                        <td style="text-align: center; color: var(--danger); font-weight: 600;"><?= $row['absent'] ?></td>
                        <td style="text-align: center; font-weight: 600;"><?= $row['total'] ?></td>
                        <td style="text-align: right; padding-right: 32px;">
                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px;">
                                <div style="font-weight: 700; color: var(--<?= $color_class ?>); font-size: 16px; width: 50px;"><?= $pct ?>%</div>
                                <div style="width: 100px; height: 8px; background: var(--bg-main); border-radius: 4px; overflow: hidden;">
                                    <div style="width: <?= $pct ?>%; height: 100%; background: var(--<?= $color_class ?>); border-radius: 4px;"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                
                <?php if(empty($report_data)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted);">
                            No students found in this cohort.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
