<?= $this->extend('org/layout') ?>

<?= $this->section('title') ?>
Internship Applications & Offers
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Internship Applications & Offers</h3>
            <p class="text-muted mb-0">Review student resumes, extend time-limited offers, assign academic mentors, and invite external supervisors.</p>
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
                        <th class="ps-4">Student</th>
                        <th>Company & Role</th>
                        <th>Resume & Letter</th>
                        <th>Mentor Assigned</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($applications)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-user-graduate fa-3x mb-3 text-secondary opacity-50"></i>
                                <p class="mb-0">No student applications received yet.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($applications as $app): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark"><?= esc($app['first_name'] . ' ' . $app['last_name']) ?></div>
                                    <small class="text-muted"><?= esc($app['roll_number']) ?> &bull; <?= esc($app['student_email']) ?></small>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= esc($app['company_name']) ?></div>
                                    <small class="text-primary"><?= esc($app['role_title']) ?></small>
                                </td>
                                <td>
                                    <?php if (!empty($app['resume_file'])): ?>
                                        <a href="<?= base_url($app['resume_file']) ?>" class="badge bg-primary-subtle text-primary border border-primary px-2 py-1 text-decoration-none" target="_blank" download>
                                            <i class="fas fa-file-pdf me-1"></i> View Resume
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small">No Resume</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($app['mentor_first'])): ?>
                                        <span class="badge bg-success-subtle text-success border border-success">
                                            <i class="fas fa-user-tie me-1"></i> <?= esc($app['mentor_first'] . ' ' . $app['mentor_last']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-muted">Unassigned</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($app['status'] === 'applied'): ?>
                                        <span class="badge bg-info-subtle text-info">Applied</span>
                                    <?php elseif ($app['status'] === 'shortlisted'): ?>
                                        <span class="badge bg-primary">Shortlisted</span>
                                    <?php elseif ($app['status'] === 'offered'): ?>
                                        <span class="badge bg-warning text-dark">Offer Extended (<?= !empty($app['offer_valid_until']) ? date('M d', strtotime($app['offer_valid_until'])) : '' ?>)</span>
                                    <?php elseif ($app['status'] === 'in_progress'): ?>
                                        <span class="badge bg-success">In Progress</span>
                                    <?php elseif ($app['status'] === 'completed'): ?>
                                        <span class="badge bg-primary-subtle text-primary"><i class="fas fa-stamp me-1"></i> Certified</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger"><?= ucfirst(esc($app['status'])) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                                            <li>
                                                <form action="<?= site_url('org/internships/updateApplicationStatus') ?>" method="POST">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="enrollment_id" value="<?= $app['id'] ?>">
                                                    <input type="hidden" name="action" value="shortlist">
                                                    <button type="submit" class="dropdown-item"><i class="fas fa-check text-primary me-2"></i> Shortlist</button>
                                                </form>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#offerModal_<?= $app['id'] ?>">
                                                    <i class="fas fa-paper-plane text-success me-2"></i> Extend Offer & Mentor
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#inviteModal_<?= $app['id'] ?>">
                                                    <i class="fas fa-magic text-info me-2"></i> Invite Industry Supervisor
                                                </button>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#rejectModal_<?= $app['id'] ?>">
                                                    <i class="fas fa-times text-danger me-2"></i> Reject Candidate
                                                </button>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Offer Modal -->
                                    <div class="modal fade" id="offerModal_<?= $app['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered text-start">
                                            <div class="modal-content rounded-4 border-0 shadow">
                                                <form action="<?= site_url('org/internships/updateApplicationStatus') ?>" method="POST">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="enrollment_id" value="<?= $app['id'] ?>">
                                                    <input type="hidden" name="action" value="extend_offer">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="fw-bold">Extend Offer to <?= esc($app['first_name']) ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Assign Faculty Mentor</label>
                                                            <select name="faculty_mentor_id" class="form-select" required>
                                                                <?php foreach ($faculty as $f): ?>
                                                                    <option value="<?= $f['id'] ?>"><?= esc($f['first_name'] . ' ' . $f['last_name']) ?> (<?= esc($f['designation'] ?? 'Faculty') ?>)</option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Offer Validity Window (Days)</label>
                                                            <input type="number" name="validity_days" class="form-control" value="3" min="1" max="14" required>
                                                            <small class="text-muted">Student sees a live acceptance countdown timer</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success rounded-pill px-4">Send Offer</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Invite Supervisor Modal -->
                                    <div class="modal fade" id="inviteModal_<?= $app['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered text-start">
                                            <div class="modal-content rounded-4 border-0 shadow">
                                                <form action="<?= site_url('org/internships/updateApplicationStatus') ?>" method="POST">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="enrollment_id" value="<?= $app['id'] ?>">
                                                    <input type="hidden" name="action" value="invite_supervisor">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="fw-bold">Generate Magic Link for Supervisor</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">External Supervisor Name</label>
                                                            <input type="text" name="supervisor_name" class="form-control" placeholder="e.g. John Doe" value="<?= esc($app['supervisor_name'] ?? '') ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Official Company Email</label>
                                                            <input type="email" name="supervisor_email" class="form-control" placeholder="john@company.com" value="<?= esc($app['supervisor_email'] ?? '') ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary rounded-pill px-4">Generate Magic Link</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal_<?= $app['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered text-start">
                                            <div class="modal-content rounded-4 border-0 shadow">
                                                <form action="<?= site_url('org/internships/updateApplicationStatus') ?>" method="POST">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="enrollment_id" value="<?= $app['id'] ?>">
                                                    <input type="hidden" name="action" value="reject">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="fw-bold text-danger">Reject Candidate</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Rejection Reason</label>
                                                            <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Provide reason for feedback..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger rounded-pill px-4">Confirm Rejection</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
