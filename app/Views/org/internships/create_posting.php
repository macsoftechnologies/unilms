<?= $this->extend('org/layout') ?>

<?= $this->section('title') ?>
<?= $posting ? 'Edit Internship & Roadmap' : 'Post Internship with Roadmap' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><?= $posting ? 'Edit Internship & Roadmap' : 'Post Internship & Roadmap Builder' ?></h3>
            <p class="text-muted mb-0">Build sequential learning milestones and deliverable tasks for student execution.</p>
        </div>
        <a href="<?= site_url('org/internships') ?>" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i> Back to Internships
        </a>
    </div>

    <form action="<?= site_url('org/internships/savePosting') ?>" method="POST" id="postingForm">
        <?= csrf_field() ?>
        <?php if ($posting): ?>
            <input type="hidden" name="id" value="<?= esc($posting['uuid'] ?? $posting['id']) ?>">
        <?php endif; ?>

        <!-- Posting Master Details -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-briefcase text-primary me-2"></i> Company & Posting Master Details</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Company / Organization Name <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" class="form-control" placeholder="e.g. Google, Microsoft, TCS" value="<?= esc($posting['company_name'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Internship Role Title <span class="text-danger">*</span></label>
                    <input type="text" name="role_title" class="form-control" placeholder="e.g. Full Stack Developer Intern" value="<?= esc($posting['role_title'] ?? '') ?>" required>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Job Description & Outcomes</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Provide complete role overview..."><?= esc($posting['description'] ?? '') ?></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Work Mode</label>
                    <select name="work_mode" class="form-select">
                        <option value="onsite" <?= ($posting['work_mode'] ?? '') === 'onsite' ? 'selected' : '' ?>>Onsite</option>
                        <option value="remote" <?= ($posting['work_mode'] ?? '') === 'remote' ? 'selected' : '' ?>>Remote</option>
                        <option value="hybrid" <?= ($posting['work_mode'] ?? '') === 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Location</label>
                    <input type="text" name="location" class="form-control" placeholder="e.g. Hyderabad / Bangalore" value="<?= esc($posting['location'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Monthly Stipend (₹)</label>
                    <input type="number" step="100" name="stipend_amount" class="form-control" value="<?= esc($posting['stipend_amount'] ?? 0) ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Total Seats</label>
                    <input type="number" name="total_seats" class="form-control" value="<?= esc($posting['total_seats'] ?? 5) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Minimum CGPA</label>
                    <input type="number" step="0.1" name="min_cgpa" class="form-control" value="<?= esc($posting['min_cgpa'] ?? 6.5) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="<?= esc($posting['start_date'] ?? date('Y-m-d')) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="<?= esc($posting['end_date'] ?? date('Y-m-d', strtotime('+8 weeks'))) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Application Deadline</label>
                    <input type="date" name="application_deadline" class="form-control" value="<?= esc($posting['application_deadline'] ?? date('Y-m-d', strtotime('+14 days'))) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Target Department</label>
                    <select name="target_department_id" class="form-select">
                        <option value="">-- Open for All Departments --</option>
                        <?php foreach ($departments as $d): ?>
                            <option value="<?= $d['id'] ?>" <?= ($posting['target_department_id'] ?? '') == $d['id'] ? 'selected' : '' ?>><?= esc($d['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Sequential Roadmap Builder -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-map-signs text-success me-2"></i> Sequential Roadmap Builder</h5>
                    <small class="text-muted">Define sequential milestones and tasks. Milestone 2 unlocks only after Milestone 1 is completed.</small>
                </div>
                <button type="button" class="btn btn-outline-success rounded-pill" id="btnAddMilestone">
                    <i class="fas fa-plus me-1"></i> Add Milestone
                </button>
            </div>

            <div id="milestonesContainer">
                <?php if (!empty($milestones)): ?>
                    <?php foreach ($milestones as $mIdx => $m): ?>
                        <div class="milestone-block border border-2 border-primary-subtle rounded-4 p-3 mb-4 bg-light position-relative" data-mindex="<?= $mIdx ?>">
                            <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2 rounded-circle btn-remove-m"><i class="fas fa-times"></i></button>
                            <h6 class="fw-bold text-primary mb-2">Milestone <?= $mIdx + 1 ?></h6>
                            <div class="row g-2 mb-3">
                                <div class="col-md-9">
                                    <input type="text" name="milestones[<?= $mIdx ?>][title]" class="form-control fw-bold" placeholder="Milestone Title (e.g. Week 1-2: Architecture & Setup)" value="<?= esc($m['title']) ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check pt-2">
                                        <input class="form-check-input" type="checkbox" name="milestones[<?= $mIdx ?>][is_midpoint_gate]" value="1" <?= !empty($m['is_midpoint_gate']) ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-bold small text-danger">Midpoint Gate Checkpoint</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Tasks inside milestone -->
                            <div class="tasks-container p-2 bg-white rounded-3 border">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small fw-bold text-muted text-uppercase">Deliverable Tasks</span>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill py-0 btn-add-task" data-mindex="<?= $mIdx ?>">+ Add Task</button>
                                </div>
                                <div class="tasks-list">
                                    <?php $tasks = $m['tasks'] ?? []; ?>
                                    <?php foreach ($tasks as $tIdx => $t): ?>
                                        <div class="task-row border rounded-3 p-2 mb-2 bg-light position-relative">
                                            <button type="button" class="btn btn-sm text-danger position-absolute top-0 end-0 btn-remove-t"><i class="fas fa-times"></i></button>
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <input type="text" name="milestones[<?= $mIdx ?>][tasks][<?= $tIdx ?>][task_title]" class="form-control form-control-sm" placeholder="Task Title" value="<?= esc($t['task_title']) ?>" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <select name="milestones[<?= $mIdx ?>][tasks][<?= $tIdx ?>][submission_type]" class="form-select form-select-sm">
                                                        <option value="file" <?= $t['submission_type'] === 'file' ? 'selected' : '' ?>>File Deliverable (PDF/ZIP)</option>
                                                        <option value="link" <?= $t['submission_type'] === 'link' ? 'selected' : '' ?>>Code Link (GitHub/GitLab)</option>
                                                        <option value="text" <?= $t['submission_type'] === 'text' ? 'selected' : '' ?>>Text / Explanation</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" step="0.5" name="milestones[<?= $mIdx ?>][tasks][<?= $tIdx ?>][estimated_hours]" class="form-control form-control-sm" placeholder="Est. Hours" value="<?= esc($t['estimated_hours']) ?>">
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                <i class="fas fa-save me-2"></i> Publish Internship & Roadmap
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let mCount = document.querySelectorAll('.milestone-block').length;

    document.getElementById('btnAddMilestone')?.addEventListener('click', function () {
        const template = `
            <div class="milestone-block border border-2 border-primary-subtle rounded-4 p-3 mb-4 bg-light position-relative" data-mindex="${mCount}">
                <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2 rounded-circle btn-remove-m"><i class="fas fa-times"></i></button>
                <h6 class="fw-bold text-primary mb-2">Milestone ${mCount + 1}</h6>
                <div class="row g-2 mb-3">
                    <div class="col-md-9">
                        <input type="text" name="milestones[${mCount}][title]" class="form-control fw-bold" placeholder="Milestone Title (e.g. Week 1: Environment & Foundations)" required>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check pt-2">
                            <input class="form-check-input" type="checkbox" name="milestones[${mCount}][is_midpoint_gate]" value="1">
                            <label class="form-check-label fw-bold small text-danger">Midpoint Gate Checkpoint</label>
                        </div>
                    </div>
                </div>

                <div class="tasks-container p-2 bg-white rounded-3 border">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small fw-bold text-muted text-uppercase">Deliverable Tasks</span>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill py-0 btn-add-task" data-mindex="${mCount}">+ Add Task</button>
                    </div>
                    <div class="tasks-list">
                        <div class="task-row border rounded-3 p-2 mb-2 bg-light position-relative">
                            <button type="button" class="btn btn-sm text-danger position-absolute top-0 end-0 btn-remove-t"><i class="fas fa-times"></i></button>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="text" name="milestones[${mCount}][tasks][0][task_title]" class="form-control form-control-sm" placeholder="Task Title" required>
                                </div>
                                <div class="col-md-3">
                                    <select name="milestones[${mCount}][tasks][0][submission_type]" class="form-select form-select-sm">
                                        <option value="file">File Deliverable (PDF/ZIP)</option>
                                        <option value="link">Code Link (GitHub/GitLab)</option>
                                        <option value="text">Text / Explanation</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" step="0.5" name="milestones[${mCount}][tasks][0][estimated_hours]" class="form-control form-control-sm" placeholder="Est. Hours" value="6">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('milestonesContainer').insertAdjacentHTML('beforeend', template);
        mCount++;
    });

    document.getElementById('milestonesContainer')?.addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove-m')) {
            e.target.closest('.milestone-block').remove();
        } else if (e.target.closest('.btn-remove-t')) {
            e.target.closest('.task-row').remove();
        } else if (e.target.closest('.btn-add-task')) {
            const block = e.target.closest('.milestone-block');
            const mIdx = block.getAttribute('data-mindex');
            const list = block.querySelector('.tasks-list');
            const tCount = list.querySelectorAll('.task-row').length;

            const tTemplate = `
                <div class="task-row border rounded-3 p-2 mb-2 bg-light position-relative">
                    <button type="button" class="btn btn-sm text-danger position-absolute top-0 end-0 btn-remove-t"><i class="fas fa-times"></i></button>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="text" name="milestones[${mIdx}][tasks][${tCount}][task_title]" class="form-control form-control-sm" placeholder="Task Title" required>
                        </div>
                        <div class="col-md-3">
                            <select name="milestones[${mIdx}][tasks][${tCount}][submission_type]" class="form-select form-select-sm">
                                <option value="file">File Deliverable (PDF/ZIP)</option>
                                <option value="link">Code Link (GitHub/GitLab)</option>
                                <option value="text">Text / Explanation</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" step="0.5" name="milestones[${mIdx}][tasks][${tCount}][estimated_hours]" class="form-control form-control-sm" placeholder="Est. Hours" value="6">
                        </div>
                    </div>
                </div>
            `;
            list.insertAdjacentHTML('beforeend', tTemplate);
        }
    });
});
</script>
<?= $this->endSection() ?>
