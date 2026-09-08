<?= $this->extend('lms/layout') ?>

<?= $this->section('page_title') ?>
Technical Essay - <?= esc($assessment['title']) ?>
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
                <span class="badge" style="background: rgba(245, 158, 11, 0.12); color: var(--warning); font-size: 11.5px; font-weight: 700; margin-bottom: 6px; display: inline-block;">
                    <i class="fa-solid fa-pen-nib me-1"></i> Technical Essay Assessment
                </span>
                <h2 style="font-family: 'Outfit', sans-serif; font-size: 22px; font-weight: 800; color: var(--text-main); margin: 0 0 6px;">
                    <?= esc($assessment['title']) ?>
                </h2>
                <p style="color: var(--text-muted); font-size: 13.5px; margin: 0;">
                    Please review the essay prompt instructions and write your analytical response in the editor below.
                </p>
            </div>
            <div style="display: flex; gap: 10px;">
                <div style="background: var(--card-bg); padding: 10px 16px; border-radius: 12px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); text-align: center;">
                    <div style="font-size: 11px; color: var(--text-muted); font-weight: 600;">Max Marks</div>
                    <div style="font-size: 16px; font-weight: 800; color: var(--primary);"><?= esc($assessment['max_marks']) ?> Pts</div>
                </div>
                <div style="background: var(--card-bg); padding: 10px 16px; border-radius: 12px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); text-align: center;">
                    <div style="font-size: 11px; color: var(--text-muted); font-weight: 600;">Word Limit</div>
                    <div style="font-size: 16px; font-weight: 800; color: var(--text-main);"><?= (int)($assessment['min_word_count'] ?? 100) ?> - <?= (int)($assessment['max_word_count'] ?? 1000) ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Prompt Box Card -->
    <div class="card" style="border-radius: 16px; border: 1px solid var(--border); padding: 22px; margin-bottom: 24px; background: var(--card-bg); box-shadow: var(--shadow-sm);">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(245, 158, 11, 0.12); color: var(--warning); display: flex; align-items: center; justify-content: center; font-size: 15px;">
                <i class="fa-solid fa-feather-pointed"></i>
            </div>
            <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800; color: var(--text-main); margin: 0;">
                Essay Topic & Problem Statement
            </h3>
        </div>
        <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 12px; padding: 16px 20px; font-size: 13.5px; color: var(--text-main); line-height: 1.65;">
            <?= nl2br(esc($assessment['description'])) ?>
        </div>
    </div>

    <?php if ($submission): ?>
        <!-- Submitted Essay Card -->
        <div class="card" style="border-radius: 16px; border: 1px solid var(--border); padding: 28px; background: var(--card-bg); box-shadow: var(--shadow-sm); margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); font-size: 12.5px; font-weight: 700; padding: 6px 12px; border-radius: 20px;">
                        <i class="fa-solid fa-circle-check me-1"></i> Essay Submitted
                    </span>
                    <span style="font-size: 12.5px; color: var(--text-muted);">
                        Submitted on <?= date('M d, Y \a\t h:i A', strtotime($submission['submitted_at'])) ?> &bull; <strong><?= esc($submission['essay_word_count'] ?? 0) ?> words</strong>
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
                        <i class="fa-solid fa-hourglass-half me-1"></i> Pending Faculty Grading
                    </span>
                <?php endif; ?>
            </div>

            <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 12px; padding: 20px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; line-height: 1.7; color: var(--text-main); white-space: pre-wrap; max-height: 400px; overflow-y: auto;">
                <?= esc($submission['submitted_essay']) ?>
            </div>

            <?php if (!empty($submission['faculty_feedback'])): ?>
                <div style="margin-top: 20px; background: rgba(99, 102, 241, 0.05); border: 1px solid rgba(99, 102, 241, 0.2); border-radius: 12px; padding: 14px 18px;">
                    <div style="font-size: 12.5px; font-weight: 700; color: var(--primary); margin-bottom: 4px;">
                        <i class="fa-solid fa-comment-dots me-1"></i> Faculty Evaluation Feedback:
                    </div>
                    <div style="font-size: 13.5px; color: var(--text-main);"><?= esc($submission['faculty_feedback']) ?></div>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Essay Composition Form -->
        <form action="<?= site_url('lms/assessments/submit/' . ($assessment['uuid'] ?? $assessment['id'])) ?>" method="POST" id="essayForm">
            <?= csrf_field() ?>
            
            <div class="card" style="border-radius: 16px; border: 1px solid var(--border); padding: 24px; background: var(--card-bg); box-shadow: var(--shadow-sm); margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                    <label style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800; color: var(--text-main); margin: 0;">
                        Your Essay Response
                    </label>
                    <div id="wordCountBadge" style="display: inline-flex; align-items: center; gap: 8px; font-size: 12.5px; font-weight: 700; padding: 6px 14px; border-radius: 20px; background: var(--bg-canvas); border: 1px solid var(--border); color: var(--text-muted); transition: all 0.2s ease;">
                        <i class="fa-solid fa-chart-simple"></i>
                        <span>Words: <strong id="wordCountNum" style="color: var(--text-main);">0</strong> (Min <?= (int)($assessment['min_word_count'] ?? 0) ?> &bull; Max <?= (int)($assessment['max_word_count'] ?? 1000) ?>)</span>
                    </div>
                </div>

                <div style="position: relative; margin-bottom: 20px;">
                    <textarea 
                        name="submitted_essay" 
                        id="essayEditor" 
                        rows="14" 
                        placeholder="Begin composing your structured essay response here..." 
                        style="width: 100%; padding: 18px; border: 1.5px solid var(--border); border-radius: 12px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; line-height: 1.65; color: var(--text-main); background: #fff; outline: none; transition: border-color 0.2s ease; resize: vertical;" 
                        required
                    ></textarea>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; padding-top: 14px; border-top: 1px solid var(--border-light);">
                    <div style="font-size: 12.5px; color: var(--text-muted);">
                        <i class="fa-solid fa-circle-info text-primary me-1"></i> Make sure to address key architectural differences, trade-offs, and examples.
                    </div>
                    <button type="submit" class="btn btn-primary" id="btnSubmitEssay" style="padding: 10px 28px; font-weight: 700; font-size: 14px; background: #10b981; border-color: #10b981;">
                        <i class="fa-solid fa-paper-plane me-2"></i> Submit Final Essay
                    </button>
                </div>
            </div>
        </form>

        <style>
        #essayEditor:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        </style>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editor = document.getElementById('essayEditor');
            const countNum = document.getElementById('wordCountNum');
            const badge = document.getElementById('wordCountBadge');
            const minWords = <?= (int)($assessment['min_word_count'] ?? 0) ?>;
            const maxWords = <?= (int)($assessment['max_word_count'] ?? 1000) ?>;

            if (editor) {
                editor.addEventListener('input', function () {
                    const text = this.value.trim();
                    const words = text ? text.split(/\s+/).filter(w => w.length > 0).length : 0;
                    countNum.textContent = words;

                    if (minWords > 0 && words < minWords) {
                        badge.style.backgroundColor = 'rgba(245, 158, 11, 0.1)';
                        badge.style.color = '#d97706';
                        badge.style.borderColor = 'rgba(245, 158, 11, 0.3)';
                    } else if (maxWords > 0 && words > maxWords) {
                        badge.style.backgroundColor = 'rgba(239, 68, 68, 0.1)';
                        badge.style.color = '#dc2626';
                        badge.style.borderColor = 'rgba(239, 68, 68, 0.3)';
                    } else {
                        badge.style.backgroundColor = 'rgba(16, 185, 129, 0.1)';
                        badge.style.color = '#059669';
                        badge.style.borderColor = 'rgba(16, 185, 129, 0.3)';
                    }
                });
            }
        });
        </script>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

