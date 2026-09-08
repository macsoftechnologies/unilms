<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Programs<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Manage Programs</h2>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Add Program</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Duration</th>
                    <th>Accredited Outcomes (OBE)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($programs)): foreach($programs as $prog): ?>
                <tr>
                    <td><span class="badge badge-primary"><?= esc($prog['code']) ?></span></td>
                    <td><strong><?= esc($prog['name']) ?></strong></td>
                    <td><?= esc($prog['dept_name']) ?></td>
                    <td><?= esc($prog['duration_years']) ?> Years</td>
                    <td>
                        <?php if(!empty($prog['pos'])): ?>
                            <button type="button" class="badge" style="background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25); font-weight: 700; padding: 4px 10px; border-radius: 20px; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;" onclick='viewPos(<?= json_encode($prog) ?>)'>
                                <i class="fa-solid fa-award"></i> <?= count($prog['pos']) ?> Outcomes (11 POs + PSOs)
                            </button>
                        <?php else: ?>
                            <span class="badge" style="background: #f1f5f9; color: #64748b;">Standard POs</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-icon" onclick='editProg(<?= json_encode($prog) ?>)' title="Edit"><i class="fa-solid fa-pen"></i></button>
                            <a href="<?= base_url('org/academics/programs/delete/' . ($prog['uuid'] ?? $prog['id'])) ?>" class="btn-icon text-danger" onclick="return confirm('Are you sure you want to delete this program? This is blocked if cohorts exist.')" title="Delete"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6">No programs found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- View POs Modal -->
<div class="drawer-overlay" id="poModal">
    <div class="drawer-content" style="max-width: 650px;">
        <div class="drawer-header">
            <div>
                <h3 id="po_modal_title" style="margin: 0 0 2px;">Program Outcomes (POs / PSOs)</h3>
                <small class="text-muted" id="po_modal_sub">NBA Washington Accord Tier-1 Accreditation Framework</small>
            </div>
            <button type="button" class="btn-close" onclick="closePoModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="drawer-body" id="po_modal_body" style="padding: 20px; display: flex; flex-direction: column; gap: 12px; max-height: 70vh; overflow-y: auto;">
            <!-- Populated dynamically via JS -->
        </div>
        <div class="drawer-footer">
            <button type="button" class="btn btn-outline" onclick="closePoModal()">Close</button>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/academics/programs/save') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="form_id">
            <div class="drawer-header">
                <h3 id="modal_title">Add Program</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Department</label>
                    <select name="dept_id" id="form_dept_id" class="form-control" required>
                        <option value="" disabled selected>-- Select Department --</option>
                        <?php foreach($departments as $d): ?>
                            <option value="<?= $d['id'] ?>"><?= esc($d['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Program Code</label>
                    <input type="text" name="code" id="form_code" class="form-control" required placeholder="e.g. BT-CS">
                </div>
                <div class="form-group">
                    <label>Program Name</label>
                    <input type="text" name="name" id="form_name" class="form-control" required placeholder="e.g. B.Tech Computer Science">
                </div>
                <div class="form-group">
                    <label>Duration (Years)</label>
                    <input type="number" name="duration_years" id="form_duration_years" class="form-control" required min="1" max="10">
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Program</button>
            </div>
        </form>
    </div>
</div>

<script>
function viewPos(prog) {
    $('#po_modal_title').text('Outcomes: ' + prog.name + ' (' + prog.code + ')');
    $('#po_modal_sub').text('Program Outcomes (11 POs + PSOs) defined for ' + prog.name);
    
    let html = '';
    if (prog.pos && prog.pos.length > 0) {
        prog.pos.forEach(function(po) {
            let parts = (po.description || '').split(':');
            let title = parts.length > 1 ? parts[0].trim() : (po.title || 'Program Outcome');
            let desc = parts.length > 1 ? parts.slice(1).join(':').trim() : (po.description || '');
            
            html += '<div style="background: var(--bg-canvas, #f8fafc); border: 1px solid var(--border, #e2e8f0); border-radius: 10px; padding: 14px;">';
            html += '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">';
            html += '<span style="font-weight: 800; color: var(--primary, #6366f1); font-size: 13.5px;">' + po.code + '</span>';
            html += '<span class="badge" style="background: rgba(16, 185, 129, 0.12); color: #059669; font-size: 10.5px;">Accredited</span>';
            html += '</div>';
            html += '<div style="font-weight: 700; font-size: 13px; color: var(--text-main, #0f172a); margin-bottom: 4px;">' + title + '</div>';
            html += '<div style="font-size: 12.5px; color: var(--text-muted, #64748b); line-height: 1.45;">' + desc + '</div>';
            html += '</div>';
        });
    } else {
        html = '<div class="text-muted p-4 text-center">No POs initialized for this program.</div>';
    }
    
    $('#po_modal_body').html(html);
    $('#poModal').addClass('active');
}

function closePoModal() {
    $('#poModal').removeClass('active');
}

function openModal() {
    $('#form_id').val('');
    $('#form_dept_id').val('');
    $('#form_code').val('');
    $('#form_name').val('');
    $('#form_duration_years').val('');
    $('#modal_title').text('Add Program');
    $('#addModal').addClass('active');
}
function editProg(data) {
    $('#form_id').val(data.id);
    $('#form_dept_id').val(data.dept_id);
    $('#form_code').val(data.code);
    $('#form_name').val(data.name);
    $('#form_duration_years').val(data.duration_years);
    $('#modal_title').text('Edit Program');
    $('#addModal').addClass('active');
}
function closeModal() {
    $('#addModal').removeClass('active');
}
</script>
<?= $this->endSection() ?>
