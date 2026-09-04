<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Exam Grants & Expenditures<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Exam Grants & Expenditures</h2>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <!-- Grants -->
        <div class="card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                <h3>Grants Received</h3>
                <button class="btn btn-sm btn-primary" onclick="openGrantModal()">Add Grant</button>
            </div>
            <table class="data-table">
                <thead><tr><th>Type & Source</th><th>Amount</th><th>Date</th></tr></thead>
                <tbody>
                    <?php if(!empty($grants)): foreach($grants as $g): ?>
                    <tr>
                        <td><strong><?= esc($g['grant_type']) ?></strong><br><small><?= esc($g['source']) ?></small></td>
                        <td><span style="color:green; font-weight:bold;">+<?= esc($g['amount']) ?></span></td>
                        <td><?= esc(date('d/m/Y', strtotime($g['received_date']))) ?></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="3">No grants found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Expenditures -->
        <div class="card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                <h3>Expenditures</h3>
                <button class="btn btn-sm btn-danger" onclick="openExpModal()">Add Expenditure</button>
            </div>
            <table class="data-table">
                <thead><tr><th>Head & Exam</th><th>Amount</th><th>Date</th></tr></thead>
                <tbody>
                    <?php if(!empty($expenditures)): foreach($expenditures as $ex): ?>
                    <tr>
                        <td><strong><?= esc($ex['head']) ?></strong><br><small><?= esc($ex['exam_name'] ?: 'General') ?></small></td>
                        <td><span style="color:red; font-weight:bold;">-<?= esc($ex['amount']) ?></span></td>
                        <td><?= esc(date('d/m/Y', strtotime($ex['date']))) ?></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="3">No expenditures found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Grant Modal -->
<div class="drawer-overlay" id="grantModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/examinations/save_grant') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header">
                <h3>Add Grant</h3>
                <button type="button" class="btn-close" onclick="closeGrantModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Grant Type</label>
                    <input type="text" name="grant_type" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Amount (₹)</label>
                    <input type="number" min="0" name="amount" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Source / Agency</label>
                    <input type="text" name="source" class="form-control">
                </div>
                <div class="form-group">
                    <label>Received Date</label>
                    <input type="date" name="received_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group">
                    <label>Purpose</label>
                    <textarea name="purpose" class="form-control"></textarea>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="submit" class="btn btn-primary">Save Grant</button>
            </div>
        </form>
    </div>
</div>

<!-- Expenditure Modal -->
<div class="drawer-overlay" id="expModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/examinations/save_expenditure') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header">
                <h3>Add Expenditure</h3>
                <button type="button" class="btn-close" onclick="closeExpModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Expenditure Head (e.g. Paper Printing, Invigilation)</label>
                    <input type="text" name="head" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Linked Exam (Optional)</label>
                    <select name="exam_id" class="form-control">
                        <option value="">-- General / Unlinked --</option>
                        <?php foreach($exams as $e): ?>
                        <option value="<?= $e['id'] ?>"><?= esc($e['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Amount (₹)</label>
                    <input type="number" min="0" name="amount" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="submit" class="btn btn-danger">Save Expenditure</button>
            </div>
        </form>
    </div>
</div>

<script>
function openGrantModal() { $('#grantModal').addClass('active'); }
function closeGrantModal() { $('#grantModal').removeClass('active'); }
function openExpModal() { $('#expModal').addClass('active'); }
function closeExpModal() { $('#expModal').removeClass('active'); }
</script>
<?= $this->endSection() ?>
