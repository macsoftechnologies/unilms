<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Setup Periods - <?= esc($template['name']) ?><?= $this->endSection() ?>
<?= $this->section('content') ?>

<div style="margin-bottom: 20px;">
    <a href="<?= base_url('org/timetable') ?>" style="color: #7C3AED; text-decoration: none; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
        <i class="fa-solid fa-arrow-left"></i> Back to Timetable Templates
    </a>
</div>

<div class="header-banner" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
    <div>
        <h1 class="header-title" style="margin: 0; font-size: 22px; font-weight: 700; color: var(--text-primary);">Setup Periods: <?= esc($template['name']) ?></h1>
        <p class="header-subtitle" style="margin: 4px 0 0; color: var(--text-secondary); font-size: 13px;">Define daily lecture time slots, morning intervals, and lunch breaks for this master template.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <button type="button" class="btn btn-primary" onclick="openAutoGeneratorModal()" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); border: none; padding: 10px 18px; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(124, 58, 237, 0.25);">
            <i class="fa-solid fa-wand-magic-sparkles"></i> ⚡ Auto-Generate Schedule
        </button>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    
    <!-- Periods List -->
    <div>
        <div class="card" style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: var(--text-primary);">Configured Daily Periods (<?= count($periods) ?> Slots)</h3>
            </div>
            <?php if(empty($periods)): ?>
                <div style="text-align: center; padding: 45px 20px; color: var(--text-secondary);">
                    <i class="fa-regular fa-clock" style="font-size: 48px; opacity: 0.35; margin-bottom: 14px; display: block; color: #7C3AED;"></i>
                    <p style="font-size: 15px; font-weight: 600; margin: 0 0 6px;">No period slots added yet.</p>
                    <p style="font-size: 13px; color: var(--text-secondary); margin: 0 0 16px;">Click "Auto-Generate Schedule" to instantly create your bell timetable, or add slots manually on the right.</p>
                    <button type="button" class="btn btn-primary" onclick="openAutoGeneratorModal()" style="background: #7C3AED; border: none; padding: 8px 16px; font-weight: 600; border-radius: 8px;">
                        <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Auto-Generate Now
                    </button>
                </div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr style="background: var(--bg-main, #f8fafc);">
                            <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Period / Break Name</th>
                            <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Time Slot</th>
                            <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase; text-align: center;">Slot Category</th>
                            <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($periods as $p): ?>
                            <tr>
                                <td style="padding: 12px 16px; font-weight: 600; color: var(--text-primary);">
                                    <?= esc($p['period_name']) ?>
                                </td>
                                <td style="padding: 12px 16px; font-weight: 600; font-size: 13px; color: var(--text-primary);">
                                    <?= date('h:i A', strtotime($p['start_time'])) ?> - <?= date('h:i A', strtotime($p['end_time'])) ?>
                                </td>
                                <td style="padding: 12px 16px; text-align: center;">
                                    <?php if($p['is_break']): ?>
                                        <span class="badge" style="background: rgba(245, 158, 11, 0.12); color: #d97706; font-weight: 700; padding: 4px 10px; border-radius: 6px; font-size: 11.5px;">
                                            <i class="fa-solid fa-mug-hot me-1"></i> Break / Lunch
                                        </span>
                                    <?php else: ?>
                                        <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: #059669; font-weight: 700; padding: 4px 10px; border-radius: 6px; font-size: 11.5px;">
                                            <i class="fa-solid fa-chalkboard-user me-1"></i> Academic Class
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 12px 16px; text-align: right;">
                                    <form action="<?= base_url('org/timetable/periods/delete/' . ($p['uuid'] ?? $p['id'])) ?>" method="POST" onsubmit="return confirm('Delete this period slot?');" style="display: inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn-icon text-danger" style="background: none; border: 1px solid var(--border-color); padding: 6px 10px; border-radius: 6px; color: #DC2626; cursor: pointer;" title="Delete Slot">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Manual Add Form -->
    <div>
        <div class="card" style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px;">
            <h3 style="margin: 0 0 16px; font-size: 16px; font-weight: 700; color: var(--text-primary);"><i class="fa-solid fa-plus-circle me-1" style="color: #7C3AED;"></i> Add Custom Slot</h3>
            <form action="<?= base_url('org/timetable/periods/save') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="template_id" value="<?= $template['id'] ?>">

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Period Name / Title <span class="text-danger">*</span></label>
                    <input type="text" name="period_name" class="form-control" placeholder="e.g. Period 1, Library / Sports" required style="font-size: 13.5px;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-size: 12.5px; font-weight: 700;">Start Time <span class="text-danger">*</span></label>
                        <input type="time" name="start_time" class="form-control no-flatpickr" required style="font-size: 13px;">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 12.5px; font-weight: 700;">End Time <span class="text-danger">*</span></label>
                        <input type="time" name="end_time" class="form-control no-flatpickr" required style="font-size: 13px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label style="font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_break" value="1">
                        This slot is an Interval / Lunch Break
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; background: #7C3AED; border: none; padding: 10px; font-weight: 700; border-radius: 8px;">
                    <i class="fa-solid fa-plus me-1"></i> Add Period Slot
                </button>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- INTERACTIVE AUTO-GENERATE SCHEDULE MODAL   -->
