<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Visitor Gate Pass - <?= esc($visitor['pass_number']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; color: #1e293b; }
        .pass-card { max-width: 480px; margin: 0 auto; border: 2px dashed #4f46e5; border-radius: 12px; padding: 24px; position: relative; }
        .pass-header { text-align: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 14px; margin-bottom: 18px; }
        .pass-title { font-size: 20px; font-weight: 700; color: #4f46e5; margin: 0; }
        .pass-sub { font-size: 13px; color: #64748b; margin-top: 4px; }
        .badge-no { display: inline-block; background: #4f46e5; color: white; padding: 6px 14px; font-weight: bold; border-radius: 20px; font-size: 14px; margin-top: 8px; }
        .field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px; }
        .field-label { font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 600; }
        .field-value { font-size: 14px; font-weight: 600; margin-top: 2px; }
        .pass-footer { margin-top: 24px; padding-top: 14px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; font-size: 12px; color: #64748b; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            .pass-card { border: 2px solid #000; }
        }
    </style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 15px;" class="no-print">
        <button onclick="window.print()" style="padding: 8px 18px; background: #4f46e5; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
            Print Visitor Pass
        </button>
    </div>

    <div class="pass-card">
        <div class="pass-header">
            <h1 class="pass-title"><?= esc($visitor['org_name'] ?? 'CAMPUS VISITOR PASS') ?></h1>
            <div class="pass-sub">Official Campus Security Entry Clearance</div>
            <div class="badge-no"><?= esc($visitor['pass_number']) ?></div>
        </div>

        <div class="field-grid">
            <div>
                <div class="field-label">Visitor Name</div>
                <div class="field-value"><?= esc($visitor['visitor_name']) ?></div>
            </div>
            <div>
                <div class="field-label">Phone Number</div>
                <div class="field-value"><?= esc($visitor['phone']) ?></div>
            </div>
            <div>
                <div class="field-label">Visitor Type</div>
                <div class="field-value"><?= esc($visitor['visitor_type']) ?></div>
            </div>
            <div>
                <div class="field-label">ID Proof / Badge</div>
                <div class="field-value"><?= esc($visitor['id_proof'] ?: 'N/A') ?></div>
            </div>
            <div style="grid-column: span 2;">
                <div class="field-label">Purpose of Visit</div>
                <div class="field-value"><?= esc($visitor['purpose']) ?></div>
            </div>
            <div>
                <div class="field-label">Person To Meet</div>
                <div class="field-value"><?= esc($visitor['person_to_meet'] ?: 'General') ?></div>
            </div>
            <div>
                <div class="field-label">Department</div>
                <div class="field-value"><?= esc($visitor['department_name'] ?? 'N/A') ?></div>
            </div>
            <div>
                <div class="field-label">In-Time (Entry)</div>
                <div class="field-value"><?= date('d/m/Y, h:i A', strtotime($visitor['in_time'])) ?></div>
            </div>
            <div>
                <div class="field-label">Out-Time (Exit)</div>
                <div class="field-value"><?= !empty($visitor['out_time']) ? date('d/m/Y, h:i A', strtotime($visitor['out_time'])) : 'Active Inside' ?></div>
            </div>
        </div>

        <div class="pass-footer">
            <div>Security Desk Sign: ____________</div>
            <div>Visitor Sign: ____________</div>
        </div>
    </div>
</body>
</html>
