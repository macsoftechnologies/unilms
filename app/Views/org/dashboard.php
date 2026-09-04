<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Dashboard<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Total Users</div>
                <div class="stat-value"><?= $total_users ?></div>
            </div>
            <div class="stat-icon-badge badge-blue"><i class="fa-solid fa-users"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Subscription Status</div>
                <div class="stat-value" style="font-size: 16px; color: #059669;"><?= date('d/m/Y', strtotime($org['subscription_end_date'])) ?></div>
            </div>
            <div class="stat-icon-badge badge-green"><i class="fa-regular fa-calendar-check"></i></div>
        </div>
    </div>

    <div class="dashboard-widgets">
        <div class="widget" style="margin-bottom: 24px;">
            <h2 style="display: flex; justify-content: space-between; align-items: center;">
                Welcome to your Organization Portal
                <div style="font-size: 14px; font-weight: normal;">
                    Modules Active: 
                    <?php if($org['cms_enabled']): ?><span class="badge badge-success" style="margin-left: 5px;">CMS</span> <?php endif; ?>
                    <?php if($org['lms_enabled']): ?><span class="badge badge-primary" style="margin-left: 5px;">LMS</span> <?php endif; ?>
                </div>
            </h2>
            <p style="color: var(--text-muted); line-height: 1.7; font-size: 15px;">
                This is your centralized dashboard. Use the sidebar on the left to navigate between your enabled modules.
                <?php if(session()->get('is_org_admin')): ?>
                <br><br>As the <strong>Organization Admin</strong>, you have full access to every feature. 
                Head to <a href="<?= base_url('org/systems/access-groups') ?>" style="color: var(--primary); text-decoration: none; font-weight: 600;">System → Access Groups</a> to set up custom roles for your staff and team members.
                <?php endif; ?>
            </p>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
