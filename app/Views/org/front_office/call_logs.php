<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Phone Call Logs<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <div>
            <h2><i class="fa-solid fa-phone"></i> Phone Call Communications</h2>
            <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 13px;">Inward and outward campus phone communication registry with followup scheduling.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Record Phone Call</button>
            <a href="<?= base_url('org/front-office') ?>" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Hub</a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card" style="padding: 14px 20px; margin-bottom: 20px; border-radius: 10px;">
        <form method="GET" action="<?= base_url('org/front-office/calls') ?>" style="display: flex; gap: 12px; align-items: center;">
            <div style="min-width: 180px;">
                <select name="type" class="form-control" style="height: 38px;">
                    <option value="">All Call Types</option>
                    <option value="Incoming" <?= $selected_type === 'Incoming' ? 'selected' : '' ?>>Incoming Calls</option>
                    <option value="Outgoing" <?= $selected_type === 'Outgoing' ? 'selected' : '' ?>>Outgoing Calls</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="height: 38px;"><i class="fa-solid fa-filter"></i> Filter</button>
            <a href="<?= base_url('org/front-office/calls') ?>" class="btn btn-outline" style="height: 38px;">Reset</a>
        </form>
    </div>

    <!-- Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Caller / Contact Name</th>
                    <th>Phone Number</th>
                    <th>Purpose</th>
                    <th>Duration</th>
                    <th>Result</th>
                    <th>Follow-up</th>
                    <th>Date & Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($calls)): foreach($calls as $c): ?>
                <tr>
                    <td>
                        <span class="badge" style="background: <?= $c['call_type'] === 'Incoming' ? '#10b98115' : '#3b82f615' ?>; color: <?= $c['call_type'] === 'Incoming' ? '#10b981' : '#3b82f6' ?>;">
                            <i class="fa-solid <?= $c['call_type'] === 'Incoming' ? 'fa-phone-arrow-down-left' : 'fa-phone-arrow-up-right' ?> me-1"></i> <?= esc($c['call_type']) ?>
                        </span>
                    </td>
                    <td><strong><?= esc($c['caller_name']) ?></strong></td>
                    <td><?= esc($c['phone_number']) ?></td>
                    <td><?= esc($c['purpose'] ?: 'General') ?></td>
                    <td><?= esc($c['call_duration'] ?: '-') ?></td>
                    <td>
                        <span class="badge" style="background: rgba(100, 116, 139, 0.1); color: #475569;">
                            <?= esc($c['call_result']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if($c['followup_required'] && $c['followup_date']): ?>
                            <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; font-weight: 600;">
                                <i class="fa-solid fa-bell me-1"></i> <?= date('d/m/Y', strtotime($c['followup_date'])) ?>
                            </span>
                        <?php else: ?>
                            <span style="color: var(--text-muted); font-size: 12px;">None</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size: 12px; color: var(--text-muted);"><?= date('d M, H:i', strtotime($c['created_at'])) ?></td>
                    <td>
                        <a href="<?= base_url('org/front-office/calls/delete/' . ($c['uuid'] ?? $c['id'])) ?>" class="btn-icon text-danger" onclick="return confirm('Delete this call log?')" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="9" style="text-align: center; padding: 30px; color: var(--text-muted);">No phone communications logged yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Record Call Modal -->
<div class="drawer-overlay" id="callModal" style="display: none;">
    <div class="drawer-content" style="max-width: 500px;">
        <form action="<?= base_url('org/front-office/calls/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 700;"><i class="fa-solid fa-phone me-2" style="color: #0ea5e9;"></i> Record Phone Call</h3>
                <button type="button" class="btn-close" onclick="closeModal()" style="background: none; border: none; font-size: 18px; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 20px;">
                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Call Direction *</label>
                    <select name="call_type" class="form-control" required>
                        <option value="Incoming">Incoming Call</option>
                        <option value="Outgoing">Outgoing Call</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Caller / Person Name *</label>
                    <input type="text" name="caller_name" class="form-control" required placeholder="Full name">
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Phone Number *</label>
                    <input type="text" name="phone_number" class="form-control" required placeholder="10-digit number">
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Purpose of Call</label>
                    <input type="text" name="purpose" class="form-control" placeholder="Admissions inquiry, verification, etc.">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Duration</label>
                        <input type="text" name="call_duration" class="form-control" placeholder="e.g. 5 mins">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Call Result</label>
                        <select name="call_result" class="form-control">
                            <option value="Answered">Answered</option>
                            <option value="Missed">Missed</option>
                            <option value="Busy">Busy</option>
                            <option value="Callback Required">Callback Required</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="followup_required" value="1" id="fupToggle" onchange="document.getElementById('fupDateBox').style.display = this.checked ? 'block' : 'none'">
                        Follow-up Required?
                    </label>
                </div>

                <div class="form-group" id="fupDateBox" style="display: none; margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Follow-up Date</label>
                    <input type="date" name="followup_date" class="form-control">
                </div>

                <div class="form-group">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Call Summary / Remarks</label>
                    <textarea name="remarks" class="form-control" rows="2" placeholder="Notes from phone conversation"></textarea>
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Save Call Log</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() { document.getElementById('callModal').style.display = 'flex'; }
    function closeModal() { document.getElementById('callModal').style.display = 'none'; }
</script>
<?= $this->endSection() ?>
