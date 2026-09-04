<?= $this->extend('lms/layout') ?>

<?= $this->section('page_title') ?>
CBT Quiz - <?= esc($assessment['title']) ?>
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
                <span class="badge" style="background: rgba(6, 182, 212, 0.12); color: var(--info); font-size: 11.5px; font-weight: 700; margin-bottom: 6px; display: inline-block;">
                    <i class="fa-solid fa-list-check me-1"></i> CBT Objective Assessment
                </span>
                <h2 style="font-family: 'Outfit', sans-serif; font-size: 22px; font-weight: 800; color: var(--text-main); margin: 0 0 6px;">
                    <?= esc($assessment['title']) ?>
                </h2>
                <p style="color: var(--text-muted); font-size: 13.5px; margin: 0;">
                    Select the correct answer for each multiple-choice question.
                </p>
            </div>
            <div style="display: flex; gap: 10px;">
                <div style="background: var(--card-bg); padding: 10px 16px; border-radius: 12px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); text-align: center;">
                    <div style="font-size: 11px; color: var(--text-muted); font-weight: 600;">Max Marks</div>
                    <div style="font-size: 16px; font-weight: 800; color: var(--primary);"><?= esc($assessment['max_marks']) ?> Pts</div>
                </div>
                <?php if (!$submission && !empty($assessment['time_limit_mins']) && $assessment['time_limit_mins'] > 0): ?>
                    <div style="background: rgba(239, 68, 68, 0.08); padding: 10px 16px; border-radius: 12px; border: 1px solid rgba(239, 68, 68, 0.3); text-align: center;">
                        <div style="font-size: 11px; color: var(--danger); font-weight: 700;">Time Remaining</div>
                        <div style="font-size: 16px; font-weight: 800; color: var(--danger);" id="timerDisplay">00:00</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($submission): ?>
        <div class="card" style="text-align: center; padding: 40px 24px; border-radius: 20px; border: 1px solid var(--border); margin-bottom: 24px;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: rgba(16, 185, 129, 0.12); color: var(--success); display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 16px;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h3 style="font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 800; color: var(--text-main); margin-bottom: 8px;">
                Quiz Completed!
            </h3>
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px;">
                Submitted on <?= date('M d, Y \a\t h:i A', strtotime($submission['submitted_at'])) ?>
            </p>
            <div style="display: inline-flex; align-items: center; gap: 12px; background: var(--bg-canvas); padding: 12px 24px; border-radius: 12px; border: 1px solid var(--border); margin-bottom: 24px;">
                <span style="color: var(--text-muted); font-size: 13px;">Your Total Score:</span>
                <span style="font-size: 20px; font-weight: 800; color: var(--primary);">
                    <?= esc($submission['final_marks'] ?? $submission['auto_score'] ?? 0) ?> / <?= esc($assessment['max_marks']) ?>
                </span>
            </div>
            <div>
                <a href="<?= site_url('lms/assessments') ?>" class="btn btn-primary" style="padding: 10px 24px;">
                    <i class="fa-solid fa-arrow-left me-1"></i> Return to Assessments
                </a>
            </div>
        </div>
    <?php else: ?>
        <form action="<?= site_url('lms/assessments/submit/' . $assessment['id']) ?>" method="POST" id="quizForm">
            <?= csrf_field() ?>
            
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <?php foreach ($questions as $qIdx => $q): ?>
                    <div class="card" style="border-radius: 16px; border: 1px solid var(--border); padding: 24px; background: var(--card-bg); box-shadow: var(--shadow-sm);" id="qcard_<?= $qIdx ?>">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid var(--border-light);">
                            <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 8px;">
                                Question <?= $qIdx + 1 ?> of <?= count($questions) ?>
                            </span>
                            <span style="font-size: 12px; font-weight: 700; color: var(--text-muted);">
                                <?= esc($q['points']) ?> Marks
                            </span>
                        </div>
                        
                        <div style="font-size: 15px; font-weight: 700; color: var(--text-main); margin-bottom: 18px; line-height: 1.5;">
                            <?= esc($q['question_text']) ?>
                        </div>

                        <?php $opts = json_decode($q['options'], true) ?: []; ?>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <?php foreach ($opts as $optIdx => $opt): ?>
                                <label class="cbt-opt-label">
                                    <input type="radio" name="answers[<?= $q['id'] ?>]" value="<?= esc($opt['key']) ?>" class="q-radio" data-qindex="<?= $qIdx ?>" required>
                                    <strong style="color: var(--primary); font-size: 13px;"><?= esc($opt['key']) ?>.</strong>
                                    <span><?= esc($opt['text']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Submit Panel -->
            <div style="margin-top: 24px; display: flex; justify-content: space-between; align-items: center; background: var(--card-bg); padding: 18px 24px; border-radius: 16px; border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
                <div style="font-size: 13px; color: var(--text-muted);">
                    <i class="fa-solid fa-circle-check text-success me-1"></i> Ensure all <?= count($questions) ?> questions are selected before submission.
                </div>
                <button type="submit" class="btn btn-primary" style="background: #10b981; border-color: #10b981; padding: 10px 28px; font-weight: 700; font-size: 14px;" onclick="return confirm('Are you sure you want to finish and submit the test?');">
                    <i class="fa-solid fa-paper-plane me-2"></i> Submit Quiz
                </button>
            </div>
        </form>

        <style>
        .cbt-opt-label {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: var(--bg-canvas);
            cursor: pointer;
            transition: all 0.15s ease;
            font-size: 13.5px;
            color: var(--text-main);
        }
        .cbt-opt-label:hover {
            border-color: var(--primary);
            background: #fff;
        }
        .cbt-opt-label input[type="radio"] {
            accent-color: var(--primary);
            width: 16px;
            height: 16px;
            margin: 0;
        }
        </style>

        <?php if (!empty($assessment['time_limit_mins']) && $assessment['time_limit_mins'] > 0): ?>
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                let totalSeconds = <?= (int)$assessment['time_limit_mins'] * 60 ?>;
                const timerEl = document.getElementById('timerDisplay');
                const quizForm = document.getElementById('quizForm');

                const interval = setInterval(function () {
                    if (totalSeconds <= 0) {
                        clearInterval(interval);
                        alert('Time has expired! Submitting your quiz now.');
                        quizForm.submit();
                        return;
                    }
                    totalSeconds--;
                    const mins = Math.floor(totalSeconds / 60);
                    const secs = totalSeconds % 60;
                    if (timerEl) {
                        timerEl.textContent = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
                    }
                }, 1000);
            });
            </script>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

