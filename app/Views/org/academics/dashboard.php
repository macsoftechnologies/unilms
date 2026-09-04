<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Academics Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Departments</div>
                <div class="stat-value"><?= $total_depts ?></div>
            </div>
            <div class="stat-icon-badge badge-blue"><i class="fa-solid fa-building"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Programs</div>
                <div class="stat-value"><?= $total_programs ?></div>
            </div>
            <div class="stat-icon-badge badge-purple"><i class="fa-solid fa-graduation-cap"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Active Cohorts</div>
                <div class="stat-value"><?= $total_cohorts ?></div>
            </div>
            <div class="stat-icon-badge badge-cyan"><i class="fa-solid fa-users"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Subjects</div>
                <div class="stat-value"><?= $total_subjects ?></div>
            </div>
            <div class="stat-icon-badge badge-amber"><i class="fa-solid fa-book-open"></i></div>
        </div>
    </div>
    
    <div class="dashboard-widgets">
        <div class="widget">
            <h2>Welcome to Academics Module</h2>
            <p style="color: var(--text-muted); line-height: 1.6;">
                Manage your institution's core academic hierarchy here. Ensure you follow the correct sequence when setting up data:
                <br><br>
                1. <strong>Departments</strong> (e.g. Computer Science)<br>
                2. <strong>Programs</strong> (e.g. B.Tech Computer Science)<br>
                3. <strong>Academic Years & Semesters</strong> (Global configuration)<br>
                4. <strong>Cohorts</strong> (Enrollment batches like B.Tech 2026)<br>
                5. <strong>Subjects</strong> (Curriculum attached to programs and semesters)
            </p>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
