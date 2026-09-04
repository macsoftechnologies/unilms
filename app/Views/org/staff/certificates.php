<?= $this->extend('org/layout') ?>

<?= $this->section('page_title') ?>Staff Certificates<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Staff Certificates</h2>
        <button class="btn btn-primary" onclick="openDrawer('drawerCert')">
            <i class="fa-solid fa-plus"></i> Issue Certificate
        </button>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Staff Name</th>
                    <th>Certificate Type</th>
                    <th>Issue Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($certificates as $c): ?>
                <tr>
                    <td><strong><?= esc($c['staff_name']) ?></strong></td>
                    <td><?= esc($c['certificate_type']) ?></td>
                    <td><?= esc($c['issue_date']) ?></td>
                    <td>
                        <a href="<?= base_url('org/staff/generate_pdf/'.$c['id']) ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                            <i class="fa-solid fa-file-pdf"></i> Download PDF
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($certificates)): ?>
                <tr>
                    <td colspan="4" class="text-center text-muted">No certificates issued.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Drawer for Issuing Certificate -->
<div class="drawer-overlay" id="drawerCert" onclick="closeDrawer('drawerCert')">
    <div class="drawer-content" onclick="event.stopPropagation()">
        <div class="drawer-header">
            <h3>Issue Certificate</h3>
            <button class="btn-close" onclick="closeDrawer('drawerCert')">&times;</button>
        </div>
        <div class="drawer-body">
            <form action="<?= base_url('org/staff/save_certificate') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label>Select Staff</label>
                    <select name="staff_id" class="form-control" required>
                        <option value="">-- Select Staff --</option>
                        <?php foreach($staff_list as $sl): ?>
                            <option value="<?= $sl['id'] ?>"><?= esc($sl['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Certificate Type</label>
                    <select name="certificate_type" class="form-control" required>
                        <option value="Experience Certificate">Experience Certificate</option>
                        <option value="No Objection Certificate (NOC)">No Objection Certificate (NOC)</option>
                        <option value="Relieving Letter">Relieving Letter</option>
                        <option value="Service Certificate">Service Certificate</option>
                    </select>
                </div>
                
                <div class="form-group mb-4">
                    <label>Issue Date</label>
                    <input type="date" name="issue_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Generate Record</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
