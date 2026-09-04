<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Cohorts & Sections<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 14px;">
        <div>
            <h2 style="margin: 0; font-size: 22px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-users" style="color: #7C3AED;"></i> Manage Cohorts & Class Sections
            </h2>
            <div style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">
                Define student batch intake years and create classroom sections (Sec A, Sec B, Sec C) directly under each batch
            </div>
        </div>
        <div style="display: flex; gap: 10px;">
            <button class="btn btn-primary" onclick="openModal()" style="background: #7C3AED; border: none; padding: 10px 18px; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(124, 58, 237, 0.25);">
                <i class="fa-solid fa-plus-circle"></i> Add Batch (Cohort)
            </button>
        </div>
    </div>
    
    <div class="table-container" style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden;">
        <table class="data-table">
            <thead>
                <tr style="background: var(--bg-main, #f8fafc);">
                    <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Batch Name & Class Sections</th>
                    <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Degree Program</th>
                    <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Academic Year</th>
                    <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Current Semester</th>
                    <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; text-transform: uppercase; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($cohorts)): foreach($cohorts as $c): ?>
                <tr>
                    <td style="padding: 14px 18px;">
                        <strong style="color: var(--text-primary); font-size: 14px; display: block; margin-bottom: 6px;">
                            <?= esc($c['name']) ?>
                        </strong>
                        <!-- Section Badges Inside Cohort -->
                        <div style="display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
                            <span style="font-size: 11.5px; font-weight: 600; color: var(--text-secondary);">Classes:</span>
                            <?php if(!empty($c['sections'])): foreach($c['sections'] as $sec): ?>
                                <span style="background: rgba(16, 185, 129, 0.1); color: #059669; border: 1px solid rgba(16, 185, 129, 0.2); font-weight: 700; font-size: 11.5px; padding: 3px 8px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa-solid fa-door-open" style="font-size: 10px;"></i> <?= esc($sec['name']) ?> (<?= (int)$sec['max_students'] ?> cap)
                                </span>
                            <?php endforeach; else: ?>
                                <span style="font-size: 11.5px; color: var(--text-secondary); font-style: italic;">No classes created yet</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td style="padding: 14px 18px; font-weight: 600; color: var(--text-primary);">
                        <?= esc($c['program_name']) ?>
                    </td>
                    <td style="padding: 14px 18px;">
                        <span style="background: var(--bg-main, #f1f5f9); color: var(--text-primary); font-weight: 700; font-size: 12.5px; padding: 4px 10px; border-radius: 6px;">
                            <?= esc($c['ay_name']) ?>
                        </span>
                    </td>
                    <td style="padding: 14px 18px;">
                        <?php if($c['current_semester_id']): ?>
                            <span class="badge badge-primary" style="background: rgba(124, 58, 237, 0.1); color: #7C3AED; border: 1px solid rgba(124, 58, 237, 0.2); font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 12px;">
                                <?= esc($c['semester_name']) ?>
                            </span>
                        <?php else: ?>
                            <span class="badge badge-warning" style="font-weight: 600;">Not Set</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 14px 18px; text-align: right;">
                        <div class="action-buttons" style="display: inline-flex; gap: 6px; justify-content: flex-end; align-items: center;">
                            <!-- 1-Click Add Class Section Icon Button -->
                            <button type="button" class="btn-icon" onclick="openAddSectionForCohort(<?= $c['id'] ?>, '<?= esc($c['name'], 'js') ?>')" title="➕ Add Class Section (Sec A, Sec B)" style="padding: 6px 10px; border-radius: 6px; border: 1px solid rgba(124, 58, 237, 0.3); background: rgba(124, 58, 237, 0.08); color: #7C3AED; cursor: pointer;">
                                <i class="fa-solid fa-plus-circle"></i>
                            </button>
                            <!-- Edit Cohort -->
                            <button class="btn-icon" onclick='editCohort(<?= json_encode($c) ?>)' title="Edit Batch" style="padding: 6px 10px; border-radius: 6px; border: 1px solid var(--border-color); background: none; color: var(--text-primary); cursor: pointer;">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <!-- Delete Cohort -->
                            <a href="<?= base_url('org/academics/cohorts/delete/'.$c['id']) ?>" class="btn-icon text-danger" onclick="return confirm('Are you sure you want to delete this cohort? Blocks if students enrolled.')" title="Delete Batch" style="padding: 6px 10px; border-radius: 6px; border: 1px solid var(--border-color); background: none; color: #DC2626; cursor: pointer;">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5" style="text-align: center; padding: 30px; color: var(--text-secondary);">No cohorts found. Click "Add Batch (Cohort)" to create your first batch.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- ========================================== -->
