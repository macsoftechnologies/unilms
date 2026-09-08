<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Fee Plans - <?= esc($program['name']) ?><?= $this->endSection() ?>
<?= $this->section('content') ?>

<!-- Breadcrumb Navigation -->
<div style="margin-bottom: 18px; display: flex; align-items: center; gap: 8px; font-size: 13.5px;">
    <a href="<?= base_url('org/fee-config/structures') ?>" style="color: #7C3AED; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
        <i class="fa-solid fa-arrow-left"></i> Fee Structures Hub
    </a>
    <span style="color: var(--text-secondary);">/</span>
    <span style="color: var(--text-primary); font-weight: 700;"><?= esc($program['name']) ?></span>
</div>

<!-- Header Banner -->
<div class="header-banner" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
    <div style="display: flex; align-items: center; gap: 14px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 22px;">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 3px;">
                <span class="badge" style="background: rgba(124, 58, 237, 0.12); color: #7C3AED; font-weight: 800; font-size: 12px; padding: 3px 8px; border-radius: 6px;"><?= esc($program['code']) ?></span>
                <h1 class="header-title" style="margin: 0; font-size: 20px; font-weight: 700; color: var(--text-primary);"><?= esc($program['name']) ?></h1>
            </div>
            <p class="header-subtitle" style="margin: 0; color: var(--text-secondary); font-size: 13px;">
                Department: <strong><?= esc($program['department_name'] ?? 'Engineering') ?></strong> • 4 Years (8 Semesters)
            </p>
        </div>
    </div>
    <div>
        <button type="button" class="btn btn-primary" onclick="openStructureDrawer()" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); border: none; padding: 10px 18px; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(124, 58, 237, 0.25);">
            <i class="fa-solid fa-plus-circle"></i> Add / Edit Semester Fee Package
        </button>
    </div>
</div>

