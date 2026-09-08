<?= $this->extend('parent/layout') ?>
<?= $this->section('page_title') ?>Fee Obligations & Payments<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Student Selector -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
    <div style="display: flex; gap: 10px;">
        <?php foreach($students as $s): ?>
            <a href="<?= base_url('parent/dashboard/select_student/' . ($s['uuid'] ?? $s['id'])) ?>" 
               style="text-decoration: none; padding: 8px 18px; border-radius: 6px; font-weight: 600; font-size: 14px;
                      <?= $s['id'] == $selected_student['id'] ? 'background: var(--primary); color: white;' : 'background: white; color: var(--text-main); border: 1px solid var(--border-color);' ?>">
                <i class="fa-solid fa-graduation-cap me-1"></i> <?= esc($s['first_name'] . ' ' . $s['last_name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
    <div style="font-size: 13px; color: var(--text-muted);">
        Roll Number: <strong><?= esc($selected_student['roll_number']) ?></strong>
    </div>
</div>

<!-- Fee Ledger Table -->
<div class="card">
    <h2 style="margin-top: 0; font-size: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
        <i class="fa-solid fa-receipt me-2" style="color: #4f46e5;"></i> Fee Ledger & Term Structures
    </h2>

    <table class="data-table">
        <thead>
            <tr>
                <th>Fee Structure Name</th>
                <th>Total Fee Amount</th>
                <th>Amount Paid</th>
                <th>Outstanding Balance</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($ledger)): foreach($ledger as $item): ?>
            <tr>
                <td><strong><?= esc($item['structure_name'] ?? 'Academic Tuition') ?></strong></td>
                <td>₹<?= number_format($item['amount_due'], 2) ?></td>
                <td>₹<?= number_format($item['amount_paid'], 2) ?></td>
                <td style="font-weight: 700; color: <?= $item['balance'] > 0 ? '#ef4444' : '#10b981' ?>;">
                    ₹<?= number_format($item['balance'], 2) ?>
                </td>
                <td>
                    <span style="padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;
                        <?= $item['status'] === 'paid' ? 'background: rgba(16,185,129,0.12); color: #10b981;' : ($item['status'] === 'partial' ? 'background: rgba(245,158,11,0.12); color: #f59e0b;' : 'background: rgba(239,68,68,0.12); color: #ef4444;') ?>">
                        <?= ucfirst(esc($item['status'])) ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td colspan="5" style="text-align: center; padding: 24px; color: var(--text-muted);">No fee ledger items recorded for this student.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Past Payment Receipts -->
<div class="card" style="margin-top: 24px;">
    <h2 style="margin-top: 0; font-size: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
        <i class="fa-solid fa-clock-rotate-left me-2" style="color: #10b981;"></i> Payment Transaction History
    </h2>

    <table class="data-table">
        <thead>
            <tr>
                <th>Receipt / Trans. Ref</th>
                <th>Payment Method</th>
                <th>Amount</th>
                <th>Payment Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($payments)): foreach($payments as $pay): ?>
            <tr>
                <td><strong><?= esc($pay['transaction_reference'] ?: 'REC-' . strtoupper(substr($pay['uuid'] ?? str_pad($pay['id'], 5, '0', STR_PAD_LEFT), 0, 8))) ?></strong></td>
                <td><?= esc($pay['payment_method'] ?? 'Online Payment') ?></td>
                <td style="font-weight: 600; color: #10b981;">₹<?= number_format($pay['amount_paid'], 2) ?></td>
                <td><?= date('d/m/Y, h:i A', strtotime($pay['created_at'])) ?></td>
                <td>
                    <span style="background: rgba(16,185,129,0.12); color: #10b981; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                        Successful
                    </span>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td colspan="5" style="text-align: center; padding: 24px; color: var(--text-muted);">No past payment receipts found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
