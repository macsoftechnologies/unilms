<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Library Members<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Library Members</h2>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Add Member</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Name / ID</th>
                    <th>Max Books Allowed</th>
                    <th>Valid Till</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($members)): foreach($members as $m): ?>
                <tr>
                    <td><span class="badge badge-primary"><?= esc(ucfirst($m['member_type'])) ?></span></td>
                    <td>
                        <?php if($m['member_type'] == 'student'): ?>
                            <strong><?= esc($m['first_name'].' '.$m['last_name']) ?></strong><br><small><?= esc($m['roll_number']) ?></small>
                        <?php else: ?>
                            <strong><?= esc($m['staff_name']) ?></strong>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($m['max_books_allowed']) ?></td>
                    <td><?= $m['valid_till'] ? esc(date('d/m/Y', strtotime($m['valid_till']))) : 'Lifetime' ?></td>
                    <td>
                        <?php if($m['status'] == 'active'): ?>
                            <span class="badge badge-success">Active</span>
                        <?php else: ?>
                            <span class="badge badge-secondary"><?= esc(ucfirst($m['status'])) ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5">No members found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Drawer -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/library/save_member') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="form_id">
            <div class="drawer-header">
                <h3 id="modal_title">Add Member</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Member Type</label>
                    <select name="member_type" id="form_member_type" class="form-control" required onchange="toggleMemberType()">
                        <option value="student">Student</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
                <div class="form-group" id="group_student">
                    <label>Select Student</label>
                    <select name="student_id" id="form_student_id" class="form-control">
                        <?php foreach($students as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= esc($s['first_name'].' '.$s['last_name'].' - '.$s['roll_number']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" id="group_staff" style="display: none;">
                    <label>Select Staff</label>
                    <select name="staff_id" id="form_staff_id" class="form-control">
                        <?php foreach($staff as $u): ?>
                        <option value="<?= $u['id'] ?>"><?= esc($u['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Max Books Allowed</label>
                    <input type="number" min="0" name="max_books_allowed" id="form_max_books_allowed" class="form-control" value="3" required>
                </div>
                <div class="form-group">
                    <label>Valid Till (Optional)</label>
                    <input type="date" name="valid_till" id="form_valid_till" class="form-control">
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Member</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleMemberType() {
    var type = $('#form_member_type').val();
    if(type === 'student') {
        $('#group_student').show();
        $('#group_staff').hide();
    } else {
        $('#group_student').hide();
        $('#group_staff').show();
    }
}
function openModal() {
    $('#form_id').val('');
    $('#form_member_type').val('student');
    toggleMemberType();
    $('#form_max_books_allowed').val('3');
    $('#form_valid_till').val('');
    $('#modal_title').text('Add Member');
    $('#addModal').addClass('active');
}
function closeModal() {
    $('#addModal').removeClass('active');
}
</script>
<?= $this->endSection() ?>
