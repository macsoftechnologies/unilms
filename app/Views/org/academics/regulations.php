<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Academic Regulations<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <div>
            <h2><i class="fa-solid fa-scroll"></i> Academic Regulations & Policies</h2>
            <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 13px;">Manage university curriculum regulations (e.g. R20, R23), minimum graduation credits, and grading standards.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-primary" onclick="openRegulationModal()"><i class="fa-solid fa-plus"></i> Add Regulation</button>
            <a href="<?= base_url('org/academics') ?>" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Academics Home</a>
        </div>
    </div>

    <!-- Regulations Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Regulation Title / Code</th>
                    <th>Effective Year</th>
                    <th>Graduation Credits</th>
                    <th>Minimum Pass %</th>
                    <th>Policy Summary</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($regulations)): foreach($regulations as $r): ?>
                <tr>
                    <td><strong><?= esc($r['name']) ?></strong></td>
                    <td><span class="badge" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; font-weight: 700;"><?= esc($r['start_year']) ?></span></td>
                    <td><strong><?= (int)$r['total_credits'] ?> Credits</strong></td>
                    <td><?= $r['pass_percentage'] ?>%</td>
                    <td style="font-size: 13px; color: var(--text-muted); max-width: 300px;"><?= esc($r['description'] ?: 'Standard statutory regulations.') ?></td>
                    <td>
                        <span class="badge" style="background: <?= $r['is_active'] ? 'rgba(16, 185, 129, 0.12)' : 'rgba(100, 116, 139, 0.12)' ?>; color: <?= $r['is_active'] ? '#10b981' : '#64748b' ?>; font-weight: 600;">
                            <?= $r['is_active'] ? 'Active' : 'Archived' ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?= base_url('org/academics/regulations/delete/' . $r['id']) ?>" class="btn-icon text-danger" onclick="return confirm('Delete this regulation?')" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="7" style="text-align: center; padding: 30px; color: var(--text-muted);">No academic regulations defined yet. Click "Add Regulation" to begin.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Add Regulation Modal -->
<div class="drawer-overlay" id="regModal">
    <div class="drawer-content" style="max-width: 500px;">
        <form action="<?= base_url('org/academics/regulations/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); color: #fff; padding: 18px 22px; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 17px; font-weight: 700; color: #fff;"><i class="fa-solid fa-scroll me-2"></i> Add Academic Regulation</h3>
                <button type="button" class="btn-close" onclick="closeRegulationModal()" style="color: #fff; background: none; border: none; font-size: 18px; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 22px;">
                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Regulation Title / Code <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. R26 Autonomous Curriculum Regulation" style="font-size: 13.5px;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-size: 12.5px; font-weight: 700;">Effective Start Year <span class="text-danger">*</span></label>
                        <input type="number" name="start_year" class="form-control" required value="<?= date('Y') ?>" style="font-size: 13.5px;">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 12.5px; font-weight: 700;">Graduation Credits <span class="text-danger">*</span></label>
                        <input type="number" name="total_credits" class="form-control" required value="160" style="font-size: 13.5px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Passing Threshold (%)</label>
                    <input type="number" step="0.1" name="pass_percentage" class="form-control" value="40.0" style="font-size: 13.5px;">
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Regulation Summary & Details</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Overview of grading scales, promotion rules, detention criteria..."></textarea>
                </div>

                <div class="form-group">
                    <label style="font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" checked>
                        Set as Active Curriculum Policy
                    </label>
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeRegulationModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background: #7C3AED; border: none; padding: 10px 20px; font-weight: 700;"><i class="fa-solid fa-check"></i> Save Regulation</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRegulationModal() {
    $('#regModal').addClass('active');
}
function closeRegulationModal() {
    $('#regModal').removeClass('active');
}
</script>
<?= $this->endSection() ?>
