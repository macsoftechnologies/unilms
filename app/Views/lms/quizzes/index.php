<?= $this->extend('lms/layout') ?>
<?= $this->section('page_title') ?>CBT Online Exams & Quizzes<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="lms-page-header">
    <h1><i class="fa-solid fa-laptop-code me-2" style="color: var(--primary);"></i> Computer-Based Tests (CBT) & Quizzes</h1>
    <p>Attempt cohort-assigned timed online quizzes, monitor active test sessions, and analyze score breakdowns.</p>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: var(--success); padding: 12px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
        <i class="fa-solid fa-circle-check me-2"></i> <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: var(--danger); padding: 12px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
        <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if(empty($quizzes)): ?>
    <div class="card" style="text-align: center; padding: 48px 24px; border: 1px solid var(--border); border-radius: 16px;">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(99, 102, 241, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 16px;">
            <i class="fa-solid fa-laptop-code"></i>
        </div>
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: var(--text-main); margin-bottom: 6px;">No Active Quizzes Scheduled</h3>
        <p style="color: var(--text-muted); font-size: 13.5px; margin: 0; max-width: 420px; margin: 0 auto;">There are no scheduled CBT online exams or active quizzes assigned to your cohort at this moment.</p>
    </div>
<?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
        <?php foreach($quizzes as $q): 
            $maxAttempts = isset($q['max_attempts']) ? (int)$q['max_attempts'] : 3;
            $attemptCount = count($q['attempts'] ?? []);
            $timeLimit = isset($q['time_limit_minutes']) ? (int)$q['time_limit_minutes'] : 0;
        ?>
            <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; border: 1px solid var(--border); border-radius: 16px; padding: 22px;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; gap: 10px;">
                        <div>
                            <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 8px; margin-bottom: 6px; display: inline-block;">
                                <?= esc($q['subject_code'] ?? 'CS201') ?>
                            </span>
                            <h3 style="font-family: 'Outfit', sans-serif; margin: 0; font-size: 16px; font-weight: 800; color: var(--text-main);">
                                <?= esc($q['title'] ?? 'Module Quiz') ?>
                            </h3>
                        </div>
                        <?php if(!empty($q['has_active_attempt'])): ?>
                            <span class="badge" style="background: rgba(245, 158, 11, 0.12); color: var(--warning); padding: 4px 8px; border-radius: 8px; font-size: 11px; font-weight: 700; white-space: nowrap;">
                                <i class="fa-solid fa-spinner fa-spin me-1"></i> In Progress
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <p style="font-size: 12.5px; color: var(--text-muted); margin: 0 0 14px; line-height: 1.5;">
                        <?= esc($q['description'] ?? 'Cohort evaluation test.') ?>
                    </p>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px; font-size: 12px;">
                        <div style="background: var(--bg-canvas); padding: 10px; border-radius: 10px; text-align: center; border: 1px solid var(--border);">
                            <div style="color: var(--text-muted); margin-bottom: 2px; font-size: 11px; font-weight: 600;">Time Limit</div>
                            <div style="font-weight: 800; color: var(--text-main);">
                                <i class="fa-regular fa-clock me-1 text-primary"></i> <?= $timeLimit > 0 ? $timeLimit . ' mins' : 'Unlimited' ?>
                            </div>
                        </div>
                        <div style="background: var(--bg-canvas); padding: 10px; border-radius: 10px; text-align: center; border: 1px solid var(--border);">
                            <div style="color: var(--text-muted); margin-bottom: 2px; font-size: 11px; font-weight: 600;">Attempts</div>
                            <div style="font-weight: 800; color: var(--text-main);">
                                <i class="fa-solid fa-rotate-right me-1 text-primary"></i> <?= $attemptCount ?> / <?= $maxAttempts ?>
                            </div>
                        </div>
                    </div>

                    <?php if(!empty($q['attempts'])): ?>
                        <div style="margin-bottom: 16px; font-size: 12px;">
                            <span style="color: var(--text-muted); font-weight: 600;">Past Scores:</span>
                            <div style="display: flex; gap: 6px; margin-top: 6px; flex-wrap: wrap;">
                                <?php foreach($q['attempts'] as $a): ?>
                                    <?php if(($a['status'] ?? '') !== 'in_progress'): ?>
                                        <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); font-weight: 700; padding: 3px 8px; border-radius: 8px;">
                                            <?= floatval($a['score_obtained'] ?? 0) ?> / <?= floatval($a['max_score'] ?? 0) ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div style="margin-top: auto;">
                    <?php if(!empty($q['has_active_attempt'])): ?>
                        <a href="<?= base_url('lms/quizzes/exam/' . $q['active_attempt_id']) ?>" class="btn btn-primary" style="width: 100%; justify-content: center; background: #f59e0b; border-color: #f59e0b; font-weight: 700;">
                            <i class="fa-solid fa-play me-1"></i> Resume Exam
                        </a>
                    <?php elseif(!empty($q['can_attempt'])): ?>
                        <a href="<?= base_url('lms/quizzes/start/' . $q['id']) ?>" class="btn btn-primary" style="width: 100%; justify-content: center; font-weight: 700;" onclick="return confirm('Ready to start the exam? The timer will begin immediately.');">
                            <i class="fa-solid fa-pen-nib me-1"></i> Start Exam
                        </a>
                    <?php else: ?>
                        <button class="btn btn-outline" style="width: 100%; justify-content: center; opacity: 0.6; cursor: not-allowed; font-weight: 600;" disabled>
                            <i class="fa-solid fa-ban me-1"></i> Max Attempts Reached
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
