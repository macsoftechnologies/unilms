<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?><?= esc($semester['name']) ?> Fee Plan - <?= esc($program['name']) ?><?= $this->endSection() ?>
<?= $this->section('content') ?>

<!-- Breadcrumb Navigation -->
<div style="margin-bottom: 18px; display: flex; align-items: center; gap: 8px; font-size: 13.5px; flex-wrap: wrap;">
    <a href="<?= base_url('org/fee-config/structures') ?>" style="color: #7C3AED; text-decoration: none; font-weight: 600;">
        Fee Structures Hub
    </a>
    <span style="color: var(--text-secondary);">/</span>
    <a href="<?= base_url('org/fee-config/program/' . $program['id']) ?>" style="color: #7C3AED; text-decoration: none; font-weight: 600;">
        <?= esc($program['name']) ?>
    </a>
    <span style="color: var(--text-secondary);">/</span>
    <span style="color: var(--text-primary); font-weight: 700;"><?= esc($semester['name']) ?> Plan (<?= esc($academic_year['name']) ?>)</span>
</div>

<!-- Plan Summary Banner -->
<div class="card" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); color: #fff; border-radius: 14px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(124, 58, 237, 0.25);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                <span class="badge" style="background: rgba(255, 255, 255, 0.2); color: #fff; font-weight: 800; font-size: 13px; padding: 4px 10px; border-radius: 6px;"><?= esc($semester['name']) ?></span>
                <span style="font-size: 14px; color: rgba(255, 255, 255, 0.9);">Academic Year: <strong><?= esc($academic_year['name']) ?></strong></span>
            </div>
            <h1 style="margin: 0 0 6px; font-size: 22px; font-weight: 700; color: #fff;"><?= esc($program['name']) ?></h1>
            <p style="margin: 0; font-size: 13.5px; color: rgba(255, 255, 255, 0.85);">
                Department: <?= esc($program['department_name'] ?? 'General Engineering') ?> • Payment Due Date: <strong><?= $due_date ? date('d M, Y', strtotime($due_date)) : 'Standard Date' ?></strong>
            </p>
        </div>

        <div style="display: flex; align-items: center; gap: 24px;">
            <div style="text-align: right; background: rgba(255, 255, 255, 0.12); padding: 12px 20px; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.2);">
                <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: rgba(255, 255, 255, 0.8);">Total Semester Billing</div>
                <strong style="font-size: 24px; font-weight: 900; color: #fff;">₹ <?= number_format((float)$total_amount, 2) ?></strong>
                <div style="font-size: 12px; color: rgba(255, 255, 255, 0.85);"><?= count($items) ?> Fee Heads Included</div>
            </div>

            <button type="button" class="btn" onclick="openStructureDrawer()" style="background: #fff; color: #7C3AED; border: none; padding: 12px 20px; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                <i class="fa-solid fa-pen-to-square"></i> Edit Fee Package
            </button>
        </div>
    </div>
</div>

<!-- Itemized Fee Heads Table Card -->
<div class="card" style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 14px; padding: 22px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
        <h3 style="margin: 0; font-size: 16.5px; font-weight: 700; color: var(--text-primary);"><i class="fa-solid fa-list-check me-2" style="color: #7C3AED;"></i> Itemized Fee Heads Breakdown</h3>
    </div>

    <div class="table-responsive" style="overflow-x: auto;">
        <table class="data-table" style="width: 100%;">
            <thead>
                <tr style="background: var(--bg-main, #f8fafc);">
                    <th style="padding: 14px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Fee Category / Head</th>
                    <th style="padding: 14px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Applicable Category</th>
                    <th style="padding: 14px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Ledger Code</th>
                    <th style="padding: 14px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Payment Due Date</th>
                    <th style="padding: 14px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase; text-align: right;">Amount (₹)</th>
                    <th style="padding: 14px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($items)): foreach($items as $item): ?>
                    <tr>
                        <td style="padding: 14px 16px; font-weight: 700; color: var(--text-primary); font-size: 14px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-tag" style="color: #7C3AED; font-size: 12px;"></i>
                                <?= esc($item['fee_type_name']) ?>
                            </div>
                        </td>
                        <td style="padding: 14px 16px; color: var(--text-secondary); font-size: 13px;">
                            <?= esc($item['applicable_to'] ?: 'All Students') ?>
                        </td>
                        <td style="padding: 14px 16px; font-size: 12.5px; font-family: monospace; color: #7C3AED; font-weight: 600;">
                            <?= esc($item['gl_head'] ?: '1000-GENERAL') ?>
                        </td>
                        <td style="padding: 14px 16px; font-size: 13px; color: var(--text-primary);">
                            <?= $item['due_date'] ? date('d/m/Y', strtotime($item['due_date'])) : '—' ?>
                        </td>
                        <td style="padding: 14px 16px; text-align: right;">
                            <strong style="color: #059669; font-size: 15px;">₹ <?= number_format((float)$item['amount'], 2) ?></strong>
                        </td>
                        <td style="padding: 14px 16px; text-align: right;">
                            <form action="<?= base_url('org/fee-config/delete-structure') ?>" method="POST" style="display: inline;" onsubmit="return confirm('Remove this fee head from this semester?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                <button type="submit" class="btn-icon text-danger" style="background: none; border: 1px solid var(--border-color); padding: 6px 10px; border-radius: 6px; color: #DC2626; cursor: pointer;" title="Delete Head">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="6" style="text-align:center; padding: 40px; color: var(--text-secondary);">No fee heads added for this semester yet. Click "Edit Fee Package" to add heads.</td></tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr style="background: var(--bg-main, #f8fafc); font-weight: 800;">
                    <td colspan="4" style="padding: 16px; text-align: right; font-size: 14px; color: var(--text-primary);">Total Semester Package:</td>
                    <td style="padding: 16px; text-align: right; font-size: 16px; color: #059669;">₹ <?= number_format((float)$total_amount, 2) ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Edit Drawer Modal -->
