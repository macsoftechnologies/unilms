<?= $this->extend('org/layout') ?>

<?= $this->section('title') ?>
Marks Audit Trail
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Marks Security Audit Trail</h3>
            <p class="text-muted mb-0">Immutable compliance logs tracking all mark submissions, lock states, and HOD override unlocks.</p>
        </div>
        <a href="<?= site_url('org/marks/entry') ?>" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i> Back to Marks Entry
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Timestamp</th>
                        <th>Subject & Cohort</th>
                        <th>Exam Type / Component</th>
                        <th>Action</th>
                        <th>User</th>
                        <th class="pe-4">Reason / Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-history fa-3x mb-3 text-secondary opacity-50"></i>
                                <p class="mb-0">No audit log records available yet.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $l): ?>
                            <tr>
                                <td class="ps-4 text-muted small">
                                    <?= date('d/m/Y, h:i A', strtotime($l['created_at'])) ?>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= esc($l['subject_name']) ?></div>
                                    <small class="text-muted"><?= esc($l['cohort_name']) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?= ucfirst(esc($l['exam_type'])) ?></span>
                                    <small class="text-muted d-block"><?= esc($l['component_name']) ?></small>
                                </td>
                                <td>
                                    <?php if ($l['action'] === 'locked'): ?>
                                        <span class="badge bg-danger"><i class="fas fa-lock me-1"></i> Locked</span>
                                    <?php elseif ($l['action'] === 'unlocked'): ?>
                                        <span class="badge bg-warning text-dark"><i class="fas fa-unlock me-1"></i> Unlocked</span>
                                    <?php else: ?>
                                        <span class="badge bg-info-subtle text-info">Submitted</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark"><?= esc($l['performed_by_name'] ?: 'User #' . $l['performed_by_user_id']) ?></span>
                                </td>
                                <td class="pe-4">
                                    <small class="text-muted"><?= esc($l['unlock_reason'] ?: 'None') ?></small>
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
