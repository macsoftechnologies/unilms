<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Document Verification<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Document Verification</h2>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>App Number</th>
                    <th>Applicant Name</th>
                    <th>Documents Required</th>
                    <th>Verified</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($applications)): foreach($applications as $app): ?>
                <tr>
                    <td><strong><?= esc($app['adm_number']) ?></strong></td>
                    <td><?= esc($app['full_name']) ?></td>
                    <td>3 (Demo)</td>
                    <td>0 / 3</td>
                    <td>
                        <?php if($app['status'] === 'Verified' || $app['status'] === 'Offer Made' || $app['status'] === 'Enrolled'): ?>
                            <span class="badge badge-success">Verified</span>
                        <?php else: ?>
                            <span class="badge badge-warning">Pending Verification</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($app['status'] === 'Submitted'): ?>
                            <a href="<?= base_url('org/admissions/documents/verify/' . ($app['uuid'] ?? $app['id'])) ?>" class="btn btn-sm btn-success" style="padding: 5px 12px; font-size: 12px;"><i class="fa-solid fa-check"></i> Approve & Verify</a>
                        <?php else: ?>
                            <span style="color: var(--text-muted); font-size: 12px;"><i class="fa-solid fa-check-double text-success"></i> Approved</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6">No applications require verification.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
