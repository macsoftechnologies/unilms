<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Student Admission Application | <?= esc($org['name'] ?? 'University') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #F1F5F9; color: #1E293B; margin: 0; padding: 40px 20px; }
        .form-container { max-width: 720px; margin: 0 auto; background: white; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); overflow: hidden; }
        .form-header { background: linear-gradient(135deg, #4F46E5, #3730A3); color: white; padding: 32px; text-align: center; }
        .form-header h1 { margin: 0; font-size: 24px; font-weight: 700; }
        .form-header p { margin: 8px 0 0; opacity: 0.9; font-size: 14px; }
        .form-body { padding: 32px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #334155; }
        .form-control { width: 100%; height: 42px; padding: 8px 14px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; font-family: inherit; }
        .form-control:focus { outline: none; border-color: #4F46E5; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15); }
        textarea.form-control { height: auto; }
        .btn-submit { width: 100%; height: 46px; background: #4F46E5; color: white; border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.2s; }
        .btn-submit:hover { background: #4338CA; }
        @media (max-width: 600px) { .grid-2 { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<div class="form-container">
    <div class="form-header">
        <h1><?= esc($org['name'] ?? 'University Admission Application') ?></h1>
        <p>Online Application for Academic Year <?= date('Y') ?> - <?= date('Y') + 1 ?></p>
    </div>

    <form action="<?= base_url('admissions/apply') ?>" method="POST" class="form-body">
        <?= csrf_field() ?>
        <input type="hidden" name="org_id" value="<?= esc($org['id'] ?? 1) ?>">

        <h3 style="font-size: 16px; margin: 0 0 16px; color: #4F46E5; border-bottom: 1px solid #E2E8F0; padding-bottom: 8px;">
            <i class="fa-solid fa-user me-2"></i> Candidate Details
        </h3>

        <div class="form-group">
            <label>Student Full Name (As per 10th Standard Certificate) *</label>
            <input type="text" name="full_name" class="form-control" required placeholder="First Middle Last Name">
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="email" class="form-control" required placeholder="applicant@example.com">
            </div>
            <div class="form-group">
                <label>Mobile Number (WhatsApp Enabled) *</label>
                <input type="tel" name="phone" id="phone" class="form-control" required placeholder="10-digit mobile number" maxlength="10" inputmode="numeric" pattern="[0-9]{10}">
            </div>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label>Preferred Program / Degree *</label>
                <select name="program_id" class="form-control" required>
                    <option value="">-- Choose Program --</option>
                    <?php foreach($programs as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= esc($p['name']) ?> (<?= esc($p['code']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Qualifying Exam % / CGPA (12th / Diploma) *</label>
                <input type="number" step="0.1" name="previous_percentage" max="100" class="form-control" required placeholder="e.g. 86.5">
            </div>
        </div>

        <h3 style="font-size: 16px; margin: 20px 0 16px; color: #4F46E5; border-bottom: 1px solid #E2E8F0; padding-bottom: 8px;">
            <i class="fa-solid fa-people-roof me-2"></i> Parent / Communication Information
        </h3>

        <div class="form-group">
            <label>Parent / Guardian Full Name *</label>
            <input type="text" name="parent_name" class="form-control" required placeholder="Father or Mother name">
        </div>

        <div class="form-group">
            <label>Residential Postal Address *</label>
            <textarea name="address" class="form-control" rows="2" required placeholder="Street address, city, state, pincode"></textarea>
        </div>

        <button type="submit" class="btn-submit" style="margin-top: 24px;">
            <i class="fa-solid fa-paper-plane"></i> Submit Admission Application
        </button>
    </form>
</div>

<script>
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('keypress', function(e) {
            const charCode = e.which ? e.which : e.keyCode;
            if (charCode < 48 || charCode > 57) {
                e.preventDefault();
            }
        });
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });
    }
</script>

</body>
</html>
