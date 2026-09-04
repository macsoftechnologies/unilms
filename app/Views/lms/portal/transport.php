<?= $this->extend('lms/layout') ?>
<?= $this->section('page_title') ?>Campus Transit & Transport Pass<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="lms-page-header">
    <h1><i class="fa-solid fa-bus me-2" style="color: var(--primary);"></i> Campus Transit & Bus Pass Facility</h1>
    <p>Subscribe to designated university fleet routes, choose pickup landmarks, and download your digital QR bus commute pass.</p>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: var(--success); padding: 12px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
        <i class="fa-solid fa-circle-check me-2"></i> <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 24px;">
    
    <!-- Apply for Transport Card -->
    <div class="card" style="padding: 24px; border: 1px solid var(--border); border-radius: 16px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px;">
            <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(99, 102, 241, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-route"></i>
            </div>
            <div>
                <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0; color: var(--text-main);">Request Transit Pass</h3>
                <span style="font-size: 12px; color: var(--text-muted);">GPS Tracked Air-Conditioned Fleet</span>
            </div>
        </div>

        <form action="<?= base_url('lms/transport/submit') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-size: 12.5px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-main);">Select Transit Route *</label>
                <select name="route_id" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; background: var(--surface);">
                    <option value="1">Route 1: North Campus Express (Hebbal - Yelahanka)</option>
                    <option value="2" selected>Route 2: Tech Corridor Express (Whitefield - Marathahalli)</option>
                    <option value="3">Route 3: Central City Shuttle (MG Road - Indiranagar)</option>
                    <option value="4">Route 4: South Metro Connect (Silk Board - Electronic City)</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-size: 12.5px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-main);">Designated Boarding Landmark / Stop *</label>
                <input type="text" name="halt_name" class="form-control" placeholder="e.g. Marathahalli Bridge Junction, Near Metro Pillar #42" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; background: var(--surface);">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; font-weight: 700; padding: 11px;">
                <i class="fa-solid fa-paper-plane me-1"></i> Submit Transit Registration
            </button>
        </form>
    </div>

    <!-- Active Transport Registration Card -->
    <div class="card" style="padding: 24px; border: 1px solid var(--border); border-radius: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
            <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0; color: var(--text-main);">
                <i class="fa-solid fa-qrcode me-2" style="color: var(--primary);"></i> Digital Transit Pass Status
            </h3>
            <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11px; padding: 3px 8px; border-radius: 10px; font-weight: 700;">
                <?= count($registrations ?? []) ?> Active
            </span>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 10px 14px;">Requested On</th>
                        <th style="padding: 10px 14px; text-align: center;">Transit Pass</th>
                        <th style="padding: 10px 14px; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($registrations)): foreach($registrations as $r): ?>
                        <tr>
                            <td style="padding: 12px 14px; font-size: 12.5px; color: var(--text-muted);"><?= date('d M Y', strtotime($r['created_at'])) ?></td>
                            <td style="padding: 12px 14px; text-align: center;">
                                <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); padding: 3px 8px; border-radius: 8px; font-weight: 700; font-size: 11px;">
                                    <i class="fa-solid fa-qrcode me-1"></i> QR Active
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
                            <td colspan="3" style="text-align: center; padding: 28px; color: var(--text-muted); font-size: 13px;">
                                <i class="fa-solid fa-bus" style="font-size: 28px; opacity: 0.3; margin-bottom: 8px; display: block;"></i>
                                No transport registration record found. Apply using the form on the left.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
