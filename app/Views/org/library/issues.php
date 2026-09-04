<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Issue & Return Books<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Issue & Return Books</h2>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-hand-holding-hand"></i> Issue Book</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Book Details</th>
                    <th>Issued To</th>
                    <th>Issue Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($issues)): foreach($issues as $i): ?>
                <tr>
                    <td><strong><?= esc($i['accession_no']) ?></strong><br><small><?= esc($i['title']) ?></small></td>
                    <td>
                        <?php if($i['member_type'] == 'student'): ?>
                            <?= esc($i['first_name'].' '.$i['last_name']) ?> <span class="badge badge-secondary">Student</span>
                        <?php else: ?>
                            <?= esc($i['staff_name']) ?> <span class="badge badge-secondary">Staff</span>
                        <?php endif; ?>
                    </td>
                    <td><?= esc(date('d/m/Y', strtotime($i['issue_date']))) ?></td>
                    <td>
                        <?php
                            $due = strtotime($i['due_date']);
                            $now = time();
                            $overdue = ($i['status'] == 'issued' && $now > $due);
                        ?>
                        <span style="<?= $overdue ? 'color: red; font-weight: bold;' : '' ?>">
                            <?= esc(date('d/m/Y', $due)) ?>
                        </span>
                        <?php if($overdue): ?>
                            <br><small style="color:red;">Overdue</small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($i['status'] == 'issued'): ?>
                            <span class="badge badge-warning">Issued</span>
                        <?php else: ?>
                            <span class="badge badge-success">Returned</span><br>
                            <small><?= esc(date('d/m/Y', strtotime($i['return_date']))) ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($i['status'] == 'issued'): ?>
                        <form action="<?= base_url('org/library/return_book') ?>" method="POST" style="display:inline;" onsubmit="return confirm('Confirm return of this book?');">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $i['id'] ?>">
                            <input type="hidden" name="book_id" value="<?= $i['book_id'] ?>">
                            <button class="btn btn-success btn-sm">Mark Returned</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6">No records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Drawer -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/library/issue_book') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header">
                <h3 id="modal_title">Issue Book</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Select Book (Available)</label>
                    <select name="book_id" class="form-control" required>
                        <?php foreach($books as $b): ?>
                        <option value="<?= $b['id'] ?>"><?= esc($b['accession_no'].' - '.$b['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Select Member</label>
                    <select name="member_id" class="form-control" required>
                        <?php foreach($members as $m): ?>
                            <?php if($m['member_type'] == 'student'): ?>
                                <option value="<?= $m['id'] ?>">Student: <?= esc($m['first_name'].' '.$m['last_name'].' ('.$m['roll_number'].')') ?></option>
                            <?php else: ?>
                                <option value="<?= $m['id'] ?>">Staff: <?= esc($m['staff_name']) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Issue Date</label>
                    <input type="date" name="issue_date" id="form_issue_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group">
                    <label>Due Date</label>
                    <!-- Default to 14 days from today -->
                    <input type="date" name="due_date" class="form-control" value="<?= date('Y-m-d', strtotime('+14 days')) ?>" required>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Confirm Issue</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    $('#addModal').addClass('active');
}
function closeModal() {
    $('#addModal').removeClass('active');
}
</script>
<?= $this->endSection() ?>
