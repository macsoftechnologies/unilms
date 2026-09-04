<?= $this->extend('org/layout') ?>

<?= $this->section('page_title') ?>Staff Checklists<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Onboarding & Exit Checklists</h2>
        <button class="btn btn-primary" onclick="openDrawer('drawerCheck')">
            <i class="fa-solid fa-plus"></i> Add Checklist Item
        </button>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Staff Name</th>
                    <th>Type</th>
                    <th>Item Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($checklists as $c): ?>
                <tr>
                    <td><strong><?= esc($c['staff_name']) ?></strong></td>
                    <td><span class="badge bg-<?= $c['checklist_type']=='Joining'?'info':'secondary' ?>"><?= esc($c['checklist_type']) ?></span></td>
                    <td><?= esc($c['item_name']) ?></td>
                    <td>
                        <?php if($c['is_completed']): ?>
                            <span class="text-success"><i class="fa-solid fa-check-circle"></i> Completed</span>
                        <?php else: ?>
                            <span class="text-danger"><i class="fa-solid fa-clock"></i> Pending</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick='editCheck(<?= json_encode($c) ?>)'>Edit</button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($checklists)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">No checklist items found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Drawer -->
<div class="drawer-overlay" id="drawerCheck" onclick="closeDrawer('drawerCheck')">
    <div class="drawer-content" onclick="event.stopPropagation()">
        <div class="drawer-header">
            <h3 id="drawerCheckTitle">Add Checklist Item</h3>
            <button class="btn-close" onclick="closeDrawer('drawerCheck')">&times;</button>
        </div>
        <div class="drawer-body">
            <form action="<?= base_url('org/staff/save_checklist') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="check_id">
                
                <div class="form-group">
                    <label>Select Staff</label>
                    <select name="staff_id" id="check_staff" class="form-control" required>
                        <option value="">-- Select Staff --</option>
                        <?php foreach($staff_list as $sl): ?>
                            <option value="<?= $sl['id'] ?>"><?= esc($sl['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Checklist Type</label>
                    <select name="checklist_type" id="check_type" class="form-control" required>
                        <option value="Joining">Joining / Onboarding</option>
                        <option value="Exit">Exit / Offboarding</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Item Description</label>
                    <input type="text" name="item_name" id="check_item" class="form-control" required placeholder="e.g. ID Card Issued">
                </div>
                
                <div class="form-group form-check form-switch mb-4" style="padding-left: 2.5em;">
                    <input class="form-check-input" type="checkbox" name="is_completed" id="check_completed" value="1">
                    <label class="form-check-label" for="check_completed">Mark as Completed</label>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Save Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editCheck(c) {
    document.getElementById('check_id').value = c.id;
    document.getElementById('check_staff').value = c.staff_id;
    document.getElementById('check_type').value = c.checklist_type;
    document.getElementById('check_item').value = c.item_name;
    document.getElementById('check_completed').checked = c.is_completed == 1;
    document.getElementById('drawerCheckTitle').innerText = 'Edit Checklist Item';
    openDrawer('drawerCheck');
}
</script>
<?= $this->endSection() ?>
