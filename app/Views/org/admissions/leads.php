<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Leads CRM<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <div>
            <h2>Manage Leads CRM</h2>
            <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: #10b981; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; font-size: 11px; margin-top: 4px;">
                <span style="width: 7px; height: 7px; background: #10b981; border-radius: 50%; display: inline-block; box-shadow: 0 0 6px #10b981;"></span>
                <span id="leads_live_txt">Live Sync Active</span>
            </span>
        </div>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Add New Lead</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Lead Name</th>
                    <th>Contact Info</th>
                    <th>Program Interest</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Follow-up Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="leads_table_body">
                <?php if(!empty($leads)): foreach($leads as $lead): ?>
                <tr>
                    <td><strong><?= esc($lead['full_name']) ?></strong></td>
                    <td>
                        <?= esc($lead['phone']) ?><br>
                        <small style="color:var(--text-muted)"><?= esc($lead['email']) ?></small>
                    </td>
                    <td><?= esc($lead['program_name']) ?: '<span style="color:var(--text-muted)">Undecided</span>' ?></td>
                    <td>
                        <span class="badge badge-<?= $lead['status'] == 'New' ? 'primary' : ($lead['status'] == 'Contacted' ? 'warning' : 'success') ?>">
                            <?= esc($lead['status']) ?>
                        </span>
                    </td>
                    <td><?= esc($lead['assigned_name']) ?: '<span style="color:var(--text-muted)">Unassigned</span>' ?></td>
                    <td><?= esc($lead['follow_up_date']) ?: '-' ?></td>
                    <td>
                        <div class="action-buttons">
                            <?php if($lead['status'] != 'Converted'): ?>
                            <a href="<?= base_url('org/admissions/leads/convert/' . ($lead['uuid'] ?? $lead['id'])) ?>" class="btn-icon text-success" title="Convert to Application" onclick="return confirm('Convert this lead into a formal application?')"><i class="fa-solid fa-file-signature"></i></a>
                            <?php endif; ?>
                            <button class="btn-icon" onclick='editLead(<?= json_encode($lead) ?>)' title="Edit"><i class="fa-solid fa-pen"></i></button>
                            <a href="<?= base_url('org/admissions/leads/delete/' . ($lead['uuid'] ?? $lead['id'])) ?>" class="btn-icon text-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this lead?')"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="7">No leads found in the CRM.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Modal -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/admissions/leads/save') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="form_id">
            <div class="drawer-header">
                <h3 id="modal_title">Add New Lead</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" id="form_full_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" pattern="[0-9]{10}" maxlength="10" name="phone" id="form_phone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" id="form_email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>City / Location</label>
                        <input type="text" name="city" id="form_city" class="form-control">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 10px;">
                    <div class="form-group">
                        <label>Program Interested In</label>
                        <select name="program_id" id="form_program_id" class="form-control">
                            <option value="">-- Undecided --</option>
                            <?php foreach($programs as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= esc($p['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Source</label>
                        <select name="source" id="form_source" class="form-control">
                            <option value="Walk-in">Walk-in</option>
                            <option value="Website">Website</option>
                            <option value="Referral">Referral</option>
                            <option value="Agent">Agent</option>
                            <option value="Social Media">Social Media</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-top: 10px;">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="form_status" class="form-control" required>
                            <option value="New">New</option>
                            <option value="Contacted">Contacted</option>
                            <option value="Interested">Interested</option>
                            <option value="Not Interested">Not Interested</option>
                            <option value="Converted">Converted to Application</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Assign To Officer</label>
                        <select name="assigned_to" id="form_assigned_to" class="form-control">
                            <option value="">-- Unassigned --</option>
                            <?php foreach($staff as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= esc($s['full_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Next Follow-up Date</label>
                        <input type="date" name="follow_up_date" id="form_follow_up_date" class="form-control">
                    </div>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Lead</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    $('#form_id').val('');
    $('#form_full_name').val('');
    $('#form_phone').val('');
    $('#form_email').val('');
    $('#form_city').val('');
    $('#form_program_id').val('');
    $('#form_source').val('Walk-in');
    $('#form_status').val('New');
    $('#form_assigned_to').val('');
    $('#form_follow_up_date').val('');
    $('#modal_title').text('Add New Lead');
    $('#addModal').addClass('active');
}
function editLead(data) {
    $('#form_id').val(data.id);
    $('#form_full_name').val(data.full_name);
    $('#form_phone').val(data.phone);
    $('#form_email').val(data.email);
    $('#form_city').val(data.city);
    $('#form_program_id').val(data.program_id);
    $('#form_source').val(data.source);
    $('#form_status').val(data.status);
    $('#form_assigned_to').val(data.assigned_to);
    $('#form_follow_up_date').val(data.follow_up_date);
    $('#modal_title').text('Edit Lead');
    $('#addModal').addClass('active');
}
function closeModal() {
    $('#addModal').removeClass('active');
}

// Live Autoloader for Admissions Leads
let lastKnownLeadCount = <?= !empty($leads) ? count($leads) : 0 ?>;
setInterval(function() {
    fetch('<?= base_url('org/admissions/api-live-leads') ?>')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const txt = document.getElementById('leads_live_txt');
                if (txt) txt.innerText = 'Live Sync: ' + data.timestamp;

                if (data.total_leads > lastKnownLeadCount && data.latest_leads.length > 0) {
                    lastKnownLeadCount = data.total_leads;
                    // Flash notification that new lead arrived
                    if (window.toastNotification) {
                        toastNotification('New admission application received!', 'success');
                    }
                }
            }
        })
        .catch(err => console.debug('Leads sync idle'));
}, 15000);
</script>
<?= $this->endSection() ?>
