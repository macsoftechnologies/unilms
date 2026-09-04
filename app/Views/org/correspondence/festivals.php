<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Festival Greetings<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Festival Greetings</h1>
        <p class="header-subtitle">Schedule and send festival campaigns to staff and students.</p>
    </div>
    <div>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-paper-plane"></i> Schedule Campaign</button>
    </div>
</div>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Festival Name</th>
                <th>Audience</th>
                <th>Template Used</th>
                <th>Send Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($festivals as $f): ?>
                <tr>
                    <td style="font-weight: 600; color: var(--primary);"><?= esc($f['festival_name']) ?></td>
                    <td><span class="badge badge-secondary"><?= esc($f['audience']) ?></span></td>
                    <td><?= esc($f['template_name']) ?></td>
                    <td><?= esc(date('d/m/Y', strtotime($f['send_date']))) ?></td>
                    <td>
                        <?php if($f['status'] == 'Sent'): ?>
                            <span style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">DISPATCHED</span>
                        <?php else: ?>
                            <span style="background: rgba(245, 158, 11, 0.1); color: var(--warning); padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;"><?= esc(strtoupper($f['status'])) ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($festivals)): ?>
                <tr><td colspan="5" style="text-align: center;">No festival campaigns scheduled yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Drawer -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/correspondence/save_festival') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="form_id">
            
            <div class="drawer-header">
                <h3 id="modal_title">Schedule Campaign</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <div class="drawer-body">
                <div class="form-group">
                    <label>Festival Name</label>
                    <input type="text" name="festival_name" id="form_name" class="form-control" required placeholder="e.g. Diwali 2026">
                </div>
                
                <div class="form-group">
                    <label>Message Template</label>
                    <select name="template_id" id="form_template" class="form-control" required>
                        <option value="">-- Select Template --</option>
                        <?php foreach($templates as $t): ?>
                            <option value="<?= $t['id'] ?>"><?= esc($t['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Target Audience</label>
                    <select name="audience" id="form_audience" class="form-control" required>
                        <option value="All">All Users (Staff & Students)</option>
                        <option value="Students">Students Only</option>
                        <option value="Staff">Staff Only</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Dispatch Date</label>
                    <input type="date" name="send_date" id="form_date" class="form-control" required>
                    <small style="color: var(--text-muted); display: block; margin-top: 8px;"><i class="fa-solid fa-bolt"></i> Note: For this initial release, all campaigns dispatch notifications immediately upon save.</small>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Dispatch Campaign</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    $('#form_id').val('');
    $('#form_name').val('');
    $('#form_template').val('');
    $('#form_audience').val('All');
    $('#form_date').val('<?= date('Y-m-d') ?>');
    $('#modal_title').text('Schedule Campaign');
    $('#addModal').addClass('active');
}
function closeModal() {
    $('#addModal').removeClass('active');
}
</script>

<?= $this->endSection() ?>
