<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Admission Categories<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Categories / Castes</h1>
        <p class="header-subtitle">Manage admission categories for scholarship and fee waivers.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <div>
        <div class="card">
            <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Add/Edit Category</h2>
            <form action="<?= base_url('org/admissions/save-category') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="cat_id">
                <div class="form-group">
                    <label>Category Name</label>
                    <input type="text" name="name" id="cat_name" class="form-control" required placeholder="e.g. SC, ST, OBC, General">
                </div>
                <div class="form-group">
                    <label>Description (Optional)</label>
                    <textarea name="description" id="cat_desc" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Save Category</button>
            </form>
        </div>
    </div>
    
    <div>
        <div class="card">
            <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Category List</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($categories as $c): ?>
                        <tr>
                            <td style="font-weight: 600;"><?= esc($c['name']) ?></td>
                            <td><?= esc($c['description']) ?></td>
                            <td>
                                <button type="button" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px;" onclick="editCat(<?= $c['id'] ?>, '<?= esc($c['name'], 'js') ?>', '<?= esc($c['description'], 'js') ?>')">Edit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($categories)): ?>
                        <tr><td colspan="3" style="text-align:center;">No categories defined yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function editCat(id, name, desc) {
    document.getElementById('cat_id').value = id;
    document.getElementById('cat_name').value = name;
    document.getElementById('cat_desc').value = desc;
}
</script>

<?= $this->endSection() ?>
