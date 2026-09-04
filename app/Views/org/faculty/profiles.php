<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Faculty Profiles<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
<div class="view-header">
    <h2>Faculty Profiles</h2>
    <button class="btn btn-primary" onclick="openPanel()"><i class="fa-solid fa-plus"></i> Add Profile</button>
</div>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Department</th>
                <th>Qualification</th>
                <th>Experience</th>
                <th>Joining Date</th>
                <th>Specialization</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($profiles)): ?>
                <?php foreach($profiles as $p): ?>
                <tr>
                    <td>
                        <strong><?= esc($p['full_name']) ?></strong><br>
                        <span style="font-size:12px; color:var(--text-muted);"><?= esc($p['email']) ?></span>
                    </td>
                    <td><?= esc($p['department_name'] ?? 'Not Assigned') ?></td>
                    <td><?= esc($p['qualification']) ?></td>
                    <td><?= esc($p['experience_years']) ?> yrs</td>
                    <td><?= $p['joining_date'] ? date('d/m/Y', strtotime($p['joining_date'])) : '-' ?></td>
                    <td><?= esc($p['specialization']) ?></td>
                    <td>
                        <button class="btn btn-outline" onclick='editProfile(<?= json_encode($p) ?>)' style="padding: 4px 10px; font-size: 12px;"><i class="fa-solid fa-pen"></i></button>
                        <form action="<?= base_url('org/faculty/profiles/delete') ?>" method="POST" style="display:inline;" onsubmit="return confirm('Delete this profile?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="profile_id" value="<?= $p['id'] ?>">
                            <button type="submit" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px; color: var(--danger); border-color: var(--danger);"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" style="text-align:center; padding: 40px; color: var(--text-muted);">No faculty profiles added yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Slide Panel -->
<style>
.side-panel-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 9998; opacity: 0; visibility: hidden; transition: all 0.3s ease; }
.side-panel-overlay.active { opacity: 1; visibility: visible; }
.side-panel { position: fixed; top: 0; right: -450px; width: 420px; max-width: 100%; height: 100vh; background: var(--card-bg, #fff); box-shadow: -10px 0 40px rgba(0,0,0,0.15); z-index: 9999; transition: right 0.3s cubic-bezier(0.16, 1, 0.3, 1); display: flex; flex-direction: column; }
.side-panel.active { right: 0; }
.side-panel-header { padding: 24px 32px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: var(--bg-main); }
.side-panel-header h3 { margin: 0; font-size: 18px; font-weight: 700; color: var(--text-main); }
.side-panel-body { padding: 32px; flex: 1; overflow-y: auto; }
.side-panel-footer { padding: 24px 32px; border-top: 1px solid var(--border-color); display: flex; gap: 12px; background: var(--bg-main); }
.side-panel-footer button { flex: 1; }
</style>

<div class="side-panel-overlay" id="panel-overlay" onclick="closePanel()"></div>
<div class="side-panel" id="side-panel">
    <form action="<?= base_url('org/faculty/profiles/save') ?>" method="POST" style="display: flex; flex-direction: column; height: 100%;">
        <?= csrf_field() ?>
        <input type="hidden" name="profile_id" id="profile_id">
        <div class="side-panel-header">
            <h3 id="panel-title">Add Faculty Profile</h3>
            <button type="button" class="btn btn-outline" style="border:none; padding: 4px;" onclick="closePanel()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="side-panel-body">
            <div class="form-group">
                <label>Select User <span style="color:var(--danger)">*</span></label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value="">-- Select Staff Member --</option>
                    <?php foreach($staff as $u): ?>
                        <option value="<?= $u['id'] ?>"><?= esc($u['full_name']) ?> (<?= esc($u['email']) ?>)</option>
                    <?php endforeach; ?>
                </select>
                <small style="color:var(--text-muted)">Must be created in Systems > Users first.</small>
            </div>
            <div class="form-group">
                <label>Department</label>
                <select name="department_id" id="department_id" class="form-control">
                    <option value="">-- Optional --</option>
                    <?php foreach($departments as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= esc($d['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Qualification</label>
                <input type="text" name="qualification" id="qualification" class="form-control" placeholder="e.g. Ph.D. in Computer Science">
            </div>
            <div class="form-group">
                <label>Years of Experience</label>
                <input type="number" name="experience_years" id="experience_years" class="form-control" value="0" min="0">
            </div>
            <div class="form-group">
                <label>Specialization</label>
                <input type="text" name="specialization" id="specialization" class="form-control" placeholder="e.g. Machine Learning, Networks">
            </div>
            <div class="form-group">
                <label>Date of Joining</label>
                <input type="date" name="joining_date" id="joining_date" class="form-control">
            </div>
        </div>
        <div class="side-panel-footer">
            <button type="button" class="btn btn-outline" onclick="closePanel()">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Profile</button>
        </div>
    </form>
</div>

<script>
function openPanel() {
    $('#profile_id').val('');
    $('#user_id').val('').prop('disabled', false);
    $('#department_id').val('');
    $('#qualification').val('');
    $('#experience_years').val(0);
    $('#specialization').val('');
    $('#joining_date').val('');
    $('#panel-title').text('Add Faculty Profile');
    $('#panel-overlay').addClass('active');
    $('#side-panel').addClass('active');
}
function editProfile(p) {
    $('#profile_id').val(p.id);
    $('#user_id').val(p.user_id).prop('disabled', true); // Prevent changing user once linked
    $('#department_id').val(p.department_id);
    $('#qualification').val(p.qualification);
    $('#experience_years').val(p.experience_years);
    $('#specialization').val(p.specialization);
    $('#joining_date').val(p.joining_date ? p.joining_date.substring(0, 10) : '');
    $('#panel-title').text('Edit Faculty Profile');
    
    // add hidden input since disabled select doesn't submit
    if ($('#hidden_user_id').length == 0) {
        $('<input>').attr({type: 'hidden', id: 'hidden_user_id', name: 'user_id'}).appendTo('form');
    }
    $('#hidden_user_id').val(p.user_id);
    
    $('#panel-overlay').addClass('active');
    $('#side-panel').addClass('active');
}
function closePanel() {
    $('#panel-overlay').removeClass('active');
    $('#side-panel').removeClass('active');
}
</script>
</section>

<?= $this->endSection() ?>
