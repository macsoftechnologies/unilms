<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Set Papers<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Exam Papers Setup (Set Papers)</h2>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Assign Paper Setting</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Exam Name</th>
                    <th>Subject</th>
                    <th>Faculty Assigned</th>
                    <th>Paper Type</th>
                    <th>Deadline</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($papers)): foreach($papers as $p): ?>
                <tr>
                    <td><strong><?= esc($p['exam_name']) ?></strong></td>
                    <td><?= esc($p['subject_name']) ?></td>
                    <td><?= esc($p['faculty_name']) ?></td>
                    <td><?= esc($p['paper_type']) ?></td>
                    <td>
                        <?php
                            $due = strtotime($p['deadline']);
                            $overdue = ($p['status'] == 'Pending' && time() > $due);
                        ?>
                        <span style="<?= $overdue ? 'color: red; font-weight: bold;' : '' ?>">
                            <?= esc(date('d/m/Y', $due)) ?>
                        </span>
                    </td>
                    <td>
                        <?php if($p['status'] == 'Approved'): ?>
                            <span class="badge badge-success">Approved</span>
                        <?php elseif($p['status'] == 'Submitted'): ?>
                            <span class="badge badge-primary">Submitted</span>
                        <?php else: ?>
                            <span class="badge badge-warning">Pending</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6">No paper setting tasks found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Modal -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/examinations/save_paper') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header">
                <h3 id="modal_title">Assign Set Paper Task</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Select Exam</label>
                    <select name="exam_id" class="form-control" required>
                        <?php foreach($exams as $e): ?>
                        <option value="<?= $e['id'] ?>"><?= esc($e['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Select Subject</label>
                    <select name="subject_id" class="form-control" required>
                        <?php foreach($subjects as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Assign Faculty</label>
                    <select name="faculty_id" class="form-control" required>
                        <?php foreach($faculty as $f): ?>
                        <option value="<?= $f['id'] ?>"><?= esc($f['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Paper Type (e.g. Set A, External, Internal)</label>
                    <input type="text" name="paper_type" class="form-control">
                </div>
                <div class="form-group">
                    <label>Deadline</label>
                    <input type="date" name="deadline" class="form-control" required>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Assign Task</button>
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
