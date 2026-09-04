<?= $this->extend('org/layout') ?>

<?= $this->section('title') ?>
Submissions - <?= esc($assessment['title']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Submissions & Evaluation</h3>
            <p class="text-muted mb-0">Assessment: <strong><?= esc($assessment['title']) ?></strong> (Max: <?= esc($assessment['max_marks']) ?> Marks)</p>
        </div>
        <a href="<?= site_url('org/assessments') ?>" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i> Back to Assessments
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Student</th>
                        <th>Submitted At</th>
                        <th>Status</th>
                        <th>Calculated Score</th>
                        <th>Final Awarded Marks</th>
                        <th class="text-end pe-4">Grading Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($submissions)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 text-secondary opacity-50"></i>
                                <p class="mb-0">No submissions received yet from enrolled students.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($submissions as $sub): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark"><?= esc($sub['first_name'] . ' ' . $sub['last_name']) ?></div>
                                    <small class="text-muted"><?= esc($sub['roll_number']) ?> &bull; <?= esc($sub['email']) ?></small>
                                </td>
                                <td>
                                    <div><?= date('M d, Y h:i A', strtotime($sub['submitted_at'])) ?></div>
                                    <?php if ($sub['is_late']): ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger small"><i class="fas fa-clock me-1"></i> Late Submission</span>
                                    <?php else: ?>
                                        <span class="badge bg-success-subtle text-success border border-success small"><i class="fas fa-check me-1"></i> On Time</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($sub['status'] === 'graded'): ?>
                                        <span class="badge bg-success">Graded</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Pending Evaluation</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= ($sub['auto_score'] !== null) ? esc($sub['auto_score']) . ' / ' . esc($assessment['max_marks']) : '<span class="text-muted">N/A</span>' ?>
                                </td>
                                <td>
                                    <span class="fw-bold fs-6 text-primary">
                                        <?= ($sub['final_marks'] !== null) ? esc($sub['final_marks']) . ' / ' . esc($assessment['max_marks']) : '<span class="text-muted">Unassigned</span>' ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?= site_url('org/assessments/grade/' . $sub['id']) ?>" class="btn btn-sm btn-primary rounded-pill px-3">
                                        <i class="fas fa-marker me-1"></i> <?= ($sub['status'] === 'graded') ? 'Review Grade' : 'Grade Submission' ?>
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
