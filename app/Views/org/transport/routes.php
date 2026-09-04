<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Transport Routes<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div class="title-area">
        <h1>Transport Routes</h1>
        <p>Define bus routes and paths</p>
    </div>
    <div class="action-area">
        <button type="button" class="btn btn-primary" onclick="openModal('addRouteModal')">
            <i class="fa-solid fa-plus"></i> Add Route
        </button>
    </div>
</div>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Route Name</th>
                <th>Start Point</th>
                <th>End Point</th>
                <th style="width: 120px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($routes as $r): ?>
                <tr>
                    <td style="font-weight: 500; color: var(--primary);"><?= esc($r['name']) ?></td>
                    <td><?= esc($r['start_point']) ?></td>
                    <td><?= esc($r['end_point']) ?></td>
                    <td>
                        <button class="btn btn-outline" style="padding: 4px 8px; font-size: 12px;" onclick="editRoute(<?= htmlspecialchars(json_encode($r)) ?>)">Edit</button>
                        <a href="<?= base_url('org/transport/delete_route/'.$r['id']) ?>" class="btn btn-outline" style="padding: 4px 8px; font-size: 12px; color: var(--danger); border-color: var(--danger);" onclick="return confirm('Delete this route?')">Del</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($routes)): ?>
                <tr><td colspan="4" style="text-align: center;">No routes defined.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="addRouteModal" class="modal" style="display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div class="modal-content card" style="width: 400px; max-width: 90%;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
            <h2 id="modalTitle" style="margin: 0;">Add Route</h2>
            <button type="button" style="background: none; border: none; font-size: 20px; cursor: pointer;" onclick="closeModal('addRouteModal')">&times;</button>
        </div>
        <form action="<?= base_url('org/transport/routes') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="route_id" value="">
            
            <div class="form-group" style="margin-bottom: 16px;">
                <label>Route Name</label>
                <input type="text" name="name" id="name" class="form-control" required placeholder="e.g. Route A">
            </div>
            
            <div class="form-group" style="margin-bottom: 16px;">
                <label>Start Point</label>
                <input type="text" name="start_point" id="start_point" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>End Point</label>
                <input type="text" name="end_point" id="end_point" class="form-control" required>
            </div>

            <div style="text-align: right; margin-top: 24px;">
                <button type="button" class="btn btn-outline" onclick="closeModal('addRouteModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Route</button>
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
    document.getElementById('route_id').value = '';
    document.getElementById('modalTitle').innerText = 'Add Route';
    document.querySelector('#addRouteModal form').reset();
}
function editRoute(r) {
    document.getElementById('route_id').value = r.id;
    document.getElementById('name').value = r.name;
    document.getElementById('start_point').value = r.start_point;
    document.getElementById('end_point').value = r.end_point;
    document.getElementById('modalTitle').innerText = 'Edit Route';
    openModal('addRouteModal');
}
</script>

<?= $this->endSection() ?>
