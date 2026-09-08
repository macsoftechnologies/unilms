<?= $this->extend('org/layout') ?>

<?= $this->section('title') ?>
Internship Management & TPO Portal
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Corporate Internships & TPO Gatekeeper</h3>
            <p class="text-muted mb-0">Manage industry postings, sequential milestone roadmaps, offers, and verified certification.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= site_url('org/internships/tpoAudit') ?>" class="btn btn-outline-success rounded-pill px-3">
                <i class="fas fa-stamp me-1"></i> TPO Audit & Certification
            </a>
            <a href="<?= site_url('org/internships/mentorship') ?>" class="btn btn-outline-primary rounded-pill px-3">
                <i class="fas fa-chalkboard-teacher me-1"></i> Faculty Mentorship
            </a>
            <a href="<?= site_url('org/internships/createPosting') ?>" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus-circle me-1"></i> Post Internship
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Company & Role</th>
                        <th>Mode & Location</th>
                        <th>Stipend</th>
                        <th>Seats</th>
                        <th>Dates</th>
                        <th>Applications</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($postings)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-briefcase fa-3x mb-3 text-secondary opacity-50"></i>
                                <p class="mb-0">No internship opportunities posted yet. Click <strong>Post Internship</strong> to create one.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($postings as $p): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark fs-6"><?= esc($p['company_name']) ?></div>
                                    <small class="text-primary fw-semibold"><?= esc($p['role_title']) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border mb-1"><?= ucfirst(esc($p['work_mode'])) ?></span>
                                    <div><small class="text-muted"><?= esc($p['location'] ?: 'Virtual') ?></small></div>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">
                                        <?= ($p['stipend_amount'] > 0) ? '₹' . number_format($p['stipend_amount'], 2) : 'Unpaid / Academic' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-dark"><?= esc($p['available_seats']) ?> / <?= esc($p['total_seats']) ?> Available</span>
                                </td>
                                <td>
                                    <small class="text-muted d-block">Start: <?= esc($p['start_date']) ?></small>
                                    <small class="text-muted d-block">End: <?= esc($p['end_date']) ?></small>
                                </td>
                                <td>
                                    <a href="<?= site_url('org/internships/applications/' . ($p['uuid'] ?? $p['id'])) ?>" class="badge bg-info-subtle text-info border border-info px-2 py-1 text-decoration-none">
                                        <i class="fas fa-users me-1"></i> <?= $p['applicant_count'] ?> Applicants
                                    </a>
                                </td>
                                <td>
                                    <?php if ($p['status'] === 'published'): ?>
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i> Published</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?= site_url('org/internships/applications/' . ($p['uuid'] ?? $p['id'])) ?>" class="btn btn-sm btn-outline-info rounded-circle me-1" title="Review Applications">
                                        <i class="fas fa-user-check"></i>
                                    </a>
                                    <a href="<?= site_url('org/internships/editPosting/' . ($p['uuid'] ?? $p['id'])) ?>" class="btn btn-sm btn-outline-primary rounded-circle" title="Edit Posting & Roadmap">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
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
