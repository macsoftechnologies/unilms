<?= $this->extend('lms/layout') ?>

<?= $this->section('page_title') ?>
Assessments & Tests
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="lms-page-header">
    <h1><i class="fa-solid fa-clipboard-check me-2" style="color: var(--primary);"></i> My Assessments & Tests</h1>
    <p>Browse and complete your active course assessments, quizzes, video checkpoint tests, and essay tasks.</p>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: var(--success); padding: 12px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
        <i class="fa-solid fa-circle-check me-2"></i> <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: var(--danger); padding: 12px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
        <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (empty($assessments)): ?>
    <div class="card" style="text-align: center; padding: 48px 24px; border: 1px solid var(--border); border-radius: 16px;">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(99, 102, 241, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 16px;">
            <i class="fa-solid fa-clipboard-check"></i>
        </div>
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: var(--text-main); margin-bottom: 6px;">No Active Assessments Scheduled</h3>
        <p style="color: var(--text-muted); font-size: 13.5px; margin: 0; max-width: 420px; margin: 0 auto;">All assignments and tests for your current cohort are up-to-date.</p>
    </div>
<?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
        <?php foreach ($assessments as $a): ?>
            <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; border: 1px solid var(--border); border-radius: 16px; padding: 22px;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; gap: 8px;">
                        <span class="badge" style="background: var(--bg-canvas); color: var(--text-muted); border: 1px solid var(--border); font-size: 11px; font-weight: 700;">
                            <?= esc($a['subject_name'] ?? 'Subject') ?>
                        </span>
                        <?php if ($a['assessment_type'] === 'cbt_quiz'): ?>
                            <span class="badge" style="background: rgba(6, 182, 212, 0.12); color: var(--info); font-size: 11px; font-weight: 700;"><i class="fa-solid fa-list-check me-1"></i> CBT Quiz</span>
                        <?php elseif ($a['assessment_type'] === 'interactive_video'): ?>
                            <span class="badge" style="background: rgba(239, 68, 68, 0.12); color: var(--danger); font-size: 11px; font-weight: 700;"><i class="fa-solid fa-circle-play me-1"></i> Video Test</span>
                        <?php elseif ($a['assessment_type'] === 'essay'): ?>
                            <span class="badge" style="background: rgba(245, 158, 11, 0.12); color: var(--warning); font-size: 11px; font-weight: 700;"><i class="fa-solid fa-pen-nib me-1"></i> Essay</span>
                        <?php else: ?>
                            <span class="badge" style="background: rgba(99, 102, 241, 0.12); color: var(--primary); font-size: 11px; font-weight: 700;"><i class="fa-solid fa-cloud-arrow-up me-1"></i> File Upload</span>
                        <?php endif; ?>
                    </div>

                    <h3 style="font-family: 'Outfit', sans-serif; margin: 0 0 6px; font-size: 16px; font-weight: 800; color: var(--text-main);">
                        <?= esc($a['title']) ?>
                    </h3>
                    <p style="color: var(--text-muted); font-size: 12.5px; line-height: 1.5; margin: 0 0 14px;">
                        <?= esc(substr(strip_tags($a['description']), 0, 110)) ?>...
                    </p>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px; font-size: 12px;">
                        <div style="background: var(--bg-canvas); padding: 10px; border-radius: 10px; text-align: center; border: 1px solid var(--border);">
                            <div style="color: var(--text-muted); margin-bottom: 2px; font-size: 11px; font-weight: 600;">Max Marks</div>
                            <div style="font-weight: 800; color: var(--primary);">
                                <i class="fa-solid fa-award me-1"></i> <?= esc($a['max_marks']) ?> Pts
                            </div>
                        </div>
                        <div style="background: var(--bg-canvas); padding: 10px; border-radius: 10px; text-align: center; border: 1px solid var(--border);">
                            <div style="color: var(--text-muted); margin-bottom: 2px; font-size: 11px; font-weight: 600;">Due Date</div>
                            <div style="font-weight: 800; color: var(--text-main);">
                                <i class="fa-regular fa-clock me-1 text-danger"></i> <?= !empty($a['due_date']) ? date('M d', strtotime($a['due_date'])) : 'No Limit' ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 10px; border-top: 1px solid var(--border-light);">
                    <?php if (!empty($a['submission'])): ?>
                        <div>
                            <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); font-weight: 700;"><i class="fa-solid fa-check me-1"></i> Submitted</span>
                            <?php 
                                $score = $a['submission']['final_marks'] ?? $a['submission']['marks_obtained'] ?? $a['submission']['marks'] ?? null;
                                if ($score !== null): 
                            ?>
                                <span style="font-weight: 800; color: var(--primary); margin-left: 4px; font-size: 13px;"><?= esc($score) ?>/<?= esc($a['max_marks'] ?? 100) ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="<?= site_url('lms/assessments/view/' . $a['id']) ?>" class="btn btn-sm btn-outline" style="font-weight: 700;">
                            View Result
                        </a>
                    <?php else: ?>
                        <span class="badge" style="background: var(--bg-canvas); color: var(--text-muted); font-weight: 600;">Not Attempted</span>
                        <a href="<?= site_url('lms/assessments/view/' . $a['id']) ?>" class="btn btn-sm btn-primary" style="font-weight: 700;">
                            Start Assessment <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>

