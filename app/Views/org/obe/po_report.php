<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>PO Attainment Report<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Program Outcome (PO) Attainment</h1>
        <p class="header-subtitle">NAAC/NBA Compliant Program Level Attainment Report.</p>
    </div>
</div>

<div class="card" style="margin-bottom: 24px; padding: 16px;">
    <form action="<?= base_url('org/obe/po-report') ?>" method="GET" style="display: flex; gap: 16px; align-items: flex-end;">
        <div class="form-group" style="margin-bottom: 0;">
            <label>Select Program</label>
            <select name="program_id" class="form-control" required>
                <option value="">-- Select --</option>
                <?php foreach($programs as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $p['id'] == $selected_program_id ? 'selected' : '' ?>><?= esc($p['name']) ?> (<?= esc($p['code']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Generate Report</button>
    </form>
</div>

<?php if($selected_program_id): ?>
<div class="card">
    <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">PO Attainment (Scale of 3)</h2>
    <div style="background: #F3F4F6; padding: 12px; border-radius: 6px; margin-bottom: 16px; font-size: 14px;">
        <strong>Calculation Formula:</strong> Total Attainment = (80% of Direct Attainment) + (20% of Indirect Attainment)
    </div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th>PO Code</th>
                <th>Description</th>
                <th style="text-align: center;">Direct (0.8)</th>
                <th style="text-align: center;">Indirect (0.2)</th>
                <th style="text-align: center;">Total Attainment</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($po_attainment as $po): ?>
                <tr>
                    <td style="font-weight: bold; color: var(--primary);"><?= esc($po['po_code']) ?></td>
                    <td style="font-size: 12px;"><?= esc($po['description']) ?></td>
                    <td style="text-align: center;"><?= number_format($po['direct_attainment'], 2) ?></td>
                    <td style="text-align: center;"><?= number_format($po['indirect_attainment'], 2) ?></td>
                    <td style="text-align: center; font-weight: bold; font-size: 16px; color: <?= $po['total_attainment'] >= 2.0 ? 'green' : 'orange' ?>;">
                        <?= number_format($po['total_attainment'], 2) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($po_attainment)): ?>
                <tr><td colspan="5" style="text-align: center;">No POs defined for this program.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
