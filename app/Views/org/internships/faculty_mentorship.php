<?= $this->extend('org/layout') ?>

<?= $this->section('title') ?>
Faculty Internship Mentorship
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Faculty Internship Mentorship</h3>
            <p class="text-muted mb-0">Active mentorship workspace: review deliverables, check weekly progress, and submit final 6-dimension rubric.</p>
        </div>
        <a href="<?= site_url('org/internships') ?>" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i> Back to Internships
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Mentee Student</th>
                        <th>Company & Role</th>
                        <th>Midpoint Review</th>
                        <th>Final Evaluation</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($enrollments)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-user-friends fa-3x mb-3 text-secondary opacity-50"></i>
                                <p class="mb-0">No active students assigned to you for internship mentorship currently.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($enrollments as $e): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark"><?= esc($e['first_name'] . ' ' . $e['last_name']) ?></div>
                                    <small class="text-muted">Roll: <?= esc($e['roll_number']) ?></small>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= esc($e['company_name']) ?></div>
                                    <small class="text-primary"><?= esc($e['role_title']) ?></small>
                                </td>
                                <td>
                                    <?php if (!empty($e['faculty_mid_approved'])): ?>
                                        <span class="badge bg-success-subtle text-success border border-success"><i class="fas fa-check me-1"></i> Mid-Review Cleared</span>
                                    <?php elseif (!empty($e['student_mid_review'])): ?>
                                        <span class="badge bg-warning-subtle text-warning border border-warning">Pending Mentor Approval</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-muted">Not Submitted Yet</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($e['total_weighted_grade'])): ?>
                                        <span class="badge bg-success"><i class="fas fa-award me-1"></i> <?= esc($e['total_weighted_grade']) ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Pending Evaluation</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?= site_url('org/internships/reviewStudent/' . $e['id']) ?>" class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm">
                                        <i class="fas fa-tasks me-1"></i> Mentorship Workspace
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
