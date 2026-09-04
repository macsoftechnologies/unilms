<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Visitors Book<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <div>
            <h2><i class="fa-solid fa-person-walking"></i> Campus Visitors Book</h2>
            <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 13px;">Visitor entry check-in, exit timestamping, and gate pass management.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Check-in Visitor</button>
            <a href="<?= base_url('org/front-office') ?>" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Hub</a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card" style="padding: 16px 20px; margin-bottom: 20px; border-radius: 10px;">
        <form method="GET" action="<?= base_url('org/front-office/visitors') ?>" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <div>
                <input type="date" name="date" value="<?= esc($selected_date) ?>" class="form-control" style="height: 38px;">
            </div>
            <div>
                <select name="status" class="form-control" style="height: 38px;">
                    <option value="">All Visitors</option>
                    <option value="inside" <?= $selected_status === 'inside' ? 'selected' : '' ?>>Currently Inside Campus</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="height: 38px;"><i class="fa-solid fa-filter"></i> Filter</button>
            <a href="<?= base_url('org/front-office/visitors') ?>" class="btn btn-outline" style="height: 38px;"><i class="fa-solid fa-rotate-left"></i> Today</a>
        </form>
    </div>

    <!-- Visitors Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Pass No.</th>
                    <th>Visitor Name</th>
                    <th>Type</th>
                    <th>Purpose</th>
                    <th>Person / Dept To Meet</th>
                    <th>In Time</th>
                    <th>Out Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($visitors)): foreach($visitors as $v): ?>
                <tr>
                    <td><span class="badge" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; font-weight: 700;"><?= esc($v['pass_number']) ?></span></td>
                    <td>
                        <strong><?= esc($v['visitor_name']) ?></strong>
                        <div style="font-size: 11px; color: var(--text-muted);"><i class="fa-solid fa-phone me-1"></i> <?= esc($v['phone']) ?></div>
                    </td>
                    <td><span class="badge" style="background: rgba(100, 116, 139, 0.1); color: #475569;"><?= esc($v['visitor_type']) ?></span></td>
                    <td><?= esc($v['purpose']) ?></td>
                    <td>
                        <div><?= esc($v['person_to_meet'] ?: 'General') ?></div>
                        <span style="font-size: 11px; color: var(--text-muted);"><?= esc($v['department_name'] ?? '') ?></span>
                    </td>
                    <td><?= date('d M, H:i', strtotime($v['in_time'])) ?></td>
                    <td>
                        <?php if($v['out_time']): ?>
                            <span style="color: #64748b; font-size: 13px;"><?= date('H:i', strtotime($v['out_time'])) ?></span>
                        <?php else: ?>
                            <a href="<?= base_url('org/front-office/visitors/checkout/' . $v['id']) ?>" class="btn btn-outline" style="padding: 3px 8px; font-size: 11px; color: #ef4444; border-color: #ef4444;" onclick="return confirm('Check out visitor?')">
                                <i class="fa-solid fa-right-from-bracket"></i> Check-out
                            </a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="<?= base_url('org/front-office/visitors/print-pass/' . $v['id']) ?>" target="_blank" class="btn-icon" title="Print Gate Pass">
                                <i class="fa-solid fa-print"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        No visitors found for this date/filter.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Check-in Modal -->
<div class="drawer-overlay" id="visitorModal" style="display: none;">
    <div class="drawer-content" style="max-width: 540px;">
        <form action="<?= base_url('org/front-office/visitors/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 700;"><i class="fa-solid fa-person-walking me-2" style="color: #4f46e5;"></i> Visitor Check-in</h3>
                <button type="button" class="btn-close" onclick="closeModal()" style="background: none; border: none; font-size: 18px; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div class="form-group" style="grid-column: span 2;">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Visitor Full Name *</label>
                        <input type="text" name="visitor_name" class="form-control" required placeholder="Full Name">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Phone Number *</label>
                        <input type="text" name="phone" class="form-control" required placeholder="10-digit number">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Visitor Type</label>
                        <select name="visitor_type" class="form-control">
                            <option value="Guest">Guest</option>
                            <option value="Parent">Parent</option>
                            <option value="Vendor">Vendor</option>
                            <option value="Student">Student</option>
                            <option value="Government Officer">Govt Officer</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Purpose of Visit *</label>
                        <input type="text" name="purpose" class="form-control" required placeholder="Reason for visit">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Person to Meet</label>
                        <input type="text" name="person_to_meet" class="form-control" placeholder="Staff / Officer name">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Department</label>
                        <select name="department_id" class="form-control">
                            <option value="">Any Department</option>
                            <?php foreach($departments as $d): ?>
                                <option value="<?= $d['id'] ?>"><?= esc($d['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">ID Proof / Badge No.</label>
                        <input type="text" name="id_proof" class="form-control" placeholder="Aadhaar / DL / ID #">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Remarks / Notes</label>
                        <input type="text" name="remarks" class="form-control" placeholder="Optional notes">
                    </div>
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Check-in Visitor</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('visitorModal').style.display = 'flex';
    }
    function closeModal() {
        document.getElementById('visitorModal').style.display = 'none';
    }
</script>
<?= $this->endSection() ?>
