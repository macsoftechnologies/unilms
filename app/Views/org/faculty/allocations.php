<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Subject Allocations<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
<div class="view-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h2 style="margin: 0; font-size: 22px; font-weight: 700; color: var(--text-primary);"><i class="fa-solid fa-user-tie" style="color: #7C3AED;"></i> Subject & Faculty Allocations</h2>
        <p style="margin: 4px 0 0; color: var(--text-secondary); font-size: 13px;">Assign teaching professors and lab instructors to subject courses and student batches.</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="<?= base_url('org/timetable') ?>" class="btn btn-outline" style="padding: 10px 16px; border-radius: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-calendar-days" style="color: #7C3AED;"></i> Timetable & Periods
        </a>
        <button class="btn btn-primary" onclick="$('#modal-alloc').addClass('active')" style="background: #7C3AED; border: none; padding: 10px 18px; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-plus-circle"></i> Allocate Subject
        </button>
    </div>
</div>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>Faculty</th>
                <th>Subject</th>
                <th>Cohort</th>
                <th>Semester</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($allocations)): ?>
                <?php foreach($allocations as $a): ?>
                <tr>
                    <td><strong><?= esc($a['faculty_name']) ?></strong></td>
                    <td><?= esc($a['subject_code']) ?> - <?= esc($a['subject_name']) ?></td>
                    <td><span class="badge badge-primary"><?= esc($a['cohort_name']) ?></span></td>
                    <td><?= esc($a['semester_name'] ?? 'All') ?></td>
                    <td>
                        <form action="<?= base_url('org/faculty/allocations/delete') ?>" method="POST" onsubmit="return confirm('Remove allocation?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="allocation_id" value="<?= $a['id'] ?>">
                            <button type="submit" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px; color: var(--danger); border-color: var(--danger);"><i class="fa-solid fa-unlink"></i> Remove</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center; padding: 40px; color: var(--text-muted);">No subjects allocated to faculty yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="drawer-overlay" id="modal-alloc">
    <div class="drawer-content">
        <form action="<?= base_url('org/faculty/allocations/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header">
                <h3>Allocate Subject to Faculty</h3>
                <button type="button" class="btn-close" onclick="$('#modal-alloc').removeClass('active')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Faculty Member <span class="text-danger">*</span></label>
                    <select name="faculty_user_id" class="form-control" required>
                        <option value="">-- Select Faculty Member --</option>
                        <?php foreach($faculties as $f): ?>
                            <option value="<?= $f['user_id'] ?>">
                                <?= esc($f['full_name']) ?> <?= !empty($f['employee_code']) ? '(' . esc($f['employee_code']) . ')' : (!empty($f['designation']) ? '— ' . esc($f['designation']) : '') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <select name="subject_id" class="form-control" required>
                        <?php foreach($subjects as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= esc($s['code']) ?> - <?= esc($s['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Cohort (Class)</label>
                    <select name="cohort_id" class="form-control" required>
                        <?php foreach($cohorts as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Semester (Optional limit)</label>
                    <select name="semester_id" class="form-control">
                        <option value="">-- Apply to entire duration --</option>
                        <?php foreach($semesters as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="button" class="btn btn-outline" onclick="$('#modal-alloc').removeClass('active')">Cancel</button>
                <button type="submit" class="btn btn-primary">Allocate</button>
            </div>
        </form>
    </div>
</div>
</section>

<?= $this->endSection() ?>
