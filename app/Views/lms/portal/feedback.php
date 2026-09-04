<?= $this->extend('lms/layout') ?>
<?= $this->section('page_title') ?>Course & Faculty Feedback<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="lms-page-header">
    <h1><i class="fa-solid fa-comment-dots me-2" style="color: var(--primary);"></i> 100% Confidential Course & Faculty Feedback</h1>
    <p>Your honest ratings and constructive suggestions directly enhance classroom pedagogy, lab facilities, and learning outcomes.</p>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: var(--success); padding: 12px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
        <i class="fa-solid fa-circle-check me-2"></i> <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 24px;">
    
    <!-- Feedback Submission Card -->
    <div class="card" style="padding: 24px; border: 1px solid var(--border); border-radius: 16px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px;">
            <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(99, 102, 241, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-user-secret"></i>
            </div>
            <div>
                <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0; color: var(--text-main);">Submit Anonymous Review</h3>
                <span style="font-size: 12px; color: var(--text-muted);">Encrypted & Untraceable Evaluation</span>
            </div>
        </div>

        <form action="<?= base_url('lms/feedback/submit') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-size: 12.5px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-main);">Enrolled Subject / Course *</label>
                <select name="subject_id" class="form-control" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; background: var(--surface);">
                    <option value="" disabled selected>Select Subject...</option>
                    <?php if(!empty($subjects)): foreach($subjects as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?> (<?= esc($s['code']) ?>)</option>
                    <?php endforeach; else: ?>
                        <option value="1">Data Structures & Algorithms (CS201)</option>
                        <option value="2">Database Management Systems (CS202)</option>
                        <option value="3">Object Oriented Programming (CS203)</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-size: 12.5px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-main);">Course Instructor / Faculty *</label>
                <select name="faculty_id" class="form-control" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; background: var(--surface);">
                    <option value="" disabled selected>Select Faculty Member...</option>
                    <?php if(!empty($faculty)): foreach($faculty as $f): ?>
                        <option value="<?= $f['id'] ?>"><?= esc($f['first_name']) ?> <?= esc($f['last_name']) ?> (<?= esc($f['designation'] ?? 'Professor') ?>)</option>
                    <?php endforeach; else: ?>
                        <option value="1">Dr. Rajesh Sharma (Professor)</option>
                        <option value="2">Prof. Priya Sundaram (Associate Professor)</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-size: 12.5px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-main);">Overall Teaching & Course Rating *</label>
                <select name="rating" class="form-control" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; background: var(--surface);">
                    <option value="5">★★★★★ (5 - Excellent & Highly Effective)</option>
                    <option value="4" selected>★★★★☆ (4 - Good & Clear Concept Delivery)</option>
                    <option value="3">★★★☆☆ (3 - Satisfactory / Average Pace)</option>
                    <option value="2">★★☆☆☆ (2 - Needs Improvement in Lab/Clarity)</option>
                    <option value="1">★☆☆☆☆ (1 - Unsatisfactory)</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-size: 12.5px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-main);">Constructive Feedback & Suggestions (Optional)</label>
                <textarea name="comments" class="form-control" placeholder="Mention any specific suggestions regarding syllabus pacing, practical lab hours, or resource clarity..." style="width: 100%; height: 90px; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; background: var(--surface); resize: vertical;"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; font-weight: 700; padding: 11px;">
                <i class="fa-solid fa-paper-plane me-1"></i> Submit Feedback
            </button>
        </form>
    </div>

    <!-- Feedback Submission History -->
    <div class="card" style="padding: 24px; border: 1px solid var(--border); border-radius: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
            <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0; color: var(--text-main);">
                <i class="fa-solid fa-clock-rotate-left me-2" style="color: var(--primary);"></i> My Submission Logs
            </h3>
            <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11px; padding: 3px 8px; border-radius: 10px; font-weight: 700;">
                <?= count($feedbacks ?? []) ?> Recorded
            </span>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 10px 14px;">Date Submitted</th>
                        <th style="padding: 10px 14px; text-align: center;">Rating</th>
                        <th style="padding: 10px 14px; text-align: center;">Privacy Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($feedbacks)): foreach($feedbacks as $f): ?>
                        <tr>
                            <td style="padding: 12px 14px; font-size: 13px;">
                                <i class="fa-regular fa-calendar-check me-1 text-muted"></i>
                                <?= date('d M Y, h:i A', strtotime($f['created_at'] ?? $f['submitted_at'] ?? date('Y-m-d H:i:s'))) ?>
                            </td>
                            <td style="padding: 12px 14px; text-align: center;">
                                <span style="font-weight: 800; color: #f59e0b; font-size: 13.5px;">
                                    <?= str_repeat('★', (int)$f['rating']) ?>
                                </span>
                                <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">(<?= esc($f['rating']) ?>/5)</span>
                            </td>
                            <td style="padding: 12px 14px; text-align: center;">
                                <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); padding: 3px 8px; border-radius: 10px; font-size: 11px; font-weight: 700;">
                                    <i class="fa-solid fa-shield-check me-1"></i> Anonymous
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 28px; color: var(--text-muted); font-size: 13px;">
                                <i class="fa-solid fa-comments" style="font-size: 28px; opacity: 0.3; margin-bottom: 8px; display: block;"></i>
                                No feedback submitted for the current semester cycle yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
