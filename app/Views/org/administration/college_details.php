<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>College Profile & Details<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
    <div>
        <h1 class="header-title" style="margin: 0; font-size: 22px; font-weight: 700; color: var(--text-primary);"><i class="fa-solid fa-building" style="color: #7C3AED;"></i> College Profile & Institutional Details</h1>
        <p class="header-subtitle" style="margin: 4px 0 0; color: var(--text-secondary); font-size: 13.5px;">Manage official institutional information, AICTE/UGC codes, logo, and contact channels for automated certificates and fee receipts.</p>
    </div>
</div>

<?php 
    $hasData = !empty($details['name']) || !empty($details['college_name']);
?>

<!-- ========================================== -->
<!-- 1. READ-ONLY VIEW MODE (DEFAULT)           -->
<!-- ========================================== -->
<div id="profileViewCard" class="card" style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 14px; max-width: 900px; margin: 0 auto; padding: 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); display: <?= $hasData ? 'block' : 'none' ?>;">
    
    <!-- Top Header Banner inside card -->
    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 20px; border-bottom: 1px solid var(--border-color); margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <?php if(!empty($details['logo_path'])): ?>
                <img src="<?= base_url($details['logo_path']) ?>" alt="Logo" style="max-height: 56px; max-width: 80px; object-fit: contain; border-radius: 10px; border: 1px solid var(--border-color); padding: 4px; background: #fff;">
            <?php else: ?>
                <div style="width: 52px; height: 52px; border-radius: 12px; background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
            <?php endif; ?>
            <div>
                <h2 style="margin: 0 0 4px; font-size: 20px; font-weight: 800; color: var(--text-primary);"><?= esc($details['name'] ?? $details['college_name'] ?? 'College Profile') ?></h2>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <span class="badge" style="background: rgba(124, 58, 237, 0.12); color: #7C3AED; font-weight: 700; font-size: 12px; padding: 3px 8px; border-radius: 6px;">
                        <?= esc($details['affiliation'] ?: 'Autonomous Technical University') ?>
                    </span>
                    <?php if(!empty($details['naac_grade'])): ?>
                        <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: #059669; font-weight: 700; font-size: 12px; padding: 3px 8px; border-radius: 6px;">
                            AICTE / NAAC: <?= esc($details['naac_grade']) ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-primary" onclick="switchToEditMode()" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); border: none; padding: 10px 18px; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(124, 58, 237, 0.25);">
            <i class="fa-solid fa-pen-to-square"></i> Edit Profile
        </button>
    </div>

    <!-- Info Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div style="background: var(--bg-main, #f8fafc); padding: 14px 16px; border-radius: 10px; border: 1px solid var(--border-color);">
            <div style="font-size: 11.5px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 4px;">
                <i class="fa-solid fa-envelope me-1" style="color: #7C3AED;"></i> Official Contact Email
            </div>
            <strong style="font-size: 14px; color: var(--text-primary); word-break: break-all;">
                <?= esc($details['contact_email'] ?: '—') ?>
            </strong>
        </div>

        <div style="background: var(--bg-main, #f8fafc); padding: 14px 16px; border-radius: 10px; border: 1px solid var(--border-color);">
            <div style="font-size: 11.5px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 4px;">
                <i class="fa-solid fa-phone me-1" style="color: #7C3AED;"></i> Official Contact Phone
            </div>
            <strong style="font-size: 14px; color: var(--text-primary);">
                <?= esc($details['contact_phone'] ?: '—') ?>
            </strong>
        </div>

        <div style="background: var(--bg-main, #f8fafc); padding: 14px 16px; border-radius: 10px; border: 1px solid var(--border-color);">
            <div style="font-size: 11.5px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 4px;">
                <i class="fa-solid fa-award me-1" style="color: #7C3AED;"></i> Approval Code / NAAC
            </div>
            <strong style="font-size: 14px; color: var(--text-primary);">
                <?= esc($details['naac_grade'] ?: '—') ?>
            </strong>
        </div>

        <div style="background: var(--bg-main, #f8fafc); padding: 14px 16px; border-radius: 10px; border: 1px solid var(--border-color);">
            <div style="font-size: 11.5px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 4px;">
                <i class="fa-solid fa-certificate me-1" style="color: #7C3AED;"></i> Affiliated University
            </div>
            <strong style="font-size: 14px; color: var(--text-primary);">
                <?= esc($details['affiliation'] ?: '—') ?>
            </strong>
        </div>

        <div style="grid-column: 1 / -1; background: var(--bg-main, #f8fafc); padding: 14px 16px; border-radius: 10px; border: 1px solid var(--border-color);">
            <div style="font-size: 11.5px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 4px;">
                <i class="fa-solid fa-location-dot me-1" style="color: #7C3AED;"></i> Campus Physical Address
            </div>
            <div style="font-size: 13.5px; color: var(--text-primary); font-weight: 600; line-height: 1.5;">
                <?= nl2br(esc($details['address'] ?: '—')) ?>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- 2. EDIT FORM MODE                          -->
