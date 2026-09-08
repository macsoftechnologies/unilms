<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Transport Vehicles<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div class="title-area">
        <h1>Transport Vehicles</h1>
        <p>Manage the college transportation fleet</p>
    </div>
    <div class="action-area">
        <button type="button" class="btn btn-primary" onclick="openModal('addVehicleModal')">
            <i class="fa-solid fa-plus"></i> Add Vehicle
        </button>
    </div>
</div>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Vehicle No</th>
                <th>Type</th>
                <th>Capacity</th>
                <th>Model Year</th>
                <th>Fuel Type</th>
                <th>Owner Status</th>
                <th style="width: 120px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($vehicles as $v): ?>
                <tr>
                    <td style="font-weight: 500; color: var(--primary);"><?= esc($v['vehicle_no']) ?></td>
                    <td><?= esc($v['type']) ?></td>
                    <td><?= esc($v['capacity']) ?> Seats</td>
                    <td><?= esc($v['model_year'] ?? '-') ?></td>
                    <td><?= esc($v['fuel_type'] ?? '-') ?></td>
                    <td>
                        <?php if($v['owner_status'] == 'College'): ?>
                            <span style="color: green; font-weight: 600;"><i class="fa-solid fa-building"></i> College</span>
                        <?php else: ?>
                            <span style="color: orange; font-weight: 600;"><i class="fa-solid fa-handshake"></i> Hired</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-outline" style="padding: 4px 8px; font-size: 12px;" onclick="editVehicle(<?= htmlspecialchars(json_encode($v)) ?>)">Edit</button>
                        <a href="<?= base_url('org/transport/delete_vehicle/' . ($v['uuid'] ?? $v['id'])) ?>" class="btn btn-outline" style="padding: 4px 8px; font-size: 12px; color: var(--danger); border-color: var(--danger);" onclick="return confirm('Delete this vehicle?')">Del</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($vehicles)): ?>
                <tr><td colspan="7" style="text-align: center;">No vehicles found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="addVehicleModal" class="modal" style="display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div class="modal-content card" style="width: 500px; max-width: 90%;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
            <h2 id="modalTitle" style="margin: 0;">Add Vehicle</h2>
            <button type="button" style="background: none; border: none; font-size: 20px; cursor: pointer;" onclick="closeModal('addVehicleModal')">&times;</button>
        </div>
        <form action="<?= base_url('org/transport/vehicles') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="vehicle_id" value="">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label>Vehicle Number</label>
                    <input type="text" name="vehicle_no" id="vehicle_no" class="form-control" required placeholder="e.g. MH12 AB 1234">
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="Bus">Bus</option>
                        <option value="Van">Van</option>
                        <option value="Car">Car</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Capacity (Seats)</label>
                    <input type="number" name="capacity" id="capacity" class="form-control" required min="1">
                </div>
                <div class="form-group">
                    <label>Model Year</label>
                    <input type="text" name="model_year" id="model_year" class="form-control" placeholder="e.g. 2018">
                </div>
                <div class="form-group">
                    <label>Fuel Type</label>
                    <select name="fuel_type" id="fuel_type" class="form-control">
                        <option value="Diesel">Diesel</option>
                        <option value="Petrol">Petrol</option>
                        <option value="CNG">CNG</option>
                        <option value="Electric">Electric</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Owner Status</label>
                    <select name="owner_status" id="owner_status" class="form-control" required>
                        <option value="College">College</option>
                        <option value="Hired">Hired</option>
                    </select>
                </div>
            </div>

            <div style="text-align: right; margin-top: 24px;">
                <button type="button" class="btn btn-outline" onclick="closeModal('addVehicleModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Vehicle</button>
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
    document.getElementById('vehicle_id').value = '';
    document.getElementById('modalTitle').innerText = 'Add Vehicle';
    document.querySelector('#addVehicleModal form').reset();
}
function editVehicle(v) {
    document.getElementById('vehicle_id').value = v.id;
    document.getElementById('vehicle_no').value = v.vehicle_no;
    document.getElementById('type').value = v.type;
    document.getElementById('capacity').value = v.capacity;
    document.getElementById('model_year').value = v.model_year;
    document.getElementById('fuel_type').value = v.fuel_type;
    document.getElementById('owner_status').value = v.owner_status;
    document.getElementById('modalTitle').innerText = 'Edit Vehicle';
    openModal('addVehicleModal');
}
</script>

<?= $this->endSection() ?>
