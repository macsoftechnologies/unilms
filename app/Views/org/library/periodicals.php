<?= $this->extend('org/layout') ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Periodicals & Journals</h2>
        <button class="btn btn-primary" onclick="openModal()">
            <i class="fa-solid fa-plus"></i> Add Periodical
        </button>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>ISSN</th>
                    <th>Frequency</th>
                    <th>Valid Till</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($periodicals as $per): ?>
                <tr>
                    <td><?= esc($per['title']) ?></td>
                    <td><?= esc($per['issn']) ?></td>
                    <td><?= esc($per['frequency']) ?></td>
                    <td><?= esc($per['valid_till']) ?></td>
                    <td>
                        <span class="badge bg-<?= $per['status'] == 'active' ? 'success' : 'danger' ?>">
                            <?= ucfirst($per['status']) ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick='editPeriodical(<?= json_encode($per) ?>)'>Edit</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<div class="drawer-overlay" id="periodicalModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/library/periodicals') ?>" method="post">
            <?= csrf_field() ?>
            <div class="drawer-header">
                <h3 id="modalTitle">Add Periodical</h3>
                <button type="button" class="close-btn" onclick="closeModal()"><i class="fa-solid fa-times"></i></button>
            </div>
            <div class="drawer-body">
                <input type="hidden" name="id" id="per_id">
                    
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>ISSN</label>
                            <input type="text" name="issn" id="issn" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Frequency</label>
                            <select name="frequency" id="frequency" class="form-select">
                                <option value="Daily">Daily</option>
                                <option value="Weekly">Weekly</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Quarterly">Quarterly</option>
                                <option value="Yearly">Yearly</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Subscription Date</label>
                            <input type="date" name="subscription_date" id="subscription_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Valid Till</label>
                            <input type="date" name="valid_till" id="valid_till" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label>Copies</label>
                            <input type="number" min="0" name="copies" id="copies" class="form-control" value="1">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Periodical</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    resetForm();
    document.getElementById('periodicalModal').classList.add('active');
}

function closeModal() {
    document.getElementById('periodicalModal').classList.remove('active');
}
function resetForm() {
    document.getElementById('per_id').value = '';
    document.getElementById('title').value = '';
    document.getElementById('issn').value = '';
    document.getElementById('frequency').value = 'Monthly';
    document.getElementById('subscription_date').value = '';
    document.getElementById('valid_till').value = '';
    document.getElementById('copies').value = '1';
    document.getElementById('status').value = 'active';
    document.getElementById('modalTitle').innerText = 'Add Periodical';
}

function editPeriodical(per) {
    document.getElementById('per_id').value = per.id;
    document.getElementById('title').value = per.title;
    document.getElementById('issn').value = per.issn;
    document.getElementById('frequency').value = per.frequency;
    document.getElementById('subscription_date').value = per.subscription_date;
    document.getElementById('valid_till').value = per.valid_till;
    document.getElementById('copies').value = per.copies;
    document.getElementById('status').value = per.status;
    document.getElementById('modalTitle').innerText = 'Edit Periodical';
    document.getElementById('periodicalModal').classList.add('active');
}
</script>
<?= $this->endSection() ?>
