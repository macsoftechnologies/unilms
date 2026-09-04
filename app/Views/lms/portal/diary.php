<?= $this->extend('lms/layout') ?>
<?= $this->section('page_title') ?>Campus Notices & Circulars<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="lms-page-header">
    <h1><i class="fa-solid fa-bullhorn me-2" style="color: var(--primary);"></i> Campus Notices & Academic Circulars</h1>
    <p>Official announcements, holiday schedules, examination guidelines, and administrative orders published by university leadership.</p>
</div>

<div class="card" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; margin: 0; color: var(--text-main);">
            <i class="fa-solid fa-newspaper me-2" style="color: var(--primary);"></i> Active University Circulars
        </h2>
        <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11.5px; padding: 4px 10px; border-radius: 12px; font-weight: 700;">
            <?= count($notices ?? []) ?> Published Notices
        </span>
    </div>

    <div style="display: flex; flex-direction: column; gap: 16px;">
        <?php if(!empty($notices)): foreach($notices as $n): ?>
            <div style="border: 1px solid var(--border); border-radius: 14px; padding: 20px; background: var(--surface); transition: all 0.2s ease;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 14px; margin-bottom: 10px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                            <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11px; padding: 3px 8px; border-radius: 8px; font-weight: 700;">
                                <i class="fa-solid fa-users me-1"></i> <?= esc($n['target_audience'] ?: 'All Students') ?>
                            </span>
                            <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">
                                <i class="fa-regular fa-calendar me-1"></i> <?= date('d M Y', strtotime($n['publish_date'])) ?>
                            </span>
                        </div>
                        <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800; color: var(--text-main); margin: 0;">
                            <?= esc($n['title']) ?>
                        </h3>
                    </div>
                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); font-size: 11px; padding: 3px 8px; border-radius: 8px; font-weight: 700;">
                        Active
                    </span>
                </div>
                <div style="font-size: 13.5px; color: var(--text-muted); line-height: 1.6; margin-top: 8px;">
                    <?= nl2br(esc($n['description'])) ?>
                </div>
            </div>
        <?php endforeach; else: ?>
            <!-- Fallback Mock Notices if empty -->
            <div style="border: 1px solid var(--border); border-radius: 14px; padding: 20px; background: var(--surface);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 14px; margin-bottom: 10px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                            <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11px; padding: 3px 8px; border-radius: 8px; font-weight: 700;">
                                <i class="fa-solid fa-users me-1"></i> All Students
                            </span>
                            <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">
                                <i class="fa-regular fa-calendar me-1"></i> <?= date('d M Y') ?>
                            </span>
                        </div>
                        <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800; color: var(--text-main); margin: 0;">
                            Mid-Semester Continuous Assessment Schedule Announced
                        </h3>
                    </div>
                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); font-size: 11px; padding: 3px 8px; border-radius: 8px; font-weight: 700;">
                        Official
                    </span>
                </div>
                <div style="font-size: 13.5px; color: var(--text-muted); line-height: 1.6; margin-top: 8px;">
                    All students are hereby notified that the Continuous Assessment Test 1 (CIA-1) will commence next Monday. Please review the updated syllabus breakdowns and lab project requirements on the LMS portal.
                </div>
            </div>

            <div style="border: 1px solid var(--border); border-radius: 14px; padding: 20px; background: var(--surface);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 14px; margin-bottom: 10px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success); font-size: 11px; padding: 3px 8px; border-radius: 8px; font-weight: 700;">
                                <i class="fa-solid fa-graduation-cap me-1"></i> Placement Cell
                            </span>
                            <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">
                                <i class="fa-regular fa-calendar me-1"></i> <?= date('d M Y', strtotime('-2 days')) ?>
                            </span>
                        </div>
                        <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800; color: var(--text-main); margin: 0;">
                            Registration Open for Upcoming Tech Campus Recruitment Drives
                        </h3>
                    </div>
                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); font-size: 11px; padding: 3px 8px; border-radius: 8px; font-weight: 700;">
                        Placements
                    </span>
                </div>
                <div style="font-size: 13.5px; color: var(--text-muted); line-height: 1.6; margin-top: 8px;">
                    Eligible final and pre-final year students can apply for the upcoming Google, Amazon, and Infosys campus hiring drives via the Placements Hub tab. Ensure your resume and backlog credentials are kept up to date.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
