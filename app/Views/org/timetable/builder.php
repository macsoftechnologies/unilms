<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Timetable Builder<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <h1 class="header-title">Timetable Builder</h1>
    <p class="header-subtitle">Assign a template to a cohort and drag-and-drop to build the grid.</p>
</div>

<div class="card" style="margin-bottom: 24px;">
    <form method="GET" action="" style="display: flex; gap: 16px; align-items: flex-end;">
        <div class="form-group" style="flex: 1; margin: 0;">
            <label>Select Cohort to Build Timetable For</label>
            <select name="cohort_id" class="form-control" onchange="this.form.submit()">
                <option value="">-- Choose Cohort --</option>
                <?php foreach($cohorts as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $selected_cohort_id == $c['id'] ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

<?php if($selected_cohort_id): ?>
    <?php if(!$schedule): ?>
        <div class="card" style="text-align: center; padding: 40px;">
            <i class="fa-solid fa-link" style="font-size: 48px; color: var(--text-muted); opacity: 0.5; margin-bottom: 16px;"></i>
            <h3>No Template Assigned</h3>
            <p style="color: var(--text-muted); margin-bottom: 24px;">This cohort doesn't have a timetable template assigned yet.</p>
            
            <form action="<?= base_url('org/timetable/assign') ?>" method="POST" style="max-width: 400px; margin: 0 auto;">
                <?= csrf_field() ?>
                <input type="hidden" name="cohort_id" value="<?= $selected_cohort_id ?>">
                <div class="form-group">
                    <select name="template_id" class="form-control" required>
                        <option value="">-- Choose Master Template --</option>
                        <?php foreach($templates as $t): ?>
                            <option value="<?= $t['id'] ?>"><?= esc($t['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Assign Template & Start Building</button>
            </form>
        </div>
    <?php else: ?>
        
        <div class="card" style="overflow-x: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h2 style="margin: 0; font-size: 18px;">Grid: <?= esc($template['name']) ?></h2>
            </div>
            
            <table class="data-table" style="min-width: 1000px; table-layout: fixed;">
                <thead>
                    <tr>
                        <th style="width: 100px; text-align: center; background: var(--bg-main);">Day</th>
                        <?php foreach($periods as $p): ?>
                            <th style="text-align: center; font-size: 12px; border-left: 1px solid var(--border-color);">
                                <div style="font-weight: 700; color: var(--primary);"><?= esc($p['period_name']) ?></div>
                                <div style="color: var(--text-muted); margin-top: 4px;"><?= date('h:i A', strtotime($p['start_time'])) ?></div>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $days = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'];
                    foreach($days as $day_num => $day_name): 
                    ?>
                        <tr>
                            <td style="font-weight: 700; text-align: center; background: var(--bg-main); border-bottom: 1px solid var(--border-color);"><?= $day_name ?></td>
                            
                            <?php foreach($periods as $p): ?>
                                <?php 
                                    $is_break = $p['is_break'];
                                    $entry = $entries_map[$day_num][$p['id']] ?? null;
                                ?>
                                <td style="border-left: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); text-align: center; padding: 8px; vertical-align: top; background: <?= $is_break ? 'rgba(245, 158, 11, 0.05)' : 'white' ?>;">
                                    <?php if($is_break): ?>
                                        <div style="color: #f59e0b; font-weight: 600; font-size: 12px; margin-top: 10px;">BREAK</div>
                                    <?php else: ?>
                                        <button class="btn btn-outline" style="width: 100%; font-size: 11px; padding: 8px; border-style: dashed; display: flex; flex-direction: column; align-items: center; gap: 4px; min-height: 60px; <?= $entry ? 'border-color: var(--primary); background: rgba(79, 70, 229, 0.05);' : '' ?>" onclick="openSlotModal(<?= $day_num ?>, <?= $p['id'] ?>, '<?= htmlspecialchars(json_encode($entry)) ?>')">
                                            <?php if($entry): ?>
                                                <?php 
                                                    // Find subject name manually for UI display since we didn't join in controller
                                                    $s_name = ''; $f_name = '';
                                                    foreach($subjects as $s) if($s['id'] == $entry['subject_id']) $s_name = $s['code'];
                                                    foreach($faculty as $f) if($f['id'] == $entry['faculty_user_id']) $f_name = $f['full_name'];
                                                ?>
                                                <strong style="color: var(--primary);"><?= esc($s_name) ?></strong>
                                                <span style="color: var(--text-muted);"><?= esc($f_name) ?></span>
                                                <?php if($entry['room_number']): ?>
                                                    <span style="background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid var(--border-color);"><?= esc($entry['room_number']) ?></span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <i class="fa-solid fa-plus" style="color: var(--text-muted);"></i>
                                                <span style="color: var(--text-muted);">Assign</span>
                                            <?php endif; ?>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
    <?php endif; ?>
<?php endif; ?>

<!-- Slot Assignment Modal -->
<div id="slotModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 400px;">
        <h3 style="margin-top: 0;">Assign Slot</h3>
        <form id="slotForm" method="POST" onsubmit="saveSlot(event)">
            <?= csrf_field() ?>
            <input type="hidden" name="schedule_id" value="<?= $schedule ? $schedule['id'] : '' ?>">
            <input type="hidden" name="day" id="modalDay">
            <input type="hidden" name="period_id" id="modalPeriod">
            
            <div class="form-group">
                <label>Subject</label>
                <select name="subject_id" id="modalSubject" class="form-control" required>
                    <option value="">-- Select Subject --</option>
                    <?php foreach($subjects as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?> (<?= esc($s['code']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Faculty</label>
                <select name="faculty_id" id="modalFaculty" class="form-control" required>
                    <option value="">-- Select Faculty --</option>
                    <?php foreach($faculty as $f): ?>
                        <option value="<?= $f['id'] ?>"><?= esc($f['full_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Room Number (Optional)</label>
                <input type="text" name="room_number" id="modalRoom" class="form-control">
            </div>
            
            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="button" class="btn btn-outline" style="flex: 1;" onclick="closeSlotModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex: 1;">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openSlotModal(day, periodId, entryDataStr) {
        document.getElementById('modalDay').value = day;
        document.getElementById('modalPeriod').value = periodId;
        
        let entry = null;
        if (entryDataStr && entryDataStr !== 'null') {
            entry = JSON.parse(entryDataStr);
        }
        
        document.getElementById('modalSubject').value = entry ? entry.subject_id : '';
        document.getElementById('modalFaculty').value = entry ? entry.faculty_user_id : '';
        document.getElementById('modalRoom').value = entry ? entry.room_number : '';
        
        document.getElementById('slotModal').style.display = 'flex';
    }
    
    function closeSlotModal() {
        document.getElementById('slotModal').style.display = 'none';
    }
    
    function saveSlot(e) {
        e.preventDefault();
        
        const formData = new FormData(document.getElementById('slotForm'));
        
        fetch('<?= base_url('org/timetable/save_entry') ?>', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Failed to save entry');
            }
        });
    }
</script>

<?= $this->endSection() ?>

