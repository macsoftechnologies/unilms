<?= $this->extend('parent/layout') ?>
<?= $this->section('page_title') ?>Weekly Class Timetable<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Student Selector -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
    <div style="display: flex; gap: 10px;">
        <?php foreach($students as $s): ?>
            <a href="<?= base_url('parent/dashboard/select_student/' . ($s['uuid'] ?? $s['id'])) ?>" 
               style="text-decoration: none; padding: 8px 18px; border-radius: 6px; font-weight: 600; font-size: 14px;
                      <?= $s['id'] == $selected_student['id'] ? 'background: var(--primary); color: white;' : 'background: white; color: var(--text-main); border: 1px solid var(--border-color);' ?>">
                <i class="fa-solid fa-graduation-cap me-1"></i> <?= esc($s['first_name'] . ' ' . $s['last_name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
    <div style="font-size: 13px; color: var(--text-muted);">
        Roll Number: <strong><?= esc($selected_student['roll_number']) ?></strong>
    </div>
</div>

<div class="card">
    <h2 style="margin-top: 0; font-size: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
        <i class="fa-solid fa-calendar-days me-2" style="color: #4f46e5;"></i> Weekly Class Schedule
    </h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-top: 16px;">
        <?php foreach($days as $day): ?>
            <div style="border: 1px solid var(--border-color); border-radius: 8px; padding: 16px; background: #FAF5FF;">
                <h3 style="margin: 0 0 12px 0; font-size: 15px; font-weight: 700; color: #6B21A8; border-bottom: 1px solid #E9D5FF; padding-bottom: 8px;">
                    <?= $day ?>
                </h3>

                <?php if(!empty($timetable_grid[$day])): ?>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <?php foreach($timetable_grid[$day] as $slot): ?>
                            <div style="background: white; border: 1px solid #E5E7EB; border-radius: 6px; padding: 10px 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
                                <div style="display: flex; justify-content: space-between; font-size: 11px; color: #6B7280; font-weight: 600;">
                                    <span><?= esc($slot['period_name'] ?: 'Period') ?></span>
                                    <span><?= esc($slot['start_time']) ?> - <?= esc($slot['end_time']) ?></span>
                                </div>
                                <div style="font-weight: 700; font-size: 14px; margin-top: 4px; color: #1F2937;">
                                    <?= esc($slot['subject_name']) ?>
                                </div>
                                <?php if(!empty($slot['teacher_name'])): ?>
                                    <div style="font-size: 12px; color: #4F46E5; margin-top: 2px;">
                                        <i class="fa-solid fa-chalkboard-user me-1"></i> <?= esc($slot['teacher_name']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div style="color: #9CA3AF; font-size: 12px; font-style: italic; padding: 10px 0;">
                        No scheduled classes.
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?= $this->endSection() ?>