<!-- ========================================== -->
<div class="drawer-overlay" id="autoGenModal">
    <div class="drawer-content" style="max-width: 540px;">
        <form action="<?= base_url('org/timetable/periods/auto-generate/' . ($template['uuid'] ?? $template['id'])) ?>" method="POST" id="autoGenForm">
            <?= csrf_field() ?>
            <div class="drawer-header" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); color: #fff; padding: 18px 22px; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 17px; font-weight: 700; color: #fff;"><i class="fa-solid fa-wand-magic-sparkles me-2"></i> Auto-Generate Timetable Schedule</h3>
                <button type="button" class="btn-close" onclick="closeAutoGeneratorModal()" style="color: #fff; background: none; border: none; font-size: 18px; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 22px; max-height: calc(85vh - 130px); overflow-y: auto;">
                <div style="background: rgba(124, 58, 237, 0.06); border: 1px solid rgba(124, 58, 237, 0.2); border-radius: 10px; padding: 12px 14px; margin-bottom: 18px; font-size: 13px; color: var(--text-primary);">
                    <i class="fa-solid fa-circle-info me-1" style="color: #7C3AED;"></i> Configure your college start time, class durations, and break positions to automatically calculate all daily period slots.
                </div>

                <!-- 1. Timing Basics -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                    <div class="form-group">
                        <label style="font-size: 12.5px; font-weight: 700;">College Start Time <span class="text-danger">*</span></label>
                        <input type="time" name="start_time" id="cfg_start_time" value="09:00" class="form-control no-flatpickr" required onchange="renderPreview()">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 12.5px; font-weight: 700;">Period Duration (mins) <span class="text-danger">*</span></label>
                        <input type="number" name="period_duration" id="cfg_duration" value="50" min="30" max="120" class="form-control" required oninput="renderPreview()">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Total Academic Periods per Day <span class="text-danger">*</span></label>
                    <select name="total_periods" id="cfg_total_periods" class="form-control" required onchange="renderPreview()">
                        <option value="5">5 Periods per day</option>
                        <option value="6">6 Periods per day</option>
                        <option value="7" selected>7 Periods per day (Standard)</option>
                        <option value="8">8 Periods per day</option>
                    </select>
                </div>

                <!-- 2. Morning / Tea Break Config -->
                <div style="border: 1px solid var(--border-color); border-radius: 10px; padding: 14px; margin-bottom: 16px; background: var(--bg-main, #f8fafc);">
                    <label style="font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 8px; cursor: pointer; margin-bottom: 10px; color: var(--text-primary);">
                        <input type="checkbox" name="has_tea_break" id="cfg_has_tea" value="1" checked onchange="toggleBreakOptions(); renderPreview();">
                        <span><i class="fa-solid fa-mug-hot" style="color: #d97706;"></i> Include Morning Interval / Tea Break</span>
                    </label>
                    <div id="tea_break_fields" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group" style="margin: 0;">
                            <label style="font-size: 11.5px; font-weight: 600; color: var(--text-secondary);">Break After Period</label>
                            <select name="tea_break_after" id="cfg_tea_after" class="form-control" style="font-size: 13px;" onchange="renderPreview()">
                                <option value="1">After Period 1</option>
                                <option value="2" selected>After Period 2</option>
                                <option value="3">After Period 3</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin: 0;">
                            <label style="font-size: 11.5px; font-weight: 600; color: var(--text-secondary);">Break Duration (mins)</label>
                            <input type="number" name="tea_break_duration" id="cfg_tea_dur" value="15" min="5" max="30" class="form-control" style="font-size: 13px;" oninput="renderPreview()">
                        </div>
                    </div>
                </div>

                <!-- 3. Lunch Break Config -->
                <div style="border: 1px solid var(--border-color); border-radius: 10px; padding: 14px; margin-bottom: 16px; background: var(--bg-main, #f8fafc);">
                    <label style="font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 8px; cursor: pointer; margin-bottom: 10px; color: var(--text-primary);">
                        <input type="checkbox" name="has_lunch_break" id="cfg_has_lunch" value="1" checked onchange="toggleBreakOptions(); renderPreview();">
                        <span><i class="fa-solid fa-utensils" style="color: #059669;"></i> Include Lunch Break</span>
                    </label>
                    <div id="lunch_break_fields" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group" style="margin: 0;">
                            <label style="font-size: 11.5px; font-weight: 600; color: var(--text-secondary);">Lunch After Period</label>
                            <select name="lunch_break_after" id="cfg_lunch_after" class="form-control" style="font-size: 13px;" onchange="renderPreview()">
                                <option value="3">After Period 3</option>
                                <option value="4" selected>After Period 4</option>
                                <option value="5">After Period 5</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin: 0;">
                            <label style="font-size: 11.5px; font-weight: 600; color: var(--text-secondary);">Lunch Duration (mins)</label>
                            <input type="number" name="lunch_break_duration" id="cfg_lunch_dur" value="45" min="20" max="90" class="form-control" style="font-size: 13px;" oninput="renderPreview()">
                        </div>
                    </div>
                </div>

                <!-- Overwrite Checkbox -->
                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="font-size: 12.5px; font-weight: 600; display: flex; align-items: center; gap: 8px; cursor: pointer; color: #DC2626;">
                        <input type="checkbox" name="clear_existing" value="1" checked>
                        Clear and replace all existing slots in this template
                    </label>
                </div>

                <!-- Live Calculated Schedule Preview -->
                <div style="border-top: 1px solid var(--border-color); padding-top: 14px;">
                    <label style="font-size: 12.5px; font-weight: 700; color: var(--text-primary); display: block; margin-bottom: 8px;">
                        <i class="fa-solid fa-eye me-1" style="color: #7C3AED;"></i> Live Schedule Preview:
                    </label>
                    <div id="preview_slots_box" style="background: var(--bg-main, #f8fafc); border: 1px solid var(--border-color); border-radius: 8px; padding: 10px; max-height: 180px; overflow-y: auto; font-size: 12.5px;">
                        <!-- Generated dynamically via JS -->
                    </div>
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeAutoGeneratorModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background: #7C3AED; border: none; padding: 10px 20px; font-weight: 700;">
                    <i class="fa-solid fa-check-circle me-1"></i> Generate All Periods
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAutoGeneratorModal() {
    $('#autoGenModal').addClass('active');
    toggleBreakOptions();
    renderPreview();
}
function closeAutoGeneratorModal() {
    $('#autoGenModal').removeClass('active');
}

