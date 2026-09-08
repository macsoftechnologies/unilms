<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Attendance Management<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 class="header-title">Attendance Tracker</h1>
            <p class="header-subtitle">Create sessions, take roll call, and view attendance reports.</p>
        </div>
        <div>
            <a href="<?= base_url('org/attendance/report') ?>" class="btn btn-outline" style="background: white;"><i class="fa-solid fa-chart-pie"></i> View Reports</a>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">

    <!-- Create Session Form -->
    <div>
        <div class="card">
            <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Create New Session</h2>
            <form action="<?= base_url('org/attendance/create_session') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label>Cohort</label>
                    <select name="cohort_id" class="form-control" required>
                        <option value="">-- Select Cohort --</option>
                        <?php foreach($cohorts as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Subject</label>
                    <select name="subject_id" class="form-control" required>
                        <option value="">-- Select Subject --</option>
                        <?php foreach($subjects as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?> (<?= esc($s['code']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="session_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fa-solid fa-clipboard-user"></i> Create Session & Take Attendance</button>
            </form>
        </div>
    </div>

    <!-- Recent Sessions -->
    <div>
        <div class="card">
            <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">My Recent Sessions</h2>
            <?php if(empty($recent_sessions)): ?>
                <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                    <i class="fa-solid fa-clock-rotate-left" style="font-size: 48px; opacity: 0.5; margin-bottom: 16px;"></i>
                    <p>No recent attendance sessions found.</p>
                </div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Cohort</th>
                            <th>Subject</th>
                            <th>Topic</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recent_sessions as $rs): ?>
                            <tr>
                                <td style="font-weight: 600;"><?= date('d/m/Y', strtotime($rs['session_date'])) ?></td>
                                <td><?= esc($rs['cohort_name']) ?></td>
                                <td><?= esc($rs['subject_name']) ?></td>
                                <td style="font-size: 13px; color: var(--text-muted);"><?= esc($rs['topic_taught']) ?: '-' ?></td>
                                <td style="text-align: right;">
                                    <a href="<?= base_url('org/attendance/take/' . ($rs['uuid'] ?? $rs['id'])) ?>" class="btn btn-primary" style="padding: 4px 10px; font-size: 12px;"><i class="fa-solid fa-pen"></i> Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
