<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Exam Reports<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Examinations Reports</h2>
    </div>
    
    <!-- D-Form: Exam Fee Defaults -->
    <div class="card" style="margin-bottom: 30px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h3>D-Form: Exam Fee Defaulters</h3>
        <p style="color: #666; font-size: 14px;">Students who have applied for an exam but have not paid the exam fee.</p>
        <table class="data-table" style="margin-top: 15px;">
            <thead><tr><th>Exam Name</th><th>Student</th><th>Roll No</th></tr></thead>
            <tbody>
                <?php if(!empty($dues)): foreach($dues as $d): ?>
                <tr>
                    <td><?= esc($d['exam_name']) ?></td>
                    <td><?= esc($d['first_name'].' '.$d['last_name']) ?></td>
                    <td><strong><?= esc($d['roll_number']) ?></strong></td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="3">No fee defaulters found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Exam Registrations Summary -->
    <div class="card" style="margin-bottom: 30px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h3>Exam Registrations Summary</h3>
        <table class="data-table" style="margin-top: 15px;">
            <thead><tr><th>Exam Name</th><th>Total Applications</th><th>Fee Paid Count</th></tr></thead>
            <tbody>
                <?php if(!empty($counts)): foreach($counts as $c): ?>
                <tr>
                    <td><strong><?= esc($c['exam_name']) ?></strong></td>
                    <td><?= esc($c['total_applied']) ?></td>
                    <td><span style="color:green;"><?= esc($c['total_paid']) ?></span></td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="3">No application data.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Progress Report -->
    <div class="card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h3>Student Progress Report</h3>
        <form method="GET" action="" style="display: flex; gap: 10px; align-items: flex-end; margin-bottom: 20px;">
            <div class="form-group" style="margin: 0;">
                <label>Select Student</label>
                <select name="student_id" class="form-control" style="width: 300px;">
                    <option value="">-- Select Student --</option>
                    <?php foreach($students as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= isset($_GET['student_id']) && $_GET['student_id'] == $s['id'] ? 'selected' : '' ?>>
                            <?= esc($s['first_name'].' '.$s['last_name'].' - '.$s['roll_number']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="btn btn-primary">Generate Report</button>
        </form>
        
        <?php if(isset($progress)): ?>
            <?php if(!empty($selected_student)): ?>
                <h4>Report for: <?= esc($selected_student['first_name'].' '.$selected_student['last_name'].' ('.$selected_student['roll_number'].')') ?></h4>
            <?php endif; ?>
            
            <table class="data-table" style="margin-top: 15px;">
                <thead>
                    <tr>
                        <th>Exam</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Marks Obtained</th>
                        <th>Max / Passing</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($progress)): foreach($progress as $p): ?>
                    <tr>
                        <td><strong><?= esc($p['exam_name']) ?></strong></td>
                        <td><?= esc($p['subject_name']) ?></td>
                        <td><?= esc(date('d/m/Y', strtotime($p['exam_date']))) ?></td>
                        <td><strong><?= esc($p['marks_obtained']) ?></strong></td>
                        <td><?= esc($p['max_marks'].' / '.$p['passing_marks']) ?></td>
                        <td>
                            <?php if($p['status'] == 'Pass'): ?>
                                <span class="badge badge-success">Pass</span>
                            <?php elseif($p['status'] == 'Fail'): ?>
                                <span class="badge badge-danger">Fail</span>
                            <?php else: ?>
                                <span class="badge badge-warning"><?= esc($p['status']) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="6">No marks data found for this student.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <?php if(!empty($progress)): ?>
                <div style="margin-top: 20px;">
                    <button class="btn btn-outline" onclick="window.print()"><i class="fa-solid fa-print"></i> Print Progress Report</button>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>