function toggleBreakOptions() {
    var hasTea = $('#cfg_has_tea').is(':checked');
    $('#tea_break_fields').css('opacity', hasTea ? '1' : '0.4').find('input, select').prop('disabled', !hasTea);

    var hasLunch = $('#cfg_has_lunch').is(':checked');
    $('#lunch_break_fields').css('opacity', hasLunch ? '1' : '0.4').find('input, select').prop('disabled', !hasLunch);
}

function formatTime(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // hour '0' should be '12'
    minutes = minutes < 10 ? '0' + minutes : minutes;
    var strTime = (hours < 10 ? '0' + hours : hours) + ':' + minutes + ' ' + ampm;
    return strTime;
}

function renderPreview() {
    var startTimeVal = $('#cfg_start_time').val() || '09:00';
    var duration = parseInt($('#cfg_duration').val()) || 50;
    var totalPeriods = parseInt($('#cfg_total_periods').val()) || 7;

    var hasTea = $('#cfg_has_tea').is(':checked');
    var teaAfter = parseInt($('#cfg_tea_after').val()) || 2;
    var teaDur = parseInt($('#cfg_tea_dur').val()) || 15;

    var hasLunch = $('#cfg_has_lunch').is(':checked');
    var lunchAfter = parseInt($('#cfg_lunch_after').val()) || 4;
    var lunchDur = parseInt($('#cfg_lunch_dur').val()) || 45;

    var timeParts = startTimeVal.split(':');
    var current = new Date(2026, 0, 1, parseInt(timeParts[0]), parseInt(timeParts[1]), 0);

    var html = '<ul style="margin: 0; padding-left: 18px; line-height: 1.8;">';

    for (var p = 1; p <= totalPeriods; p++) {
        var startStr = formatTime(current);
        var next = new Date(current.getTime() + duration * 60000);
        var endStr = formatTime(next);

        html += '<li><strong>Period ' + p + '</strong>: <span style="color: #059669; font-weight: 600;">' + startStr + ' - ' + endStr + '</span> (' + duration + 'm)</li>';
        current = next;

        if (hasTea && p === teaAfter && p < totalPeriods) {
            var bStartStr = formatTime(current);
            var bNext = new Date(current.getTime() + teaDur * 60000);
            var bEndStr = formatTime(bNext);
            html += '<li style="color: #d97706; font-weight: 700;">☕ Morning Break: ' + bStartStr + ' - ' + bEndStr + ' (' + teaDur + 'm)</li>';
            current = bNext;
        }

        if (hasLunch && p === lunchAfter && p < totalPeriods) {
            var lStartStr = formatTime(current);
            var lNext = new Date(current.getTime() + lunchDur * 60000);
            var lEndStr = formatTime(lNext);
            html += '<li style="color: #d97706; font-weight: 700;">🍱 Lunch Break: ' + lStartStr + ' - ' + lEndStr + ' (' + lunchDur + 'm)</li>';
            current = lNext;
        }
    }

    html += '</ul>';
    $('#preview_slots_box').html(html);
}
</script>

<?= $this->endSection() ?>
