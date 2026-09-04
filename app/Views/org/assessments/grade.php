<?= $this->extend('org/layout') ?>

<?= $this->section('title') ?>
Grade Submission - <?= esc($student['first_name'] . ' ' . $student['last_name']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Grading & Rubric Evaluation</h3>
            <p class="text-muted mb-0">Student: <strong><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></strong> (Roll: <?= esc($student['roll_number']) ?>)</p>
        </div>
        <a href="<?= site_url('org/assessments/submissions/' . $assessment['id']) ?>" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i> Back to Submissions
        </a>
    </div>

    <div class="row">
        <!-- Left Column: Student Deliverable Inspection -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-file-alt text-primary me-2"></i> Student Submitted Output</h5>

                <?php if ($assessment['assessment_type'] === 'file_upload'): ?>
                    <div class="p-4 bg-light rounded-3 text-center border">
                        <i class="fas fa-file-download fa-3x text-primary mb-3"></i>
                        <h6>Uploaded File Deliverable</h6>
                        <?php if (!empty($submission['submitted_file'])): ?>
                            <a href="<?= base_url($submission['submitted_file']) ?>" class="btn btn-primary rounded-pill px-4" target="_blank" download>
                                <i class="fas fa-download me-2"></i> Download Deliverable
                            </a>
                        <?php else: ?>
                            <span class="text-danger">No file uploaded</span>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($submission['student_comments'])): ?>
                        <div class="mt-3">
                            <label class="fw-bold small text-muted">Student Comments:</label>
                            <p class="p-3 bg-light rounded-3 border mb-0"><?= nl2br(esc($submission['student_comments'])) ?></p>
                        </div>
                    <?php endif; ?>

                <?php elseif ($assessment['assessment_type'] === 'essay'): ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-secondary-subtle text-dark border">Word Count: <?= esc($submission['essay_word_count']) ?> words</span>
                    </div>
                    <div class="p-3 bg-light rounded-3 border overflow-auto" style="max-height: 450px; white-space: pre-wrap; font-family: 'Georgia', serif; line-height: 1.6;">
                        <?= esc($submission['submitted_essay']) ?>
                    </div>

                <?php elseif (in_array($assessment['assessment_type'], ['cbt_quiz', 'interactive_video'])): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-calculator me-2"></i> Auto-Calculated Assessment Score: <strong><?= esc($submission['auto_score']) ?> / <?= esc($assessment['max_marks']) ?></strong>
                    </div>
                    <?php $answers = json_decode($submission['answers_payload'] ?? '[]', true); ?>
                    <div class="bg-light p-3 rounded-3 border">
                        <h6 class="fw-bold">Student Responses Summary:</h6>
                        <pre class="mb-0 bg-white p-2 rounded border small"><?= esc(json_encode($answers, JSON_PRETTY_PRINT)) ?></pre>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Column: Grading & Feedback -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-check-circle text-success me-2"></i> Evaluation & Score</h5>

                <form action="<?= site_url('org/assessments/saveGrade') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="submission_id" value="<?= $submission['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Final Awarded Marks (out of <?= esc($assessment['max_marks']) ?>) <span class="text-danger">*</span></label>
                        <input type="number" step="0.5" max="<?= esc($assessment['max_marks']) ?>" min="0" name="final_marks" class="form-control form-control-lg border-primary fw-bold text-primary" value="<?= esc($submission['final_marks'] ?? $submission['auto_score'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Faculty Feedback Remarks</label>
                        <textarea name="faculty_feedback" class="form-control" rows="4" placeholder="Provide constructive remarks, points of improvement, and commentary..."><?= esc($submission['faculty_feedback'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 rounded-pill shadow-sm">
                        <i class="fas fa-save me-2"></i> Save Grade & Publish to Student
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