<div class="drawer-overlay" id="feeStructureDrawer">
    <div class="drawer-content" style="max-width: 520px; width: 100%;">
        <form action="<?= base_url('org/fee-config/save-structure') ?>" method="POST" id="feePackageForm">
            <?= csrf_field() ?>
            <input type="hidden" name="program_id" value="<?= $program['id'] ?>">
            <input type="hidden" name="semester_id" value="<?= $semester['id'] ?>">
            <input type="hidden" name="academic_year_id" value="<?= $academic_year['id'] ?>">
            
            <div class="drawer-header" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); color: #fff; padding: 18px 22px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255, 255, 255, 0.2); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 17px; font-weight: 700; color: #fff;">Edit <?= esc($semester['name']) ?> Package</h3>
                        <div style="font-size: 12px; color: rgba(255, 255, 255, 0.85);"><?= esc($program['name']) ?></div>
                    </div>
                </div>
                <button type="button" class="btn-close" onclick="closeStructureDrawer()" style="color: #fff; background: none; border: none; font-size: 20px; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 22px; max-height: calc(85vh - 130px); overflow-y: auto;">
                <div class="form-group" style="margin-bottom: 18px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Payment Due Date</label>
                    <input type="date" name="due_date" id="struct_due" value="<?= $due_date ?: '2026-08-15' ?>" class="form-control" style="font-size: 13.5px;">
                </div>

                <!-- Fee Heads List with Amount Inputs -->
                <div style="border: 1px solid var(--border-color); border-radius: 10px; overflow: hidden; margin-bottom: 18px;">
                    <div style="background: var(--bg-main, #f8fafc); padding: 10px 14px; border-bottom: 1px solid var(--border-color); font-size: 12px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary); display: flex; justify-content: space-between;">
                        <span>Fee Head Category</span>
                        <span>Amount (₹)</span>
                    </div>
                    <div style="max-height: 280px; overflow-y: auto; padding: 6px 12px;">
                        <?php 
                            $existingMap = [];
                            foreach($items as $it) {
                                $existingMap[$it['fee_type_id']] = (float)$it['amount'];
                            }
                        ?>
                        <?php foreach($fee_types as $ft): ?>
                            <?php 
                                $val = isset($existingMap[$ft['id']]) ? $existingMap[$ft['id']] : 0;
                            ?>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px dashed var(--border-color); gap: 10px;">
                                <label style="font-size: 13px; font-weight: 600; color: var(--text-primary); margin: 0; flex: 1;">
                                    <?= esc($ft['name']) ?>
                                </label>
                                <div style="display: flex; align-items: center; width: 130px; position: relative;">
                                    <span style="position: absolute; left: 10px; font-size: 12px; color: var(--text-secondary); font-weight: 600;">₹</span>
                                    <input type="number" min="0" step="100" name="fee_amounts[<?= $ft['id'] ?>]" value="<?= $val ?>" class="form-control fee-head-input" oninput="calculateTotalPackage()" style="padding-left: 24px; font-size: 13.5px; font-weight: 700; text-align: right; height: 34px;">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div style="background: rgba(124, 58, 237, 0.08); padding: 12px 16px; border-top: 1px solid rgba(124, 58, 237, 0.2); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 13px; font-weight: 700; color: #7C3AED;">Total Semester Package:</span>
                        <strong style="font-size: 16px; font-weight: 800; color: #7C3AED;" id="totalPackageDisplay">₹ <?= number_format((float)$total_amount, 2) ?></strong>
                    </div>
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeStructureDrawer()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); border: none; padding: 10px 20px; font-weight: 700;">
                    <i class="fa-solid fa-check-circle me-1"></i> Update Semester Package
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
