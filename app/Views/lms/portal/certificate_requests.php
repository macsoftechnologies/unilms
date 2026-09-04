<?= $this->extend('lms/layout') ?>
<?= $this->section('page_title') ?>Official Certificate Requests<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="lms-page-header">
    <h1><i class="fa-solid fa-certificate me-2" style="color: var(--primary);"></i> Official Academic Certificate Desk</h1>
    <p>Request digitally signed certificates directly from the Academic Registrar for visas, scholarships, internships, or transfer procedures.</p>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: var(--success); padding: 12px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
        <i class="fa-solid fa-circle-check me-2"></i> <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 1fr 1.4fr; gap: 24px;">
    
    <!-- New Request Card -->
    <div class="card" style="padding: 24px; border: 1px solid var(--border); border-radius: 16px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px;">
            <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(99, 102, 241, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-file-signature"></i>
            </div>
            <div>
                <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0; color: var(--text-main);">Apply for Certificate</h3>
                <span style="font-size: 12px; color: var(--text-muted);">Standard Processing: 2-3 Working Days</span>
            </div>
        </div>

        <form action="<?= base_url('lms/certificates/submit') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-size: 12.5px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-main);">Certificate Type *</label>
                <select name="certificate_type" class="form-control" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; background: var(--surface);">
                    <option value="Bonafide Certificate">Bonafide Certificate (For Passport / Bank / Visa)</option>
                    <option value="Course Completion Certificate">Course Completion / Study Certificate</option>
                    <option value="Conduct & Character Certificate">Conduct & Character Certificate</option>
                    <option value="No Objection Certificate">No Objection Certificate (NOC for External Internship)</option>
                    <option value="Medium of Instruction Certificate">Medium of Instruction (English)</option>
                    <option value="Transfer Certificate">Transfer Certificate (TC / Migration)</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-size: 12.5px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-main);">Purpose / Justification *</label>
                <textarea name="reason" class="form-control" required placeholder="Specify purpose (e.g. Passport application, Education Loan verification, Internship onboarding)..." style="width: 100%; height: 90px; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; background: var(--surface); resize: vertical;"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; font-weight: 700; padding: 11px;">
                <i class="fa-solid fa-paper-plane me-1"></i> Submit Certificate Request
            </button>
        </form>
    </div>

    <!-- Requests History Table -->
    <div class="card" style="padding: 24px; border: 1px solid var(--border); border-radius: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
            <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0; color: var(--text-main);">
                <i class="fa-solid fa-clock-rotate-left me-2" style="color: var(--primary);"></i> My Certificate Records
            </h3>
            <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11px; padding: 3px 8px; border-radius: 10px; font-weight: 700;">
                <?= count($requests ?? []) ?> Applications
            </span>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 10px 14px;">Certificate</th>
                        <th style="padding: 10px 14px;">Date</th>
                        <th style="padding: 10px 14px; text-align: center;">Fee Status</th>
                        <th style="padding: 10px 14px; text-align: center;">Registrar Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($requests)): foreach($requests as $req): ?>
                        <tr>
                            <td style="padding: 12px 14px;">
                                <strong style="color: var(--text-main); font-size: 13px;"><?= esc($req['certificate_type']) ?></strong>
                            </td>
                            <td style="padding: 12px 14px; font-size: 12px; color: var(--text-muted);"><?= date('d M Y', strtotime($req['request_date'] ?? $req['created_at'] ?? date('Y-m-d'))) ?></td>
                            <td style="padding: 12px 14px; text-align: center;">
                                <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); padding: 3px 8px; border-radius: 10px; font-size: 11px; font-weight: 700;">
                                    <?= esc($req['payment_status'] ?: 'Free') ?>
                                </span>
                            </td>
                            <td style="padding: 12px 14px; text-align: center;">
                                <?php if(($req['status'] ?? '') == 'Issued' || ($req['status'] ?? '') == 'Approved'): ?>
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); padding: 4px 10px; border-radius: 12px; font-weight: 800; font-size: 11px;">
                                        <i class="fa-solid fa-circle-check me-1"></i> Issued
                                    </span>
                                <?php elseif(($req['status'] ?? '') == 'Rejected'): ?>
                                    <span class="badge" style="background: rgba(239, 68, 68, 0.12); color: var(--danger); padding: 4px 10px; border-radius: 12px; font-weight: 800; font-size: 11px;">
                                        <i class="fa-solid fa-circle-xmark me-1"></i> Rejected
                                    </span>
                                <?php else: ?>
                                    <span class="badge" style="background: rgba(245, 158, 11, 0.12); color: var(--warning); padding: 4px 10px; border-radius: 12px; font-weight: 800; font-size: 11px;">
                                        <i class="fa-solid fa-clock me-1"></i> Under Review
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 28px; color: var(--text-muted); font-size: 13px;">
                                <i class="fa-solid fa-certificate" style="font-size: 28px; opacity: 0.3; margin-bottom: 8px; display: block;"></i>
                                No certificate requests made yet. Submit a new request using the left form.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
