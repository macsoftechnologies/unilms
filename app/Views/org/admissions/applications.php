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
                            <button class="btn-icon text-primary" title="View Profile"><i class="fa-solid fa-eye"></i></button>
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
<?= $this->endSection() ?>
