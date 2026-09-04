<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Accounts Dashboard<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Accounts Dashboard</h1>
        <p class="header-subtitle">Overview of financial balances and ledgers.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
    <div class="card" style="display: flex; align-items: center; gap: 20px;">
        <div style="background: #E0E7FF; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-building-columns" style="font-size: 24px; color: var(--primary);"></i>
        </div>
        <div>
            <div style="font-size: 14px; color: var(--text-muted); font-weight: 500; text-transform: uppercase;">Total Bank Balance</div>
            <div style="font-size: 32px; font-weight: bold; color: var(--text-main);">₹<?= number_format($total_bank, 2) ?></div>
        </div>
    </div>
    
    <div class="card" style="display: flex; align-items: center; gap: 20px;">
        <div style="background: #D1FAE5; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-money-bill-wave" style="font-size: 24px; color: #059669;"></i>
        </div>
        <div>
            <div style="font-size: 14px; color: var(--text-muted); font-weight: 500; text-transform: uppercase;">Total Cash in Hand</div>
            <div style="font-size: 32px; font-weight: bold; color: var(--text-main);">₹<?= number_format($total_cash, 2) ?></div>
        </div>
    </div>
</div>

<div class="card">
    <h2 style="margin-top: 0;">Quick Actions</h2>
    <div style="display: flex; gap: 16px;">
        <a href="<?= base_url('org/accounts/transactions') ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Transaction</a>
        <a href="<?= base_url('org/accounts/daybook') ?>" class="btn" style="background: #F3F4F6; border: 1px solid var(--border-color);"><i class="fa-solid fa-book"></i> View Day Book</a>
        <a href="<?= base_url('org/accounts/pl-statement') ?>" class="btn" style="background: #F3F4F6; border: 1px solid var(--border-color);"><i class="fa-solid fa-chart-line"></i> Profit & Loss</a>
    </div>
</div>

<?= $this->endSection() ?>
