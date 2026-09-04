<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Assessment Config<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">OBE Assessment Configuration</h1>
        <p class="header-subtitle">Configure examination sessions and calculation logics.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    
    <!-- Exam Sessions -->
    <div>
        <div class="card" style="margin-bottom: 24px;">
            <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Create Exam Session</h2>
            <form action="<?= base_url('org/obe/assessment-config/save') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="add_session">
                <div class="form-group">
                    <label>Session Name</label>
                    <input type="text" name="session_name" class="form-control" placeholder="e.g. B.Tech SEM-1 2024" required>
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" class="form-control" required>
                        <option value="Internal">Internal Assessment</option>
                        <option value="External">External Examination</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Create Session</button>
            </form>
        </div>
        
        <div class="card">
            <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Active Sessions</h2>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <?php foreach($sessions as $s): ?>
                    <li style="border-bottom: 1px solid var(--border-color); padding: 8px 0; display: flex; justify-content: space-between;">
                        <strong><?= esc($s['session_name']) ?></strong>
                        <span style="font-size: 12px; color: var(--text-muted);"><?= esc($s['type']) ?></span>
                    </li>
                <?php endforeach; ?>
                <?php if(empty($sessions)): ?>
                    <li>No sessions configured.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
    <!-- Assessment Logic -->
    <div class="card">
        <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Configure Assessment Logic</h2>
        <form action="<?= base_url('org/obe/assessment-config/save') ?>" method="POST" style="margin-bottom: 24px;">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="add_config">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label>Exam Session</label>
                    <select name="exam_session_id" class="form-control" required>
                        <?php foreach($sessions as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= esc($s['session_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Short Name (e.g. MID-1)</label>
                    <input type="text" name="short_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Max Marks</label>
                    <input type="number" min="0" step="0.5" name="max_marks" class="form-control" value="100.0" required>
                </div>
                <div class="form-group">
                    <label>Passing Marks</label>
                    <input type="number" min="0" step="0.5" name="passing_marks" class="form-control" value="40.0" required>
                </div>
                <div class="form-group">
                    <label>Average Logic</label>
                    <select name="average_logic" class="form-control" required>
                        <option value="Standard">Standard (No averaging)</option>
                        <option value="Best of N">Best of N</option>
                        <option value="Average of N">Average of N</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>N Value (if applicable)</label>
                    <input type="number" min="0" name="n_value" class="form-control" value="1">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save Configuration</button>
        </form>
        
        <h3 style="font-size: 16px; margin-bottom: 12px; border-top: 1px solid var(--border-color); padding-top: 16px;">Configured Exams</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Exam</th>
                    <th>Max / Pass</th>
                    <th>Logic</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($configs as $c): ?>
                    <tr>
                        <td style="font-weight: 500;"><?= esc($c['short_name']) ?></td>
                        <td><?= esc($c['max_marks']) ?> / <?= esc($c['passing_marks']) ?></td>
                        <td><?= esc($c['average_logic']) ?> <?= $c['average_logic'] != 'Standard' ? "({$c['n_value']})" : '' ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($configs)): ?>
                    <tr><td colspan="3" style="text-align: center;">No configs found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?= $this->endSection() ?>
