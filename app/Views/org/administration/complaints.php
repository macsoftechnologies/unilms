<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Complaints & Grievances<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Complaints & Grievances</h2>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Submitted By</th>
                    <th>Role</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($complaints)): foreach($complaints as $c): ?>
                <tr>
                    <td><strong><?= esc($c['subject']) ?></strong></td>
                    <td><?= esc($c['user_name'] ?? 'Unknown User') ?></td>
                    <td><span class="badge badge-secondary"><?= esc(ucfirst($c['role'] ?? 'N/A')) ?></span></td>
                    <td><?= esc(date('d/m/Y', strtotime($c['created_at']))) ?></td>
                    <td>
                        <?php if($c['status'] == 'Open'): ?>
                            <span class="badge badge-danger">Open</span>
                        <?php elseif($c['status'] == 'In Progress'): ?>
                            <span class="badge badge-warning">In Progress</span>
                        <?php elseif($c['status'] == 'Resolved'): ?>
                            <span class="badge badge-success">Resolved</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Closed</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-icon" onclick='viewComplaint(<?= json_encode($c) ?>)'><i class="fa-solid fa-eye"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6">No complaints found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Modal -->
<div class="drawer-overlay" id="viewModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/administration/update_complaint_status') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="form_id">
            <div class="drawer-header">
                <h3>Complaint Details</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Subject</label>
                    <p id="view_subject" style="font-weight: 600; padding: 10px; background: rgba(0,0,0,0.02); border-radius: 6px;"></p>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <p id="view_description" style="padding: 10px; background: rgba(0,0,0,0.02); border-radius: 6px; white-space: pre-wrap;"></p>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="form_status" class="form-control">
                        <option value="Open">Open</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Resolved">Resolved</option>
                        <option value="Closed">Closed</option>
                    </select>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Status</button>
            </div>
        </form>
    </div>
</div>

<script>
function viewComplaint(data) {
    $('#form_id').val(data.id);
    $('#view_subject').text(data.subject);
    $('#view_description').text(data.description);
    $('#form_status').val(data.status);
    $('#viewModal').addClass('active');
}
function closeModal() {
    $('#viewModal').removeClass('active');
}
</script>
<?= $this->endSection() ?>
