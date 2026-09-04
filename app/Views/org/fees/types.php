<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Fee Types<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="header-title" style="font-size: 24px; font-weight: 700; margin-bottom: 4px;">Fee Types</h1>
        <p class="header-subtitle" style="color: var(--text-muted); font-size: 14px; margin: 0;">Configure and manage standardized fee categories (Tuition, Examination, Library, Transport, etc.).</p>
    </div>
    <div style="display: flex; gap: 12px; align-items: center;">
        <button type="button" class="btn btn-primary" onclick="openFeeTypeDrawer()" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 600; padding: 10px 18px; border-radius: 8px;">
            <i class="fa-solid fa-plus"></i> Add Fee Type
        </button>
    </div>
</div>

<div class="card" style="padding: 0; overflow: hidden; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
    <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <h3 style="font-size: 16px; font-weight: 600; margin: 0;">Defined Fee Categories</h3>
            <span class="badge" style="background: var(--sidebar-hover); color: var(--text-main); font-size: 12px; font-weight: 600; padding: 3px 8px; border-radius: 6px;">
                <?= count($fee_types) ?> Total
            </span>
        </div>
        <div style="max-width: 300px; width: 100%;">
            <input type="text" id="feeTypeSearch" class="form-control" placeholder="Search fee types..." onkeyup="filterFeeTypes()" style="height: 38px; font-size: 13.5px; border-radius: 6px;">
        </div>
    </div>

    <div class="table-responsive" style="overflow-x: auto;">
        <table class="data-table" id="feeTypesTable" style="width: 100%; margin: 0;">
            <thead>
                <tr>
                    <th style="padding: 14px 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Fee Name</th>
                    <th style="padding: 14px 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Description</th>
                    <th style="padding: 14px 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Applicable To</th>
                    <th style="padding: 14px 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">GL Accounts Head</th>
                    <th style="padding: 14px 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($fee_types)): ?>
                    <?php foreach($fee_types as $t): ?>
                        <tr>
                            <td style="padding: 14px 20px; font-weight: 600; color: var(--text-main);">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: rgba(79, 70, 229, 0.1); color: #4F46E5; font-size: 14px;">
                                        <i class="fa-solid fa-tags"></i>
                                    </span>
                                    <span><?= esc($t['name']) ?></span>
                                </div>
                            </td>
                            <td style="padding: 14px 20px; color: var(--text-muted); font-size: 13.5px; max-width: 320px;">
                                <?= esc($t['description'] ?: '—') ?>
                            </td>
                            <td style="padding: 14px 20px;">
                                <?php if(!empty($t['applicable_to'])): ?>
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #059669; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 6px;">
                                        <?= esc($t['applicable_to']) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-size: 13px;">All Students</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 14px 20px;">
                                <?php if(!empty($t['gl_head'])): ?>
                                    <code style="background: var(--sidebar-hover); padding: 3px 8px; border-radius: 4px; font-size: 12.5px; color: #4F46E5; font-weight: 600;"><?= esc($t['gl_head']) ?></code>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-size: 13px;">—</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 14px 20px; text-align: right;">
                                <div style="display: inline-flex; gap: 8px;">
                                    <button type="button" class="btn btn-outline" style="padding: 6px 12px; font-size: 12.5px; display: inline-flex; align-items: center; gap: 6px; border-radius: 6px;" onclick="editFeeType(<?= $t['id'] ?>, '<?= esc($t['name'], 'js') ?>', '<?= esc($t['description'] ?? '', 'js') ?>', '<?= esc($t['applicable_to'] ?? '', 'js') ?>', '<?= esc($t['gl_head'] ?? '', 'js') ?>')">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </button>
                                    <form action="<?= base_url('org/fee-config/delete-type') ?>" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this fee type? Existing fee structures referencing it may be affected.');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                        <button type="submit" class="btn btn-outline" style="padding: 6px 12px; font-size: 12.5px; color: #DC2626; border-color: rgba(220, 38, 38, 0.2); display: inline-flex; align-items: center; gap: 6px; border-radius: 6px;">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 48px 20px;">
                            <div style="max-width: 320px; margin: 0 auto;">
                                <div style="width: 56px; height: 56px; background: rgba(79, 70, 229, 0.08); color: #4F46E5; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 16px;">
                                    <i class="fa-solid fa-tags"></i>
                                </div>
                                <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 6px;">No Fee Types Defined</h4>
                                <p style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 18px;">Get started by creating your first fee category such as Tuition, Examination, or Lab Fees.</p>
                                <button type="button" class="btn btn-primary" onclick="openFeeTypeDrawer()" style="display: inline-flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 600;">
                                    <i class="fa-solid fa-plus"></i> Add Fee Type
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Slide Drawer for Add/Edit Fee Type -->
<div id="feeTypeDrawer" class="drawer-overlay" onclick="closeDrawer('feeTypeDrawer')">
    <div class="drawer-content" onclick="event.stopPropagation()" style="max-width: 480px; width: 100%;">
        <div class="drawer-header" style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--border-color);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(79, 70, 229, 0.1); color: #4F46E5; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                    <i class="fa-solid fa-tags"></i>
                </div>
                <h3 id="feeTypeDrawerTitle" style="font-size: 18px; font-weight: 700; margin: 0;">Add Fee Type</h3>
            </div>
            <button type="button" class="close-btn" onclick="closeDrawer('feeTypeDrawer')" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-muted); line-height: 1;">&times;</button>
        </div>
        
        <div class="drawer-body" style="padding: 24px;">
            <form id="feeTypeForm" action="<?= base_url('org/fee-config/save-type') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="type_id">
                
                <div class="form-group" style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13.5px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">
                        Fee Category Name <span style="color: #DC2626;">*</span>
                    </label>
                    <input type="text" name="name" id="type_name" class="form-control" placeholder="e.g. Tuition Fee, Hostel Fee, Exam Fee" required style="width: 100%; padding: 10px 14px; font-size: 14px; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13.5px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">
                        Description
                    </label>
                    <textarea name="description" id="type_desc" class="form-control" rows="3" placeholder="Brief summary of what this fee covers..." style="width: 100%; padding: 10px 14px; font-size: 14px; border-radius: 8px;"></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13.5px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">
                        Applicable Cohort / Category
                    </label>
                    <input type="text" name="applicable_to" id="type_applicable" class="form-control" placeholder="e.g. All Students, Hostellers, Transport Users" style="width: 100%; padding: 10px 14px; font-size: 14px; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13.5px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">
                        GL Account Head / Ledger Code
                    </label>
                    <input type="text" name="gl_head" id="type_gl" class="form-control" placeholder="e.g. 1001-TUITION-ACC" style="width: 100%; padding: 10px 14px; font-size: 14px; border-radius: 8px;">
                    <small style="color: var(--text-muted); font-size: 12px; display: block; margin-top: 4px;">Used for automatic mapping in Chart of Accounts and Daybook transactions.</small>
                </div>

                <div style="display: flex; gap: 12px; align-items: center;">
                    <button type="button" class="btn btn-outline" onclick="closeDrawer('feeTypeDrawer')" style="flex: 1; padding: 10px; font-weight: 600; border-radius: 8px;">
                        Cancel
                    </button>
                    <button type="submit" id="feeTypeSubmitBtn" class="btn btn-primary" style="flex: 1.5; padding: 10px; font-weight: 600; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                        <i class="fa-solid fa-check"></i> Save Fee Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openFeeTypeDrawer() {
    document.getElementById('feeTypeDrawerTitle').innerText = 'Add Fee Type';
    document.getElementById('type_id').value = '';
    document.getElementById('type_name').value = '';
    document.getElementById('type_desc').value = '';
    document.getElementById('type_applicable').value = '';
    document.getElementById('type_gl').value = '';
    document.getElementById('feeTypeSubmitBtn').innerHTML = '<i class="fa-solid fa-check"></i> Save Fee Type';
    openDrawer('feeTypeDrawer');
}

function editFeeType(id, name, desc, applicable, gl) {
    document.getElementById('feeTypeDrawerTitle').innerText = 'Edit Fee Type';
    document.getElementById('type_id').value = id;
    document.getElementById('type_name').value = name;
    document.getElementById('type_desc').value = desc;
    document.getElementById('type_applicable').value = applicable;
    document.getElementById('type_gl').value = gl;
    document.getElementById('feeTypeSubmitBtn').innerHTML = '<i class="fa-solid fa-check"></i> Update Fee Type';
    openDrawer('feeTypeDrawer');
}

function filterFeeTypes() {
    const input = document.getElementById('feeTypeSearch');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('feeTypesTable');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const rowText = tr[i].innerText.toLowerCase();
        if (rowText.includes(filter)) {
            tr[i].style.display = '';
        } else {
            tr[i].style.display = 'none';
        }
    }
}
</script>

<?= $this->endSection() ?>
