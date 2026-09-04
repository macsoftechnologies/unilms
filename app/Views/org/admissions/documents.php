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
                        <span class="badge badge-warning">Pending Verification</span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary">Review Docs</button>
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
