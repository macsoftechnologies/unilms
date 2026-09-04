<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hall Ticket - <?= esc($ticket['hall_ticket_number']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; color: #333; }
        .ticket-container { max-width: 800px; margin: 0 auto; border: 2px solid #333; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .header h2 { margin: 5px 0 0; font-size: 18px; color: #555; }
        .details-table { width: 100%; margin-bottom: 20px; }
        .details-table td { padding: 8px; vertical-align: top; }
        .details-table td strong { display: inline-block; width: 120px; }
        .schedule-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .schedule-table th, .schedule-table td { border: 1px solid #333; padding: 10px; text-align: left; }
        .schedule-table th { background: #f0f0f0; }
        .footer { display: flex; justify-content: space-between; margin-top: 50px; }
        .signature-box { text-align: center; width: 200px; }
        .signature-box hr { border: 1px solid #333; margin-bottom: 5px; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            .ticket-container { border: none; padding: 0; }
        }
    </style>
</head>
<body>
    <div style="text-align: right; margin-bottom: 10px;" class="no-print">
        <button onclick="window.print()" style="padding: 8px 16px; background: #0ea5e9; color: white; border: none; border-radius: 4px; cursor: pointer;">Print Document</button>
    </div>
    
    <div class="ticket-container">
        <div class="header">
            <h1><?= esc(session('org_name') ?? 'Organization') ?></h1>
            <h2>HALL TICKET</h2>
            <p style="margin:5px 0 0; font-weight:bold;"><?= esc($ticket['exam_name']) ?></p>
        </div>
        
        <table class="details-table">
            <tr>
                <td>
                    <strong>HT Number:</strong> <?= esc($ticket['hall_ticket_number']) ?><br>
                    <strong>Student Name:</strong> <?= esc($ticket['first_name'] . ' ' . $ticket['last_name']) ?><br>
                    <strong>Roll No:</strong> <?= esc($ticket['roll_number']) ?><br>
                </td>
                <td>
                    <strong>Program:</strong> <?= esc($ticket['program_name']) ?><br>
                    <strong>Semester:</strong> <?= esc($ticket['semester_name']) ?><br>
                    <strong>Issue Date:</strong> <?= date('d/m/Y', strtotime($ticket['issue_date'])) ?>
                </td>
                <td style="text-align:right; width: 120px;">
                    <div style="width:100px; height:120px; border:1px solid #ccc; display:inline-block; text-align:center; line-height:120px; background:#f9f9f9; color:#999; font-size:12px;">
                        Photo
                    </div>
                </td>
            </tr>
        </table>
        
        <h3 style="margin-top:0;">Examination Schedule</h3>
        <table class="schedule-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Invigilator Sign</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($schedules)): ?>
                    <?php foreach($schedules as $s): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($s['exam_date'])) ?></td>
                            <td><?= date('H:i', strtotime($s['start_time'])) ?> - <?= date('H:i', strtotime($s['end_time'])) ?></td>
                            <td><?= esc($s['subject_code']) ?></td>
                            <td><?= esc($s['subject_name']) ?></td>
                            <td></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center;">Schedule not available.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="footer">
            <div class="signature-box">
                <hr>
                <p style="margin:0; font-size:12px;">Student Signature</p>
            </div>
            <div class="signature-box">
                <hr>
                <p style="margin:0; font-size:12px;">Controller of Examinations</p>
            </div>
        </div>
    </div>
    
    <script>
        // Auto print on load (optional, but requested by some users)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
