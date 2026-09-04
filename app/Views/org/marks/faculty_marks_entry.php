<?= $this->extend('org/layout') ?>

<?= $this->section('title') ?>
Term Marks Entry & Locking Engine
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Term Marks Engine & Security State Machine</h3>
            <p class="text-muted mb-0">Standardized exam evaluations (Internal 1-3, Midterm, Final, Practical) with audit-locked states.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= site_url('org/marks/auditTrail') ?>" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="fas fa-history me-1"></i> Marks Audit Trail
            </a>
            <a href="<?= site_url('org/marks/components') ?>" class="btn btn-outline-primary rounded-pill px-3">
                <i class="fas fa-cog me-1"></i> Components Setup
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

    <!-- Selection Filters Card -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <form method="GET" action="<?= site_url('org/marks/entry') ?>" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Subject</label>
                <select name="subject_id" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($subjects as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $selected_subject_id == $s['id'] ? 'selected' : '' ?>><?= esc($s['name']) ?> (<?= esc($s['code']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Cohort / Section</label>
                <select name="cohort_id" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($cohorts as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $selected_cohort_id == $c['id'] ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Assessment Component</label>
                <select name="component_id" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($components as $comp): ?>
                        <option value="<?= $comp['id'] ?>" <?= $selected_component_id == $comp['id'] ? 'selected' : '' ?>><?= esc($comp['name']) ?> (Max: <?= esc($comp['max_marks']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Standard Exam Type</label>
                <select name="exam_type" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($examTypes as $eKey => $eLabel): ?>
                        <option value="<?= $eKey ?>" <?= $selected_exam_type === $eKey ? 'selected' : '' ?>><?= esc($eLabel) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>

    <!-- Marks Spreadsheet Entry Grid -->
    <?php if (empty($students)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center text-muted">
            <i class="fas fa-user-slash fa-3x mb-3 text-secondary opacity-50"></i>
            <p class="mb-0">No students enrolled in this cohort section.</p>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge bg-primary fs-6 me-2"><?= esc($examTypes[$selected_exam_type] ?? 'Internal') ?></span>
                    <span class="text-muted small">Max Marks: <strong><?= esc($max_marks) ?></strong> &bull; Total Students: <strong><?= count($students) ?></strong></span>
                </div>
                <div>
                    <?php if ($is_locked): ?>
                        <span class="badge bg-danger fs-6 px-3 py-2 me-2"><i class="fas fa-lock me-1"></i> Marks Locked by HOD/Admin</span>
                        <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#unlockModal">
                            <i class="fas fa-unlock me-1"></i> Request HOD Unlock
                        </button>
                    <?php else: ?>
                        <span class="badge bg-success fs-6 px-3 py-2"><i class="fas fa-edit me-1"></i> Active Draft Entry Mode</span>
                    <?php endif; ?>
                </div>
            </div>

            <form action="<?= site_url('org/marks/saveMarks') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="subject_id" value="<?= esc($selected_subject_id) ?>">
                <input type="hidden" name="cohort_id" value="<?= esc($selected_cohort_id) ?>">
                <input type="hidden" name="component_id" value="<?= esc($selected_component_id) ?>">
                <input type="hidden" name="exam_type" value="<?= esc($selected_exam_type) ?>">

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">Roll Number</th>
                                <th>Student Name</th>
                                <th style="width: 200px;">Marks Scored (Max: <?= esc($max_marks) ?>)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $stu): ?>
                                <?php 
                                $sId = $stu['id'];
                                $scoreVal = isset($marks_data[$sId]['score']) ? $marks_data[$sId]['score'] : '';
                                ?>
                                <tr>
                                    <td class="ps-4 fw-bold font-monospace"><?= esc($stu['roll_number']) ?></td>
                                    <td><?= esc($stu['first_name'] . ' ' . $stu['last_name']) ?></td>
                                    <td>
                                        <input type="number" step="0.5" min="0" max="<?= esc($max_marks) ?>" 
                                            name="scores[<?= $sId ?>]" 
                                            class="form-control form-control-sm border-primary fw-bold" 
                                            value="<?= esc($scoreVal) ?>" 
                                            placeholder="0 - <?= esc($max_marks) ?>"
                                            <?= $is_locked ? 'disabled' : '' ?>>
                                    </td>
                                    <td>
                                        <?php if ($scoreVal !== '' && $scoreVal !== null): ?>
                                            <span class="badge bg-success-subtle text-success">Recorded</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-muted">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (!$is_locked): ?>
                    <div class="p-4 bg-light border-top d-flex justify-content-between align-items-center">
                        <button type="submit" name="finalize_submit" value="0" class="btn btn-outline-primary rounded-pill px-4">
                            <i class="fas fa-save me-1"></i> Save Draft
                        </button>
                        <button type="submit" name="finalize_submit" value="1" class="btn btn-success rounded-pill px-5 shadow-sm" onclick="return confirm('Finalize and lock marks? Modifications will require an HOD unlock request.');">
                            <i class="fas fa-lock me-1"></i> Submit & Lock Final Marks
                        </button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    <?php endif; ?>

    <!-- Unlock Modal -->
    <div class="modal fade" id="unlockModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form action="<?= site_url('org/marks/unlockMarks') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="subject_id" value="<?= esc($selected_subject_id) ?>">
                    <input type="hidden" name="cohort_id" value="<?= esc($selected_cohort_id) ?>">
                    <input type="hidden" name="component_id" value="<?= esc($selected_component_id) ?>">
                    <input type="hidden" name="exam_type" value="<?= esc($selected_exam_type) ?>">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="fw-bold text-dark">Administrative Unlock Marks</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">Unlocking will allow teachers to edit finalized marks. For compliance reasons, an audit reason is required.</p>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mandatory Unlock Reason <span class="text-danger">*</span></label>
                            <textarea name="unlock_reason" class="form-control" rows="3" placeholder="e.g. Re-evaluation requested by student / internal assessment calculation correction" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">Confirm & Unlock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
