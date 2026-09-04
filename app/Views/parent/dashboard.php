<?= $this->extend('parent/layout') ?>
<?= $this->section('page_title') ?>Dashboard<?= $this->endSection() ?>
<?= $this->section('content') ?>

<?php if(empty($students)): ?>
    <div class="card" style="text-align: center; padding: 40px;">
        <h2>Welcome to the Parent Portal</h2>
        <p>No students are currently linked to your account.</p>
    </div>
<?php else: ?>

    <!-- Student Selector -->
    <div style="display: flex; gap: 12px; margin-bottom: 24px;">
        <?php foreach($students as $s): ?>
            <a href="<?= base_url('parent/dashboard/select_student/'.$s['id']) ?>" 
               style="text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 500; 
                      <?= $s['id'] == $selected_student['id'] ? 'background: var(--primary); color: white;' : 'background: white; color: var(--text-main); border: 1px solid var(--border-color);' ?>">
                <?= esc($s['first_name']) ?> <?= esc($s['last_name']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
        
        <!-- Profile Card -->
        <div class="card">
            <h2 style="margin-top: 0; font-size: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">Student Profile</h2>
            <div style="text-align: center; margin: 20px 0;">
                <div style="background: #E5E7EB; width: 80px; height: 80px; border-radius: 50%; margin: 0 auto 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-user" style="font-size: 32px; color: #9CA3AF;"></i>
                </div>
                <h3 style="margin: 0;"><?= esc($selected_student['first_name']) ?> <?= esc($selected_student['last_name']) ?></h3>
                <p style="color: var(--text-muted); margin: 4px 0;">Roll No: <?= esc($selected_student['roll_number']) ?></p>
            </div>
            
            <h3 style="font-size: 14px; color: var(--text-muted); text-transform: uppercase;">Attendance Overview</h3>
            <div style="background: #F3F4F6; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span>Total Sessions</span>
                    <strong><?= $attendance['total_sessions'] ?? 0 ?></strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Present</span>
                    <strong style="color: green;"><?= $attendance['present_count'] ?? 0 ?></strong>
                </div>
            </div>
            
            <h3 style="font-size: 14px; color: var(--text-muted); text-transform: uppercase;">Fee Balance</h3>
            <div style="background: rgba(239, 68, 68, 0.1); padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--danger); font-weight: 500;">Outstanding Dues</span>
                    <strong style="color: var(--danger); font-size: 20px;">₹<?= number_format($fee_dues ?? 0, 2) ?></strong>
                </div>
            </div>
        </div>
        
        <!-- Main Details -->
        <div>
            <div class="card">
                <h2 style="margin-top: 0; font-size: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">External Examination Results</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Exam</th>
                            <th>Subject</th>
                            <th>Marks</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($marks as $m): ?>
                            <tr>
                                <td><?= esc($m['exam_name']) ?></td>
                                <td><?= esc($m['subject_name']) ?></td>
                                <td style="font-weight: bold;"><?= esc($m['marks_obtained']) ?></td>
                                <td>
                                    <?php if($m['status'] == 'Pass'): ?>
                                        <span style="color: green; font-weight: bold;">Pass</span>
                                    <?php elseif($m['status'] == 'Fail'): ?>
                                        <span style="color: red; font-weight: bold;">Fail</span>
                                    <?php else: ?>
                                        <span style="color: orange; font-weight: bold;"><?= esc($m['status']) ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if(empty($marks)): ?>
                            <tr><td colspan="4" style="text-align: center;">No external marks recorded yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <h2 style="margin-top: 0; font-size: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">Recent Circulars & Notices</h2>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <?php foreach($notices as $n): ?>
                        <li style="border-bottom: 1px solid var(--border-color); padding: 12px 0;">
                            <div style="font-size: 12px; color: var(--text-muted);"><?= date('d/m/Y', strtotime($n['publish_date'])) ?></div>
                            <div style="font-weight: 500; margin: 4px 0; color: var(--primary);"><?= esc($n['title']) ?></div>
                            <div style="font-size: 14px; color: var(--text-main);"><?= esc($n['description']) ?></div>
                        </li>
                    <?php endforeach; ?>
                    <?php if(empty($notices)): ?>
                        <li>No recent notices available.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

    </div>

<?php endif; ?>

<?= $this->endSection() ?>
