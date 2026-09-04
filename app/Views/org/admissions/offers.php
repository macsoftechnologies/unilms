<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Admission Offers<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Manage Offers</h2>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Generate Offer</button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>App Number</th>
                    <th>Applicant Name</th>
                    <th>Program</th>
                    <th>Fee Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($offers)): foreach($offers as $offer): ?>
                <tr>
                    <td><strong><?= esc($offer['adm_number']) ?></strong></td>
                    <td><?= esc($offer['full_name']) ?></td>
                    <td><?= esc($offer['program_name']) ?></td>
                    <td>₹<?= number_format($offer['fee_amount'], 2) ?></td>
                    <td><?= esc($offer['due_date']) ?: 'N/A' ?></td>
                    <td>
                        <span class="badge badge-<?= $offer['status'] == 'Pending' ? 'warning' : ($offer['status'] == 'Accepted' ? 'success' : 'danger') ?>">
                            <?= esc($offer['status']) ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline"><i class="fa-solid fa-download"></i> PDF</button>
                        <!-- In reality, accepting an offer might happen via applicant portal or manually here -->
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="7">No offers generated yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Modal -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content">
        <form action="<?= base_url('org/admissions/offers/generate') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header">
                <h3>Generate New Offer</h3>
                <button type="button" class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Select Verified Application</label>
                    <select name="application_id" class="form-control" required>
                        <option value="">-- Choose Application --</option>
                        <?php foreach($applications as $app): ?>
                            <option value="<?= $app['id'] ?>"><?= esc($app['adm_number']) ?> - <?= esc($app['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="margin-top: 15px;">
                    <label>Admission Fee Amount (₹)</label>
                    <input type="number" min="0" step="0.01" name="fee_amount" class="form-control" required>
                </div>
                <div class="form-group" style="margin-top: 15px;">
                    <label>Payment Due Date</label>
                    <input type="date" name="due_date" class="form-control" required>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Generate Offer</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() { $('#addModal').addClass('active'); }
function closeModal() { $('#addModal').removeClass('active'); }
</script>
<?= $this->endSection() ?>
