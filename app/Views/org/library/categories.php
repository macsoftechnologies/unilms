<?= $this->extend('org/layout') ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Book Categories</h2>
        <button class="btn btn-primary" onclick="openModal()">
            <i class="fa-solid fa-plus"></i> Add Category
        </button>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($categories as $cat): ?>
                <tr>
                    <td><?= $cat['id'] ?></td>
                    <td><?= esc($cat['name']) ?></td>
                    <td><?= esc($cat['description']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick='editCategory(<?= json_encode($cat) ?>)'>Edit</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<div class="drawer-overlay" id="categoryModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/library/categories') ?>" method="post">
            <?= csrf_field() ?>
            <div class="drawer-header">
                <h3 id="modalTitle">Add Category</h3>
                <button type="button" class="close-btn" onclick="closeModal()"><i class="fa-solid fa-times"></i></button>
            </div>
            <div class="drawer-body">
                <input type="hidden" name="id" id="cat_id">
                    
                    <div class="mb-3">
                        <label>Category Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label>Description</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Category</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    resetForm();
    document.getElementById('categoryModal').classList.add('active');
}

function closeModal() {
    document.getElementById('categoryModal').classList.remove('active');
}
function resetForm() {
    document.getElementById('cat_id').value = '';
    document.getElementById('name').value = '';
    document.getElementById('description').value = '';
    document.getElementById('modalTitle').innerText = 'Add Category';
}

function editCategory(cat) {
    document.getElementById('cat_id').value = cat.id;
    document.getElementById('name').value = cat.name;
    document.getElementById('description').value = cat.description;
    document.getElementById('modalTitle').innerText = 'Edit Category';
    document.getElementById('categoryModal').classList.add('active');
}
</script>
<?= $this->endSection() ?>
