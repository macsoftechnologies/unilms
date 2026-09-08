<?= $this->extend('org/layout') ?>

<?= $this->section('page_title') ?>Staff Directory<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Staff Directory Lookup</h2>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                <?php $staffIdx = 1; foreach($staff as $s): ?>
                <tr>
                    <td><?= $staffIdx++ ?></td>
                    <td><strong><?= esc($s['full_name']) ?></strong></td>
                    <td><?= esc($s['email']) ?></td>
                    <td><span class="badge bg-secondary"><?= ucfirst(esc($s['role'])) ?></span></td>
                    <td><?= esc($s['phone'] ?? 'N/A') ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($staff)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">No staff found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
