<?= $this->extend('lms/layout') ?>

<?= $this->section('page_title') ?>
Submit Deliverable - <?= esc($assessment['title']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div style="max-width: 960px; margin: 0 auto;">
    <!-- Breadcrumbs & Header -->
    <div style="margin-bottom: 24px;">
        <a href="<?= site_url('lms/assessments') ?>" class="btn btn-sm btn-outline" style="margin-bottom: 12px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-arrow-left"></i> Back to Assessments
        </a>
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
            <div>
                <span class="badge" style="background: rgba(99, 102, 241, 0.12); color: var(--primary); font-size: 11.5px; font-weight: 700; margin-bottom: 6px; display: inline-block;">
                    <i class="fa-solid fa-cloud-arrow-up me-1"></i> File Upload Assignment
                </span>
                <h2 style="font-family: 'Outfit', sans-serif; font-size: 22px; font-weight: 800; color: var(--text-main); margin: 0 0 6px;">
                    <?= esc($assessment['title']) ?>
                </h2>
                <p style="color: var(--text-muted); font-size: 13.5px; margin: 0;">
                    Review the assignment description and upload your project deliverable archive.
                </p>
            </div>
            <div style="background: var(--card-bg); padding: 10px 18px; border-radius: 12px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); text-align: center;">
                <div style="font-size: 11px; color: var(--text-muted); font-weight: 600;">Max Marks</div>
                <div style="font-size: 16px; font-weight: 800; color: var(--primary);"><?= esc($assessment['max_marks']) ?> Pts</div>
            </div>
        </div>
    </div>

    <!-- Instructions Card -->
    <div class="card" style="border-radius: 16px; border: 1px solid var(--border); padding: 22px; margin-bottom: 24px; background: var(--card-bg); box-shadow: var(--shadow-sm);">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(99, 102, 241, 0.12); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 15px;">
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800; color: var(--text-main); margin: 0;">
                Deliverable Instructions & Requirements
            </h3>
        </div>
        <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 12px; padding: 16px 20px; font-size: 13.5px; color: var(--text-main); line-height: 1.65; margin-bottom: 16px;">
            <?= nl2br(esc($assessment['description'])) ?>
        </div>

        <?php if (!empty($assessment['reference_attachment'])): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(99, 102, 241, 0.05); border: 1px solid rgba(99, 102, 241, 0.2); border-radius: 12px; padding: 12px 18px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-paperclip text-primary fa-lg"></i>
                    <span style="font-size: 13px; font-weight: 700; color: var(--text-main);">Faculty Problem Statement Document</span>
                </div>
                <a href="<?= base_url($assessment['reference_attachment']) ?>" class="btn btn-sm btn-primary" download target="_blank">
                    <i class="fa-solid fa-download me-1"></i> Download Attachment
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($submission): ?>
        <!-- Submission Details Card -->
        <div class="card" style="border-radius: 16px; border: 1px solid var(--border); padding: 28px; background: var(--card-bg); box-shadow: var(--shadow-sm); margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); font-size: 12.5px; font-weight: 700; padding: 6px 12px; border-radius: 20px;">
                        <i class="fa-solid fa-circle-check me-1"></i> Deliverable Uploaded
                    </span>
                    <span style="font-size: 12.5px; color: var(--text-muted);">
                        Submitted on <?= date('M d, Y \a\t h:i A', strtotime($submission['submitted_at'])) ?>
                    </span>
                </div>

                <?php 
                    $score = $submission['final_marks'] ?? $submission['marks_obtained'] ?? $submission['marks'] ?? null;
                    if ($score !== null): 
                ?>
                    <div style="background: var(--bg-canvas); padding: 6px 16px; border-radius: 12px; border: 1px solid var(--border); font-weight: 800; font-size: 15px; color: var(--success);">
                        Score: <?= esc($score) ?> / <?= esc($assessment['max_marks']) ?>
                    </div>
                <?php else: ?>
                    <span class="badge" style="background: rgba(245, 158, 11, 0.12); color: var(--warning); font-weight: 700; padding: 6px 12px; border-radius: 20px;">
                        <i class="fa-solid fa-hourglass-half me-1"></i> Under Faculty Review
                    </span>
                <?php endif; ?>
            </div>

            <?php if (!empty($submission['submitted_file'])): ?>
                <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 12px; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fa-solid fa-file-lines fa-2x text-primary"></i>
                        <div>
                            <div style="font-size: 13.5px; font-weight: 700; color: var(--text-main);">Uploaded File Archive</div>
                            <div style="font-size: 12px; color: var(--text-muted);"><?= esc($submission['submitted_file']) ?></div>
                        </div>
                    </div>
                    <a href="<?= base_url($submission['submitted_file']) ?>" class="btn btn-sm btn-outline" target="_blank" download>
                        <i class="fa-solid fa-download me-1"></i> Download File
                    </a>
                </div>
            <?php endif; ?>

            <?php if (!empty($submission['faculty_feedback'])): ?>
                <div style="margin-top: 20px; background: rgba(99, 102, 241, 0.05); border: 1px solid rgba(99, 102, 241, 0.2); border-radius: 12px; padding: 14px 18px;">
                    <div style="font-size: 12.5px; font-weight: 700; color: var(--primary); margin-bottom: 4px;">
                        <i class="fa-solid fa-comment-dots me-1"></i> Faculty Review Remarks:
                    </div>
                    <div style="font-size: 13.5px; color: var(--text-main);"><?= esc($submission['faculty_feedback']) ?></div>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Upload Form -->
        <form action="<?= site_url('lms/assessments/submit/' . ($assessment['uuid'] ?? $assessment['id'])) ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="card" style="border-radius: 16px; border: 1px solid var(--border); padding: 24px; background: var(--card-bg); box-shadow: var(--shadow-sm); margin-bottom: 24px;">
                <div style="margin-bottom: 20px;">
                    <label style="font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 800; color: var(--text-main); margin-bottom: 6px; display: block;">
                        Select File Deliverable <span style="color: var(--danger);">*</span>
                    </label>
                    <p style="font-size: 12.5px; color: var(--text-muted); margin: 0 0 10px;">
                        Allowed file formats: <strong><?= esc($assessment['allowed_extensions'] ?? '.pdf,.zip') ?></strong> &bull; Maximum file size: <strong><?= esc($assessment['max_file_size_mb'] ?? 10) ?>MB</strong>
                    </p>
                    <input type="file" name="submitted_file" style="width: 100%; padding: 12px; border: 1.5px dashed var(--border); border-radius: 12px; background: var(--bg-canvas); font-size: 13px;" required>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="font-family: 'Outfit', sans-serif; font-size: 14px; font-weight: 700; color: var(--text-main); margin-bottom: 6px; display: block;">
                        Optional Notes for Teacher / Evaluator
                    </label>
                    <textarea name="student_comments" rows="3" placeholder="Add any explanatory remarks about your deliverable..." style="width: 100%; padding: 14px; border: 1px solid var(--border); border-radius: 10px; font-family: inherit; font-size: 13.5px; outline: none;"></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; padding-top: 14px; border-top: 1px solid var(--border-light);">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 28px; font-weight: 700; font-size: 14px; background: #10b981; border-color: #10b981;">
                        <i class="fa-solid fa-cloud-arrow-up me-2"></i> Submit Assignment Deliverable
                    </button>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

