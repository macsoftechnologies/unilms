<?= $this->extend('super_admin/layout') ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Total Organizations</div>
                <div class="stat-value"><?= $total_orgs ?></div>
            </div>
            <div class="stat-icon-badge badge-blue"><i class="fa-solid fa-building"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Active Subscriptions</div>
                <div class="stat-value"><?= $active_orgs ?></div>
            </div>
            <div class="stat-icon-badge badge-green"><i class="fa-solid fa-check-circle"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">₹<?= number_format($total_revenue, 2) ?></div>
            </div>
            <div class="stat-icon-badge badge-amber"><i class="fa-solid fa-wallet"></i></div>
        </div>
    </div>

    <div class="dashboard-widgets">
        <div class="widget">
            <h2>Recent Organizations</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Organization Name</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Joined Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($recent_orgs)): ?>
                        <?php foreach($recent_orgs as $org): ?>
                        <tr>
                            <td><?= esc($org['name']) ?></td>
                            <td><?= esc($org['plan_name']) ?></td>
                            <td>
                                <?php if($org['status'] == 'active'): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Suspended</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($org['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No organizations found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
