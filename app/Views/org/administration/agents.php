<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Agents / Counselors<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Admission Agents & Counselors</h1>
        <p class="header-subtitle">Manage agents and track their commission rates.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <div>
        <div class="card">
            <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Add/Edit Agent</h2>
            <form action="<?= base_url('org/administration/save-agent') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="agent_id">
                
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" id="agent_name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" pattern="[0-9]{10}" maxlength="10" name="phone" id="agent_phone" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" id="agent_email" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Commission Rate (%)</label>
                    <input type="number" min="0" step="0.01" name="commission_rate" id="agent_comm" class="form-control" value="0">
                </div>
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="agent_status" class="form-control">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Save Agent</button>
            </form>
        </div>
    </div>
    
    <div>
        <div class="card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Commission</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($agents as $a): ?>
                        <tr>
                            <td style="font-weight: 600;"><?= esc($a['name']) ?></td>
                            <td><?= esc($a['phone']) ?><br><small><?= esc($a['email']) ?></small></td>
                            <td><?= esc($a['commission_rate']) ?>%</td>
                            <td>
                                <?php if($a['status'] == 'Active'): ?>
                                    <span style="color: green; font-weight: bold;">Active</span>
                                <?php else: ?>
                                    <span style="color: red; font-weight: bold;">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px;" onclick='editAgent(<?= json_encode($a) ?>)'>Edit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function editAgent(data) {
    document.getElementById('agent_id').value = data.id;
    document.getElementById('agent_name').value = data.name;
    document.getElementById('agent_phone').value = data.phone;
    document.getElementById('agent_email').value = data.email;
    document.getElementById('agent_comm').value = data.commission_rate;
    document.getElementById('agent_status').value = data.status;
}
</script>

<?= $this->endSection() ?>
