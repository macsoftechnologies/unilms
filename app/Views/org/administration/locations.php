<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Locations Setup<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Locations</h1>
        <p class="header-subtitle">Manage Campuses, Buildings, Floors, and Rooms.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <div>
        <div class="card">
            <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Add/Edit Location</h2>
            <form action="<?= base_url('org/administration/save-location') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="loc_id">
                
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" id="loc_name" class="form-control" required placeholder="e.g. Block A / Room 101">
                </div>
                
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" id="loc_type" class="form-control" required>
                        <option value="Campus">Campus</option>
                        <option value="Building">Building</option>
                        <option value="Floor">Floor</option>
                        <option value="Room">Room / Classroom</option>
                        <option value="Lab">Laboratory</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Capacity (For Rooms/Labs)</label>
                    <input type="number" min="0" name="capacity" id="loc_cap" class="form-control" value="0">
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Save Location</button>
            </form>
        </div>
    </div>
    
    <div>
        <div class="card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Location Name</th>
                        <th>Type</th>
                        <th>Capacity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($locations as $l): ?>
                        <tr>
                            <td style="font-weight: 600;"><?= esc($l['name']) ?></td>
                            <td><?= esc($l['type']) ?></td>
                            <td><?= $l['capacity'] > 0 ? esc($l['capacity']) : '-' ?></td>
                            <td>
                                <button type="button" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px;" onclick="editLoc(<?= $l['id'] ?>, '<?= esc($l['name'], 'js') ?>', '<?= esc($l['type'], 'js') ?>', <?= $l['capacity'] ?>)">Edit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function editLoc(id, name, type, cap) {
    document.getElementById('loc_id').value = id;
    document.getElementById('loc_name').value = name;
    document.getElementById('loc_type').value = type;
    document.getElementById('loc_cap').value = cap;
}
</script>

<?= $this->endSection() ?>