<!-- ADD / EDIT COHORT BATCH MODAL             -->
<!-- ========================================== -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content" style="max-width: 500px;">
        <form action="<?= base_url('org/academics/cohorts/save') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="form_id">
            <div class="drawer-header" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); color: #fff; padding: 18px 22px;">
                <h3 id="modal_title" style="margin: 0; font-size: 17px; font-weight: 700; color: #fff;">Add Cohort (Batch)</h3>
                <button type="button" class="btn-close" onclick="closeModal()" style="color: #fff;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body" style="padding: 22px;">
                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Degree Program <span class="text-danger">*</span></label>
                    <select name="program_id" id="form_program_id" class="form-control" required style="font-size: 13.5px;">
                        <option value="" disabled selected>-- Select Program --</option>
                        <?php foreach($programs as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= esc($p['name']) ?> (<?= esc($p['code']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Academic Year (Starting Batch Year) <span class="text-danger">*</span></label>
                    <select name="academic_year_id" id="form_academic_year_id" class="form-control" required style="font-size: 13.5px;">
                        <option value="" disabled selected>-- Select Academic Year --</option>
                        <?php foreach($academic_years as $ay): ?>
                            <option value="<?= $ay['id'] ?>"><?= esc($ay['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Cohort Batch Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="form_name" class="form-control" required placeholder="e.g. B.Tech CSE - 2026 Batch" style="font-size: 13.5px;">
                </div>
                <div class="form-group">
                    <label style="font-size: 12.5px; font-weight: 700;">Current Semester</label>
                    <select name="current_semester_id" id="form_current_semester_id" class="form-control" style="font-size: 13.5px;">
                        <option value="">-- None (Graduated / Not Started) --</option>
                        <?php foreach($semesters as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="drawer-footer" style="padding: 16px 20px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background: #7C3AED; border: none; padding: 10px 20px; font-weight: 700;">Save Cohort</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- QUICK ADD CLASS SECTION MODAL              -->
<!-- ========================================== -->
<div class="drawer-overlay" id="sectionModal">
    <div class="drawer-content" style="max-width: 480px;">
        <form action="<?= base_url('org/academics/sections/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header" style="background: linear-gradient(135deg, #059669 0%, #064E3B 100%); color: #fff; padding: 18px 22px;">
                <h3 style="margin: 0; font-size: 17px; font-weight: 700; color: #fff;"><i class="fa-solid fa-door-open me-2"></i> Add Class Section (Division)</h3>
                <button type="button" class="btn-close" onclick="closeSectionModal()" style="color: #fff;"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <div class="drawer-body" style="padding: 22px;">
                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Target Cohort (Batch) <span class="text-danger">*</span></label>
                    <select name="cohort_id" id="sec_cohort_id" class="form-control" required style="font-size: 13.5px;">
                        <option value="">-- Choose Academic Cohort --</option>
                        <?php foreach($cohorts as $co): ?>
                            <option value="<?= $co['id'] ?>"><?= esc($co['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Section Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="sec_name" class="form-control" required placeholder="e.g. Section A, Section B" style="font-size: 13.5px;">
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Maximum Intake Capacity</label>
                    <input type="number" name="max_students" id="sec_max_students" class="form-control" value="60" min="1" max="250">
                </div>

                <div class="form-group">
                    <label style="font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" checked>
                        Active for Student Enrollments & Timetable
                    </label>
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeSectionModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background: #059669; border: none; padding: 10px 20px; font-weight: 700;"><i class="fa-solid fa-check"></i> Save Class Section</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    $('#form_id').val('');
    $('#form_program_id').val('');
    $('#form_academic_year_id').val('');
    $('#form_current_semester_id').val('');
    $('#form_name').val('');
    $('#modal_title').text('Add Cohort (Batch)');
    $('#addModal').addClass('active');
}
function editCohort(data) {
    $('#form_id').val(data.id);
    $('#form_program_id').val(data.program_id);
    $('#form_academic_year_id').val(data.academic_year_id);
    $('#form_current_semester_id').val(data.current_semester_id);
    $('#form_name').val(data.name);
    $('#modal_title').text('Edit Cohort (Batch)');
    $('#addModal').addClass('active');
}
function closeModal() {
    $('#addModal').removeClass('active');
}

function openAddSectionForCohort(cohortId, cohortName) {
    $('#sec_cohort_id').val(cohortId);
    $('#sec_name').val('');
    $('#sec_max_students').val('60');
    $('#sectionModal').addClass('active');
}
function closeSectionModal() {
    $('#sectionModal').removeClass('active');
}
</script>
<?= $this->endSection() ?>
