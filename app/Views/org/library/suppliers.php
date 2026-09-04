<?= $this->extend('org/layout') ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Suppliers & Publishers</h2>
        <button class="btn btn-primary" onclick="openModal()">
            <i class="fa-solid fa-plus"></i> Add Supplier
        </button>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact Person</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($suppliers as $sup): ?>
                <tr>
                    <td><?= esc($sup['name']) ?></td>
                    <td><?= esc($sup['contact_person']) ?></td>
                    <td><?= esc($sup['phone']) ?></td>
                    <td><?= esc($sup['email']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick='editSupplier(<?= json_encode($sup) ?>)'>Edit</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<div class="drawer-overlay" id="supplierModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/library/suppliers') ?>" method="post">
            <?= csrf_field() ?>
            <div class="drawer-header">
                <h3 id="modalTitle">Add Supplier</h3>
                <button type="button" class="close-btn" onclick="closeModal()"><i class="fa-solid fa-times"></i></button>
            </div>
            <div class="drawer-body">
                <input type="hidden" name="id" id="sup_id">
                    
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label>Contact Person</label>
                        <input type="text" name="contact_person" id="contact_person" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="tel" pattern="[0-9]{10}" maxlength="10" name="phone" id="phone" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>

                    <div class="form-group mb-3">
                        <label>Address</label>
                        <textarea name="address" id="address" class="form-control"></textarea>
                    </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Supplier</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    resetForm();
    document.getElementById('supplierModal').classList.add('active');
}

function closeModal() {
    document.getElementById('supplierModal').classList.remove('active');
}
function resetForm() {
    document.getElementById('sup_id').value = '';
    document.getElementById('name').value = '';
    document.getElementById('contact_person').value = '';
    document.getElementById('phone').value = '';
    document.getElementById('email').value = '';
    document.getElementById('address').value = '';
    document.getElementById('modalTitle').innerText = 'Add Supplier';
}

function editSupplier(sup) {
    document.getElementById('sup_id').value = sup.id;
    document.getElementById('name').value = sup.name;
    document.getElementById('contact_person').value = sup.contact_person;
    document.getElementById('phone').value = sup.phone;
    document.getElementById('email').value = sup.email;
    document.getElementById('address').value = sup.address;
    document.getElementById('modalTitle').innerText = 'Edit Supplier';
    document.getElementById('supplierModal').classList.add('active');
}
</script>
<?= $this->endSection() ?>
