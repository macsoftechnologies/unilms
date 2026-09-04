<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Industry Supervisor Portal - <?= esc($enrollment['company_name']) ?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f8fafc; color: #0f172a; }
        .hero-banner { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); color: white; border-radius: 1rem; padding: 2.5rem; }
    </style>
</head>
<body class="py-4">
<div class="container" style="max-width: 1050px;">
    <!-- Top Hero Banner -->
    <div class="hero-banner shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-2"><i class="fas fa-magic me-1"></i> Industry Supervisor Portal (Passwordless Access)</span>
                <h2 class="fw-bold mb-1"><?= esc($enrollment['company_name']) ?> Mentorship Dashboard</h2>
                <p class="mb-0 text-white-50">Mentee: <strong><?= esc($enrollment['first_name'] . ' ' . $enrollment['last_name']) ?></strong> (<?= esc($enrollment['role_title']) ?>)</p>
            </div>
            <div class="text-end">
                <div class="small text-white-50 mb-1">Company Milestone Completion</div>
                <div class="d-flex align-items-center gap-2">
                    <div class="progress bg-white bg-opacity-25" style="width: 160px; height: 10px;">
                        <div class="progress-bar bg-success" style="width: <?= $progressPct ?>%;"></div>
                    </div>
                    <span class="fw-bold fs-5"><?= $progressPct ?>%</span>
                </div>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Roadmap & Deliverables Review -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <h4 class="fw-bold text-dark mb-3"><i class="fas fa-tasks text-primary me-2"></i> Student Deliverables & Sign-offs</h4>
        
        <?php foreach ($milestones as $m): ?>
            <div class="border rounded-4 p-3 mb-3 bg-light">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold text-primary mb-0">Milestone <?= $m['milestone_number'] ?>: <?= esc($m['title']) ?></h6>
                    <?php if ($enrollment['allow_company_tasks']): ?>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addTaskModal_<?= $m['id'] ?>">
                            <i class="fas fa-plus me-1"></i> Add Company Task
                        </button>
                    <?php endif; ?>
                </div>

                <?php foreach ($m['tasks'] as $t): ?>
                    <?php $sub = $t['submission']; ?>
                    <div class="bg-white rounded-3 p-3 border mb-2">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="fw-bold text-dark"><?= esc($t['task_title']) ?></div>
                                <small class="text-muted">Estimated: <?= esc($t['estimated_hours']) ?> hrs &bull; Tracked: <?= !empty($sub['tracked_time_seconds']) ? round($sub['tracked_time_seconds'] / 3600, 1) . ' hrs' : '0 hrs' ?></small>
                            </div>
                            <div>
                                <?php if ($sub && $sub['supervisor_status'] === 'signed_off'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success px-2 py-1"><i class="fas fa-check me-1"></i> Corporate Signed-Off</span>
                                <?php else: ?>
                                    <span class="badge bg-warning-subtle text-warning border border-warning px-2 py-1">Pending Sign-off</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if ($sub): ?>
                            <div class="p-2 bg-light rounded-3 border small mb-2">
                                <?php if (!empty($sub['submission_link'])): ?>
                                    <div><strong>Code Link:</strong> <a href="<?= esc($sub['submission_link']) ?>" target="_blank"><?= esc($sub['submission_link']) ?></a></div>
                                <?php endif; ?>
                                <?php if (!empty($sub['submission_file'])): ?>
                                    <div><strong>Deliverable:</strong> <a href="<?= base_url($sub['submission_file']) ?>" target="_blank" download><i class="fas fa-download me-1"></i> Download Output</a></div>
                                <?php endif; ?>
                                <?php if (!empty($sub['submission_text'])): ?>
                                    <div class="mt-1"><strong>Notes:</strong> <?= nl2br(esc($sub['submission_text'])) ?></div>
                                <?php endif; ?>
                            </div>

                            <?php if ($sub['supervisor_status'] !== 'signed_off'): ?>
                                <form action="<?= site_url('internship-supervisor/signoffTask') ?>" method="POST" class="d-flex gap-2">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="raw_token" value="<?= esc($rawToken) ?>">
                                    <input type="hidden" name="submission_id" value="<?= $sub['id'] ?>">
                                    <input type="text" name="supervisor_feedback" class="form-control form-control-sm" placeholder="Add industry mentor remarks..." required>
                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-4 text-nowrap"><i class="fas fa-signature me-1"></i> Sign-off Task</button>
                                </form>
                            <?php else: ?>
                                <small class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i> Signed off with feedback: "<?= esc($sub['supervisor_feedback']) ?>"</small>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Add Task Modal for Supervisor -->
            <div class="modal fade" id="addTaskModal_<?= $m['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 border-0 shadow">
                        <form action="<?= site_url('internship-supervisor/addCompanyTask') ?>" method="POST">
                            <?= csrf_field() ?>
                            <input type="hidden" name="raw_token" value="<?= esc($rawToken) ?>">
                            <input type="hidden" name="milestone_id" value="<?= $m['id'] ?>">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="fw-bold">Add Custom Company Task</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Task Title</label>
                                    <input type="text" name="task_title" class="form-control" placeholder="e.g. Implement AWS S3 Upload Script" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Description</label>
                                    <textarea name="description" class="form-control" rows="2" placeholder="Task details..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Estimated Work Hours</label>
                                    <input type="number" step="0.5" name="estimated_hours" class="form-control" value="8">
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary rounded-pill px-4">Add to Student Roadmap</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Corporate Mid-Review & Final Approval -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-star-half-alt text-warning me-2"></i> Corporate Mid-Term Rating</h5>
                <?php if (!empty($enrollment['supervisor_mid_rating'])): ?>
                    <div class="alert alert-success rounded-3 mb-0">
                        <strong>Rating: <?= esc($enrollment['supervisor_mid_rating']) ?> / 10.0</strong>
                        <p class="mb-0 mt-1">"<?= esc($enrollment['supervisor_mid_review']) ?>"</p>
                    </div>
                <?php else: ?>
                    <form action="<?= site_url('internship-supervisor/submitMidReview') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="raw_token" value="<?= esc($rawToken) ?>">
                        <input type="hidden" name="enrollment_id" value="<?= $enrollment['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Performance Rating (1.0 to 10.0)</label>
                            <input type="number" step="0.5" min="1" max="10" name="supervisor_mid_rating" class="form-control" value="9.0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Corporate Feedback Remarks</label>
                            <textarea name="supervisor_mid_review" class="form-control" rows="3" placeholder="Assess student professional performance, problem solving, and work ethic..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">Submit Mid-Review</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-check-double text-success me-2"></i> Final Corporate Program Sign-off</h5>
                <?php if (!empty($enrollment['supervisor_final_signoff'])): ?>
                    <div class="alert alert-success rounded-3 mb-0">
                        <i class="fas fa-check-circle me-1"></i> Corporate Final Sign-off Completed:
                        <p class="mb-0 mt-1">"<?= esc($enrollment['supervisor_final_signoff']) ?>"</p>
                    </div>
                <?php else: ?>
                    <form action="<?= site_url('internship-supervisor/submitFinalSignoff') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="raw_token" value="<?= esc($rawToken) ?>">
                        <input type="hidden" name="enrollment_id" value="<?= $enrollment['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Final Corporate Performance Summary</label>
                            <textarea name="supervisor_final_signoff" class="form-control" rows="4" placeholder="Confirm student has met all company expectations and completed all assigned deliverables..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Submit Final Sign-off</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
