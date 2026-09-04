<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>College Strength<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">College Strength Report</h1>
        <p class="header-subtitle">Overview of enrolled student count across all programs.</p>
    </div>
</div>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Program Code</th>
                <th>Program Name</th>
                <th>Total Enrolled Students</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($strength as $st): ?>
                <tr>
                    <td style="font-weight: 600;"><?= esc($st['code']) ?></td>
                    <td><?= esc($st['name']) ?></td>
                    <td><?= esc($st['enrolled_count']) ?> Students</td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($strength)): ?>
                <tr><td colspan="3" style="text-align:center;">No enrollment data found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
