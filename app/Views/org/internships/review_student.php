<?= $this->extend('org/layout') ?>

<?= $this->section('title') ?>
Mentorship - <?= esc($enrollment['first_name'] . ' ' . $enrollment['last_name']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Mentorship Workspace</h3>
            <p class="text-muted mb-0">Student: <strong><?= esc($enrollment['first_name'] . ' ' . $enrollment['last_name']) ?></strong> &bull; <?= esc($enrollment['company_name']) ?> (<?= esc($enrollment['role_title']) ?>)</p>
        </div>
        <a href="<?= site_url('org/internships/mentorship') ?>" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i> Back to Mentorship List
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Left: Milestone & Task Deliverable Review -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-tasks text-primary me-2"></i> Milestone Deliverables & Tasks</h5>

                <?php foreach ($milestones as $m): ?>
                    <div class="border rounded-4 p-3 mb-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold text-primary mb-0">Milestone <?= $m['milestone_number'] ?>: <?= esc($m['title']) ?></h6>
                            <?php if ($m['is_midpoint_gate']): ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger">Midpoint Gate</span>
                            <?php endif; ?>
                        </div>

                        <?php foreach ($m['tasks'] as $t): ?>
                            <?php $sub = $t['submission']; ?>
                            <div class="bg-white rounded-3 p-3 border mb-2">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <div class="fw-bold text-dark"><?= esc($t['task_title']) ?></div>
                                        <small class="text-muted">Est. Hours: <?= esc($t['estimated_hours']) ?> hrs &bull; Tracked: <?= !empty($sub['tracked_time_seconds']) ? round($sub['tracked_time_seconds'] / 3600, 1) . ' hrs' : '0 hrs' ?></small>
                                    </div>
                                    <div>
                                        <?php if (!$sub): ?>
                                            <span class="badge bg-secondary-subtle text-muted">Not Submitted</span>
                                        <?php elseif ($sub['faculty_status'] === 'approved'): ?>
                                            <span class="badge bg-success-subtle text-success border border-success"><i class="fas fa-check me-1"></i> Approved</span>
                                        <?php elseif ($sub['faculty_status'] === 'rejected'): ?>
                                            <span class="badge bg-danger-subtle text-danger">Rejected</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pending Review</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if ($sub): ?>
                                    <div class="p-2 bg-light rounded-3 border small mb-2">
                                        <?php if (!empty($sub['submission_link'])): ?>
                                            <div><strong>Code Link:</strong> <a href="<?= esc($sub['submission_link']) ?>" target="_blank"><?= esc($sub['submission_link']) ?></a></div>
                                        <?php endif; ?>
                                        <?php if (!empty($sub['submission_file'])): ?>
                                            <div><strong>Attached File:</strong> <a href="<?= base_url($sub['submission_file']) ?>" target="_blank" download><i class="fas fa-download me-1"></i> Download Deliverable</a></div>
                                        <?php endif; ?>
                                        <?php if (!empty($sub['submission_text'])): ?>
                                            <div class="mt-1"><strong>Notes:</strong> <?= nl2br(esc($sub['submission_text'])) ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Quick Review Form -->
                                    <form action="<?= site_url('org/internships/evaluateTask') ?>" method="POST" class="d-flex gap-2">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="submission_id" value="<?= $sub['id'] ?>">
                                        <input type="text" name="faculty_feedback" class="form-control form-control-sm" placeholder="Feedback remarks..." value="<?= esc($sub['faculty_feedback'] ?? '') ?>">
                                        <button type="submit" name="faculty_status" value="approved" class="btn btn-sm btn-success rounded-pill px-3 text-nowrap">Approve & Lock</button>
                                        <button type="submit" name="faculty_status" value="rejected" class="btn btn-sm btn-outline-danger rounded-pill px-3 text-nowrap">Reject</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Right: 6-Dimension Rubric Grading & Midpoint Gate -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-award text-warning me-2"></i> Final 6-Dimension Rubric Evaluation</h5>
                <p class="text-muted small">Fill the 6 core competency dimensions (0-100) to compute the total weighted grade for official TPO certification.</p>

                <form action="<?= site_url('org/internships/submitFinalRubric') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="enrollment_id" value="<?= $enrollment['id'] ?>">

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">1. Technical Competence</label>
                            <input type="number" step="1" min="0" max="100" name="rubric_technical" class="form-control" value="<?= esc($enrollment['rubric_technical'] ?? 85) ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">2. Communication</label>
                            <input type="number" step="1" min="0" max="100" name="rubric_communication" class="form-control" value="<?= esc($enrollment['rubric_communication'] ?? 80) ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">3. Discipline & Punctuality</label>
                            <input type="number" step="1" min="0" max="100" name="rubric_discipline" class="form-control" value="<?= esc($enrollment['rubric_discipline'] ?? 90) ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">4. Problem Solving</label>
                            <input type="number" step="1" min="0" max="100" name="rubric_problem_solving" class="form-control" value="<?= esc($enrollment['rubric_problem_solving'] ?? 85) ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">5. Quality of Output</label>
                            <input type="number" step="1" min="0" max="100" name="rubric_quality_output" class="form-control" value="<?= esc($enrollment['rubric_quality_output'] ?? 88) ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">6. Attendance & Consistency</label>
                            <input type="number" step="1" min="0" max="100" name="rubric_attendance" class="form-control" value="<?= esc($enrollment['rubric_attendance'] ?? 95) ?>" required>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Viva Marks (out of 100)</label>
                            <input type="number" step="1" min="0" max="100" name="viva_marks" class="form-control" value="<?= esc($enrollment['viva_marks'] ?? 85) ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Report Marks (out of 100)</label>
                            <input type="number" step="1" min="0" max="100" name="report_marks" class="form-control" value="<?= esc($enrollment['report_marks'] ?? 88) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Faculty Final Remarks</label>
                        <textarea name="faculty_final_remarks" class="form-control" rows="3" placeholder="Remarks on student performance..."><?= esc($enrollment['faculty_final_remarks'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill shadow-sm">
                        <i class="fas fa-save me-2"></i> Save Rubric & Forward to TPO
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
