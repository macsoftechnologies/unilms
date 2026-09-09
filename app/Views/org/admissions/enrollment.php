<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Enrollment Pipeline<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Final Enrollment</h2>
        <p style="color:var(--text-muted); margin-top:5px;">Applications with accepted offers waiting for final roll number generation.</p>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>App Number</th>
                    <th>Applicant Name</th>
                    <th>Program</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($applications)): foreach($applications as $app): ?>
                <tr>
                    <td><strong><?= esc($app['adm_number']) ?></strong></td>
                    <td>
                        <?= esc($app['full_name']) ?><br>
                        <small style="color:var(--text-muted)"><?= esc($app['email']) ?></small>
                    </td>
                    <td><?= esc($app['program_name']) ?></td>
                    <td>
                        <span class="badge badge-success">Offer Accepted</span>
                    </td>
                    <td>
                        <a href="<?= base_url('org/admissions/enrollment/process/' . $app['id']) ?>" 
                           class="btn btn-primary btn-sm" style="padding: 7px 16px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(124, 58, 237, 0.25);">
                           <i class="fa-solid fa-user-check"></i> Enroll Student
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5">No applications ready for enrollment.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
