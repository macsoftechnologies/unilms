<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Holidays<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Holidays Calendar</h1>
        <p class="header-subtitle">Define public and institutional holidays.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <div>
        <div class="card">
            <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Add/Edit Holiday</h2>
            <form action="<?= base_url('org/administration/save-holiday') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="hol_id">
                
                <div class="form-group">
                    <label>Holiday Title</label>
                    <input type="text" name="title" id="hol_title" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" id="hol_start" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="end_date" id="hol_end" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" id="hol_type" class="form-control">
                        <option value="Public">Public Holiday</option>
                        <option value="Institutional">Institutional Holiday</option>
                        <option value="Restricted">Restricted Holiday</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Save Holiday</button>
            </form>
        </div>
    </div>
    
    <div>
        <div class="card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Dates</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($holidays as $h): ?>
                        <tr>
                            <td style="font-weight: 600;"><?= esc($h['title']) ?></td>
                            <td><?= date('d/m/Y', strtotime($h['start_date'])) ?> to <?= date('d/m/Y', strtotime($h['end_date'])) ?></td>
                            <td><?= esc($h['type']) ?></td>
                            <td>
                                <button type="button" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px;" onclick='editHol(<?= json_encode($h) ?>)'>Edit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function editHol(data) {
    document.getElementById('hol_id').value = data.id;
    document.getElementById('hol_title').value = data.title;
    document.getElementById('hol_start').value = data.start_date;
    document.getElementById('hol_end').value = data.end_date;
    document.getElementById('hol_type').value = data.type;
}
</script>

<?= $this->endSection() ?>
