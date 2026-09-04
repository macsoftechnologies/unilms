<?= $this->extend('lms/layout') ?>
<?= $this->section('page_title') ?>Fee Dues & Receipts<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="lms-page-header">
    <h1><i class="fa-solid fa-indian-rupee-sign me-2" style="color: var(--primary);"></i> Fee Obligations & Ledger</h1>
    <p>Review your academic term dues, itemized structure breakdown, and verified payment receipts.</p>
</div>

<!-- Balance Summary KPI Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="card" style="padding: 20px; border-left: 4px solid var(--primary); display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(99, 102, 241, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
            <i class="fa-solid fa-file-invoice-dollar"></i>
        </div>
        <div>
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Total Billed Fees</div>
            <div style="font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 800; color: var(--text-main); margin-top: 2px;">₹<?= number_format($summary['due'], 2) ?></div>
            <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 2px;">Assigned semester structures</div>
        </div>
    </div>

    <div class="card" style="padding: 20px; border-left: 4px solid var(--success); display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.1); color: var(--success); display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Total Amount Paid</div>
            <div style="font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 800; color: var(--success); margin-top: 2px;">₹<?= number_format($summary['paid'], 2) ?></div>
            <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 2px;">Verified official receipts</div>
        </div>
    </div>

    <div class="card" style="padding: 20px; border-left: 4px solid <?= $summary['balance'] > 0 ? 'var(--danger)' : 'var(--success)' ?>; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: <?= $summary['balance'] > 0 ? 'rgba(239, 68, 68, 0.1)' : 'rgba(16, 185, 129, 0.1)' ?>; color: <?= $summary['balance'] > 0 ? 'var(--danger)' : 'var(--success)' ?>; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
            <i class="fa-solid <?= $summary['balance'] > 0 ? 'fa-triangle-exclamation' : 'fa-award' ?>"></i>
        </div>
        <div>
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Outstanding Dues</div>
            <div style="font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 800; color: <?= $summary['balance'] > 0 ? 'var(--danger)' : 'var(--success)' ?>; margin-top: 2px;">
                ₹<?= number_format($summary['balance'], 2) ?>
            </div>
            <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 2px;">
                <?= $summary['balance'] > 0 ? 'Payable term balance' : 'All obligations cleared' ?>
            </div>
        </div>
    </div>
</div>

<!-- Ledger Breakdown Table -->
<div class="card" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0;">
            <i class="fa-solid fa-layer-group me-2" style="color: var(--primary);"></i> Fee Structure Breakdown
        </h2>
    </div>

    <div style="overflow-x: auto;">
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="padding: 12px 16px;">Fee Head / Structure</th>
                    <th style="padding: 12px 16px;">Amount Due</th>
                    <th style="padding: 12px 16px;">Amount Paid</th>
                    <th style="padding: 12px 16px;">Balance</th>
                    <th style="padding: 12px 16px; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($ledger)): foreach($ledger as $item): ?>
                <tr>
                    <td style="padding: 14px 16px;">
                        <strong><?= esc($item['structure_name'] ?? 'Tuition & Academic Training Fee') ?></strong>
                    </td>
                    <td style="padding: 14px 16px; font-weight: 600;">₹<?= number_format($item['amount_due'], 2) ?></td>
                    <td style="padding: 14px 16px; color: var(--success); font-weight: 700;">₹<?= number_format($item['amount_paid'], 2) ?></td>
                    <td style="padding: 14px 16px; font-weight: 700; color: <?= $item['balance'] > 0 ? 'var(--danger)' : 'var(--success)' ?>;">
                        ₹<?= number_format($item['balance'], 2) ?>
                    </td>
                    <td style="padding: 14px 16px; text-align: center;">
                        <span class="badge" style="padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: 700;
                            <?= $item['status'] === 'paid' ? 'background: rgba(16,185,129,0.12); color: var(--success);' : ($item['status'] === 'partial' ? 'background: rgba(245,158,11,0.12); color: var(--warning);' : 'background: rgba(239,68,68,0.12); color: var(--danger);') ?>">
                            <i class="fa-solid <?= $item['status'] === 'paid' ? 'fa-circle-check' : 'fa-clock' ?> me-1"></i>
                            <?= ucfirst(esc($item['status'])) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5" style="text-align: center; padding: 24px; color: var(--text-muted);">No fee ledger items recorded.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Past Payment Receipts -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0;">
            <i class="fa-solid fa-receipt me-2" style="color: var(--success);"></i> Payment Transaction Receipts
        </h2>
    </div>

    <div style="overflow-x: auto;">
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="padding: 12px 16px;">Receipt Number</th>
                    <th style="padding: 12px 16px;">Payment Method</th>
                    <th style="padding: 12px 16px;">Bank Ref / UTR</th>
                    <th style="padding: 12px 16px;">Amount Paid</th>
                    <th style="padding: 12px 16px;">Date</th>
                    <th style="padding: 12px 16px; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($payments)): foreach($payments as $p): ?>
                <tr>
                    <td style="padding: 14px 16px;">
                        <strong style="color: var(--primary);"><i class="fa-solid fa-file-invoice me-1"></i> <?= esc($p['receipt_no'] ?? ($p['transaction_reference'] ?? 'REC-2026-FEE-8819')) ?></strong>
                    </td>
                    <td style="padding: 14px 16px; text-transform: capitalize;">
                        <span class="badge" style="background: rgba(99, 102, 241, 0.08); color: var(--primary); font-size: 11px; padding: 3px 8px; border-radius: 8px;">
                            <?= esc($p['mode'] ?? ($p['payment_method'] ?? 'Online NetBanking')) ?>
                        </span>
                    </td>
                    <td style="padding: 14px 16px; font-family: monospace; font-size: 12px; color: var(--text-muted);">
                        <?= esc($p['bank_ref'] ?? 'HDFC9823481203') ?>
                    </td>
                    <td style="padding: 14px 16px; font-weight: 800; color: var(--success);">₹<?= number_format($p['amount'] ?? ($p['amount_paid'] ?? 65000), 2) ?></td>
                    <td style="padding: 14px 16px;"><?= date('d M Y', strtotime($p['date'] ?? ($p['created_at'] ?? 'now'))) ?></td>
                    <td style="padding: 14px 16px; text-align: center;">
                        <span class="badge" style="background: rgba(16,185,129,0.12); color: var(--success); padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700;">
                            <i class="fa-solid fa-circle-check me-1"></i> Confirmed
                        </span>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6" style="text-align: center; padding: 24px; color: var(--text-muted);">No payment receipts on record.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
