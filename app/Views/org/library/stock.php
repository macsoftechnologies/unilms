<?= $this->extend('org/layout') ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Stock Verification</h2>
        <button class="btn btn-primary" onclick="openModal()">
            <i class="fa-solid fa-plus"></i> New Verification
        </button>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Rack No</th>
                    <th>Expected Count</th>
                    <th>Physical Count</th>
                    <th>Missing Books</th>
                    <th>Verified On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($verifications as $v): ?>
                <tr>
                    <td><?= esc($v['rack_no']) ?></td>
                    <td><?= esc($v['expected_count']) ?></td>
                    <td><?= esc($v['physical_count']) ?></td>
                    <td><?= esc($v['missing_books']) ?></td>
                    <td><?= esc($v['verified_on']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick='editStock(<?= json_encode($v) ?>)'>Edit</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Modal -->
<div class="drawer-overlay" id="stockModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/library/stock') ?>" method="post">
            <?= csrf_field() ?>
            <div class="drawer-header">
                <h3 id="modalTitle">New Stock Verification</h3>
                <button type="button" class="close-btn" onclick="closeModal()"><i class="fa-solid fa-times"></i></button>
            </div>
            <div class="drawer-body">
                <input type="hidden" name="id" id="stock_id">
                    
                    <div class="mb-3">
                        <label>Rack No</label>
                        <input type="text" name="rack_no" id="rack_no" class="form-control" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Expected Count</label>
                            <input type="number" min="0" name="expected_count" id="expected_count" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Physical Count</label>
                            <input type="number" min="0" name="physical_count" id="physical_count" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Missing Books (Accession Nos)</label>
                        <textarea name="missing_books" id="missing_books" class="form-control" placeholder="e.g. B001, B005"></textarea>
                    </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Verification</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    resetForm();
    document.getElementById('stockModal').classList.add('active');
}

function closeModal() {
    document.getElementById('stockModal').classList.remove('active');
}

function resetForm() {
    document.getElementById('stock_id').value = '';
    document.getElementById('rack_no').value = '';
    document.getElementById('expected_count').value = '';
    document.getElementById('physical_count').value = '';
    document.getElementById('missing_books').value = '';
    document.getElementById('modalTitle').innerText = 'New Stock Verification';
}

function editStock(v) {
    document.getElementById('stock_id').value = v.id;
    document.getElementById('rack_no').value = v.rack_no;
    document.getElementById('expected_count').value = v.expected_count;
    document.getElementById('physical_count').value = v.physical_count;
    document.getElementById('missing_books').value = v.missing_books;
    document.getElementById('modalTitle').innerText = 'Edit Verification';
    document.getElementById('stockModal').classList.add('active');
}
</script>
<?= $this->endSection() ?>
