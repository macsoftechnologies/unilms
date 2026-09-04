<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Gap Analysis<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">CO Gap Analysis & Action Plan</h1>
        <p class="header-subtitle">Identify un-attained Course Outcomes and plan corrective measures.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    
    <div class="card">
        <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Record New Gap</h2>
        <form action="<?= base_url('org/obe/gap-analysis/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Subject</label>
                <select name="subject_id" class="form-control" required>
                    <?php foreach($subjects as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?> (<?= esc($s['code']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>CO Code (e.g. CO1)</label>
                <input type="number" min="0" name="co_id" class="form-control" placeholder="Enter CO ID (Demo)" required>
            </div>
            <div style="display: flex; gap: 16px;">
                <div class="form-group" style="flex: 1;">
                    <label>Attained (%)</label>
                    <input type="number" min="0" step="0.01" name="attained_value" class="form-control" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Target (%)</label>
                    <input type="number" min="0" step="0.01" name="target_value" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label>Gap Identified</label>
                <textarea name="gap_identified" class="form-control" style="height: 60px;" required></textarea>
            </div>
            <div class="form-group">
                <label>Corrective Action Plan</label>
                <textarea name="action_plan" class="form-control" style="height: 60px;" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Record Action Plan</button>
        </form>
    </div>
    
    <div class="card">
        <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Identified Gaps</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Subject & CO</th>
                    <th>Att. / Tgt.</th>
                    <th>Action Plan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($gaps as $g): ?>
                    <tr>
                        <td>
                            <strong style="color: var(--primary);"><?= esc($g['co_code']) ?></strong><br>
                            <span style="font-size: 12px;"><?= esc($g['subject_name']) ?></span>
                        </td>
                        <td style="white-space: nowrap;">
                            <span style="color: red; font-weight: bold;"><?= esc($g['attained_value']) ?>%</span><br>
                            <span style="font-size: 12px; color: var(--text-muted);">T: <?= esc($g['target_value']) ?>%</span>
                        </td>
                        <td style="font-size: 13px;">
                            <strong>Gap:</strong> <?= esc($g['gap_identified']) ?><br>
                            <strong>Action:</strong> <?= esc($g['action_plan']) ?>
                        </td>
                        <td>
                            <?php if($g['status'] == 'Identified'): ?>
                                <span style="background: #FEF3C7; color: #92400E; padding: 4px 8px; border-radius: 4px; font-size: 11px;">IDENTIFIED</span>
                            <?php else: ?>
                                <span style="background: #D1FAE5; color: #065F46; padding: 4px 8px; border-radius: 4px; font-size: 11px;"><?= strtoupper(esc($g['status'])) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($gaps)): ?>
                    <tr><td colspan="4" style="text-align: center;">No gaps recorded yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?= $this->endSection() ?>
