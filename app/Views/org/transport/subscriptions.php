<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Transport Subscriptions<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div class="title-area">
        <h1>Transport Subscriptions</h1>
        <p>Manage student bus/van registrations</p>
    </div>
    <div class="action-area">
        <button type="button" class="btn btn-primary" onclick="openModal('addSubscriptionModal')">
            <i class="fa-solid fa-plus"></i> New Subscription
        </button>
    </div>
</div>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Route</th>
                <th>Halt</th>
                <th>Start Date</th>
                <th>Status</th>
                <th style="width: 120px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($subscriptions as $s): ?>
                <tr>
                    <td style="font-weight: 500;"><i class="fa-solid fa-user" style="color: var(--text-muted); margin-right: 4px;"></i> <?= esc($s['full_name']) ?></td>
                    <td><span class="badge" style="background: var(--light-blue); color: var(--primary);"><?= esc($s['route_name']) ?></span></td>
                    <td><?= esc($s['halt_name']) ?></td>
                    <td><?= date('d/m/Y', strtotime($s['start_date'])) ?></td>
                    <td>
                        <?php if($s['status'] == 'Active'): ?>
                            <span class="badge" style="background: rgba(16,185,129,0.1); color: var(--success);">Active</span>
                        <?php else: ?>
                            <span class="badge" style="background: rgba(107,114,128,0.1); color: var(--text-muted);">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-outline" style="padding: 4px 8px; font-size: 12px;" onclick="editSubscription(<?= htmlspecialchars(json_encode($s)) ?>)">Edit</button>
                        <a href="<?= base_url('org/transport/delete_subscription/' . ($s['uuid'] ?? $s['id'])) ?>" class="btn btn-outline" style="padding: 4px 8px; font-size: 12px; color: var(--danger); border-color: var(--danger);" onclick="return confirm('Delete this subscription?')">Del</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($subscriptions)): ?>
                <tr><td colspan="6" style="text-align: center;">No student subscriptions found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="addSubscriptionModal" class="modal" style="display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div class="modal-content card" style="width: 500px; max-width: 90%;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
            <h2 id="modalTitle" style="margin: 0;">New Subscription</h2>
            <button type="button" style="background: none; border: none; font-size: 20px; cursor: pointer;" onclick="closeModal('addSubscriptionModal')">&times;</button>
        </div>
        <form action="<?= base_url('org/transport/subscriptions') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="subscription_id" value="">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group" style="grid-column: span 2;">
                    <label>Student</label>
                    <select name="student_id" id="student_id" class="form-control" required>
                        <option value="">Select Student</option>
                        <?php foreach($students as $st): ?>
                            <option value="<?= $st['id'] ?>"><?= esc($st['full_name']) ?> (<?= esc($st['email']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Route</label>
                    <select name="route_id" id="route_id" class="form-control" required>
                        <option value="">Select Route</option>
                        <?php foreach($routes as $r): ?>
                            <option value="<?= $r['id'] ?>"><?= esc($r['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Halt</label>
                    <select name="halt_id" id="halt_id" class="form-control" required>
                        <option value="">Select Halt</option>
                        <?php foreach($halts as $h): ?>
                            <option value="<?= $h['id'] ?>"><?= esc($h['name']) ?> (<?= esc($h['distance_km']) ?>km)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div style="text-align: right; margin-top: 24px;">
                <button type="button" class="btn btn-outline" onclick="closeModal('addSubscriptionModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Subscription</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
    document.getElementById('subscription_id').value = '';
    document.getElementById('modalTitle').innerText = 'New Subscription';
    document.querySelector('#addSubscriptionModal form').reset();
}
function editSubscription(s) {
    document.getElementById('subscription_id').value = s.id;
    document.getElementById('student_id').value = s.student_id;
    document.getElementById('route_id').value = s.route_id;
    document.getElementById('halt_id').value = s.halt_id;
    document.getElementById('start_date').value = s.start_date;
    document.getElementById('status').value = s.status;
    document.getElementById('modalTitle').innerText = 'Edit Subscription';
    openModal('addSubscriptionModal');
}
</script>

<?= $this->endSection() ?>
