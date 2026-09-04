<!DOCTYPE html>
<html>
<head>
    <title>Fee Receipt - <?= esc($receipt['receipt_no']) ?></title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; padding: 40px; background: #f0f2f5; }
        .receipt-card { background: white; max-width: 800px; margin: 0 auto; padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 8px; }
        .header { text-align: center; border-bottom: 2px solid #3b82f6; padding-bottom: 20px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #1e293b; }
        .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .detail-row { margin-bottom: 8px; }
        .detail-label { font-weight: bold; color: #64748b; width: 120px; display: inline-block; }
        .amount-box { text-align: center; background: #f8fafc; border: 1px solid #e2e8f0; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .amount-box h2 { margin: 0; font-size: 36px; color: #3b82f6; }
        .footer { margin-top: 50px; display: flex; justify-content: space-between; align-items: flex-end; }
        .signature { border-top: 1px solid #cbd5e1; padding-top: 8px; width: 200px; text-align: center; color: #64748b; }
        @media print { body { background: white; padding: 0; } .receipt-card { box-shadow: none; max-width: 100%; padding: 0; } .no-print { display: none; } }
    </style>
</head>
<body>

    <div style="text-align: center; margin-bottom: 20px;" class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">Print Receipt</button>
        <a href="<?= base_url('org/fee-payments/dues') ?>" style="display: inline-block; margin-left: 10px; color: #3b82f6; text-decoration: none;">Back to Dues</a>
    </div>

    <div class="receipt-card">
        <div class="header">
            <h1>FEE RECEIPT</h1>
            <p>Receipt No: <strong><?= esc($receipt['receipt_no']) ?></strong> | Date: <strong><?= date('d/m/Y', strtotime($receipt['date'])) ?></strong></p>
        </div>

        <div class="details-grid">
            <div>
                <div class="detail-row"><span class="detail-label">Student Name:</span> <?= esc($receipt['first_name'] . ' ' . $receipt['last_name']) ?></div>
                <div class="detail-row"><span class="detail-label">Roll No:</span> <?= esc($receipt['roll_number']) ?></div>
                <div class="detail-row"><span class="detail-label">Program:</span> <?= esc($receipt['program_name']) ?></div>
            </div>
            <div>
                <div class="detail-row"><span class="detail-label">Payment Mode:</span> <?= strtoupper(esc($receipt['mode'])) ?></div>
                <?php if($receipt['bank_ref']): ?>
                <div class="detail-row"><span class="detail-label">Ref/Cheque No:</span> <?= esc($receipt['bank_ref']) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="amount-box">
            <p style="margin: 0 0 10px 0; color: #64748b; font-weight: bold; text-transform: uppercase;">Amount Received</p>
            <h2>₹<?= number_format($receipt['amount'], 2) ?></h2>
            <?php if($receipt['remarks']): ?>
            <p style="margin: 10px 0 0 0; color: #64748b; font-size: 14px;">Remarks: <?= esc($receipt['remarks']) ?></p>
            <?php endif; ?>
        </div>

        <div class="footer">
            <div>
                <p style="color: #94a3b8; font-size: 12px; margin: 0;">This is a computer generated receipt.</p>
            </div>
            <div class="signature">
                Authorized Signatory
            </div>
        </div>
    </div>

</body>
</html>
