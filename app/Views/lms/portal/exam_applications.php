<?= $this->extend('lms/layout') ?>
<?= $this->section('page_title') ?>Examination Applications & Enrollment<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="lms-page-header">
    <h1><i class="fa-solid fa-file-signature me-2" style="color: var(--success);"></i> Semester Examination Applications</h1>
    <p>Enroll for regular end-semester examination cycles, supplementary test series, and generate digital hall ticket admit cards.</p>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: var(--success); padding: 12px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
        <i class="fa-solid fa-circle-check me-2"></i> <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 24px;">
    
    <!-- Available Upcoming Exams Card -->
    <div class="card" style="padding: 24px; border: 1px solid var(--border); border-radius: 16px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px;">
            <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(16, 185, 129, 0.1); color: var(--success); display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-clipboard-check"></i>
            </div>
            <div>
                <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0; color: var(--text-main);">Available Examination Cycles</h3>
                <span style="font-size: 12px; color: var(--text-muted);">Open for candidate registration</span>
            </div>
        </div>

        <?php if(!empty($available_exams)): ?>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <?php foreach($available_exams as $exam): ?>
                    <div style="border: 1px solid var(--border); border-radius: 12px; padding: 16px; display: flex; justify-content: space-between; align-items: center; background: var(--bg-canvas);">
                        <div>
                            <strong style="color: var(--text-main); font-size: 14px; display: block;"><?= esc($exam['name']) ?></strong>
                            <span style="font-size: 12px; color: var(--text-muted);"><i class="fa-solid fa-tag me-1"></i> <?= esc($exam['type'] ?? 'Regular Theory') ?> Examination</span>
                        </div>
                        <form action="<?= base_url('lms/exam-applications/submit') ?>" method="POST">
                            <?= csrf_field() ?>
                            <input type="hidden" name="exam_id" value="<?= $exam['id'] ?>">
                            <button type="submit" class="btn btn-primary" style="padding: 7px 16px; font-size: 12.5px; font-weight: 700; background: var(--success); border-color: var(--success);">
                                <i class="fa-solid fa-pen-nib me-1"></i> Enroll Now
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 32px 16px; color: var(--text-muted);">
                <i class="fa-solid fa-calendar-check" style="font-size: 32px; opacity: 0.3; margin-bottom: 10px; display: block;"></i>
                <p style="margin: 0; font-size: 13.5px;">No new unapplied examination cycles currently open.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- My Applications Status Card -->
    <div class="card" style="padding: 24px; border: 1px solid var(--border); border-radius: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
            <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0; color: var(--text-main);">
                <i class="fa-solid fa-id-card me-2" style="color: var(--primary);"></i> Enrolled Exam Registrations
            </h3>
            <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11px; padding: 3px 8px; border-radius: 10px; font-weight: 700;">
                <?= count($my_applications ?? []) ?> Applications
            </span>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 10px 14px;">Exam Name</th>
                        <th style="padding: 10px 14px; text-align: center;">Hall Ticket</th>
                        <th style="padding: 10px 14px; text-align: center;">Fee Status</th>
                        <th style="padding: 10px 14px; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($my_applications)): foreach($my_applications as $app): ?>
                        <tr>
                            <td style="padding: 12px 14px;">
                                <strong style="color: var(--text-main); font-size: 13px;"><?= esc($app['exam_name']) ?></strong>
                            </td>
                            <td style="padding: 12px 14px; text-align: center;">
                                <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); padding: 3px 8px; border-radius: 8px; font-weight: 700; font-size: 11px;">
                                    <i class="fa-solid fa-qrcode me-1"></i> Generated
                                </span>
                            </td>
                            <td style="padding: 12px 14px; text-align: center;">
                                <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); padding: 3px 8px; border-radius: 8px; font-weight: 700; font-size: 11px;">
                                    <i class="fa-solid fa-check me-1"></i> Paid
                                </span>
                            </td>
                            <td style="padding: 12px 14px; text-align: center;">
                                <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); padding: 4px 10px; border-radius: 12px; font-weight: 800; font-size: 11px;">
                                    <i class="fa-solid fa-circle-check me-1"></i> Approved
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 28px; color: var(--text-muted); font-size: 13px;">
                                <i class="fa-solid fa-file-signature" style="font-size: 28px; opacity: 0.3; margin-bottom: 8px; display: block;"></i>
                                You have not registered for upcoming semester exams yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
