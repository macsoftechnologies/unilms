<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Postal & Courier Dispatch<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <div>
            <h2><i class="fa-solid fa-envelopes-bulk"></i> Postal & Courier Records</h2>
            <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 13px;">Registry of inward received parcels/letters and outward dispatched communications.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Add Postal Record</button>
            <a href="<?= base_url('org/front-office') ?>" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Hub</a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card" style="padding: 14px 20px; margin-bottom: 20px; border-radius: 10px;">
        <form method="GET" action="<?= base_url('org/front-office/postal') ?>" style="display: flex; gap: 12px; align-items: center;">
            <div style="min-width: 180px;">
                <select name="type" class="form-control" style="height: 38px;">
                    <option value="">All Records</option>
                    <option value="Receive" <?= $selected_type === 'Receive' ? 'selected' : '' ?>>Inward (Received)</option>
                    <option value="Dispatch" <?= $selected_type === 'Dispatch' ? 'selected' : '' ?>>Outward (Dispatched)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="height: 38px;"><i class="fa-solid fa-filter"></i> Filter</button>
            <a href="<?= base_url('org/front-office/postal') ?>" class="btn btn-outline" style="height: 38px;">Reset</a>
        </form>
    </div>

    <!-- Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Ref. Number</th>
                    <th>Direction</th>
                    <th>Sender / Recipient</th>
                    <th>Courier Agency</th>
                    <th>Tracking / Consignment</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($records)): foreach($records as $r): ?>
                <tr>
                    <td><span class="badge" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; font-weight: 700;"><?= esc($r['reference_number']) ?></span></td>
                    <td>
                        <span class="badge" style="background: <?= $r['record_type'] === 'Receive' ? '#10b98115' : '#f59e0b15' ?>; color: <?= $r['record_type'] === 'Receive' ? '#10b981' : '#f59e0b' ?>;">
                            <i class="fa-solid <?= $r['record_type'] === 'Receive' ? 'fa-inbox' : 'fa-paper-plane' ?> me-1"></i> <?= esc($r['record_type']) ?>
                        </span>
                    </td>
                    <td>
                        <strong><?= esc($r['sender_receiver_name']) ?></strong>
                        <?php if(!empty($r['address'])): ?>
                            <div style="font-size: 11px; color: var(--text-muted); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= esc($r['address']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($r['courier_name'] ?: 'Speed Post / Hand') ?></td>
                    <td><?= esc($r['tracking_number'] ?: 'N/A') ?></td>
                    <td style="font-size: 12px;"><?= date('d/m/Y', strtotime($r['date'])) ?></td>
                    <td style="font-size: 13px;"><?= esc($r['description'] ?: '-') ?></td>
                    <td>
                        <a href="<?= base_url('org/front-office/postal/delete/' . $r['id']) ?>" class="btn-icon text-danger" onclick="return confirm('Delete this postal record?')" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="8" style="text-align: center; padding: 30px; color: var(--text-muted);">No postal / courier dispatches registered yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Postal Modal -->
<div class="drawer-overlay" id="postalModal" style="display: none;">
    <div class="drawer-content" style="max-width: 500px;">
        <form action="<?= base_url('org/front-office/postal/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 700;"><i class="fa-solid fa-envelopes-bulk me-2" style="color: #f59e0b;"></i> Record Postal / Courier</h3>
                <button type="button" class="btn-close" onclick="closeModal()" style="background: none; border: none; font-size: 18px; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 20px;">
                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Record Type *</label>
                    <select name="record_type" class="form-control" required>
                        <option value="Receive">Inward (Received Mail / Parcel)</option>
                        <option value="Dispatch">Outward (Dispatched Communication)</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Sender / Recipient Name *</label>
                    <input type="text" name="sender_receiver_name" class="form-control" required placeholder="Name of person or institution">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Courier Agency</label>
                        <input type="text" name="courier_name" class="form-control" placeholder="DHL, BlueDart, Post">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Tracking / AWB #</label>
                        <input type="text" name="tracking_number" class="form-control" placeholder="Consignment No">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Date</label>
                    <input type="date" name="date" value="<?= date('Y-m-d') ?>" class="form-control">
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Address</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Destination or origin address"></textarea>
                </div>

                <div class="form-group">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Subject / Description</label>
                    <input type="text" name="description" class="form-control" placeholder="e.g. University affiliation documents">
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Save Record</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() { document.getElementById('postalModal').style.display = 'flex'; }
    function closeModal() { document.getElementById('postalModal').style.display = 'none'; }
</script>
<?= $this->endSection() ?>
