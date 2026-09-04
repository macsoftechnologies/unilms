<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Fee Structures - Departments & Degree Programs<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
    <div>
        <h1 class="header-title" style="margin: 0; font-size: 22px; font-weight: 700; color: var(--text-primary);"><i class="fa-solid fa-file-invoice-dollar" style="color: #7C3AED;"></i> Program Fee Structures & Billing</h1>
        <p class="header-subtitle" style="margin: 4px 0 0; color: var(--text-secondary); font-size: 13.5px;">Select a degree program to manage its batches, year levels, and semester fee packages.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <a href="<?= base_url('org/fee-config/types') ?>" class="btn btn-outline" style="padding: 10px 16px; border-radius: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; border: 1px solid var(--border-color); background: var(--card-bg, #fff);">
            <i class="fa-solid fa-tags" style="color: #7C3AED;"></i> Manage Fee Heads
        </a>
        <button type="button" class="btn btn-primary" onclick="openStructureDrawer()" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); border: none; padding: 10px 18px; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(124, 58, 237, 0.25);">
            <i class="fa-solid fa-plus-circle"></i> Quick Fee Package Creator
        </button>
    </div>
</div>

<?php if(empty($dept_grouped_programs)): ?>
    <div class="card" style="text-align: center; padding: 48px 20px; background: #fff; border-radius: 14px; border: 1px solid var(--border-color);">
        <i class="fa-solid fa-building-columns" style="font-size: 48px; opacity: 0.35; margin-bottom: 14px; display: block; color: #7C3AED;"></i>
        <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 6px;">No Degree Programs Found</h4>
        <p style="font-size: 13.5px; color: var(--text-secondary); margin-bottom: 18px;">Create your academic programs first under Academics > Degree Programs.</p>
    </div>
<?php else: ?>

    <?php foreach($dept_grouped_programs as $deptName => $progList): ?>
        <div class="card" style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 14px; padding: 22px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            
            <!-- Department Title Banner -->
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 2px solid rgba(124, 58, 237, 0.15);">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <div>
                    <h2 style="margin: 0; font-size: 17.5px; font-weight: 700; color: var(--text-primary);"><?= esc($deptName) ?></h2>
                    <div style="font-size: 12.5px; color: var(--text-secondary);"><?= count($progList) ?> Degree Program(s) in this department</div>
                </div>
            </div>

            <!-- Degree Programs Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 18px;">
                <?php foreach($progList as $prog): ?>
                    <div style="border: 1px solid var(--border-color); border-radius: 12px; padding: 18px; background: #fff; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                                <span class="badge" style="background: rgba(124, 58, 237, 0.12); color: #7C3AED; font-weight: 800; font-size: 12px; padding: 4px 10px; border-radius: 6px;">
                                    <?= esc($prog['code']) ?>
                                </span>
                                <?php if((int)$prog['configured_semesters_count'] > 0): ?>
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: #059669; font-weight: 700; font-size: 11.5px; padding: 4px 8px; border-radius: 6px;">
                                        <i class="fa-solid fa-check-circle me-1"></i> Active Billing
                                    </span>
                                <?php else: ?>
                                    <span class="badge" style="background: rgba(245, 158, 11, 0.12); color: #d97706; font-weight: 700; font-size: 11.5px; padding: 4px 8px; border-radius: 6px;">
                                        Pending Setup
                                    </span>
                                <?php endif; ?>
                            </div>

                            <h3 style="margin: 0 0 6px; font-size: 16px; font-weight: 700; color: var(--text-primary);">
                                <?= esc($prog['name']) ?>
                            </h3>
                            <div style="font-size: 12.5px; color: var(--text-secondary); margin-bottom: 14px;">
                                Duration: <strong>4 Years (8 Semesters)</strong> • Degree Credits: <strong><?= esc($prog['total_credits'] ?? '160') ?></strong>
                            </div>

                            <div style="background: var(--bg-main, #f8fafc); border-radius: 10px; padding: 12px 14px; margin-bottom: 16px; display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                <div>
                                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary);">Configured Semesters</div>
                                    <strong style="font-size: 15px; color: var(--text-primary);"><?= (int)$prog['configured_semesters_count'] ?> / 8 Semesters</strong>
                                </div>
                                <div>
                                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary);">Total Program Fee</div>
                                    <strong style="font-size: 15px; color: #059669;">₹ <?= number_format((float)($prog['total_program_fee'] ?? 0), 2) ?></strong>
                                </div>
                            </div>
                        </div>

                        <a href="<?= base_url('org/fee-config/program/' . $prog['id']) ?>" class="btn btn-primary" style="width: 100%; background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); border: none; padding: 10px; font-weight: 700; border-radius: 8px; text-decoration: none; text-align: center; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                            <i class="fa-solid fa-folder-open"></i> Open Program Batches & Semesters →
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

<?php endif; ?>

<!-- Quick Multi-Head Package Creator Drawer -->
<div class="drawer-overlay" id="feeStructureDrawer">
    <div class="drawer-content" style="max-width: 520px; width: 100%;">
        <form action="<?= base_url('org/fee-config/save-structure') ?>" method="POST" id="feePackageForm">
            <?= csrf_field() ?>
            
            <div class="drawer-header" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); color: #fff; padding: 18px 22px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255, 255, 255, 0.2); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 17px; font-weight: 700; color: #fff;">Semester Fee Package Builder</h3>
                        <div style="font-size: 12px; color: rgba(255, 255, 255, 0.85);">Set all fee amounts at once</div>
                    </div>
                </div>
                <button type="button" class="btn-close" onclick="closeStructureDrawer()" style="color: #fff; background: none; border: none; font-size: 20px; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 22px; max-height: calc(85vh - 130px); overflow-y: auto;">
                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Target Degree Program <span class="text-danger">*</span></label>
                    <select name="program_id" id="struct_program" class="form-control" required style="font-size: 13.5px;">
                        <option value="">-- Select Degree Program --</option>
                        <?php foreach($programs as $p): ?>
                            <option value="<?= $p['id'] ?>" selected><?= esc($p['name']) ?> (<?= esc($p['code']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-size: 12.5px; font-weight: 700;">Semester <span class="text-danger">*</span></label>
                        <select name="semester_id" id="struct_semester" class="form-control" required style="font-size: 13.5px;">
                            <option value="">-- Select Semester --</option>
                            <?php foreach($semesters as $s): ?>
                                <option value="<?= $s['id'] ?>" <?= $s['sequence'] == 1 ? 'selected' : '' ?>><?= esc($s['name']) ?></option>
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
                    <!-- Live Total Package Calculator -->
                    <div style="background: rgba(124, 58, 237, 0.08); padding: 12px 16px; border-top: 1px solid rgba(124, 58, 237, 0.2); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 13px; font-weight: 700; color: #7C3AED;">Total Semester Package:</span>
                        <strong style="font-size: 16px; font-weight: 800; color: #7C3AED;" id="totalPackageDisplay">₹ 85,000.00</strong>
                    </div>
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeStructureDrawer()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); border: none; padding: 10px 20px; font-weight: 700;">
                    <i class="fa-solid fa-check-circle me-1"></i> Save Entire Fee Package
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
