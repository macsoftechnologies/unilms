<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Exam Schedules<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Exam Schedules (Timetable)</h1>
        <p class="header-subtitle">Schedule subjects for exams on specific dates and times.</p>
    </div>
</div>

<div class="card" style="margin-bottom: 24px;">
    <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Add Schedule Entry</h2>
    <form action="<?= base_url('org/examinations/save-schedule') ?>" method="POST" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; align-items: end;">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="sch_id">
        
        <div class="form-group" style="margin: 0;">
            <label>Exam</label>
            <select name="exam_id" id="sch_exam" class="form-control" required>
                <option value="">-- Select Exam --</option>
                <?php foreach($exams as $e): ?>
                    <option value="<?= $e['id'] ?>"><?= esc($e['name']) ?> (<?= $e['type'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group" style="margin: 0;">
            <label>Program</label>
            <select name="program_id" id="sch_prog" class="form-control" required>
                <option value="">-- Select Program --</option>
                <?php foreach($programs as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= esc($p['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group" style="margin: 0;">
            <label>Semester</label>
            <select name="semester_id" id="sch_sem" class="form-control" required>
                <option value="">-- Select Semester --</option>
                <?php foreach($semesters as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group" style="margin: 0;">
            <label>Subject</label>
            <select name="subject_id" id="sch_sub" class="form-control" required>
                <option value="">-- Select Subject --</option>
                <?php foreach($subjects as $sub): ?>
                    <option value="<?= $sub['id'] ?>"><?= esc($sub['name']) ?> (<?= esc($sub['code']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group" style="margin: 0;">
            <label>Date</label>
            <input type="date" name="exam_date" id="sch_date" class="form-control" required>
        </div>
        
        <div class="form-group" style="margin: 0;">
            <label>Start Time</label>
            <input type="time" name="start_time" id="sch_start" class="form-control" required>
        </div>
        
        <div class="form-group" style="margin: 0;">
            <label>End Time</label>
            <input type="time" name="end_time" id="sch_end" class="form-control" required>
        </div>
        
        <div class="form-group" style="margin: 0;">
            <label>Marks (Max / Pass)</label>
            <div style="display:flex; gap:8px;">
                <input type="number" min="0" name="max_marks" id="sch_max" class="form-control" value="100" required>
                <input type="number" min="0" name="passing_marks" id="sch_pass" class="form-control" value="40" required>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary" style="grid-column: span 4;">Save Schedule Entry</button>
    </form>
</div>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Exam</th>
                <th>Program / Sem</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Timing</th>
                <th>Marks (Max/Pass)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($schedules as $sch): ?>
                <tr>
                    <td><?= esc($sch['exam_name']) ?></td>
                    <td><?= esc($sch['program_name']) ?><br><small style="color:#666;"><?= esc($sch['semester_name']) ?></small></td>
                    <td><?= esc($sch['subject_name']) ?></td>
                    <td><?= date('d/m/Y', strtotime($sch['exam_date'])) ?></td>
                    <td><?= date('h:i A', strtotime($sch['start_time'])) ?> - <?= date('h:i A', strtotime($sch['end_time'])) ?></td>
                    <td><?= esc($sch['max_marks']) ?> / <?= esc($sch['passing_marks']) ?></td>
                    <td>
                        <button type="button" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px;" onclick='editSch(<?= json_encode($sch) ?>)'>Edit</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($schedules)): ?>
                <tr><td colspan="7" style="text-align:center;">No schedules created yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function editSch(data) {
    document.getElementById('sch_id').value = data.id;
    document.getElementById('sch_exam').value = data.exam_id;
    document.getElementById('sch_prog').value = data.program_id;
    document.getElementById('sch_sem').value = data.semester_id;
    document.getElementById('sch_sub').value = data.subject_id;
    document.getElementById('sch_date').value = data.exam_date;
    document.getElementById('sch_start').value = data.start_time;
    document.getElementById('sch_end').value = data.end_time;
    document.getElementById('sch_max').value = data.max_marks;
    document.getElementById('sch_pass').value = data.passing_marks;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<?= $this->endSection() ?>
