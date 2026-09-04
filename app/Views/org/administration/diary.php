<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Diary & Notice Board<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Diary / Notice Board</h2>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Post Notice</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Audience</th>
                    <th>Publish Date</th>
                    <th>Expiry Date</th>
                    <th>Posted By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($diaries)): foreach($diaries as $d): ?>
                <tr>
                    <td><strong><?= esc($d['title']) ?></strong></td>
                    <td><span class="badge badge-primary"><?= esc($d['target_audience']) ?></span></td>
                    <td><?= esc(date('d/m/Y', strtotime($d['publish_date']))) ?></td>
                    <td><?= $d['expiry_date'] ? esc(date('d/m/Y', strtotime($d['expiry_date']))) : '<em>Never</em>' ?></td>
                    <td><?= esc($d['created_by_name'] ?? 'System') ?></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-icon" onclick='editDiary(<?= json_encode($d) ?>)'><i class="fa-solid fa-pen"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6">No notices found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Modal -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/administration/save_diary') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="form_id">
            <div class="drawer-header">
                <h3 id="modal_title">Post Notice</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" id="form_title" class="form-control" required placeholder="e.g. Tomorrow is a Holiday">
                </div>
                <div class="form-group">
                    <label>Notice Content</label>
                    <textarea name="content" id="form_content" class="form-control" rows="4" required placeholder="Detailed message..."></textarea>
                </div>
                <div class="form-group">
                    <label>Target Audience</label>
                    <select name="target_audience" id="form_target_audience" class="form-control" required>
                        <option value="All">All</option>
                        <option value="Students">Students Only</option>
                        <option value="Faculty">Faculty Only</option>
                        <option value="Staff">Staff Only</option>
                    </select>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label>Publish Date</label>
                        <input type="date" name="publish_date" id="form_publish_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Expiry Date (Optional)</label>
                        <input type="date" name="expiry_date" id="form_expiry_date" class="form-control">
                    </div>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Notice</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    $('#form_id').val('');
    $('#form_title').val('');
    $('#form_content').val('');
    $('#form_target_audience').val('All');
    $('#form_publish_date').val('<?= date('Y-m-d') ?>');
    $('#form_expiry_date').val('');
    $('#modal_title').text('Post Notice');
    $('#addModal').addClass('active');
}
function editDiary(data) {
    $('#form_id').val(data.id);
    $('#form_title').val(data.title);
    $('#form_content').val(data.content);
    $('#form_target_audience').val(data.target_audience);
    $('#form_publish_date').val(data.publish_date);
    $('#form_expiry_date').val(data.expiry_date);
    $('#modal_title').text('Edit Notice');
    $('#addModal').addClass('active');
}
function closeModal() {
    $('#addModal').removeClass('active');
}
</script>
<?= $this->endSection() ?>
