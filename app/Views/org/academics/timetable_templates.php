<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Timetable Templates<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
<div class="view-header">
    <h2>Timetable Templates & Periods</h2>
    <button class="btn btn-primary" onclick="$('#modal-template').addClass('active')"><i class="fa-solid fa-plus"></i> New Template</button>
</div>

<div class="grid-layout" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 24px;">
    <?php if(!empty($templates)): ?>
        <?php foreach($templates as $t): ?>
            <div class="stat-card" style="box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                    <div>
                        <h3 style="margin: 0; font-size: 16px;"><?= esc($t['name']) ?></h3>
                        <p style="margin: 4px 0 0; font-size: 12px; color: var(--text-muted);"><?= esc($t['description']) ?></p>
                    </div>
                    <form action="<?= base_url('org/academics/timetable/templates/delete') ?>" method="POST" onsubmit="return confirm('Delete this template and ALL its periods?')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="template_id" value="<?= $t['id'] ?>">
                        <button type="submit" class="btn btn-outline" style="border:none; color:var(--danger); padding:4px;"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </div>
                
                <div style="margin-bottom: 16px;">
                    <strong style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Time Periods</strong>
                    <ul style="list-style: none; padding: 0; margin: 8px 0;">
                        <?php if(!empty($templatePeriods[$t['id']])): ?>
                            <?php foreach($templatePeriods[$t['id']] as $p): ?>
                                <li style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px dashed var(--border-color); font-size: 13px;">
                                    <span>
                                        <?php if($p['is_break']): ?>
                                            <i class="fa-solid fa-mug-hot" style="color:var(--warning); margin-right:4px;"></i>
                                        <?php else: ?>
                                            <i class="fa-regular fa-clock" style="color:var(--primary); margin-right:4px;"></i>
                                        <?php endif; ?>
                                        <?= esc($p['period_name']) ?>
                                    </span>
                                    <span><?= substr($p['start_time'],0,5) ?> - <?= substr($p['end_time'],0,5) ?></span>
                                    <form action="<?= base_url('org/academics/timetable/periods/delete') ?>" method="POST" style="margin-left:8px;" onsubmit="return confirm('Delete period?')">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="period_id" value="<?= $p['id'] ?>">
                                        <button type="submit" style="background:none; border:none; cursor:pointer; color:var(--danger);"><i class="fa-solid fa-xmark"></i></button>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li style="font-size: 12px; color: var(--text-muted);">No periods added yet.</li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <button class="btn btn-outline" style="width: 100%; font-size: 12px; border-style: dashed;" onclick="addPeriod(<?= $t['id'] ?>)">+ Add Time Period</button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted);">
            Create a generic template (e.g. "B.Tech 1st Year") and define the daily time slots.
        </div>
    <?php endif; ?>
</div>

<!-- Add Template Modal -->
<div class="drawer-overlay" id="modal-template">
    <div class="drawer-content">
        <form action="<?= base_url('org/academics/timetable/templates/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header">
                <h3>New Timetable Template</h3>
                <button type="button" class="btn-close" onclick="$('#modal-template').removeClass('active')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Template Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Standard M-F Schedule" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="submit" class="btn btn-primary">Create Template</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Period Modal -->
<div class="drawer-overlay" id="modal-period">
    <div class="drawer-content">
        <form action="<?= base_url('org/academics/timetable/periods/save') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="template_id" id="p_template_id">
            <div class="drawer-header">
                <h3>Add Time Period</h3>
                <button type="button" class="btn-close" onclick="$('#modal-period').removeClass('active')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Period Name</label>
                    <input type="text" name="period_name" class="form-control" placeholder="e.g. Period 1, Lunch Break" required>
                </div>
                <div style="display: flex; gap: 16px;">
                    <div class="form-group" style="flex:1;">
                        <label>Start Time</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label>End Time</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>
                </div>
                <div class="form-group" style="display: flex; align-items: center; gap: 8px; margin-top: 16px;">
                    <input type="checkbox" name="is_break" id="is_break" value="1" style="width:16px; height:16px;">
                    <label for="is_break" style="margin:0; cursor:pointer;">Mark as Break / Lunch (No subjects can be allocated)</label>
                </div>
            </div>
            <div class="drawer-footer">
                <button type="submit" class="btn btn-primary">Add Period</button>
            </div>
        </form>
    </div>
</div>

<script>
function addPeriod(templateId) {
    $('#p_template_id').val(templateId);
    $('#modal-period').addClass('active');
}
</script>
</section>

<?= $this->endSection() ?>
