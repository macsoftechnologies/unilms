<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Student Directory<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header" style="flex-wrap: wrap; gap: 15px;">
        <div>
            <h2><i class="fa-solid fa-user-graduate"></i> Student Directory</h2>
            <p style="margin: 4px 0 0; color: var(--text-muted, #64748b); font-size: 13px;">Manage enrolled students, profiles, imports, and academic progression.</p>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="<?= base_url('org/students/create') ?>" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> Add Student</a>
            <a href="<?= base_url('org/students/import') ?>" class="btn btn-outline"><i class="fa-solid fa-file-import"></i> Bulk Import</a>
            <a href="<?= base_url('org/students/bulk-promote') ?>" class="btn btn-outline"><i class="fa-solid fa-arrow-up-right-dots"></i> Bulk Promote</a>
            <a href="<?= base_url('org/students/parents') ?>" class="btn btn-outline"><i class="fa-solid fa-people-roof"></i> Parent Accounts</a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card" style="padding: 16px 20px; margin-bottom: 20px; border-radius: 10px;">
        <form method="GET" action="<?= base_url('org/students') ?>" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Search roll number, name, email, phone..." class="form-control" style="height: 38px;">
            </div>
            <div style="min-width: 180px;">
                <select name="program_id" class="form-control" style="height: 38px;">
                    <option value="">All Programs</option>
                    <?php foreach($programs as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= ($selected_program == $p['id']) ? 'selected' : '' ?>><?= esc($p['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="min-width: 180px;">
                <select name="cohort_id" class="form-control" style="height: 38px;">
                    <option value="">All Cohorts / Batches</option>
                    <?php foreach($cohorts as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($selected_cohort == $c['id']) ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="min-width: 140px;">
                <select name="status" class="form-control" style="height: 38px;">
                    <option value="">All Statuses</option>
                    <option value="Active" <?= ($selected_status === 'Active') ? 'selected' : '' ?>>Active</option>
                    <option value="Promoted" <?= ($selected_status === 'Promoted') ? 'selected' : '' ?>>Promoted</option>
                    <option value="Detained" <?= ($selected_status === 'Detained') ? 'selected' : '' ?>>Detained</option>
                    <option value="Graduated" <?= ($selected_status === 'Graduated') ? 'selected' : '' ?>>Graduated</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="height: 38px;"><i class="fa-solid fa-magnifying-glass"></i> Filter</button>
            <?php if(!empty($search) || !empty($selected_program) || !empty($selected_cohort) || !empty($selected_status)): ?>
                <a href="<?= base_url('org/students') ?>" class="btn btn-outline" style="height: 38px;"><i class="fa-solid fa-rotate-left"></i> Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Student Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Roll Number</th>
                    <th>Student Name</th>
                    <th>Program & Cohort</th>
                    <th>Contact Info</th>
                    <th>Parent / Guardian</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($students)): foreach($students as $st): ?>
                <tr>
                    <td>
                        <a href="<?= base_url('org/students/profile/' . $st['id']) ?>" style="font-weight: 600; color: var(--primary, #4f46e5); text-decoration: none;">
                            <?= esc($st['roll_number']) ?>
                        </a>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--text-primary);"><?= esc($st['first_name'] . ' ' . $st['last_name']) ?></div>
                    </td>
                    <td>
                        <div><?= esc($st['program_name'] ?? 'N/A') ?></div>
                        <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: #4f46e5; font-size: 11px;">
                            <?= esc($st['cohort_name'] ?? 'Unassigned') ?>
                        </span>
                    </td>
                    <td>
                        <div style="font-size: 13px;"><i class="fa-regular fa-envelope me-1" style="font-size: 11px; opacity: 0.7;"></i> <?= esc($st['email'] ?? 'N/A') ?></div>
                        <div style="font-size: 12px; color: var(--text-muted);"><i class="fa-solid fa-phone me-1" style="font-size: 10px; opacity: 0.7;"></i> <?= esc($st['phone'] ?? 'N/A') ?></div>
                    </td>
                    <td>
                        <div style="font-size: 13px; font-weight: 500;"><?= esc($st['parent_name'] ?? 'N/A') ?></div>
                        <?php if(!empty($st['parent_phone'])): ?>
                            <div style="font-size: 12px; color: var(--text-muted);"><?= esc($st['parent_phone']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                            $statusBg = '#10b981';
                            if ($st['status'] === 'Detained') $statusBg = '#ef4444';
                            elseif ($st['status'] === 'Promoted') $statusBg = '#3b82f6';
                            elseif ($st['status'] === 'Graduated') $statusBg = '#8b5cf6';
                        ?>
                        <span class="badge" style="background: <?= $statusBg ?>15; color: <?= $statusBg ?>; font-weight: 600;">
                            <?= esc($st['status'] ?? 'Active') ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="<?= base_url('org/students/profile/' . $st['id']) ?>" class="btn-icon" title="View 360 Profile">
                                <i class="fa-solid fa-id-card"></i>
                            </a>
                            <a href="<?= base_url('org/students/edit/' . $st['id']) ?>" class="btn-icon" title="Edit Student">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <i class="fa-solid fa-user-slash" style="font-size: 32px; margin-bottom: 10px; opacity: 0.4; display: block;"></i>
                        No students found matching your criteria.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
