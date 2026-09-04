<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted Successfully</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #F1F5F9; color: #1E293B; margin: 0; padding: 60px 20px; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { max-width: 540px; width: 100%; background: white; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); padding: 40px; text-align: center; }
        .icon-circle { width: 72px; height: 72px; background: rgba(16, 185, 129, 0.12); color: #10B981; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 20px; }
        .app-ref { background: #EEF2FF; color: #4F46E5; font-size: 20px; font-weight: 700; padding: 12px 24px; border-radius: 8px; display: inline-block; margin: 16px 0; letter-spacing: 1px; }
    </style>
</head>
<body>

<div class="card">
    <div class="icon-circle">
        <i class="fa-solid fa-circle-check"></i>
    </div>

    <h1 style="margin: 0 0 8px; font-size: 24px; font-weight: 700;">Application Received!</h1>
    <p style="color: #64748B; font-size: 14px; margin: 0 0 20px;">
        Thank you <strong><?= esc($student_name) ?></strong>. Your admission enquiry has been successfully registered with the Admissions Directorate.
    </p>

    <div style="font-size: 12px; color: #64748B; text-transform: uppercase; font-weight: 600;">Your Application Reference Number</div>
    <div class="app-ref"><?= esc($application_number) ?></div>

    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 16px; font-size: 13px; text-align: left; margin-bottom: 24px;">
        <div style="margin-bottom: 6px;"><strong>Next Steps:</strong></div>
        <ol style="margin: 0; padding-left: 20px; color: #475569; line-height: 1.6;">
            <li>Our Admissions Officer will verify your eligibility criteria.</li>
            <li>You will receive an SMS and Email at <strong><?= esc($email) ?></strong> with guidance for certificate verification.</li>
            <li>Keep your reference number handy for fee payment and provisional admission clearance.</li>
        </ol>
    </div>

    <a href="<?= base_url('admissions/apply') ?>" style="color: #4F46E5; text-decoration: none; font-size: 14px; font-weight: 600;">
        <i class="fa-solid fa-arrow-left me-1"></i> Submit Another Application
    </a>
</div>

</body>
</html>
