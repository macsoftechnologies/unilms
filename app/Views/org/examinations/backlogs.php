<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Backlogs<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Backlogs Tracking</h2>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Exam & Schedule</th>
                    <th>Subject</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($backlogs)): foreach($backlogs as $b): ?>
                <tr>
                    <td><strong><?= esc($b['first_name'].' '.$b['last_name']) ?></strong><br><small><?= esc($b['roll_number']) ?></small></td>
                    <td><?= esc($b['exam_name']) ?></td>
                    <td><?= esc($b['subject_name']) ?></td>
                    <td>
                        <?php if($b['status'] == 'Cleared'): ?>
                            <span class="badge badge-success">Cleared</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Pending</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="4">No backlogs found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