<!-- ========================================== -->
<div id="profileEditCard" class="card" style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 14px; max-width: 900px; margin: 0 auto; padding: 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); display: <?= $hasData ? 'none' : 'block' ?>;">
    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid var(--border-color); margin-bottom: 20px;">
        <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: var(--text-primary);"><i class="fa-solid fa-pen-to-square me-2" style="color: #7C3AED;"></i> Edit College Details</h3>
        <?php if($hasData): ?>
            <button type="button" class="btn btn-outline" onclick="switchToViewMode()" style="padding: 6px 14px; font-size: 13px; font-weight: 600; border-radius: 8px;">Cancel</button>
        <?php endif; ?>
    </div>

    <form action="<?= base_url('org/administration/save-college-details') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= isset($details['id']) ? $details['id'] : '' ?>">
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group" style="margin: 0;">
                <label style="font-size: 13px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; display: block;">
                    College / Institution Legal Name <span class="text-danger">*</span>
                </label>
                <input type="text" name="name" id="college_name" class="form-control" value="<?= isset($details['name']) ? esc($details['name']) : (isset($details['college_name']) ? esc($details['college_name']) : '') ?>" placeholder="e.g. V Apex Institute of Technology" required style="font-size: 14px; padding: 10px 14px; border-radius: 8px;">
            </div>

            <div class="form-group" style="margin: 0;">
                <label style="font-size: 13px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; display: block;">
                    Affiliated University / Board
                </label>
                <input type="text" name="affiliation" id="affiliation" class="form-control" value="<?= isset($details['affiliation']) ? esc($details['affiliation']) : '' ?>" placeholder="e.g. State Autonomous Technical University" style="font-size: 14px; padding: 10px 14px; border-radius: 8px;">
            </div>

            <div class="form-group" style="margin: 0;">
                <label style="font-size: 13px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; display: block;">
                    NAAC Grade / AICTE Approval Code
                </label>
                <input type="text" name="naac_grade" id="naac_grade" class="form-control" value="<?= isset($details['naac_grade']) ? esc($details['naac_grade']) : '' ?>" placeholder="e.g. A+ / 1-3512891" style="font-size: 14px; padding: 10px 14px; border-radius: 8px;">
            </div>

            <div class="form-group" style="margin: 0;">
                <label style="font-size: 13px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; display: block;">
                    Official Contact Email <span class="text-danger">*</span>
                </label>
                <input type="email" name="contact_email" id="contact_email" class="form-control" value="<?= isset($details['contact_email']) ? esc($details['contact_email']) : '' ?>" placeholder="e.g. principal@vapex.edu.in" required style="font-size: 14px; padding: 10px 14px; border-radius: 8px;">
            </div>

            <div class="form-group" style="margin: 0;">
                <label style="font-size: 13px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; display: block;">
                    Official Contact Phone / Landline
                </label>
                <input type="tel" name="contact_phone" id="contact_phone" class="form-control" value="<?= isset($details['contact_phone']) ? esc($details['contact_phone']) : '' ?>" placeholder="e.g. 040-23456789 / 9876543210" style="font-size: 14px; padding: 10px 14px; border-radius: 8px;">
            </div>

            <div class="form-group" style="margin: 0;">
                <label style="font-size: 13px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; display: block;">
                    College Logo / Crest
                </label>
                <input type="file" name="logo" id="college_logo" class="form-control" accept="image/*" style="font-size: 13px; padding: 7px 12px; border-radius: 8px;">
                <?php if(!empty($details['logo_path'])): ?>
                    <div style="margin-top: 8px; display: flex; align-items: center; gap: 10px;">
                        <img src="<?= base_url($details['logo_path']) ?>" alt="Logo" style="max-height: 40px; border-radius: 6px; border: 1px solid var(--border-color); padding: 2px; background: #fff;">
                        <span style="font-size: 12px; color: var(--text-secondary);">Current Logo Uploaded</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group" style="grid-column: 1 / -1; margin: 0;">
                <label style="font-size: 13px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; display: block;">
                    Campus Physical Address
                </label>
                <textarea name="address" id="campus_address" class="form-control" rows="3" placeholder="Street Address, Campus City, State - PIN Code" style="font-size: 14px; padding: 10px 14px; border-radius: 8px;"><?= isset($details['address']) ? esc($details['address']) : '' ?></textarea>
            </div>
        </div>

        <div style="border-top: 1px solid var(--border-color); padding-top: 20px; display: flex; justify-content: flex-end; gap: 12px;">
            <?php if($hasData): ?>
                <button type="button" class="btn btn-outline" onclick="switchToViewMode()" style="padding: 10px 20px; font-weight: 600; border-radius: 8px;">Cancel</button>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); border: none; padding: 12px 28px; font-size: 14px; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(124, 58, 237, 0.25);">
                <i class="fa-solid fa-check-circle"></i> Save College Details
            </button>
        </div>
    </form>
</div>

<script>
function switchToEditMode() {
    document.getElementById('profileViewCard').style.display = 'none';
    document.getElementById('profileEditCard').style.display = 'block';
}

function switchToViewMode() {
    document.getElementById('profileEditCard').style.display = 'none';
    document.getElementById('profileViewCard').style.display = 'block';
}
</script>

<?= $this->endSection() ?>