<!-- 4 Year / Batch Level Cards Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 1fr)); gap: 20px;">
    <?php foreach($year_levels as $yKey => $yData): ?>
        <div class="card" style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <!-- Batch Level Header -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--border-color);">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(124, 58, 237, 0.1); color: #7C3AED; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <h3 style="margin: 0; font-size: 15.5px; font-weight: 700; color: var(--text-primary);"><?= esc($yData['title']) ?></h3>
                            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #059669; font-weight: 700; font-size: 11px; padding: 2px 6px; border-radius: 4px;"><?= esc($yData['badge']) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Semesters in this Batch Level -->
                <?php if(!empty($yData['semesters'])): ?>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <?php foreach($yData['semesters'] as $semPkg): ?>
                            <div style="border: 1px solid var(--border-color); border-radius: 10px; padding: 14px; background: var(--bg-main, #f8fafc); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                <div>
                                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                        <span class="badge" style="background: rgba(124, 58, 237, 0.15); color: #7C3AED; font-weight: 800; font-size: 12px; padding: 3px 8px; border-radius: 6px;">
                                            <?= esc($semPkg['semester_name']) ?>
                                        </span>
                                        <span style="font-size: 12px; color: var(--text-secondary);">
                                            AY: <strong><?= esc($semPkg['academic_year_name']) ?></strong>
                                        </span>
                                    </div>
                                    <div style="font-size: 12px; color: var(--text-secondary);">
                                        <?= $semPkg['heads_count'] ?> Fee Heads • Due: <?= $semPkg['due_date'] ? date('d/m/Y', strtotime($semPkg['due_date'])) : '—' ?>
                                    </div>
                                </div>

                                <div style="text-align: right; display: flex; align-items: center; gap: 12px;">
                                    <div>
                                        <strong style="font-size: 16px; font-weight: 800; color: #059669; display: block;">
                                            ₹ <?= number_format((float)$semPkg['total_amount'], 2) ?>
                                        </strong>
                                    </div>
                                    <a href="<?= base_url('org/fee-config/semester-plan/' . ($program['uuid'] ?? $program['id']) . '/' . $semPkg['semester_id'] . '/' . $semPkg['academic_year_id']) ?>" class="btn btn-outline" style="padding: 7px 12px; font-size: 12.5px; font-weight: 700; border-radius: 7px; text-decoration: none; border: 1px solid rgba(124, 58, 237, 0.3); color: #7C3AED; background: #fff; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="fa-solid fa-arrow-right"></i> Open Plan
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 24px; color: var(--text-secondary); background: var(--bg-main, #f8fafc); border-radius: 10px;">
                        <p style="margin: 0; font-size: 13px;">No fee packages configured for this batch yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Quick Drawer for this Program -->
<div class="drawer-overlay" id="feeStructureDrawer">
    <div class="drawer-content" style="max-width: 520px; width: 100%;">
        <form action="<?= base_url('org/fee-config/save-structure') ?>" method="POST" id="feePackageForm">
            <?= csrf_field() ?>
            <input type="hidden" name="program_id" value="<?= $program['id'] ?>">
            
            <div class="drawer-header" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); color: #fff; padding: 18px 22px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255, 255, 255, 0.2); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 17px; font-weight: 700; color: #fff;">Semester Fee Package Builder</h3>
                        <div style="font-size: 12px; color: rgba(255, 255, 255, 0.85);"><?= esc($program['name']) ?></div>
                    </div>
                </div>
                <button type="button" class="btn-close" onclick="closeStructureDrawer()" style="color: #fff; background: none; border: none; font-size: 20px; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 22px; max-height: calc(85vh - 130px); overflow-y: auto;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-size: 12.5px; font-weight: 700;">Semester <span class="text-danger">*</span></label>
                        <select name="semester_id" id="struct_semester" class="form-control" required style="font-size: 13.5px;">
                            <option value="">-- Select Semester --</option>
                            <?php foreach($semesters as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 12.5px; font-weight: 700;">Academic Year <span class="text-danger">*</span></label>
                        <select name="academic_year_id" id="struct_ay" class="form-control" required style="font-size: 13.5px;">
                            <option value="">-- Select Year --</option>
                            <?php foreach($academic_years as $ay): ?>
                                <option value="<?= $ay['id'] ?>" <?= $ay['name'] == '2026-2027' ? 'selected' : '' ?>><?= esc($ay['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Standard Payment Due Date</label>
                    <input type="date" name="due_date" id="struct_due" value="2026-08-15" class="form-control" style="font-size: 13.5px;">
                </div>

                <!-- Fee Heads List with Amount Inputs -->
                <div style="border: 1px solid var(--border-color); border-radius: 10px; overflow: hidden; margin-bottom: 18px;">
                    <div style="background: var(--bg-main, #f8fafc); padding: 10px 14px; border-bottom: 1px solid var(--border-color); font-size: 12px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary); display: flex; justify-content: space-between;">
                        <span>Fee Head Category</span>
                        <span>Amount (₹)</span>
                    </div>
                    <div style="max-height: 280px; overflow-y: auto; padding: 6px 12px;">
                        <?php foreach($fee_types as $ft): ?>
                            <?php 
                                $defAmt = 0;
                                if(stripos($ft['name'], 'Tuition') !== false) $defAmt = 65000;
                                elseif(stripos($ft['name'], 'Lab') !== false) $defAmt = 15000;
                                elseif(stripos($ft['name'], 'Examination') !== false) $defAmt = 3500;
                                elseif(stripos($ft['name'], 'Library') !== false) $defAmt = 1500;
                            ?>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px dashed var(--border-color); gap: 10px;">
                                <label style="font-size: 13px; font-weight: 600; color: var(--text-primary); margin: 0; flex: 1;">
                                    <?= esc($ft['name']) ?>
                                </label>
                                <div style="display: flex; align-items: center; width: 130px; position: relative;">
                                    <span style="position: absolute; left: 10px; font-size: 12px; color: var(--text-secondary); font-weight: 600;">₹</span>
                                    <input type="number" min="0" step="100" name="fee_amounts[<?= $ft['id'] ?>]" value="<?= $defAmt ?>" class="form-control fee-head-input" oninput="calculateTotalPackage()" style="padding-left: 24px; font-size: 13.5px; font-weight: 700; text-align: right; height: 34px;">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div style="background: rgba(124, 58, 237, 0.08); padding: 12px 16px; border-top: 1px solid rgba(124, 58, 237, 0.2); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 13px; font-weight: 700; color: #7C3AED;">Total Semester Package:</span>
                        <strong style="font-size: 16px; font-weight: 800; color: #7C3AED;" id="totalPackageDisplay">₹ 85,000.00</strong>
                    </div>
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeStructureDrawer()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); border: none; padding: 10px 20px; font-weight: 700;">
                    <i class="fa-solid fa-check-circle me-1"></i> Save Fee Package
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openStructureDrawer() {
    $('#feeStructureDrawer').addClass('active');
    calculateTotalPackage();
}
function closeStructureDrawer() {
    $('#feeStructureDrawer').removeClass('active');
}
function calculateTotalPackage() {
    var total = 0;
    document.querySelectorAll('.fee-head-input').forEach(function(input) {
        var val = parseFloat(input.value) || 0;
        total += val;
    });
    document.getElementById('totalPackageDisplay').innerText = '₹ ' + total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
document.addEventListener('DOMContentLoaded', calculateTotalPackage);
</script>

<?= $this->endSection() ?>
