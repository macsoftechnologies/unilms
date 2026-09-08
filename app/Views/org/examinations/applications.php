<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Exam Applications<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Student Exam Applications</h1>
        <p class="header-subtitle">Review and approve exam applications from students.</p>
    </div>
</div>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Student</th>
                <th>Roll No</th>
                <th>Exam Applied For</th>
                <th>Application Date</th>
                <th>Fee Paid</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($applications as $app): ?>
                <tr>
                    <td><?= esc($app['first_name'] . ' ' . $app['last_name']) ?></td>
                    <td><?= esc($app['roll_number']) ?></td>
                    <td style="font-weight: 600;"><?= esc($app['exam_name']) ?></td>
                    <td><?= date('d/m/Y', strtotime($app['created_at'])) ?></td>
                    <td>
                        <?php if($app['fee_paid']): ?>
                            <span style="color: green; font-weight: bold;"><i class="fa-solid fa-check-circle"></i> Yes</span>
                        <?php else: ?>
                            <span style="color: red; font-weight: bold;"><i class="fa-solid fa-xmark-circle"></i> No</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                            $bg = '#e2e8f0';
                            $color = '#334155';
                            if ($app['status'] == 'Approved') { $bg = '#dcfce7'; $color = '#166534'; }
                            if ($app['status'] == 'Rejected') { $bg = '#fee2e2'; $color = '#991b1b'; }
                        ?>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: <?= $bg ?>; color: <?= $color ?>;">
                            <?= esc($app['status']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if($app['status'] == 'Applied'): ?>
                        <form action="<?= base_url('org/examinations/update-application-status/' . ($app['uuid'] ?? $app['id'])) ?>" method="POST" style="display:inline-block;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="Approved">
                            <button type="submit" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px; color: green; border-color: green;">Approve</button>
                        </form>
                        <form action="<?= base_url('org/examinations/update-application-status/' . ($app['uuid'] ?? $app['id'])) ?>" method="POST" style="display:inline-block;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="Rejected">
                            <button type="submit" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px; color: red; border-color: red;">Reject</button>
                        </form>
                        <?php else: ?>
                            <em>Processed</em>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($applications)): ?>
                <tr><td colspan="7" style="text-align:center;">No applications found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
