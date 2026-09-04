<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Internships Tracking<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Internships Tracking</h1>
        <p class="header-subtitle">Record and monitor student internships.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 3fr; gap: 24px;">
    
    <div class="card">
        <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Log Internship</h2>
        <form action="<?= base_url('org/placements/save-internship') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Student *</label>
                <select name="student_id" class="form-control" required>
                    <option value="">-- Select Student --</option>
                    <?php foreach($students as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= esc($s['first_name']) ?> <?= esc($s['last_name']) ?> (<?= esc($s['roll_number']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Company *</label>
                <select name="company_id" class="form-control" required>
                    <option value="">-- Select Company --</option>
                    <?php foreach($companies as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= esc($c['company_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Role</label>
                <input type="text" name="role" class="form-control" required>
            </div>
            <div style="display: flex; gap: 16px;">
                <div class="form-group" style="flex: 1;">
                    <label>Start Date</label>
                    <input type="date" name="start_date" class="form-control">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>End Date</label>
                    <input type="date" name="end_date" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label>Stipend per Month (₹)</label>
                <input type="number" min="0" step="0.01" name="stipend" class="form-control" value="0.00">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="Ongoing">Ongoing</option>
                    <option value="Completed">Completed</option>
                    <option value="Terminated">Terminated</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Record Internship</button>
        </form>
    </div>
    
    <div class="card">
        <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Internship Records</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Company</th>
                    <th>Role</th>
                    <th>Duration</th>
                    <th>Stipend</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($internships as $i): ?>
                    <tr>
                        <td>
                            <strong><?= esc($i['first_name']) ?> <?= esc($i['last_name']) ?></strong><br>
                            <small style="color: var(--text-muted);"><?= esc($i['roll_number']) ?></small>
                        </td>
                        <td style="font-weight: 500; color: var(--primary);"><?= esc($i['company_name']) ?></td>
                        <td><?= esc($i['role']) ?></td>
                        <td style="white-space: nowrap; font-size: 13px;">
                            <?= $i['start_date'] ? date('d M', strtotime($i['start_date'])) : '?' ?> - <br>
                            <?= $i['end_date'] ? date('d/m/Y', strtotime($i['end_date'])) : '?' ?>
                        </td>
                        <td style="color: green; font-weight: bold;"><?= number_format($i['stipend'], 2) ?></td>
                        <td>
                            <?php if($i['status'] == 'Completed'): ?>
                                <span style="color: green; font-weight: bold;">Completed</span>
                            <?php elseif($i['status'] == 'Terminated'): ?>
                                <span style="color: red; font-weight: bold;">Terminated</span>
                            <?php else: ?>
                                <span style="color: orange; font-weight: bold;">Ongoing</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($internships)): ?>
                    <tr><td colspan="6" style="text-align: center;">No internships recorded.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?= $this->endSection() ?>
