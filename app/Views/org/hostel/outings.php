<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Hostel Outings / Passes<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Hostel Outings & Gate Passes</h2>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Destination</th>
                    <th>Date Out</th>
                    <th>Expected Return</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($outings)): foreach($outings as $o): ?>
                <tr>
                    <td><strong><?= esc($o['first_name'].' '.$o['last_name']) ?></strong><br><small><?= esc($o['roll_number']) ?></small></td>
                    <td><?= esc($o['destination']) ?></td>
                    <td><?= esc(date('M d, Y H:i', strtotime($o['date_out']))) ?></td>
                    <td><?= esc(date('M d, Y H:i', strtotime($o['expected_return']))) ?></td>
                    <td>
                        <?php if($o['status'] == 'approved'): ?>
                            <span class="badge badge-primary">Approved</span>
                        <?php elseif($o['status'] == 'returned'): ?>
                            <span class="badge badge-success">Returned</span>
                        <?php elseif($o['status'] == 'rejected'): ?>
                            <span class="badge badge-danger">Rejected</span>
                        <?php else: ?>
                            <span class="badge badge-warning">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($o['status'] == 'pending'): ?>
                        <form action="<?= base_url('org/hostel/update_outing_status') ?>" method="POST" style="display:inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $o['id'] ?>">
                            <input type="hidden" name="status" value="approved">
                            <button class="btn btn-primary btn-sm">Approve</button>
                        </form>
                        <form action="<?= base_url('org/hostel/update_outing_status') ?>" method="POST" style="display:inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $o['id'] ?>">
                            <input type="hidden" name="status" value="rejected">
                            <button class="btn btn-outline btn-sm">Reject</button>
                        </form>
                        <?php elseif($o['status'] == 'approved'): ?>
                        <form action="<?= base_url('org/hostel/update_outing_status') ?>" method="POST" style="display:inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $o['id'] ?>">
                            <input type="hidden" name="status" value="returned">
                            <button class="btn btn-success btn-sm">Mark Returned</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6">No outings found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
