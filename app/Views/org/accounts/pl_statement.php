<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Profit & Loss Statement<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Profit & Loss Statement</h1>
        <p class="header-subtitle">Financial performance summary for a specific period.</p>
    </div>
</div>

<div class="card" style="margin-bottom: 24px; padding: 16px;">
    <form action="<?= base_url('org/accounts/pl-statement') ?>" method="GET" style="display: flex; gap: 16px; align-items: flex-end;">
        <div class="form-group" style="margin-bottom: 0;">
            <label>Start Date</label>
            <input type="date" name="start_date" class="form-control" value="<?= esc($start_date) ?>" required>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label>End Date</label>
            <input type="date" name="end_date" class="form-control" value="<?= esc($end_date) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Generate P&L</button>
    </form>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
    
    <!-- EXPENDITURES (Debit Side) -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="background: #FEE2E2; color: #991B1B; padding: 16px; font-weight: bold; font-size: 18px; text-align: center; border-bottom: 2px solid #FCA5A5;">
            EXPENDITURES (Dr.)
        </div>
        <table class="data-table" style="border: none;">
            <tbody>
                <?php 
                $total_exp = 0;
                foreach($expenses as $e): 
                    $total_exp += $e['total'];
                ?>
                    <tr>
                        <td style="font-weight: 500; border-bottom: 1px dashed #E5E7EB;"><?= esc($e['head_name']) ?></td>
                        <td style="text-align: right; border-bottom: 1px dashed #E5E7EB; font-weight: 600;"><?= number_format($e['total'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($expenses)): ?>
                    <tr><td colspan="2" style="text-align: center; padding: 20px; color: var(--text-muted);">No expenses recorded.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- INCOMES (Credit Side) -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="background: #D1FAE5; color: #065F46; padding: 16px; font-weight: bold; font-size: 18px; text-align: center; border-bottom: 2px solid #6EE7B7;">
            INCOMES (Cr.)
        </div>
        <table class="data-table" style="border: none;">
            <tbody>
                <?php 
                $total_inc = 0;
                foreach($incomes as $i): 
                    $total_inc += $i['total'];
                ?>
                    <tr>
                        <td style="font-weight: 500; border-bottom: 1px dashed #E5E7EB;"><?= esc($i['head_name']) ?></td>
                        <td style="text-align: right; border-bottom: 1px dashed #E5E7EB; font-weight: 600;"><?= number_format($i['total'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($incomes)): ?>
                    <tr><td colspan="2" style="text-align: center; padding: 20px; color: var(--text-muted);">No incomes recorded.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php 
    $net = $total_inc - $total_exp;
    $isProfit = $net >= 0;
?>

<div class="card" style="margin-top: 24px; text-align: center; padding: 30px;">
    <h2 style="margin: 0; color: var(--text-muted); font-size: 16px; text-transform: uppercase;">NET PROFIT / LOSS</h2>
    <div style="font-size: 48px; font-weight: bold; margin-top: 8px; color: <?= $isProfit ? '#059669' : '#DC2626' ?>;">
        <?= $isProfit ? 'PROFIT' : 'LOSS' ?>: ₹<?= number_format(abs($net), 2) ?>
    </div>
</div>

<?= $this->endSection() ?>
