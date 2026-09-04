<?= $this->extend('org/layout') ?>

<?= $this->section('page_title') ?>Academic Duties<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Staff Academic Duties</h2>
        <button class="btn btn-primary" onclick="openDrawer('drawerDuty')">
            <i class="fa-solid fa-plus"></i> Assign Duty
        </button>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Staff Name</th>
                    <th>Task</th>
                    <th>Assigned By</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($duties as $d): ?>
                <tr>
                    <td><strong><?= esc($d['staff_name']) ?></strong></td>
                    <td><?= esc($d['task_name']) ?></td>
                    <td><?= esc($d['assigner_name']) ?></td>
                    <td><?= esc($d['due_date']) ?></td>
                    <td>
                        <span class="badge bg-<?= $d['status']=='Completed'?'success':($d['status']=='In Progress'?'warning':'secondary') ?>">
                            <?= esc($d['status']) ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick='editDuty(<?= json_encode($d) ?>)'>Edit</button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($duties)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">No duties assigned yet.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Drawer for Duty Assignment -->
<div class="drawer-overlay" id="drawerDuty" onclick="closeDrawer('drawerDuty')">
    <div class="drawer-content" onclick="event.stopPropagation()">
        <div class="drawer-header">
            <h3 id="drawerDutyTitle">Assign Duty</h3>
            <button class="btn-close" onclick="closeDrawer('drawerDuty')">&times;</button>
        </div>
        <div class="drawer-body">
            <form action="<?= base_url('org/staff/save_duty') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="duty_id">
                
                <div class="form-group">
                    <label>Select Staff</label>
                    <select name="staff_id" id="duty_staff" class="form-control" required>
                        <option value="">-- Select Staff --</option>
                        <?php foreach($staff_list as $sl): ?>
                            <option value="<?= $sl['id'] ?>"><?= esc($sl['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Task Name</label>
                    <input type="text" name="task_name" id="duty_task" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Due Date</label>
                    <input type="date" name="due_date" id="duty_due" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="duty_status" class="form-control">
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                
                <div class="form-group mb-4">
                    <label>Remarks</label>
                    <textarea name="remarks" id="duty_remarks" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Save Duty</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editDuty(d) {
    document.getElementById('duty_id').value = d.id;
    document.getElementById('duty_staff').value = d.staff_id;
    document.getElementById('duty_task').value = d.task_name;
    document.getElementById('duty_due').value = d.due_date;
    document.getElementById('duty_status').value = d.status;
    document.getElementById('duty_remarks').value = d.remarks;
    document.getElementById('drawerDutyTitle').innerText = 'Edit Duty';
    openDrawer('drawerDuty');
}
</script>
<?= $this->endSection() ?>
