<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Bulk Term Promotion<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <div>
            <h2><i class="fa-solid fa-arrow-up-right-dots"></i> Bulk Term Promotion Wizard</h2>
            <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 13px;">Progress an entire batch to the next academic semester based on attendance and backlog eligibility criteria.</p>
        </div>
        <div>
            <a href="<?= base_url('org/students') ?>" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Back to Directory</a>
        </div>
    </div>

    <!-- Step 1: Select Source Cohort -->
    <div class="card" style="padding: 20px; border-radius: 12px; margin-bottom: 24px;">
        <form method="GET" action="<?= base_url('org/students/bulk-promote') ?>" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px;">
                <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Select Source Cohort / Current Term *</label>
                <select name="cohort_id" class="form-control" required onchange="this.form.submit()">
                    <option value="">-- Choose Cohort to Promote --</option>
                    <?php foreach($cohorts as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($selected_cohort == $c['id']) ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-users-viewfinder"></i> Load Students</button>
        </form>
    </div>

    <?php if($selected_cohort): ?>
    <form action="<?= base_url('org/students/process-bulk-promote') ?>" method="POST">
        <?= csrf_field() ?>

        <!-- Promotion Target Action Bar -->
        <div class="card" style="padding: 18px 24px; border-radius: 12px; margin-bottom: 20px; background: rgba(79, 70, 229, 0.03); border: 1px solid rgba(79, 70, 229, 0.15); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div style="display: flex; gap: 16px; align-items: center; flex: 1; min-width: 280px;">
                <label style="font-size: 14px; font-weight: 700; color: #4f46e5; white-space: nowrap;">
                    <i class="fa-solid fa-turn-up me-1"></i> Target Promotion Cohort:
                </label>
                <select name="target_cohort_id" class="form-control" style="max-width: 320px;">
                    <option value="">Select Destination Next Cohort</option>
                    <?php foreach($cohorts as $c): ?>
                        <?php if($c['id'] != $selected_cohort): ?>
                            <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" name="action" value="promote" class="btn btn-primary" onclick="return confirm('Promote selected students to the target cohort?')">
                    <i class="fa-solid fa-graduation-cap"></i> Promote Selected
                </button>
                <button type="submit" name="action" value="detain" class="btn btn-outline text-danger" onclick="return confirm('Mark selected students as Detained?')">
                    <i class="fa-solid fa-ban"></i> Detain Selected
                </button>
            </div>
        </div>

        <!-- Student Eligibility Table -->
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                        </th>
                        <th>Roll Number</th>
                        <th>Student Name</th>
                        <th>Attendance %</th>
                        <th>Backlogs</th>
                        <th>Academic Standing</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($students)): foreach($students as $st): ?>
                    <tr>
                        <td style="text-align: center;">
                            <input type="checkbox" name="student_ids[]" value="<?= $st['id'] ?>" class="student-checkbox" <?= $st['is_eligible'] ? 'checked' : '' ?>>
                        </td>
                        <td><strong><?= esc($st['roll_number']) ?></strong></td>
                        <td><?= esc($st['first_name'] . ' ' . $st['last_name']) ?></td>
                        <td>
                            <span class="badge" style="background: <?= $st['attendance_pct'] >= 75 ? '#10b98115' : '#ef444415' ?>; color: <?= $st['attendance_pct'] >= 75 ? '#10b981' : '#ef4444' ?>; font-weight: 600;">
                                <?= $st['attendance_pct'] ?>%
                            </span>
                        </td>
                        <td>
                            <span class="badge" style="background: <?= $st['backlogs_count'] == 0 ? '#10b98115' : ($st['backlogs_count'] <= 2 ? '#f59e0b15' : '#ef444415') ?>; color: <?= $st['backlogs_count'] == 0 ? '#10b981' : ($st['backlogs_count'] <= 2 ? '#f59e0b' : '#ef4444') ?>; font-weight: 600;">
                                <?= $st['backlogs_count'] ?> active
                            </span>
                        </td>
                        <td>
                            <?php if($st['is_eligible']): ?>
                                <span class="badge" style="background: #10b98115; color: #10b981; font-weight: 600;">
                                    <i class="fa-solid fa-circle-check me-1"></i> Eligible for Promotion
                                </span>
                            <?php else: ?>
                                <span class="badge" style="background: #ef444415; color: #ef4444; font-weight: 600;">
                                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Ineligible (Review/Detain)
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                            No students currently enrolled in this cohort.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </form>
    <?php endif; ?>

    <script>
        function toggleSelectAll(master) {
            const boxes = document.querySelectorAll('.student-checkbox');
            boxes.forEach(b => b.checked = master.checked);
        }
    </script>
</section>
<?= $this->endSection() ?>
