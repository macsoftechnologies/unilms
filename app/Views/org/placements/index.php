<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Placements Dashboard<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Placement & Career Services</h1>
        <p class="header-subtitle">Overview of campus drives, internships, and job offers.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 24px;">
    
    <div class="card" style="display: flex; align-items: center; gap: 20px;">
        <div style="background: #E0E7FF; width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-building" style="font-size: 20px; color: var(--primary);"></i>
        </div>
        <div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500; text-transform: uppercase;">Partners</div>
            <div style="font-size: 24px; font-weight: bold; color: var(--text-main);"><?= esc($total_companies) ?></div>
        </div>
    </div>
    
    <div class="card" style="display: flex; align-items: center; gap: 20px;">
        <div style="background: #FEF3C7; width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-user-graduate" style="font-size: 20px; color: #92400E;"></i>
        </div>
        <div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500; text-transform: uppercase;">Internships</div>
            <div style="font-size: 24px; font-weight: bold; color: var(--text-main);"><?= esc($total_internships) ?></div>
        </div>
    </div>

    <div class="card" style="display: flex; align-items: center; gap: 20px;">
        <div style="background: #D1FAE5; width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-briefcase" style="font-size: 20px; color: #059669;"></i>
        </div>
        <div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500; text-transform: uppercase;">Job Offers</div>
            <div style="font-size: 24px; font-weight: bold; color: var(--text-main);"><?= esc($total_offers) ?></div>
        </div>
    </div>

    <div class="card" style="display: flex; align-items: center; gap: 20px;">
        <div style="background: #FEE2E2; width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-indian-rupee-sign" style="font-size: 20px; color: #991B1B;"></i>
        </div>
        <div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500; text-transform: uppercase;">Highest CTC</div>
            <div style="font-size: 24px; font-weight: bold; color: var(--text-main);"><?= number_format($highest_ctc/100000, 2) ?> L</div>
        </div>
    </div>
</div>

<div class="card">
    <h2 style="margin-top: 0;">Recent Job Offers</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Student</th>
                <th>Company</th>
                <th>Role</th>
                <th>CTC (LPA)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($recent_offers as $offer): ?>
                <tr>
                    <td style="white-space: nowrap;"><?= date('d/m/Y', strtotime($offer['offer_date'])) ?></td>
                    <td>
                        <strong><?= esc($offer['first_name']) ?> <?= esc($offer['last_name']) ?></strong><br>
                        <small style="color: var(--text-muted);"><?= esc($offer['roll_number']) ?></small>
                    </td>
                    <td style="font-weight: 500; color: var(--primary);"><?= esc($offer['company_name']) ?></td>
                    <td><?= esc($offer['job_role']) ?></td>
                    <td style="font-weight: bold; color: green;"><?= number_format($offer['ctc'] / 100000, 2) ?> L</td>
                    <td>
                        <?php if($offer['status'] == 'Accepted'): ?>
                            <span style="color: green; font-weight: bold;">Accepted</span>
                        <?php elseif($offer['status'] == 'Rejected'): ?>
                            <span style="color: red; font-weight: bold;">Rejected</span>
                        <?php else: ?>
                            <span style="color: orange; font-weight: bold;">Pending</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($recent_offers)): ?>
                <tr><td colspan="6" style="text-align: center;">No job offers recorded yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
