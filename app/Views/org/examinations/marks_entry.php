<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Marks Entry Selection<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">External Marks Entry</h1>
        <p class="header-subtitle">Select an exam and subject schedule to enter marks for students.</p>
    </div>
</div>

<div class="card" style="margin-bottom: 24px;">
    <form action="" method="GET" style="display: flex; gap: 16px; align-items: end;">
        <div class="form-group" style="margin: 0; flex: 1;">
            <label>Select Exam</label>
            <select name="exam_id" class="form-control" required>
                <option value="">-- Choose Exam --</option>
                <?php foreach($exams as $e): ?>
                    <option value="<?= $e['id'] ?>" <?= (isset($selected_exam_id) && $selected_exam_id == $e['id']) ? 'selected' : '' ?>><?= esc($e['name']) ?> (<?= $e['type'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filter Schedules</button>
    </form>
</div>

<?php if(isset($schedules)): ?>
    <div class="card">
        <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Available Schedules for Marks Entry</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Program</th>
                    <th>Subject</th>
                    <th>Exam Date</th>
                    <th>Marks (Max/Pass)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($schedules as $sch): ?>
                    <tr>
                        <td><?= esc($sch['program_name']) ?></td>
                        <td style="font-weight: 600;"><?= esc($sch['subject_name']) ?></td>
                        <td><?= date('d/m/Y', strtotime($sch['exam_date'])) ?></td>
                        <td><?= esc($sch['max_marks']) ?> / <?= esc($sch['passing_marks']) ?></td>
                        <td>
                            <a href="<?= base_url('org/examinations/enter-marks/' . $sch['id']) ?>" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;">Enter Marks</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($schedules)): ?>
                    <tr><td colspan="5" style="text-align:center;">No schedules found for this exam.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
