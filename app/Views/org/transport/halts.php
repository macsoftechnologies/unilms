<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Transport Halts<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div class="title-area">
        <h1>Transport Halts</h1>
        <p>Manage stops and pricing along bus routes</p>
    </div>
    <div class="action-area">
        <button type="button" class="btn btn-primary" onclick="openModal('addHaltModal')">
            <i class="fa-solid fa-plus"></i> Add Halt
        </button>
    </div>
</div>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Route</th>
                <th>Halt Name</th>
                <th>Distance (km)</th>
                <th>Annual Fee</th>
                <th style="width: 120px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($halts as $h): ?>
                <tr>
                    <td><span class="badge" style="background: var(--light-blue); color: var(--primary);"><?= esc($h['route_name']) ?></span></td>
                    <td style="font-weight: 500;"><?= esc($h['name']) ?></td>
                    <td><?= esc($h['distance_km']) ?> km</td>
                    <td style="font-weight: 600; color: var(--success);">₹<?= number_format($h['annual_fee'], 2) ?></td>
                    <td>
                        <button class="btn btn-outline" style="padding: 4px 8px; font-size: 12px;" onclick="editHalt(<?= htmlspecialchars(json_encode($h)) ?>)">Edit</button>
                        <a href="<?= base_url('org/transport/delete_halt/' . ($h['uuid'] ?? $h['id'])) ?>" class="btn btn-outline" style="padding: 4px 8px; font-size: 12px; color: var(--danger); border-color: var(--danger);" onclick="return confirm('Delete this halt?')">Del</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($halts)): ?>
                <tr><td colspan="5" style="text-align: center;">No halts defined.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="addHaltModal" class="modal" style="display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div class="modal-content card" style="width: 400px; max-width: 90%;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
            <h2 id="modalTitle" style="margin: 0;">Add Halt</h2>
            <button type="button" style="background: none; border: none; font-size: 20px; cursor: pointer;" onclick="closeModal('addHaltModal')">&times;</button>
        </div>
        <form action="<?= base_url('org/transport/halts') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="halt_id" value="">
            
            <div class="form-group" style="margin-bottom: 16px;">
                <label>Route</label>
                <select name="route_id" id="route_id" class="form-control" required>
                    <option value="">Select Route</option>
                    <?php foreach($routes as $r): ?>
                        <option value="<?= $r['id'] ?>"><?= esc($r['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label>Halt Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            
            <div class="form-group" style="margin-bottom: 16px;">
                <label>Distance from College (km)</label>
                <input type="number" min="0" step="0.01" name="distance_km" id="distance_km" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Annual Fee ($)</label>
                <input type="number" min="0" step="0.01" name="annual_fee" id="annual_fee" class="form-control" required>
            </div>

            <div style="text-align: right; margin-top: 24px;">
                <button type="button" class="btn btn-outline" onclick="closeModal('addHaltModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Halt</button>
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
    document.getElementById('halt_id').value = '';
    document.getElementById('modalTitle').innerText = 'Add Halt';
    document.querySelector('#addHaltModal form').reset();
}
function editHalt(h) {
    document.getElementById('halt_id').value = h.id;
    document.getElementById('route_id').value = h.route_id;
    document.getElementById('name').value = h.name;
    document.getElementById('distance_km').value = h.distance_km;
    document.getElementById('annual_fee').value = h.annual_fee;
    document.getElementById('modalTitle').innerText = 'Edit Halt';
    openModal('addHaltModal');
}
</script>

<?= $this->endSection() ?>
