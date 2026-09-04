<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Enter Marks<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Enter Marks</h1>
        <p class="header-subtitle"><?= esc($schedule['exam_name']) ?> - <?= esc($schedule['subject_name']) ?> (Max: <?= esc($schedule['max_marks']) ?>, Pass: <?= esc($schedule['passing_marks']) ?>)</p>
    </div>
    <a href="<?= base_url('org/examinations/marks?exam_id=' . $schedule['exam_id']) ?>" class="btn btn-outline">Back to Schedules</a>
</div>

<div class="card">
    <form action="<?= base_url('org/examinations/save-marks') ?>" method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="exam_schedule_id" value="<?= $schedule['id'] ?>">
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Roll No</th>
                    <th>Marks Obtained</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($students as $stu): ?>
                    <tr>
                        <td><?= esc($stu['first_name'] . ' ' . $stu['last_name']) ?></td>
                        <td><?= esc($stu['roll_number']) ?></td>
                        <td>
                            <input type="number" min="0" step="0.01" name="marks[<?= $stu['student_id'] ?>][marks_obtained]" class="form-control" style="max-width: 120px;" value="<?= $stu['marks_obtained'] ?>" min="0" max="<?= $schedule['max_marks'] ?>">
                        </td>
                        <td>
                            <select name="marks[<?= $stu['student_id'] ?>][status]" class="form-control" style="max-width: 140px;">
                                <option value="Pass" <?= $stu['marks_status'] == 'Pass' ? 'selected' : '' ?>>Pass</option>
                                <option value="Fail" <?= $stu['marks_status'] == 'Fail' ? 'selected' : '' ?>>Fail</option>
                                <option value="Absent" <?= $stu['marks_status'] == 'Absent' ? 'selected' : '' ?>>Absent</option>
                                <option value="Malpractice" <?= $stu['marks_status'] == 'Malpractice' ? 'selected' : '' ?>>Malpractice</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($students)): ?>
                    <tr><td colspan="4" style="text-align:center;">No students have approved applications for this exam and program.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div style="margin-top: 24px; text-align: right;">
            <button type="submit" class="btn btn-primary">Save Marks</button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
