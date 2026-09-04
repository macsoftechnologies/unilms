<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Books Catalog<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Books Catalog</h2>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Add Book</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Accession No.</th>
                    <th>Title & Author</th>
                    <th>Category</th>
                    <th>ISBN</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($books)): foreach($books as $b): ?>
                <tr>
                    <td><strong><?= esc($b['accession_no']) ?></strong></td>
                    <td><?= esc($b['title']) ?><br><small><?= esc($b['author']) ?></small></td>
                    <td><?= esc($b['category']) ?></td>
                    <td><?= esc($b['isbn']) ?></td>
                    <td>
                        <?php if($b['status'] == 'available'): ?>
                            <span class="badge badge-success">Available</span>
                        <?php else: ?>
                            <span class="badge badge-secondary"><?= esc(ucfirst($b['status'])) ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5">No books found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Drawer -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/library/save_book') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="form_id">
            <div class="drawer-header">
                <h3 id="modal_title">Add Book</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label>Accession No.</label>
                        <input type="text" name="accession_no" id="form_accession_no" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label>ISBN</label>
                        <input type="text" name="isbn" id="form_isbn" class="form-control">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label>Title</label>
                    <input type="text" name="title" id="form_title" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label>Author</label>
                    <input type="text" name="author" id="form_author" class="form-control">
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label>Category</label>
                        <input type="text" name="category" id="form_category" class="form-control">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label>Rack No.</label>
                        <input type="text" name="rack_no" id="form_rack_no" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label>Total Copies</label>
                        <input type="number" min="0" name="copies" id="form_copies" class="form-control" value="1">
                    </div>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Book</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    $('#form_id').val('');
    $('#form_accession_no').val('');
    $('#form_title').val('');
    $('#form_author').val('');
    $('#form_category').val('');
    $('#form_isbn').val('');
    $('#form_rack_no').val('');
    $('#form_copies').val('1');
    $('#modal_title').text('Add Book');
    $('#addModal').addClass('active');
}
function closeModal() {
    $('#addModal').removeClass('active');
}
</script>
<?= $this->endSection() ?>
