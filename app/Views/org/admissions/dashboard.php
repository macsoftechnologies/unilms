<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Admissions Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header" style="margin-bottom: 14px;">
        <h2 style="font-size: 16px; font-weight: 700; color: var(--text-main);">Admissions Overview</h2>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Total Leads</div>
                <div class="stat-value">0</div>
            </div>
            <div class="stat-icon-badge badge-green"><i class="fa-solid fa-user-tag"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Active Applications</div>
                <div class="stat-value">0</div>
            </div>
            <div class="stat-icon-badge badge-blue"><i class="fa-solid fa-file-contract"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Offers Sent</div>
                <div class="stat-value">0</div>
            </div>
            <div class="stat-icon-badge badge-amber"><i class="fa-solid fa-envelope-open-text"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Enrolled Students</div>
                <div class="stat-value">0</div>
            </div>
            <div class="stat-icon-badge badge-purple"><i class="fa-solid fa-user-check"></i></div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
