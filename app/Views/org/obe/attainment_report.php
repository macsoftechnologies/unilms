<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>OBE Attainment Report<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">CO Attainment Report</h1>
        <p class="header-subtitle">View Course Outcome attainment levels based on student internal marks.</p>
    </div>
</div>

<div class="card" style="margin-bottom: 24px;">
    <form method="GET" action="" style="display: flex; gap: 16px; align-items: flex-end;">
        <div class="form-group" style="flex: 1; margin: 0;">
            <label>Select Cohort</label>
            <select name="cohort_id" class="form-control" onchange="this.form.submit()">
                <option value="">-- Choose a Cohort --</option>
                <?php foreach($cohorts as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $selected_cohort_id == $c['id'] ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> Generate Report</button>
    </form>
</div>

<?php if($selected_cohort_id): ?>
    <div class="card">
        <?php if(empty($attainments)): ?>
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                <i class="fa-solid fa-chart-bar" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                <p>No marks recorded or no outcomes mapped for this cohort.</p>
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Course Outcome Code</th>
                        <th>Description</th>
                        <th style="text-align: center;">Students Evaluated</th>
                        <th style="text-align: center;">Students Attained (&ge; 60%)</th>
                        <th style="text-align: center;">Class Attainment %</th>
                        <th style="text-align: center;">Attainment Level</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($attainments as $a): ?>
                        <tr>
                            <td style="font-weight: 600; color: var(--primary);"><?= esc($a['co_code']) ?></td>
                            <td style="font-size: 13px; max-width: 300px;"><?= esc($a['description']) ?></td>
                            <td style="text-align: center;"><?= $a['students_attempted'] ?></td>
                            <td style="text-align: center; font-weight: 600; color: var(--success);"><?= $a['students_above_target'] ?></td>
                            <td style="text-align: center;">
                                <div style="display: inline-block; background: var(--bg-light); border-radius: 12px; padding: 2px 8px; font-size: 12px; font-weight: 600;">
                                    <?= $a['class_percentage'] ?>%
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <?php
                                $color = 'var(--text-muted)';
                                if ($a['attainment_level'] == 3) $color = 'var(--success)';
                                if ($a['attainment_level'] == 2) $color = '#eab308';
                                if ($a['attainment_level'] == 1) $color = '#f97316';
                                ?>
                                <span style="display: inline-block; width: 26px; height: 26px; line-height: 26px; text-align: center; border-radius: 50%; background: <?= $color ?>; color: white; font-weight: 700;">
                                    <?= $a['attainment_level'] ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- PO Attainment Matrix Card -->
    <?php if (!empty($poAttainments)): ?>
        <div class="card" style="margin-top: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <div>
                    <h3 style="margin: 0; font-size: 18px; font-weight: 700;"><i class="fa-solid fa-graduation-cap" style="color: var(--primary);"></i> Program Outcome (PO) Weighted Attainment</h3>
                    <small style="color: var(--text-muted);">Formula: &Sigma;(CO_level &times; MappingWeight) / &Sigma;MappingWeight</small>
                </div>
                <button type="button" class="btn btn-outline" onclick="window.print()"><i class="fa-solid fa-print"></i> Export NBA/NAAC Report</button>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Program Outcome</th>
                        <th>Description</th>
                        <th style="text-align: center;">Calculated Attainment Score (1.0 to 3.0)</th>
                        <th style="text-align: center;">Attainment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($poAttainments as $po): ?>
                        <tr>
                            <td style="font-weight: 700; color: var(--primary);"><?= esc($po['po_code']) ?></td>
                            <td style="font-size: 13px; max-width: 350px;"><?= esc($po['description']) ?></td>
                            <td style="text-align: center; font-weight: 700; font-size: 15px;">
                                <?= number_format($po['attainment_score'], 2) ?>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($po['attainment_level'] === 'High'): ?>
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 4px 12px; border-radius: 12px; font-weight: 600;">High Attainment</span>
                                <?php elseif ($po['attainment_level'] === 'Medium'): ?>
                                    <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; padding: 4px 12px; border-radius: 12px; font-weight: 600;">Moderate</span>
                                <?php else: ?>
                                    <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 4px 12px; border-radius: 12px; font-weight: 600;">Low Attainment</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?= $this->endSection() ?>
