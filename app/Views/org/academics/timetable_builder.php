<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Timetable Builder<?= $this->endSection() ?>
<?= $this->section('content') ?>

<style>
.timetable-grid {
    width: 100%;
    border-collapse: collapse;
    background: var(--card-bg, #fff);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}
.timetable-grid th, .timetable-grid td {
    border: 1px solid var(--border-color);
    padding: 12px;
    text-align: center;
    vertical-align: top;
    min-width: 120px;
}
.timetable-grid th {
    background: var(--bg-main);
    font-weight: 600;
    font-size: 13px;
    color: var(--text-main);
}
.time-col {
    background: var(--bg-main);
    font-weight: 600;
    width: 120px;
    font-size: 12px;
}
.cell-break {
    background: #f8fafc;
    color: #94a3b8;
    font-style: italic;
    font-size: 14px;
    vertical-align: middle !important;
}
.cell-droppable {
    min-height: 80px;
    cursor: pointer;
    transition: background 0.2s;
    position: relative;
}
.cell-droppable:hover {
    background: #f8fafc;
}
.cell-content {
    background: #e0e7ff;
    border: 1px solid #c7d2fe;
    border-radius: 6px;
    padding: 8px;
    text-align: left;
    font-size: 12px;
    color: #3730a3;
    position: relative;
    height: 100%;
}
.cell-content .subject { font-weight: 700; margin-bottom: 4px; display: block; }
.cell-content .faculty { font-size: 11px; color: #4f46e5; }
.cell-content .room { font-size: 10px; color: #6366f1; margin-top: 4px; display: block; }

/* Allocations Sidebar */
.allocations-sidebar {
    background: var(--card-bg, #fff);
    border-radius: 8px;
    padding: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    height: calc(100vh - 120px);
    overflow-y: auto;
}
.alloc-item {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 12px;
    margin-bottom: 12px;
    cursor: grab;
}
.alloc-item:active { cursor: grabbing; }
.alloc-item .subj { font-weight: 600; font-size: 13px; color: #334155; }
.alloc-item .fac { font-size: 12px; color: #64748b; margin-top: 4px; }
</style>

<div class="view-header">
    <div>
        <a href="<?= base_url('org/academics/timetable/schedules') ?>" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px; margin-bottom: 8px;"><i class="fa-solid fa-arrow-left"></i> Back to Schedules</a>
        <h2>Timetable Builder: <?= esc($schedule['cohort_name']) ?></h2>
        <p style="color:var(--text-muted); font-size: 14px; margin: 4px 0 0;">Template: <?= esc($template['name']) ?> | Status: <?= strtoupper($schedule['status']) ?></p>
    </div>
</div>

<div style="display: flex; gap: 24px;">
    <!-- Main Timetable Grid -->
    <div style="flex: 1; overflow-x: auto;">
        <table class="timetable-grid">
            <thead>
                <tr>
                    <th class="time-col">Time</th>
                    <th>Mon</th>
                    <th>Tue</th>
                    <th>Wed</th>
                    <th>Thu</th>
                    <th>Fri</th>
                    <th>Sat</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($periods as $p): ?>
                <tr>
                    <td class="time-col">
                        <div style="font-size: 14px; color: #0f172a;"><?= substr($p['start_time'],0,5) ?></div>
                        <div style="font-size: 11px; color: #64748b;">to <?= substr($p['end_time'],0,5) ?></div>
                        <div style="font-size: 10px; margin-top: 4px; color: #94a3b8;"><?= esc($p['period_name']) ?></div>
                    </td>
                    <?php if($p['is_break']): ?>
                        <td colspan="6" class="cell-break">
                            <i class="fa-solid fa-mug-hot" style="margin-right: 8px;"></i> <?= esc($p['period_name']) ?>
                        </td>
                    <?php else: ?>
                        <?php for($day=1; $day<=6; $day++): ?>
                            <?php 
                                $entry = $entries[$day][$p['id']] ?? null; 
                            ?>
                            <td class="cell-droppable" data-day="<?= $day ?>" data-period="<?= $p['id'] ?>" onclick="openSlotModal(<?= $day ?>, <?= $p['id'] ?>)">
                                <?php if($entry): ?>
                                    <div class="cell-content">
                                        <span class="subject"><?= esc($entry['subject_code']) ?></span>
                                        <span class="faculty"><i class="fa-solid fa-user-tie"></i> <?= esc($entry['faculty_name']) ?></span>
                                        <?php if($entry['room_number']): ?>
                                            <span class="room"><i class="fa-solid fa-door-open"></i> <?= esc($entry['room_number']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div style="height: 100%; display: flex; align-items: center; justify-content: center; color: #cbd5e1; font-size: 12px;">
                                        + Assign
                                    </div>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Sidebar for quick reference -->
    <div style="width: 300px;">
        <div class="allocations-sidebar">
            <h3 style="margin: 0 0 16px; font-size: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">Allocated Subjects</h3>
            <?php if(!empty($allocations)): ?>
                <?php foreach($allocations as $a): ?>
                    <div class="alloc-item">
                        <div class="subj"><?= esc($a['subject_code']) ?> - <?= esc($a['subject_name']) ?></div>
                        <div class="fac"><i class="fa-solid fa-user-tie"></i> <?= esc($a['faculty_name']) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="font-size: 13px; color: var(--text-muted); text-align: center; padding: 20px;">
                    No subjects allocated to this cohort yet.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal to Assign Subject to Slot -->
<div class="drawer-overlay" id="modal-assign">
    <div class="drawer-content">
        <form id="assign-form" onsubmit="saveEntry(event)">
            <input type="hidden" name="schedule_id" value="<?= $schedule['id'] ?>">
            <input type="hidden" name="day_of_week" id="m_day">
            <input type="hidden" name="period_id" id="m_period">
            
            <div class="drawer-header">
                <h3>Assign Subject</h3>
                <button type="button" class="btn-close" onclick="$('#modal-assign').removeClass('active')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                <div class="form-group">
                    <label>Select Allocation</label>
                    <select name="allocation_id" class="form-control">
                        <option value="">-- Clear Slot (Remove Entry) --</option>
                        <?php foreach($allocations as $a): ?>
                            <option value="<?= $a['id'] ?>"><?= esc($a['subject_code']) ?> (<?= esc($a['faculty_name']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Room Number (Optional)</label>
                    <input type="text" name="room_number" class="form-control" placeholder="e.g. Lab 3, Room 101">
                </div>
            </div>
            <div class="drawer-footer">
                <button type="submit" class="btn btn-primary">Save Assignment</button>
            </div>
        </form>
    </div>
</div>

<script>
function openSlotModal(day, periodId) {
    <?php if($schedule['status'] === 'published'): ?>
        alert('This schedule is published and cannot be edited. Create a new draft to make changes.');
        return;
    <?php endif; ?>
    
    $('#m_day').val(day);
    $('#m_period').val(periodId);
    $('#modal-assign').addClass('active');
}

function saveEntry(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    
    fetch('<?= base_url('org/academics/timetable/builder/save_entry') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Error saving entry');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred.');
    });
}
</script>

<?= $this->endSection() ?>
