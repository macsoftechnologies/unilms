<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Certificate Verification - UniLMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f1f5f9; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .cert-card { background: white; border-radius: 1.5rem; max-width: 650px; width: 100%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); border: 2px solid #e2e8f0; }
        .cert-header { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); color: white; border-top-left-radius: 1.4rem; border-top-right-radius: 1.4rem; padding: 2.5rem; text-align: center; }
    </style>
</head>
<body>

<div class="cert-card overflow-hidden">
    <?php if ($record): ?>
        <div class="cert-header">
            <i class="fas fa-certificate fa-4x mb-3 text-warning"></i>
            <h3 class="fw-bold mb-1">Official Verified Certificate</h3>
            <p class="mb-0 text-white-50">University Training & Placement Certification Registry</p>
        </div>
        <div class="p-4 p-md-5">
            <div class="alert alert-success d-flex align-items-center rounded-3 mb-4">
                <i class="fas fa-check-circle fa-2x me-3 text-success"></i>
                <div>
                    <strong class="d-block">Cryptographic Verification Passed</strong>
                    <small>This certificate is authentic, verified, and issued by the university.</small>
                </div>
            </div>

            <div class="row g-3 small mb-4">
                <div class="col-6">
                    <span class="text-muted d-block">Student Name</span>
                    <strong class="fs-6 text-dark"><?= esc($record['first_name'] . ' ' . $record['last_name']) ?></strong>
                </div>
                <div class="col-6">
                    <span class="text-muted d-block">Roll Number</span>
                    <strong class="fs-6 text-dark"><?= esc($record['roll_number']) ?></strong>
                </div>
                <div class="col-6">
                    <span class="text-muted d-block">Company</span>
                    <strong class="fs-6 text-dark"><?= esc($record['company_name']) ?></strong>
                </div>
                <div class="col-6">
                    <span class="text-muted d-block">Role Completed</span>
                    <strong class="fs-6 text-dark"><?= esc($record['role_title']) ?></strong>
                </div>
                <div class="col-6">
                    <span class="text-muted d-block">Awarded Weighted Grade</span>
                    <span class="badge bg-success fs-6"><?= esc($record['total_weighted_grade']) ?></span>
                </div>
                <div class="col-6">
                    <span class="text-muted d-block">Certificate ID</span>
                    <span class="badge bg-primary fs-6"><?= esc($record['certificate_number']) ?></span>
                </div>
            </div>

            <div class="p-3 bg-light rounded-3 border text-center font-monospace small text-muted text-break">
                <strong>Verification Hash:</strong><br><?= esc($hash) ?>
            </div>
        </div>
    <?php else: ?>
        <div class="p-5 text-center">
            <i class="fas fa-times-circle fa-4x text-danger mb-3"></i>
            <h4 class="fw-bold text-dark">Certificate Verification Failed</h4>
            <p class="text-muted mb-0">The certificate hash <code class="text-break"><?= esc($hash) ?></code> was not found in the university registry or has been revoked.</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
