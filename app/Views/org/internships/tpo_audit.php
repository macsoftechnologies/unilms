<?= $this->extend('org/layout') ?>

<?= $this->section('title') ?>
TPO Final Audit & Certification Gatekeeper
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">TPO Final Certification Gatekeeper</h3>
            <p class="text-muted mb-0">Audit completed candidate records according to the 4 Enterprise Verification Rules and issue QR-stamped certificates.</p>
        </div>
        <a href="<?= site_url('org/internships') ?>" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i> Back to Internships
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="p-4 bg-light border-bottom">
            <h5 class="fw-bold text-dark mb-1"><i class="fas fa-shield-alt text-primary me-2"></i> The 4 Enterprise Verification Gatekeeper Rules</h5>
            <div class="row g-2 mt-2 small">
                <div class="col-md-3"><span class="badge bg-success me-1">Rule 1</span> Milestone Progress $\ge 80\%$</div>
                <div class="col-md-3"><span class="badge bg-success me-1">Rule 2</span> Zero Pending/Unapproved Tasks</div>
                <div class="col-md-3"><span class="badge bg-success me-1">Rule 3</span> Mid-Review Cleared</div>
                <div class="col-md-3"><span class="badge bg-success me-1">Rule 4</span> Final 6-Axis Rubric Submitted</div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Student</th>
                        <th>Company & Role</th>
                        <th>Weighted Grade</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Certification Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($candidates)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-award fa-3x mb-3 text-secondary opacity-50"></i>
                                <p class="mb-0">No candidate records currently pending final certification audit.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($candidates as $c): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark"><?= esc($c['first_name'] . ' ' . $c['last_name']) ?></div>
                                    <small class="text-muted">Roll: <?= esc($c['roll_number']) ?></small>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= esc($c['company_name']) ?></div>
                                    <small class="text-primary"><?= esc($c['role_title']) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-success fs-6"><i class="fas fa-star me-1"></i> <?= esc($c['total_weighted_grade']) ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Pending TPO Stamp</span>
                                </td>
                                <td class="text-end pe-4">
                                    <form action="<?= site_url('org/internships/issueCertificate') ?>" method="POST" class="d-inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="enrollment_id" value="<?= $c['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-4 shadow-sm" onclick="return confirm('Verify compliance audit and issue verifiable cryptographic QR certificate?');">
                                            <i class="fas fa-stamp me-1"></i> Approve & Issue Certificate
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
