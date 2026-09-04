<?= $this->extend('lms/layout') ?>
<?= $this->section('page_title') ?><?= esc($assignment['title']) ?><?= $this->endSection() ?>
<?= $this->section('content') ?>

<div style="margin-bottom: 32px;">
    <a href="<?= base_url('lms/assignments') ?>" style="color: var(--primary); text-decoration: none; font-size: 14px; margin-bottom: 8px; display: inline-block;"><i class="fa-solid fa-arrow-left"></i> Back to Assignments</a>
    <h1 style="margin: 0 0 8px; font-size: 28px;"><?= esc($assignment['title']) ?></h1>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    
    <!-- Left Col: Details -->
    <div>
        <div class="card" style="margin-bottom: 24px;">
            <h2 style="font-size: 18px; margin: 0 0 16px;">Instructions</h2>
            <div style="color: var(--text-muted); line-height: 1.6; font-size: 15px;">
                <?= nl2br(esc($assignment['description'])) ?>
            </div>
            
            <?php if($assignment['file_path']): ?>
                <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                    <div style="font-size: 14px; font-weight: 600; margin-bottom: 12px;">Reference File</div>
                    <a href="<?= base_url($assignment['file_path']) ?>" target="_blank" class="btn btn-outline"><i class="fa-solid fa-download"></i> Download Attachment</a>
                </div>
            <?php endif; ?>
        </div>

        <?php if($submission && $submission['status'] === 'graded'): ?>
            <div class="card" style="border-color: var(--success);">
                <h2 style="font-size: 18px; margin: 0 0 16px; color: var(--success);"><i class="fa-solid fa-check-circle"></i> Faculty Feedback</h2>
                <div style="display: flex; gap: 24px;">
                    <div>
                        <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Score</div>
                        <div style="font-size: 24px; font-weight: 700; color: var(--text-primary);">
                            <?= esc($submission['marks_obtained']) ?> <span style="font-size: 16px; color: var(--text-muted); font-weight: 400;">/ <?= $assignment['max_marks'] ?></span>
                        </div>
                    </div>
                    <?php if($submission['feedback']): ?>
                        <div style="flex-grow: 1;">
                            <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Remarks</div>
                            <div style="background: rgba(0,0,0,0.02); padding: 12px; border-radius: 8px; font-size: 14px;">
                                <?= nl2br(esc($submission['feedback'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Right Col: Submission Box -->
    <div>
        <div class="card">
            <h2 style="font-size: 18px; margin: 0 0 16px;">Your Work</h2>
            
            <div style="margin-bottom: 24px; display: flex; justify-content: space-between; font-size: 14px;">
                <span style="color: var(--text-muted);">Due Date:</span>
                <span style="font-weight: 600;"><?= date('d/m/Y, h:i A', strtotime($assignment['due_date'])) ?></span>
            </div>

            <?php if($submission): ?>
                <div style="padding: 16px; background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 8px; margin-bottom: 20px;">
                    <div style="color: var(--success); font-weight: 600; margin-bottom: 4px;"><i class="fa-solid fa-check"></i> Submitted</div>
                    <div style="font-size: 13px; color: var(--text-muted);">on <?= date('d/m/Y, h:i A', strtotime($submission['submitted_at'])) ?></div>
                </div>

                <?php if($submission['file_path']): ?>
                    <a href="<?= base_url($submission['file_path']) ?>" target="_blank" class="btn btn-outline" style="width: 100%; margin-bottom: 12px;"><i class="fa-solid fa-file"></i> View Submitted File</a>
                <?php endif; ?>
                
                <?php if($submission['submission_text']): ?>
                    <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 8px; font-weight: 600;">Text Submission:</div>
                    <div style="background: rgba(0,0,0,0.02); padding: 12px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; max-height: 150px; overflow-y: auto;">
                        <?= nl2br(esc($submission['submission_text'])) ?>
                    </div>
                <?php endif; ?>
                
                <?php if($submission['status'] !== 'graded'): ?>
                    <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 20px 0;">
                    <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 12px;">You can resubmit before grading to overwrite your work.</p>
                <?php endif; ?>
            <?php endif; ?>

            <?php if(!$submission || $submission['status'] !== 'graded'): ?>
                <form action="<?= base_url('lms/assignments/submit') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="assignment_id" value="<?= $assignment['id'] ?>">
                    
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="font-size: 13px; display: block; margin-bottom: 6px;">Upload File</label>
                        <input type="file" name="submission_file" class="form-control" style="padding: 8px; font-size: 13px;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="font-size: 13px; display: block; margin-bottom: 6px;">Or type your answer</label>
                        <textarea name="submission_text" class="form-control" rows="4" style="font-size: 13px;" placeholder="Type here..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fa-solid fa-paper-plane"></i> <?= $submission ? 'Resubmit Assignment' : 'Hand In Assignment' ?></button>
                </form>
            <?php endif; ?>
            
        </div>
    </div>
</div>

<?= $this->endSection() ?>
