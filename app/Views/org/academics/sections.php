<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Cohort Sections<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <div>
            <h2><i class="fa-solid fa-people-line"></i> Cohort Sections (Divisions)</h2>
            <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 13px;">Manage section breakdowns (Sec A, Sec B, Sec C) for academic cohorts with student intake capacities.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-primary" onclick="openSectionModal()"><i class="fa-solid fa-plus"></i> Add Cohort Section</button>
            <a href="<?= base_url('org/academics/cohorts') ?>" class="btn btn-outline"><i class="fa-solid fa-users"></i> Cohorts</a>
        </div>
    </div>

    <!-- Sections Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Section Name</th>
                    <th>Associated Academic Cohort</th>
                    <th>Program</th>
                    <th>Max Student Capacity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($sections)): foreach($sections as $sec): ?>
                <tr>
                    <td><strong style="color: #4f46e5; font-size: 15px;"><?= esc($sec['name']) ?></strong></td>
                    <td><strong><?= esc($sec['cohort_name']) ?></strong></td>
                    <td><?= esc($sec['program_name'] ?? 'N/A') ?></td>
                    <td><?= (int)$sec['max_students'] ?> Students</td>
                    <td>
                        <span class="badge" style="background: <?= $sec['is_active'] ? 'rgba(16, 185, 129, 0.12)' : 'rgba(100, 116, 139, 0.12)' ?>; color: <?= $sec['is_active'] ? '#10b981' : '#64748b' ?>; font-weight: 600;">
                            <?= $sec['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?= base_url('org/academics/sections/delete/' . $sec['id']) ?>" class="btn-icon text-danger" onclick="return confirm('Delete this section?')" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6" style="text-align: center; padding: 30px; color: var(--text-muted);">No cohort sections created yet. Click "Add Cohort Section" to create Section A, B, etc.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Section Modal -->
<div class="drawer-overlay" id="sectionModal">
    <div class="drawer-content" style="max-width: 480px;">
        <form action="<?= base_url('org/academics/sections/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); color: #fff; padding: 18px 22px; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 17px; font-weight: 700; color: #fff;"><i class="fa-solid fa-people-line me-2"></i> Add Cohort Section</h3>
                <button type="button" class="btn-close" onclick="closeSectionModal()" style="color: #fff; background: none; border: none; font-size: 18px; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 22px;">
                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Select Cohort <span class="text-danger">*</span></label>
                    <select name="cohort_id" class="form-control" required style="font-size: 13.5px;">
                        <option value="">-- Choose Academic Cohort --</option>
                        <?php foreach($cohorts as $co): ?>
                            <option value="<?= $co['id'] ?>"><?= esc($co['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Section Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Section A, Section B" style="font-size: 13.5px;">
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Maximum Intake Capacity</label>
                    <input type="number" name="max_students" class="form-control" value="60" min="1" max="250">
                </div>

                <div class="form-group">
                    <label style="font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" checked>
                        Section Active for Allocations
                    </label>
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeSectionModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background: #7C3AED; border: none; padding: 10px 20px; font-weight: 700;"><i class="fa-solid fa-check"></i> Save Section</button>
            </div>
        </form>
    </div>
</div>

<script>
function openSectionModal() {
    $('#sectionModal').addClass('active');
}
function closeSectionModal() {
    $('#sectionModal').removeClass('active');
}
</script>
<?= $this->endSection() ?>
