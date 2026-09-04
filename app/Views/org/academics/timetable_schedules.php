<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Timetable Schedules<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
<div class="view-header">
    <h2>Cohort Schedules</h2>
    <button class="btn btn-primary" onclick="$('#modal-schedule').addClass('active')"><i class="fa-solid fa-calendar-plus"></i> Create Schedule</button>
</div>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>Cohort</th>
                <th>Semester</th>
                <th>Template</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($schedules)): ?>
                <?php foreach($schedules as $s): ?>
                <tr>
                    <td><strong><?= esc($s['cohort_name']) ?></strong></td>
                    <td><?= esc($s['semester_name'] ?? 'Full Duration') ?></td>
                    <td><?= esc($s['template_name']) ?></td>
                    <td>
                        <?php if($s['status'] == 'published'): ?>
                            <span class="badge badge-success">Published</span>
                        <?php else: ?>
                            <span class="badge" style="background:#f1f5f9; color:#64748b;">Draft</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= base_url('org/academics/timetable/builder/'.$s['id']) ?>" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px; margin-right: 4px;">
                            <i class="fa-solid fa-table-cells"></i> Builder
                        </a>
                        <?php if($s['status'] == 'draft'): ?>
                            <form action="<?= base_url('org/academics/timetable/schedules/publish') ?>" method="POST" style="display:inline;" onsubmit="return confirm('Publish this timetable? It will be visible to students.')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="schedule_id" value="<?= $s['id'] ?>">
                                <button type="submit" class="btn btn-primary" style="padding: 4px 10px; font-size: 12px;">Publish</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center; padding: 40px; color: var(--text-muted);">No schedules created yet. Create a schedule to link a cohort with a template.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Schedule Modal -->
<div class="drawer-overlay" id="modal-schedule">
    <div class="drawer-content">
        <form action="<?= base_url('org/academics/timetable/schedules/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header">
                <h3>Create Schedule for Cohort</h3>
                <button type="button" class="btn-close" onclick="$('#modal-schedule').removeClass('active')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Cohort</label>
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
                <div class="form-group">
                    <label>Timetable Template</label>
                    <select name="template_id" class="form-control" required>
                        <?php foreach($templates as $t): ?>
                            <option value="<?= $t['id'] ?>"><?= esc($t['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color:var(--text-muted)">The template defines the daily time slots.</small>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="submit" class="btn btn-primary">Create Schedule</button>
            </div>
        </form>
    </div>
</div>
</section>

<?= $this->endSection() ?>
