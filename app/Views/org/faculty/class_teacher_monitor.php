<?= $this->extend('org/layout') ?>

<?= $this->section('title') ?>
Class Teacher Cohort Monitor
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Class Teacher Cohort Monitor</h3>
            <p class="text-muted mb-0">Unified dashboard for Section Incharges: Attendance Defaulter Alerts, Multi-Subject Marks Matrix & Parent Notifications.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= site_url('org/faculty/allocations') ?>" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="fas fa-arrow-left me-1"></i> Allocations
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Cohort Selector -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <form method="GET" action="<?= site_url('org/faculty/classTeacherMonitor') ?>" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Select In-charge Cohort Section</label>
                <select name="cohort_id" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($cohorts as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $selectedCohortId == $c['id'] ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-8 text-md-end">
                <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill me-2">
                    <i class="fas fa-user-times me-1"></i> <?= count($defaulters) ?> Defaulters (&lt; 75% Attendance)
                </span>
                <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-2 rounded-pill">
                    <i class="fas fa-user-graduate me-1"></i> <?= count($students) ?> Total Students
                </span>
            </div>
        </form>
    </div>

    <!-- Attendance Defaulters List Card -->
    <?php if (!empty($defaulters)): ?>
        <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-danger border-4">
            <div class="p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold text-danger mb-0"><i class="fas fa-triangle-exclamation me-2"></i> Attendance Defaulters Requiring Intervention</h5>
                        <small class="text-muted">Students falling below statutory 75% attendance threshold.</small>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th>Roll Number</th>
                                <th>Student Name</th>
                                <th>Attendance %</th>
                                <th>Sessions Attended</th>
                                <th class="text-end pe-3">Immediate Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($defaulters as $d): ?>
                                <tr>
                                    <td class="fw-bold font-monospace"><?= esc($d['roll_number']) ?></td>
                                    <td><?= esc($d['first_name'] . ' ' . $d['last_name']) ?></td>
                                    <td>
                                        <span class="badge bg-danger fs-6 px-3 py-1"><?= $d['attendance_pct'] ?>%</span>
                                    </td>
                                    <td><?= $attendanceData[$d['id']]['present'] ?> / <?= $attendanceData[$d['id']]['total'] ?> sessions</td>
                                    <td class="text-end pe-3">
                                        <form action="<?= site_url('org/faculty/sendParentNotice') ?>" method="POST" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="student_id" value="<?= $d['id'] ?>">
                                            <input type="hidden" name="reason" value="Attendance critical at <?= $d['attendance_pct'] ?>%">
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                <i class="fas fa-bell me-1"></i> Send Parent Alert
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Multi-Subject Cross-Performance Marks Matrix -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
        <div class="p-4 border-bottom">
            <h5 class="fw-bold text-dark mb-1"><i class="fas fa-table-cells text-primary me-2"></i> Section-wide Cross-Subject Marks & Attendance Matrix</h5>
            <small class="text-muted">Holistic multi-subject performance view across all active subjects for this cohort.</small>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Roll No</th>
                        <th>Student Name</th>
                        <th style="width: 140px;">Attendance</th>
                        <?php foreach ($subjects as $sub): ?>
                            <th class="text-center" title="<?= esc($sub['name']) ?>"><?= esc($sub['code']) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="<?= 3 + count($subjects) ?>" class="text-center py-5 text-muted">
                                No students enrolled in this cohort.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $stu): ?>
                            <?php $att = $attendanceData[$stu['id']]; ?>
                            <tr>
                                <td class="ps-4 fw-bold font-monospace"><?= esc($stu['roll_number']) ?></td>
                                <td><?= esc($stu['first_name'] . ' ' . $stu['last_name']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px;">
                                            <div class="progress-bar <?= $att['percentage'] < 75 ? 'bg-danger' : 'bg-success' ?>" style="width: <?= $att['percentage'] ?>%;"></div>
                                        </div>
                                        <span class="small fw-bold <?= $att['percentage'] < 75 ? 'text-danger' : 'text-success' ?>"><?= $att['percentage'] ?>%</span>
                                    </div>
                                </td>
                                <?php foreach ($subjects as $sub): ?>
                                    <?php $score = $marksMatrix[$stu['id']][$sub['id']]; ?>
                                    <td class="text-center">
                                        <?php if ($score !== '-'): ?>
                                            <span class="badge bg-light text-dark border font-monospace"><?= $score ?></span>
                                        <?php else: ?>
                                            <span class="text-muted small">-</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
