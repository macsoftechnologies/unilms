<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>OBE Attainment Targets<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">OBE Attainment Targets</h1>
        <p class="header-subtitle">Set the global thresholds for calculating CO, PO, and PSO attainment levels.</p>
    </div>
</div>

<div class="card" style="max-width: 800px;">
    <form action="<?= base_url('org/obe/targets/save') ?>" method="POST">
        <?= csrf_field() ?>
        
        <?php foreach(['co' => 'Course Outcomes (CO)', 'po' => 'Program Outcomes (PO)', 'pso' => 'Program Specific Outcomes (PSO)'] as $key => $label): ?>
            <?php $t = $targets[$key] ?? ['target_percentage' => 60, 'level_1_threshold' => 50, 'level_2_threshold' => 60, 'level_3_threshold' => 70]; ?>
            <div style="margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid var(--border-color);">
                <h3 style="margin-top: 0; color: var(--primary);"><?= $label ?> Targets</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Target Mark Percentage (%)</label>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px;">The minimum score a student must get to "attain" the outcome.</div>
                        <input type="number" min="0" step="0.01" name="<?= $key ?>_target_percentage" class="form-control" value="<?= esc($t['target_percentage']) ?>" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-top: 16px;">
                    <div class="form-group">
                        <label>Level 1 Threshold (%)</label>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px;">% of students crossing target</div>
                        <input type="number" min="0" step="0.01" name="<?= $key ?>_level_1" class="form-control" value="<?= esc($t['level_1_threshold']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Level 2 Threshold (%)</label>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px;">% of students crossing target</div>
                        <input type="number" min="0" step="0.01" name="<?= $key ?>_level_2" class="form-control" value="<?= esc($t['level_2_threshold']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Level 3 Threshold (%)</label>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px;">% of students crossing target</div>
                        <input type="number" min="0" step="0.01" name="<?= $key ?>_level_3" class="form-control" value="<?= esc($t['level_3_threshold']) ?>" required>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Global Targets</button>
    </form>
</div>

<?= $this->endSection() ?>
