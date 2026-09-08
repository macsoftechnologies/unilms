<?= $this->extend('org/layout') ?>

<?= $this->section('title') ?>
Dynamic Assessments & Exams
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Dynamic Assessments & Exam Engine</h3>
            <p class="text-muted mb-0">Manage 4-in-1 Assessments: File Submissions, CBT Quizzes, Interactive Videos, and Essay Exams.</p>
        </div>
        <a href="<?= site_url('org/assessments/create') ?>" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
            <i class="fas fa-plus-circle me-2"></i> Create Assessment
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
                        <th class="ps-4">Title & Details</th>
                        <th>Subject & Cohort</th>
                        <th>Type</th>
                        <th>Max Marks</th>
                        <th>Due Date</th>
                        <th>Submissions</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($assessments)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-clipboard-list fa-3x mb-3 text-secondary opacity-50"></i>
                                <p class="mb-0">No assessments created yet. Click <strong>Create Assessment</strong> to add one.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($assessments as $a): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark"><?= esc($a['title']) ?></div>
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">
                                        <?= esc(substr(strip_tags($a['description']), 0, 80)) ?>...
                                    </small>
                                </td>
                                <td>
                                    <div class="badge bg-light text-dark border mb-1"><?= esc($a['subject_name'] ?? 'N/A') ?></div>
                                    <div><small class="text-muted"><?= esc($a['cohort_name'] ?? 'All Cohorts') ?></small></div>
                                </td>
                                <td>
                                    <?php if ($a['assessment_type'] === 'cbt_quiz'): ?>
                                        <span class="badge bg-info-subtle text-info border border-info px-2 py-1"><i class="fas fa-tasks me-1"></i> CBT Quiz</span>
                                    <?php elseif ($a['assessment_type'] === 'interactive_video'): ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1"><i class="fas fa-play-circle me-1"></i> Interactive Video</span>
                                    <?php elseif ($a['assessment_type'] === 'essay'): ?>
                                        <span class="badge bg-warning-subtle text-warning border border-warning px-2 py-1"><i class="fas fa-feather-alt me-1"></i> Essay Exam</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1"><i class="fas fa-file-upload me-1"></i> File Upload</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="fw-bold"><?= esc($a['max_marks']) ?></span> pts</td>
                                <td>
                                    <?= !empty($a['due_date']) ? date('M d, Y h:i A', strtotime($a['due_date'])) : '<span class="text-muted">No Deadline</span>' ?>
                                </td>
                                <td>
                                    <a href="<?= site_url('org/assessments/submissions/' . ($a['uuid'] ?? $a['id'])) ?>" class="badge bg-secondary-subtle text-dark px-2 py-1 text-decoration-none">
                                        <i class="fas fa-user-check me-1"></i> <?= $a['submission_count'] ?> Submitted
                                    </a>
                                </td>
                                <td>
                                    <?php if ($a['is_published']): ?>
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i> Published</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?= site_url('org/assessments/submissions/' . ($a['uuid'] ?? $a['id'])) ?>" class="btn btn-sm btn-outline-info rounded-circle me-1" title="View Submissions">
                                        <i class="fas fa-users"></i>
                                    </a>
                                    <a href="<?= site_url('org/assessments/edit/' . ($a['uuid'] ?? $a['id'])) ?>" class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Edit Assessment">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a href="<?= site_url('org/assessments/delete/' . ($a['uuid'] ?? $a['id'])) ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Are you sure you want to delete this assessment?');" title="Delete">
                                        <i class="fas fa-trash"></i>
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
