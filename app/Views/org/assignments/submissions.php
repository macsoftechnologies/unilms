<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Assignment Submissions<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
<div class="view-header" style="display: flex; justify-content: space-between; align-items: flex-end;">
    <div>
        <a href="<?= base_url('org/assignments') ?>" style="color: var(--primary); text-decoration: none; font-size: 14px; margin-bottom: 8px; display: inline-block;"><i class="fa-solid fa-arrow-left"></i> Back to Assignments</a>
        <h2 style="margin: 0; font-size: 24px;"><?= esc($assignment['title']) ?></h2>
        <p style="color:var(--text-muted); font-size: 14px; margin: 4px 0 0;">
            <span style="font-weight: 500; color: var(--text-primary);"><?= esc($assignment['subject_name']) ?></span> &bull; Max Marks: <?= esc($assignment['max_marks']) ?> &bull; Due: <?= date('d/m/Y, h:i A', strtotime($assignment['due_date'])) ?>
        </p>
    </div>
</div>

<div class="widget" style="padding: 0; overflow: hidden; border: 1px solid var(--border-color);">
    <table class="data-table" style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 80px;">Roll No</th>
                <th>Student</th>
                <th>Submission Status</th>
                <th>Submission File</th>
                <th>Marks</th>
                <th style="width: 100px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($students as $st): ?>
                <?php 
                    $sub = isset($submissions[$st['id']]) ? $submissions[$st['id']] : null;
                ?>
                <tr>
                    <td><strong><?= esc($st['roll_number']) ?></strong></td>
                    <td><?= esc($st['first_name'] . ' ' . $st['last_name']) ?></td>
                    <td>
                        <?php if(!$sub): ?>
                            <span class="badge" style="background: rgba(238,93,80,0.1); color: var(--danger);">Not Submitted</span>
                        <?php elseif($sub['status'] == 'graded'): ?>
                            <span class="badge" style="background: rgba(5,205,153,0.1); color: var(--success);">Graded</span>
                            <br><small style="color: var(--text-muted);"><?= date('d/m/Y, h:i A', strtotime($sub['submitted_at'])) ?></small>
                        <?php else: ?>
                            <span class="badge" style="background: rgba(245,158,11,0.1); color: #d97706;">Submitted</span>
                            <br><small style="color: var(--text-muted);"><?= date('d/m/Y, h:i A', strtotime($sub['submitted_at'])) ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($sub && $sub['file_path']): ?>
                            <a href="<?= base_url($sub['file_path']) ?>" target="_blank" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px;"><i class="fa-solid fa-file-arrow-down"></i> Download</a>
                        <?php elseif($sub && $sub['submission_text']): ?>
                            <button onclick="alert('<?= esc(str_replace(["\r", "\n"], ['\r', '\n'], addslashes($sub['submission_text']))) ?>')" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px;"><i class="fa-solid fa-align-left"></i> View Text</button>
                        <?php else: ?>
                            <span style="color: var(--text-muted); font-size: 13px;">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($sub && $sub['status'] == 'graded'): ?>
                            <strong style="font-size: 16px; color: var(--primary);"><?= esc($sub['marks_obtained']) ?></strong> / <?= $assignment['max_marks'] ?>
                        <?php else: ?>
                            <span style="color: var(--text-muted); font-size: 13px;">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button onclick="openGradeModal(<?= $st['id'] ?>, '<?= esc(addslashes($st['first_name'] . ' ' . $st['last_name'])) ?>', '<?= $sub ? esc($sub['marks_obtained']) : '' ?>', '<?= $sub ? esc(addslashes($sub['feedback'])) : '' ?>')" class="btn btn-primary" style="padding: 6px 12px; font-size: 13px;"><i class="fa-solid fa-check"></i> Grade</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Grading Modal -->
<div id="gradeModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px); align-items:center; justify-content:center;">
    <div style="background:var(--card-bg, #fff); border-radius:16px; width:90%; max-width: 500px; box-shadow:0 20px 60px rgba(0,0,0,0.3); overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: var(--bg-main);">
            <h3 style="margin:0; font-size:18px;">Grade Student</h3>
            <button onclick="document.getElementById('gradeModal').style.display='none'" style="background:none; border:none; font-size:20px; cursor:pointer; color:var(--text-muted);">&times;</button>
        </div>
        
        <form action="<?= base_url('org/assignments/grade') ?>" method="POST" style="padding: 24px;">
            <?= csrf_field() ?>
            <input type="hidden" name="assignment_id" value="<?= $assignment['id'] ?>">
            <input type="hidden" name="student_id" id="modal_student_id" value="">
            
            <div style="margin-bottom: 20px;">
                <span style="font-size: 13px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Student</span>
                <div id="modal_student_name" style="font-size: 16px; font-weight: 600; margin-top: 4px;"></div>
            </div>

            <div class="form-group">
                <label>Marks Obtained (out of <?= $assignment['max_marks'] ?>)</label>
                <input type="number" min="0" step="0.01" name="marks_obtained" id="modal_marks" class="form-control" max="<?= $assignment['max_marks'] ?>" required>
            </div>
            
            <div class="form-group">
                <label>Feedback for Student</label>
                <textarea name="feedback" id="modal_feedback" class="form-control" rows="4" placeholder="Good work on the implementation..."></textarea>
            </div>

            <div style="text-align: right; margin-top: 24px;">
                <button type="button" onclick="document.getElementById('gradeModal').style.display='none'" class="btn btn-outline" style="margin-right: 12px;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">Save Grade</button>
            </div>
        </form>
    </div>
</div>

<script>
function openGradeModal(studentId, studentName, marks, feedback) {
    document.getElementById('modal_student_id').value = studentId;
    document.getElementById('modal_student_name').innerText = studentName;
    document.getElementById('modal_marks').value = marks;
    document.getElementById('modal_feedback').value = feedback;
    document.getElementById('gradeModal').style.display = 'flex';
}
</script>

</section>
<?= $this->endSection() ?>
