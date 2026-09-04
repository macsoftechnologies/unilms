<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Take Attendance<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div style="margin-bottom: 24px;">
    <a href="<?= base_url('org/attendance') ?>" style="color: var(--primary); text-decoration: none; font-size: 14px;"><i class="fa-solid fa-arrow-left"></i> Back to Sessions</a>
</div>

<div class="header-banner">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 class="header-title">Take Attendance</h1>
            <p class="header-subtitle"><?= esc($session['cohort_name']) ?> | <?= esc($session['subject_name']) ?> | <?= date('d/m/Y', strtotime($session['session_date'])) ?></p>
        </div>
        <div>
            <button type="button" class="btn btn-outline" style="background: white;" onclick="markAll('Present')">Mark All Present</button>
            <button type="button" class="btn btn-outline" style="background: white;" onclick="markAll('Absent')">Mark All Absent</button>
        </div>
    </div>
</div>

<form action="<?= base_url('org/attendance/save') ?>" method="POST">
    <?= csrf_field() ?>
    <input type="hidden" name="session_id" value="<?= $session['id'] ?>">
    
    <div class="card" style="margin-bottom: 24px;">
        <div class="form-group" style="margin-bottom: 0;">
            <label>Topic Taught Today (Optional)</label>
            <input type="text" name="topic_taught" class="form-control" value="<?= esc($session['topic_taught']) ?>" placeholder="Briefly describe what was covered in this session">
        </div>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        <table class="data-table" style="border: none;">
            <thead>
                <tr>
                    <th style="width: 60px; text-align: center;">Roll No</th>
                    <th>Student Name</th>
                    <th style="width: 300px; text-align: center;">Attendance Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($students as $s): ?>
                    <tr>
                        <td style="text-align: center; color: var(--text-muted); font-size: 13px; font-weight: 600;"><?= esc($s['roll_number']) ?></td>
                        <td style="font-weight: 600;"><?= esc($s['first_name'] . ' ' . $s['last_name']) ?></td>
                        <td>
                            <div class="status-toggle">
                                <label class="radio-btn present <?= $s['status'] === 'Present' ? 'active' : '' ?>">
                                    <input type="radio" name="attendance[<?= $s['record_id'] ?>]" value="Present" <?= $s['status'] === 'Present' ? 'checked' : '' ?> onchange="updateToggle(this)"> Present
                                </label>
                                <label class="radio-btn late <?= $s['status'] === 'Late' ? 'active' : '' ?>">
                                    <input type="radio" name="attendance[<?= $s['record_id'] ?>]" value="Late" <?= $s['status'] === 'Late' ? 'checked' : '' ?> onchange="updateToggle(this)"> Late
                                </label>
                                <label class="radio-btn absent <?= $s['status'] === 'Absent' ? 'active' : '' ?>">
                                    <input type="radio" name="attendance[<?= $s['record_id'] ?>]" value="Absent" <?= $s['status'] === 'Absent' ? 'checked' : '' ?> onchange="updateToggle(this)"> Absent
                                </label>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div style="position: sticky; bottom: 20px; background: white; padding: 16px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); display: flex; justify-content: flex-end; z-index: 100; border: 1px solid var(--border-color); margin-top: 24px;">
        <button type="submit" class="btn btn-primary" style="font-size: 16px; padding: 12px 32px;"><i class="fa-solid fa-save"></i> Save Attendance</button>
    </div>
</form>

<style>
    .status-toggle {
        display: flex;
        background: var(--bg-main);
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        height: 36px;
    }
    
    .status-toggle label {
        flex: 1;
        text-align: center;
        line-height: 36px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
        transition: all 0.2s;
        border-right: 1px solid var(--border-color);
        margin: 0;
    }
    
    .status-toggle label:last-child {
        border-right: none;
    }
    
    .status-toggle input[type="radio"] {
        display: none;
    }
    
    .status-toggle label.present.active {
        background: var(--success);
        color: white;
    }
    
    .status-toggle label.late.active {
        background: var(--warning);
        color: white;
    }
    
    .status-toggle label.absent.active {
        background: var(--danger);
        color: white;
    }
</style>

<script>
    function updateToggle(radio) {
        const container = radio.closest('.status-toggle');
        const labels = container.querySelectorAll('label');
        labels.forEach(l => l.classList.remove('active'));
        radio.closest('label').classList.add('active');
    }
    
    function markAll(status) {
        const radios = document.querySelectorAll(`input[value="${status}"]`);
        radios.forEach(r => {
            r.checked = true;
            updateToggle(r);
        });
    }
</script>

<?= $this->endSection() ?>
