<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Mark Attendance<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
<style>
.att-radio { display: none; }
.att-label { 
    display: inline-block; padding: 6px 12px; font-size: 12px; font-weight: 600; 
    border: 1px solid var(--border-color); cursor: pointer; transition: all 0.2s;
}
.att-label.left { border-radius: 6px 0 0 6px; border-right: none; }
.att-label.mid { border-right: none; }
.att-label.right { border-radius: 0 6px 6px 0; }

.att-radio:checked + .att-label.status-Present { background: #dcfce7; color: #166534; border-color: #86efac; z-index:1; position:relative;}
.att-radio:checked + .att-label.status-Absent { background: #fee2e2; color: #991b1b; border-color: #fca5a5; z-index:1; position:relative;}
.att-radio:checked + .att-label.status-Late { background: #fef9c3; color: #854d0e; border-color: #fde047; z-index:1; position:relative;}
</style>

<div class="view-header">
    <div>
        <a href="<?= base_url('org/attendance/faculty') ?>" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px; margin-bottom: 8px;"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
        <h2>Mark Attendance: <?= esc($subject['code']) ?></h2>
        <p style="color:var(--text-muted); font-size: 14px; margin: 4px 0 0;">Cohort: <?= esc($cohort['name']) ?></p>
    </div>
</div>

<form action="<?= base_url('org/attendance/save') ?>" method="POST" id="attendanceForm">
    <?= csrf_field() ?>
    <input type="hidden" name="subject_id" value="<?= $subject['id'] ?>">
    <input type="hidden" name="cohort_id" value="<?= $cohort['id'] ?>">
    <?php if($existing_session): ?>
        <input type="hidden" name="edit_session_id" value="<?= $existing_session['id'] ?>">
    <?php endif; ?>

    <div class="stat-card" style="margin-bottom: 24px;">
        <div style="display: flex; gap: 24px;">
            <div class="form-group" style="flex:1;">
                <label>Date of Session</label>
                <input type="date" name="session_date" class="form-control" value="<?= $existing_session ? $existing_session['session_date'] : date('Y-m-d') ?>" required>
            </div>
            <div class="form-group" style="flex:2;">
                <label>Topic Taught (Optional)</label>
                <input type="text" name="topic_taught" class="form-control" value="<?= $existing_session ? esc($existing_session['topic_taught']) : '' ?>" placeholder="e.g. Introduction to Data Structures">
            </div>
        </div>
        <?php if($existing_session): ?>
            <div class="form-group" style="margin-top: 16px;">
                <label>Reason for Edit <span style="color:var(--danger)">*</span></label>
                <input type="text" name="edit_reason" class="form-control" placeholder="Provide a reason for changing the submitted attendance" required>
            </div>
        <?php endif; ?>
    </div>

    <div class="table-container" style="margin-bottom: 24px;">
        <div style="padding: 16px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin:0; font-size:16px;">Student List</h3>
            <button type="button" class="btn btn-outline" onclick="markAll('Present')">Mark All Present</button>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Roll Number</th>
                    <th>Student Name</th>
                    <th style="text-align: right;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($students)): ?>
                    <?php $i=1; foreach($students as $st): ?>
                    <?php 
                        $status = isset($existing_records[$st['id']]) ? $existing_records[$st['id']] : 'Present';
                    ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><strong><?= esc($st['roll_number']) ?></strong></td>
                        <td><?= esc($st['first_name'] . ' ' . $st['last_name']) ?></td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex;">
                                <input type="radio" id="p_<?= $st['id'] ?>" name="attendance[<?= $st['id'] ?>]" value="Present" class="att-radio" <?= $status == 'Present' ? 'checked' : '' ?>>
                                <label for="p_<?= $st['id'] ?>" class="att-label status-Present left">Present</label>

                                <input type="radio" id="l_<?= $st['id'] ?>" name="attendance[<?= $st['id'] ?>]" value="Late" class="att-radio" <?= $status == 'Late' ? 'checked' : '' ?>>
                                <label for="l_<?= $st['id'] ?>" class="att-label status-Late mid">Late</label>

                                <input type="radio" id="a_<?= $st['id'] ?>" name="attendance[<?= $st['id'] ?>]" value="Absent" class="att-radio" <?= $status == 'Absent' ? 'checked' : '' ?>>
                                <label for="a_<?= $st['id'] ?>" class="att-label status-Absent right">Absent</label>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align:center; padding:20px; color:var(--text-muted);">No students enrolled in this cohort yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if(!empty($students)): ?>
        <div style="text-align: right;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 32px; font-size: 16px;">
                <?= $existing_session ? 'Update Attendance Log' : 'Save Attendance' ?>
            </button>
        </div>
    <?php endif; ?>
</form>
</section>

<script>
function markAll(status) {
    $('.att-radio[value="'+status+'"]').prop('checked', true);
}
</script>

<?= $this->endSection() ?>
