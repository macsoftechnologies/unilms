<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admission Offer Letter - <?= esc($offer['adm_number'] ?? 'PROV-OFFER') ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 30px;
            background: #f1f5f9;
            color: #1e293b;
        }
        .letter-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 50px 60px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 20px;
            margin-bottom: 28px;
        }
        .org-name {
            font-size: 22px;
            font-weight: 800;
            color: #1e1b4b;
            margin: 0;
        }
        .org-sub {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }
        .doc-badge {
            background: #eef2ff;
            color: #4338ca;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.5px;
            text-align: right;
        }
        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 13px;
            color: #475569;
        }
        .recipient {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 18px 22px;
            margin-bottom: 24px;
        }
        .recipient h4 {
            margin: 0 0 6px 0;
            font-size: 15px;
            color: #0f172a;
        }
        .subject-line {
            font-weight: 700;
            font-size: 15px;
            color: #1e293b;
            margin: 24px 0 16px;
            text-decoration: underline;
        }
        .body-text {
            font-size: 14px;
            line-height: 1.7;
            color: #334155;
            margin-bottom: 16px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 13.5px;
        }
        .details-table th, .details-table td {
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
            text-align: left;
        }
        .details-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            width: 35%;
        }
        .details-table td {
            font-weight: 600;
            color: #0f172a;
        }
        .footer-signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            padding-top: 20px;
        }
        .sig-block {
            text-align: center;
            width: 200px;
        }
        .sig-line {
            border-top: 1px solid #94a3b8;
            margin-top: 45px;
            padding-top: 6px;
            font-size: 12px;
            color: #64748b;
        }
        .action-bar {
            max-width: 800px;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn {
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-primary { background: #4f46e5; color: #fff; }
        .btn-outline { background: #fff; border: 1px solid #cbd5e1; color: #334155; }
        @media print {
            body { background: #fff; padding: 0; }
            .action-bar { display: none; }
            .letter-container { box-shadow: none; border: none; padding: 0; }
        }
    </style>
</head>
<body>

<div class="action-bar">
    <a href="<?= base_url('org/admissions/offers') ?>" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Back to Offers</a>
    <button onclick="window.print()" class="btn btn-primary"><i class="fa-solid fa-print"></i> Print / Save as PDF</button>
</div>

<div class="letter-container">
    <div class="header">
        <div>
            <h1 class="org-name"><?= esc($offer['org_name'] ?? 'OrganizationVVV Institute') ?></h1>
            <div class="org-sub"><?= esc($offer['org_address'] ?? 'Campus Administrative Building, Technical Campus') ?></div>
            <div class="org-sub">Institution Code: <strong><?= esc($offer['institution_code'] ?? 'ORGVVV') ?></strong></div>
        </div>
        <div class="doc-badge">
            <div>PROVISIONAL ADMISSION OFFER</div>
            <div style="font-size: 11px; font-weight: 500; margin-top: 4px; color: #6366f1;">Ref: OFF-<?= str_pad($offer['id'], 5, '0', STR_PAD_LEFT) ?>-v<?= $offer['version'] ?></div>
        </div>
    </div>

    <div class="meta-row">
        <div>Date of Issue: <strong><?= date('d M, Y', strtotime($offer['created_at'])) ?></strong></div>
        <div>Offer Status: <strong style="color: <?= $offer['status'] === 'Accepted' ? '#16a34a' : '#d97706' ?>;"><?= strtoupper($offer['status']) ?></strong></div>
    </div>

    <div class="recipient">
        <h4>To Candidate:</h4>
        <div><strong><?= esc($offer['full_name']) ?></strong></div>
        <div style="font-size: 12.5px; color: #64748b; margin-top: 2px;">Application No: <strong><?= esc($offer['adm_number']) ?></strong> | Mobile: <?= esc($offer['phone']) ?> | Email: <?= esc($offer['email']) ?></div>
    </div>

    <div class="subject-line">Sub: Offer of Provisional Admission for Academic Session 2024-2025</div>

    <p class="body-text">
        Dear <strong><?= esc($offer['full_name']) ?></strong>,
    </p>
    <p class="body-text">
        We are pleased to inform you that based on your application and subsequent evaluation, you have been provisionally selected for admission to <strong><?= esc($offer['org_name'] ?? 'our institution') ?></strong>.
    </p>

    <table class="details-table">
        <tr>
            <th>Program / Branch</th>
            <td><?= esc($offer['program_name'] ?? 'B.Tech Computer Science and Engineering') ?></td>
        </tr>
        <tr>
            <th>Admission Category</th>
            <td><?= esc($offer['admission_category'] ?? 'Regular / Lateral Entry') ?></td>
        </tr>
        <tr>
            <th>Admission Fee / Dues</th>
            <td style="color: #4f46e5; font-size: 15px;">₹<?= number_format($offer['fee_amount'], 2) ?></td>
        </tr>
        <tr>
            <th>Payment Due Date</th>
            <td style="color: #dc2626;"><?= !empty($offer['due_date']) ? date('d F, Y', strtotime($offer['due_date'])) : 'Prior to reporting' ?></td>
        </tr>
    </table>

    <p class="body-text" style="font-size: 13px; color: #475569;">
        <strong>Terms & Conditions:</strong><br>
        1. This offer is provisional and subject to the physical verification of all original certificates and mark sheets.<br>
        2. Failure to accept this offer or submit the admission fee on or before the due date may lead to cancellation of this allotment.<br>
        3. Upon payment confirmation, your unique Student Roll Number and Unified LMS credentials will be provisioned.
    </p>

    <div class="footer-signatures">
        <div class="sig-block">
            <div class="sig-line">Candidate Acceptance</div>
        </div>
        <div class="sig-block">
            <div style="font-weight: 700; color: #1e1b4b; font-size: 13px;">Registrar / Admissions Office</div>
            <div class="sig-line">Authorized Signatory</div>
        </div>
    </div>
</div>

</body>
</html>
