<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Parent Accounts & Wards<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="header-banner" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
    <div>
        <h1 class="header-title" style="margin: 0; font-size: 22px; font-weight: 700; color: var(--text-primary);"><i class="fa-solid fa-people-roof" style="color: #7C3AED;"></i> Parent Accounts & Student Wards</h1>
        <p class="header-subtitle" style="margin: 4px 0 0; color: var(--text-secondary); font-size: 13.5px;">Manage parent portal credentials, contact directory, and mapping to enrolled student wards.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="<?= base_url('org/students') ?>" class="btn btn-outline" style="padding: 10px 16px; border-radius: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-arrow-left"></i> Student Directory
        </a>
        <button type="button" class="btn btn-primary" onclick="openParentModal()" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); border: none; padding: 10px 18px; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(124, 58, 237, 0.25);">
            <i class="fa-solid fa-user-plus"></i> Link Parent Account
        </button>
    </div>
</div>

<!-- Parents Table Card -->
<div class="card" style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 14px; padding: 22px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
    <div class="table-responsive" style="overflow-x: auto;">
        <table class="data-table" style="width: 100%;">
            <thead>
                <tr style="background: var(--bg-main, #f8fafc);">
                    <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Parent Name</th>
                    <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Contact Information</th>
                    <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Linked Student Ward</th>
                    <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Roll Number</th>
                    <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Relationship</th>
                    <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($parents)): foreach($parents as $p): ?>
                <tr>
                    <td style="padding: 14px 16px; font-weight: 700; color: var(--text-primary); font-size: 14px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 34px; height: 34px; border-radius: 50%; background: rgba(124, 58, 237, 0.1); color: #7C3AED; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;">
                                <?= strtoupper(substr($p['parent_name'] ?? 'P', 0, 1)) ?>
                            </div>
                            <span><?= esc($p['parent_name']) ?></span>
                        </div>
                    </td>
                    <td style="padding: 14px 16px;">
                        <div style="font-size: 13px; color: var(--text-primary);"><i class="fa-regular fa-envelope me-1" style="color: #7C3AED; font-size: 11px;"></i> <?= esc($p['parent_email']) ?></div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;"><i class="fa-solid fa-phone me-1" style="color: #7C3AED; font-size: 10px;"></i> <?= esc($p['parent_phone'] ?? 'N/A') ?></div>
                    </td>
                    <td style="padding: 14px 16px; font-weight: 600; color: var(--text-primary);">
                        <?= esc($p['first_name'] . ' ' . $p['last_name']) ?>
                    </td>
                    <td style="padding: 14px 16px;">
                        <a href="<?= base_url('org/students/profile/' . ($p['student_uuid'] ?? $p['student_id'])) ?>" style="color: #7C3AED; text-decoration: none; font-weight: 700; background: rgba(124, 58, 237, 0.08); padding: 4px 10px; border-radius: 6px;">
                            <?= esc($p['roll_number']) ?>
                        </a>
                    </td>
                    <td style="padding: 14px 16px;">
                        <span class="badge" style="background: rgba(124, 58, 237, 0.1); color: #7C3AED; font-weight: 700; padding: 4px 10px; border-radius: 6px;">
                            <?= esc($p['relationship'] ?? 'Parent') ?>
                        </span>
                    </td>
                    <td style="padding: 14px 16px; text-align: right;">
                        <a href="<?= base_url('org/students/unlink-parent/' . ($p['uuid'] ?? $p['id'])) ?>" class="btn-icon text-danger" style="background: none; border: 1px solid var(--border-color); padding: 6px 10px; border-radius: 6px; color: #DC2626; cursor: pointer; display: inline-flex;" onclick="return confirm('Unlink this parent from student?')" title="Unlink Parent">
                            <i class="fa-solid fa-unlink"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 48px 20px; color: var(--text-secondary);">
                        No parent accounts linked yet. Click "Link Parent Account" to link a parent.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Link Parent Drawer Modal -->
<div class="drawer-overlay" id="parentModal">
    <div class="drawer-content" style="max-width: 500px; width: 100%;">
        <form action="<?= base_url('org/students/save-parent') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); color: #fff; padding: 18px 22px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255, 255, 255, 0.2); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                        <i class="fa-solid fa-people-roof"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 17px; font-weight: 700; color: #fff;">Link Parent Account</h3>
                </div>
                <button type="button" class="btn-close" onclick="closeParentModal()" style="color: #fff; background: none; border: none; font-size: 20px; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 22px;">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Parent Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Ramesh Patel" style="font-size: 13.5px;">
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Parent Email Address (Login ID) <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" required placeholder="ramesh.patel@example.com" style="font-size: 13.5px;">
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Parent Mobile Number</label>
                    <input type="tel" name="phone" class="form-control" placeholder="10-digit mobile number" style="font-size: 13.5px;">
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Select Student Ward <span class="text-danger">*</span></label>
                    <select name="student_id" class="form-control" required style="font-size: 13.5px;">
                        <option value="">-- Choose Student --</option>
                        <?php foreach($students as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= esc($s['roll_number']) ?> - <?= esc($s['first_name'] . ' ' . $s['last_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Relationship <span class="text-danger">*</span></label>
                    <select name="relationship" class="form-control" required style="font-size: 13.5px;">
                        <option value="Father">Father</option>
                        <option value="Mother">Mother</option>
                        <option value="Guardian">Legal Guardian</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div style="font-size: 12px; color: #7C3AED; background: rgba(124, 58, 237, 0.08); padding: 12px; border-radius: 8px; border: 1px solid rgba(124, 58, 237, 0.15);">
                    <i class="fa-solid fa-circle-info me-1"></i> Default portal login password for the parent will be set to: <strong>password</strong>.
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeParentModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); border: none; padding: 10px 20px; font-weight: 700;">
                    <i class="fa-solid fa-check me-1"></i> Link & Save
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openParentModal() {
        $('#parentModal').addClass('active');
    }
    function closeParentModal() {
        $('#parentModal').removeClass('active');
    }
</script>
<?= $this->endSection() ?>
