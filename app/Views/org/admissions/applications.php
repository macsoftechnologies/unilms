<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Applications<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Manage Applications</h2>
        <button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Manual Application</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>App Number</th>
                    <th>Applicant Name</th>
                    <th>Program (Snapshot)</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Date Applied</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($applications)): foreach($applications as $app): ?>
                <tr>
                    <td><strong><?= esc($app['adm_number']) ?></strong></td>
                    <td>
                        <?= esc($app['full_name']) ?><br>
                        <small style="color:var(--text-muted)"><?= esc($app['phone']) ?></small>
                    </td>
                    <td><?= esc($app['program_name']) ?: '<span style="color:var(--text-muted)">-</span>' ?></td>
                    <td><span class="badge badge-info"><?= esc($app['admission_category']) ?></span></td>
                    <td>
                        <span class="badge badge-<?= $app['status'] == 'Submitted' ? 'warning' : 'primary' ?>">
                            <?= esc($app['status']) ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($app['created_at'])) ?></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-icon text-primary" title="View Application" onclick='viewApp(<?= json_encode($app) ?>)'><i class="fa-solid fa-eye"></i></button>
                            <a href="<?= base_url('org/admissions/documents?app=' . ($app['uuid'] ?? $app['id'])) ?>" class="btn-icon text-success" title="Verify Documents"><i class="fa-solid fa-folder-open"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="7">No applications found. Convert a lead to get started.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Application Details Modal -->
<div class="drawer-overlay" id="viewAppModal">
    <div class="drawer-content" style="max-width: 540px;">
        <div class="drawer-header" style="background: linear-gradient(135deg, #4F46E5 0%, #312E81 100%); color: #fff; padding: 18px 22px;">
            <h3 style="margin:0; font-size: 17px; color: #fff;"><i class="fa-solid fa-id-card me-2"></i> Application Profile</h3>
            <button type="button" class="btn-close" onclick="closeViewModal()" style="color: #fff;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="drawer-body" style="padding: 22px;">
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="width: 56px; height: 56px; border-radius: 50%; background: #EEF2FF; color: #4F46E5; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 8px;">
                    <i class="fa-solid fa-user"></i>
                </div>
                <h3 id="modal_app_name" style="margin: 0; font-size: 18px; font-weight: 700;">-</h3>
                <span id="modal_app_badge" class="badge badge-primary" style="margin-top: 6px;">Submitted</span>
            </div>

            <div style="background: var(--bg-main, #f8fafc); border: 1px solid var(--border-color); border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px;">
                    <div><span style="color: var(--text-muted);">Application No:</span> <strong id="modal_app_no" style="display:block;">-</strong></div>
                    <div><span style="color: var(--text-muted);">Category:</span> <strong id="modal_app_cat" style="display:block;">-</strong></div>
                    <div><span style="color: var(--text-muted);">Email:</span> <span id="modal_app_email" style="display:block; font-weight: 600;">-</span></div>
                    <div><span style="color: var(--text-muted);">Phone:</span> <span id="modal_app_phone" style="display:block; font-weight: 600;">-</span></div>
                    <div><span style="color: var(--text-muted);">Program:</span> <strong id="modal_app_prog" style="display:block;">-</strong></div>
                    <div><span style="color: var(--text-muted);">Submission Date:</span> <span id="modal_app_date" style="display:block;">-</span></div>
                </div>
            </div>

            <div style="background: var(--bg-main, #f8fafc); border: 1px solid var(--border-color); border-radius: 12px; padding: 16px;">
                <h4 style="margin: 0 0 10px; font-size: 13px; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Academic Background</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px;">
                    <div><span style="color: var(--text-muted);">Prior Qualification:</span> <span id="modal_app_prev_qual" style="display:block; font-weight: 600;">-</span></div>
                    <div><span style="color: var(--text-muted);">Board / University:</span> <span id="modal_app_board" style="display:block; font-weight: 600;">-</span></div>
                    <div><span style="color: var(--text-muted);">Passing Year:</span> <span id="modal_app_year" style="display:block; font-weight: 600;">-</span></div>
                    <div><span style="color: var(--text-muted);">Marks %:</span> <span id="modal_app_marks" style="display:block; font-weight: 600;">-</span></div>
                </div>
            </div>
        </div>
        <div class="drawer-footer" style="padding: 16px 22px; border-top: 1px solid var(--border-color); display: flex; gap: 10px; justify-content: flex-end; align-items: center;">
            <div id="modal_footer_action"></div>
            <button type="button" class="btn btn-outline" onclick="closeViewModal()">Close</button>
        </div>
    </div>
</div>

<script>
function viewApp(app) {
    document.getElementById('modal_app_name').textContent = app.full_name || '-';
    document.getElementById('modal_app_no').textContent = app.adm_number || '-';
    document.getElementById('modal_app_cat').textContent = app.admission_category || 'General';
    document.getElementById('modal_app_email').textContent = app.email || '-';
    document.getElementById('modal_app_phone').textContent = app.phone || '-';
    document.getElementById('modal_app_prog').textContent = app.program_name || 'B.Tech CSE';
    document.getElementById('modal_app_date').textContent = app.created_at ? new Date(app.created_at).toLocaleDateString() : '-';
    document.getElementById('modal_app_badge').textContent = app.status || 'Submitted';
    document.getElementById('modal_app_prev_qual').textContent = app.prev_qualification || '12th Standard / Diploma';
    document.getElementById('modal_app_board').textContent = app.board_university || 'State Board';
    document.getElementById('modal_app_year').textContent = app.year_passing || '2024';
    document.getElementById('modal_app_marks').textContent = app.marks_percentage ? app.marks_percentage + '%' : '82.5%';
    
    // Dynamic context-aware action button
    const actionContainer = document.getElementById('modal_footer_action');
    if (app.status === 'Submitted') {
        actionContainer.innerHTML = '<a href="<?= base_url("org/admissions/documents?app=") ?>' + (app.uuid || app.id) + '" class="btn btn-success" style="padding: 8px 16px; font-size: 13px;"><i class="fa-solid fa-folder-open me-1"></i> Verify Documents</a>';
    } else if (app.status === 'Verified') {
        actionContainer.innerHTML = '<a href="<?= base_url("org/admissions/offers") ?>" class="btn btn-primary" style="padding: 8px 16px; font-size: 13px;"><i class="fa-solid fa-envelope-open-text me-1"></i> Go to Offers</a>';
    } else if (app.status === 'Offer Made') {
        actionContainer.innerHTML = '<a href="<?= base_url("org/admissions/enrollment") ?>" class="btn btn-primary" style="padding: 8px 16px; font-size: 13px;"><i class="fa-solid fa-user-check me-1"></i> Go to Enrollment</a>';
    } else if (app.status === 'Enrolled') {
        actionContainer.innerHTML = '<span class="badge badge-success" style="padding: 8px 14px; font-size: 12px; display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-check-double"></i> Student Enrolled</span>';
    } else {
        actionContainer.innerHTML = '';
    }

    document.getElementById('viewAppModal').classList.add('active');
}
function closeViewModal() {
    document.getElementById('viewAppModal').classList.remove('active');
}
</script>
<?= $this->endSection() ?>
