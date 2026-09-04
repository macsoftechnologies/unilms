<?= $this->extend('parent/layout') ?>
<?= $this->section('page_title') ?>Academic Marks & Grades<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Student Selector -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
    <div style="display: flex; gap: 10px;">
        <?php foreach($students as $s): ?>
            <a href="<?= base_url('parent/dashboard/select_student/'.$s['id']) ?>" 
               style="text-decoration: none; padding: 8px 18px; border-radius: 6px; font-weight: 600; font-size: 14px;
                      <?= $s['id'] == $selected_student['id'] ? 'background: var(--primary); color: white;' : 'background: white; color: var(--text-main); border: 1px solid var(--border-color);' ?>">
                <i class="fa-solid fa-graduation-cap me-1"></i> <?= esc($s['first_name'] . ' ' . $s['last_name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
    <div style="font-size: 13px; color: var(--text-muted);">
        Roll Number: <strong><?= esc($selected_student['roll_number']) ?></strong>
    </div>
</div>

<!-- Semester External Results -->
<div class="card">
    <h2 style="margin-top: 0; font-size: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
        <i class="fa-solid fa-award me-2" style="color: #4f46e5;"></i> Semester Examination Results
    </h2>

    <table class="data-table">
        <thead>
            <tr>
                <th>Examination Name</th>
                <th>Subject</th>
                <th>Exam Date</th>
                <th>Score Obtained</th>
                <th>Result Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($external_marks)): foreach($external_marks as $em): ?>
            <tr>
                <td><strong><?= esc($em['exam_name'] ?? 'Final Exam') ?></strong></td>
                <td><?= esc($em['subject_name']) ?></td>
                <td><?= date('d/m/Y', strtotime($em['exam_date'])) ?></td>
                <td style="font-weight: 700; font-size: 16px; color: #4f46e5;"><?= esc($em['marks_obtained']) ?></td>
                <td>
                    <?php if($em['status'] === 'Pass'): ?>
                        <span style="background: rgba(16,185,129,0.12); color: #10b981; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                            <i class="fa-solid fa-circle-check me-1"></i> Pass
                        </span>
                    <?php else: ?>
                        <span style="background: rgba(239,68,68,0.12); color: #ef4444; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                            <i class="fa-solid fa-circle-xmark me-1"></i> <?= esc($em['status']) ?>
                        </span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td colspan="5" style="text-align: center; padding: 24px; color: var(--text-muted);">No external semester results released yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Continuous Internal Assessments -->
<div class="card" style="margin-top: 24px;">
    <h2 style="margin-top: 0; font-size: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
        <i class="fa-solid fa-list-check me-2" style="color: #0ea5e9;"></i> Continuous Internal Evaluation (CIE) Marks
    </h2>

    <table class="data-table">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Component / Assessment</th>
                <th>Max Marks</th>
                <th>Marks Awarded</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($internal_marks)): foreach($internal_marks as $im): ?>
            <tr>
                <td><strong><?= esc($im['subject_name']) ?> (<?= esc($im['subject_code']) ?>)</strong></td>
                <td><?= esc($im['component_name']) ?></td>
                <td><?= esc($im['max_marks']) ?></td>
                <td style="font-weight: 700; color: #0ea5e9; font-size: 15px;"><?= esc($im['score']) ?></td>
                <td>
                    <span style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; padding: 3px 8px; border-radius: 10px; font-size: 11px;">
                        <?= $im['is_locked'] ? 'Approved & Locked' : 'Provisional' ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td colspan="5" style="text-align: center; padding: 24px; color: var(--text-muted);">No internal test evaluations recorded yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
