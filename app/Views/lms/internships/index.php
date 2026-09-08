<?= $this->extend('lms/layout') ?>
<?= $this->section('page_title') ?>Industry Internships<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="lms-page-header">
    <h1><i class="fa-solid fa-briefcase me-2" style="color: var(--primary);"></i> Corporate Internships & Workspace</h1>
    <p>Browse corporate openings, manage company offer letters, and submit weekly project milestones.</p>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: var(--success); padding: 10px 16px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; font-size: 13px;">
        <i class="fa-solid fa-circle-check"></i> <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<!-- My Active Internships / Offers -->
<div class="card" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; margin: 0;">
            <i class="fa-solid fa-user-check me-2" style="color: var(--primary);"></i> My Internship Applications & Workspace
        </h2>
    </div>

    <?php if (empty($enrollments)): ?>
        <div style="text-align: center; padding: 24px; color: var(--text-muted);">
            <p>You have not applied for any internships yet. Browse available openings below.</p>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 12px 16px;">Company & Role</th>
                        <th style="padding: 12px 16px;">Work Mode</th>
                        <th style="padding: 12px 16px;">Monthly Stipend</th>
                        <th style="padding: 12px 16px; text-align: center;">Status</th>
                        <th style="padding: 12px 16px; text-align: right;">Workspace / Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrollments as $e): ?>
                        <tr>
                            <td style="padding: 14px 16px;">
                                <div style="font-weight: 800; color: var(--text-main); font-size: 14px;">
                                    <i class="fa-solid fa-building me-1 text-primary"></i> <?= esc($e['company_name']) ?>
                                </div>
                                <div style="font-size: 12.5px; color: var(--primary); font-weight: 700; margin-top: 2px;">
                                    <?= esc($e['role_title']) ?>
                                </div>
                            </td>
                            <td style="padding: 14px 16px; text-transform: capitalize;">
                                <span class="badge" style="background: rgba(99, 102, 241, 0.08); color: var(--primary); font-size: 11px; padding: 3px 8px; border-radius: 8px;">
                                    <?= esc($e['work_mode']) ?>
                                </span>
                            </td>
                            <td style="padding: 14px 16px; font-weight: 800; color: var(--success);">
                                ₹<?= number_format($e['stipend_amount'], 2) ?>/mo
                            </td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <?php if ($e['status'] === 'in_progress'): ?>
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); padding: 4px 12px; border-radius: 12px; font-size: 11.5px; font-weight: 700;">
                                        <i class="fa-solid fa-spinner fa-spin me-1"></i> In Progress (Grade: <?= esc($e['total_weighted_grade'] ?? 'A+') ?>)
                                    </span>
                                <?php elseif ($e['status'] === 'offered'): ?>
                                    <span class="badge" style="background: rgba(245, 158, 11, 0.12); color: var(--warning); padding: 4px 12px; border-radius: 12px; font-size: 11.5px; font-weight: 700;">
                                        <i class="fa-solid fa-clock me-1"></i> Offer Letter Received
                                    </span>
                                <?php elseif ($e['status'] === 'completed'): ?>
                                    <span class="badge" style="background: rgba(99, 102, 241, 0.12); color: var(--primary); padding: 4px 12px; border-radius: 12px; font-size: 11.5px; font-weight: 700;">
                                        <i class="fa-solid fa-award me-1"></i> Certified (<?= esc($e['certificate_number'] ?? 'COMPLETED') ?>)
                                    </span>
                                <?php else: ?>
                                    <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); padding: 4px 10px; border-radius: 12px; font-size: 11px;">
                                        <?= ucfirst(esc($e['status'])) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 14px 16px; text-align: right;">
                                <?php if (in_array($e['status'], ['in_progress', 'completed'])): ?>
                                    <a href="<?= base_url('lms/internships/workspace/' . ($e['uuid'] ?? $e['id'])) ?>" class="btn btn-primary" style="font-size: 12px; padding: 6px 14px; border-radius: 10px;">
                                        Open Workspace <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                <?php elseif ($e['status'] === 'offered'): ?>
                                    <form action="<?= base_url('lms/internships/respondOffer') ?>" method="POST" style="display: inline-flex; gap: 6px;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="enrollment_id" value="<?= $e['id'] ?>">
                                        <button type="submit" name="decision" value="accept" class="btn btn-sm btn-primary">Accept</button>
                                        <button type="submit" name="decision" value="decline" class="btn btn-sm btn-outline">Decline</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Available Postings -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; margin: 0;">
            <i class="fa-solid fa-briefcase me-2" style="color: var(--success);"></i> Verified Industry Openings
        </h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px;">
        <?php if (empty($openPostings)): ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 24px; color: var(--text-muted);">
                <p>No new internship postings available at this moment.</p>
            </div>
        <?php else: ?>
            <?php foreach ($openPostings as $p): ?>
                <div class="card" style="border: 1px solid var(--border); border-radius: 14px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between; background: var(--surface);">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                            <span class="badge" style="background: rgba(99, 102, 241, 0.08); color: var(--primary); font-size: 11px; padding: 3px 8px; border-radius: 8px; text-transform: capitalize;">
                                <?= esc($p['work_mode']) ?>
                            </span>
                            <span style="font-weight: 800; color: var(--success); font-size: 13.5px;">₹<?= number_format($p['stipend_amount'], 2) ?>/mo</span>
                        </div>
                        <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800; color: var(--text-main); margin-bottom: 4px;">
                            <?= esc($p['role_title']) ?>
                        </h3>
                        <div style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 10px;">
                            <strong><?= esc($p['company_name']) ?></strong> &bull; <?= esc($p['location'] ?: 'Virtual / Remote') ?>
                        </div>
                        <p style="font-size: 12.5px; color: var(--text-muted); line-height: 1.4; margin: 0 0 14px;">
                            <?= esc(substr(strip_tags($p['description']), 0, 110)) ?>...
                        </p>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid var(--border);">
                        <span style="font-size: 11.5px; color: var(--text-muted);">Deadline: <?= date('d M Y', strtotime($p['application_deadline'])) ?></span>
                        <a href="<?= base_url('lms/internships/apply/' . ($p['uuid'] ?? $p['id'])) ?>" class="btn btn-outline" style="font-size: 12px; padding: 5px 14px; border-radius: 8px;">
                            Apply Now
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
