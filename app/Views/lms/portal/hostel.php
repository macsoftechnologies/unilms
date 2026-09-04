<?= $this->extend('lms/layout') ?>
<?= $this->section('page_title') ?>Hostel Residence & Accommodation<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="lms-page-header">
    <h1><i class="fa-solid fa-bed me-2" style="color: var(--info);"></i> Campus Hostel & Residential Accommodation</h1>
    <p>Apply for on-campus student residences, request room category upgrades, and view allotment room keys.</p>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: var(--success); padding: 12px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
        <i class="fa-solid fa-circle-check me-2"></i> <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 24px;">
    
    <!-- Apply for Hostel Card -->
    <div class="card" style="padding: 24px; border: 1px solid var(--border); border-radius: 16px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px;">
            <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(6, 182, 212, 0.1); color: var(--info); display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-hotel"></i>
            </div>
            <div>
                <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0; color: var(--text-main);">Apply for Accommodation</h3>
                <span style="font-size: 12px; color: var(--text-muted);">Includes High-Speed WiFi & Mess Dining</span>
            </div>
        </div>

        <form action="<?= base_url('lms/hostel/submit') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-size: 12.5px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-main);">Preferred Room Type *</label>
                <select name="preferred_room_type" class="form-control" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; background: var(--surface);">
                    <option value="Single AC">Single AC (Deluxe Private)</option>
                    <option value="Single Non-AC">Single Non-AC (Private Room)</option>
                    <option value="Double AC" selected>Double Sharing AC (Attached Bath)</option>
                    <option value="Double Non-AC">Double Sharing Non-AC</option>
                    <option value="Dormitory">Triple Sharing Standard</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-size: 12.5px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-main);">Expected Move-In Date *</label>
                <input type="date" name="joining_date" class="form-control" required value="<?= date('Y-m-d', strtotime('+7 days')) ?>" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; background: var(--surface);">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; font-weight: 700; padding: 11px;">
                <i class="fa-solid fa-paper-plane me-1"></i> Submit Room Request
            </button>
        </form>
    </div>

    <!-- Request Status Table -->
    <div class="card" style="padding: 24px; border: 1px solid var(--border); border-radius: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
            <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0; color: var(--text-main);">
                <i class="fa-solid fa-clock-rotate-left me-2" style="color: var(--primary);"></i> Accommodation Status
            </h3>
            <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11px; padding: 3px 8px; border-radius: 10px; font-weight: 700;">
                <?= count($requests ?? []) ?> Requests
            </span>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 10px 14px;">Applied Date</th>
                        <th style="padding: 10px 14px;">Room Category</th>
                        <th style="padding: 10px 14px; text-align: center;">Allotment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($requests)): foreach($requests as $r): ?>
                        <tr>
                            <td style="padding: 12px 14px; font-size: 12.5px; color: var(--text-muted);"><?= date('d M Y', strtotime($r['created_at'])) ?></td>
                            <td style="padding: 12px 14px;">
                                <strong style="color: var(--text-main); font-size: 13px;"><?= esc($r['preferred_room_type']) ?></strong>
                            </td>
                            <td style="padding: 12px 14px; text-align: center;">
                                <?php if(($r['status'] ?? '') == 'Approved'): ?>
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); padding: 4px 10px; border-radius: 12px; font-weight: 800; font-size: 11px;">
                                        <i class="fa-solid fa-circle-check me-1"></i> Allotted (Block B-304)
                                    </span>
                                <?php else: ?>
                                    <span class="badge" style="background: rgba(245, 158, 11, 0.12); color: var(--warning); padding: 4px 10px; border-radius: 12px; font-weight: 800; font-size: 11px;">
                                        <i class="fa-solid fa-clock me-1"></i> Verification Pending
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 28px; color: var(--text-muted); font-size: 13px;">
                                <i class="fa-solid fa-bed" style="font-size: 28px; opacity: 0.3; margin-bottom: 8px; display: block;"></i>
                                No active hostel accommodation application found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
