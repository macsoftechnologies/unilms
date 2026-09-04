<?= $this->extend('lms/layout') ?>
<?= $this->section('page_title') ?>Weekly Class Timetable<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="lms-page-header">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 14px;">
        <div>
            <h1><i class="fa-solid fa-calendar-days me-2" style="color: var(--primary);"></i> Weekly Academic Timetable</h1>
            <p>Live schedule of lectures, laboratory practicals, faculty room assignments, and academic breaks.</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <div class="term-badge-header" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); border: 1px solid rgba(99, 102, 241, 0.2); font-weight: 700; padding: 6px 14px; border-radius: 12px; font-size: 12px;">
                <i class="fa-solid fa-graduation-cap me-1"></i> <?= esc(session('cohort_name') ?: 'B.Tech CSE - Sec A') ?>
            </div>
            <button onclick="window.print()" class="btn btn-outline" style="border-color: var(--border); font-weight: 700; font-size: 12px;">
                <i class="fa-solid fa-print me-1"></i> Print
            </button>
        </div>
    </div>
</div>

<?php if(!$schedule): ?>
    <div class="card" style="text-align: center; padding: 60px 20px; border: 1px solid var(--border); border-radius: 16px;">
        <div style="width: 64px; height: 64px; border-radius: 50%; background: rgba(99, 102, 241, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 16px;">
            <i class="fa-solid fa-calendar-xmark"></i>
        </div>
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 800; color: var(--text-main); margin-bottom: 6px;">No Timetable Published</h2>
        <p style="color: var(--text-muted); font-size: 14px; margin: 0;">Your cohort timetable is being prepared by the department coordinator. Please check back shortly.</p>
    </div>
<?php else: ?>
    
    <?php 
        $days = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'];
        $current_day_num = date('N'); // 1 = Monday, 7 = Sunday
        $today_name = $days[$current_day_num] ?? 'Sunday';
        $today_entries = $entries_map[$current_day_num] ?? [];
    ?>

    <!-- Today's Classes Focus Strip -->
    <div class="card" style="padding: 20px 24px; margin-bottom: 24px; border: 1px solid var(--border); border-radius: 16px; background: var(--surface);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span class="badge" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 5px 12px; border-radius: 20px; font-weight: 800; font-size: 11px;">
                    TODAY • <?= strtoupper($today_name) ?>
                </span>
                <span style="font-size: 13px; color: var(--text-muted); font-weight: 600;">
                    <?= date('d M Y') ?>
                </span>
            </div>
            <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">
                <i class="fa-solid fa-clock me-1 text-primary"></i> <?= count($periods ?? []) ?> Scheduled Periods
            </span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
            <?php foreach($periods as $p): 
                $is_break = !empty($p['is_break']);
                $entry = $today_entries[$p['id']] ?? null;
                $start = strtotime(date('Y-m-d ') . $p['start_time']);
                $end = strtotime(date('Y-m-d ') . $p['end_time']);
                $now = time();
                $is_active = ($now >= $start && $now <= $end);
            ?>
                <div style="border: 1px solid <?= $is_active ? 'var(--primary)' : 'var(--border)' ?>; border-radius: 12px; padding: 14px; background: <?= $is_active ? 'rgba(99, 102, 241, 0.04)' : 'var(--bg-canvas)' ?>; position: relative; display: flex; flex-direction: column; justify-content: space-between;">
                    <?php if($is_active): ?>
                        <div style="position: absolute; top: -8px; right: 10px; background: var(--success); color: white; font-size: 9px; font-weight: 800; padding: 2px 8px; border-radius: 10px; letter-spacing: 0.5px; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.4);">
                            LIVE NOW
                        </div>
                    <?php endif; ?>

                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <span style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">
                                <?= esc($p['period_name']) ?>
                            </span>
                            <span style="font-size: 10.5px; color: var(--text-muted); font-weight: 600;">
                                <?= date('h:i A', strtotime($p['start_time'])) ?>
                            </span>
                        </div>

                        <?php if($is_break): ?>
                            <div style="padding: 10px 0; text-align: center;">
                                <span class="badge" style="background: rgba(245, 158, 11, 0.12); color: var(--warning); padding: 4px 12px; border-radius: 12px; font-weight: 800; font-size: 11px;">
                                    <i class="fa-solid fa-mug-hot me-1"></i> Academic Break
                                </span>
                            </div>
                        <?php elseif($entry): ?>
                            <div style="font-family: 'Outfit', sans-serif; font-size: 14px; font-weight: 800; color: var(--text-main); margin-bottom: 4px;">
                                <?= $entry['subject'] ? esc($entry['subject']['name']) : 'Subject' ?>
                            </div>
                            <?php if(!empty($entry['faculty'])): ?>
                                <div style="font-size: 11.5px; color: var(--text-muted); margin-bottom: 8px;">
                                    <i class="fa-solid fa-chalkboard-user me-1 text-primary"></i> <?= esc($entry['faculty']['full_name']) ?>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($entry['room_number'])): ?>
                                <div>
                                    <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 10.5px; font-weight: 700; padding: 2px 7px; border-radius: 6px;">
                                        <i class="fa-solid fa-door-open me-1"></i> Room <?= esc($entry['room_number']) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div style="color: var(--text-muted); font-size: 12px; font-style: italic; padding: 8px 0;">
                                Free Period / Self Study
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Master Weekly Grid -->
    <div class="card" style="padding: 24px; border: 1px solid var(--border); border-radius: 16px; margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; margin: 0; color: var(--text-main);">
                <i class="fa-solid fa-table-cells me-2" style="color: var(--primary);"></i> Complete Weekly Timetable Grid
            </h2>
            <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11.5px; padding: 4px 10px; border-radius: 12px; font-weight: 700;">
                6 Days / Week
            </span>
        </div>

        <div style="overflow-x: auto; border: 1px solid var(--border); border-radius: 12px;">
            <table class="data-table" style="min-width: 960px; width: 100%; border-collapse: collapse; margin: 0;">
                <thead>
                    <tr style="background: var(--bg-canvas);">
                        <th style="width: 130px; padding: 14px 16px; text-align: center; border-bottom: 2px solid var(--border); font-family: 'Outfit', sans-serif; font-size: 13px; font-weight: 800; color: var(--text-main);">
                            Day / Period
                        </th>
                        <?php foreach($periods as $p): ?>
                            <th style="padding: 14px 12px; text-align: center; border-left: 1px solid var(--border); border-bottom: 2px solid var(--border);">
                                <div style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 13px; color: var(--text-main);"><?= esc($p['period_name']) ?></div>
                                <div style="font-size: 11px; color: var(--text-muted); font-weight: 600; margin-top: 2px;">
                                    <?= date('h:i A', strtotime($p['start_time'])) ?> - <?= date('h:i A', strtotime($p['end_time'])) ?>
                                </div>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach($days as $day_num => $day_name): 
                        $is_today = ($day_num == $current_day_num);
                    ?>
                        <tr style="<?= $is_today ? 'background: rgba(99, 102, 241, 0.03);' : '' ?>">
                            <td style="padding: 16px; text-align: center; border-bottom: 1px solid var(--border); vertical-align: middle; background: <?= $is_today ? 'rgba(99, 102, 241, 0.08)' : 'var(--bg-canvas)' ?>;">
                                <strong style="font-family: 'Outfit', sans-serif; font-size: 13.5px; color: <?= $is_today ? 'var(--primary)' : 'var(--text-main)' ?>; display: block;">
                                    <?= $day_name ?>
                                </strong>
                                <?php if($is_today): ?>
                                    <span class="badge" style="background: var(--primary); color: white; font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 6px; margin-top: 4px; display: inline-block;">
                                        TODAY
                                    </span>
                                <?php endif; ?>
                            </td>
                            
                            <?php foreach($periods as $p): ?>
                                <?php 
                                    $is_break = !empty($p['is_break']);
                                    $entry = $entries_map[$day_num][$p['id']] ?? null;
                                ?>
                                <td style="border-left: 1px solid var(--border); border-bottom: 1px solid var(--border); text-align: center; padding: 12px 10px; vertical-align: middle;">
                                    <?php if($is_break): ?>
                                        <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: var(--warning); font-weight: 800; font-size: 10.5px; padding: 4px 8px; border-radius: 8px;">
                                            <i class="fa-solid fa-mug-hot me-1"></i> BREAK
                                        </span>
                                    <?php elseif($entry): ?>
                                        <div style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
                                            <strong style="font-family: 'Outfit', sans-serif; color: var(--primary); font-size: 13px; font-weight: 800;">
                                                <?= $entry['subject'] ? esc($entry['subject']['name']) : '-' ?>
                                            </strong>
                                            
                                            <?php if(!empty($entry['faculty'])): ?>
                                                <span style="color: var(--text-muted); font-size: 11px; font-weight: 600;">
                                                    <i class="fa-solid fa-chalkboard-user me-1" style="font-size: 10px;"></i> <?= esc($entry['faculty']['full_name']) ?>
                                                </span>
                                            <?php endif; ?>
                                            
                                            <?php if(!empty($entry['room_number'])): ?>
                                                <span class="badge" style="background: var(--bg-canvas); color: var(--text-muted); border: 1px solid var(--border); padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700;">
                                                    <i class="fa-solid fa-door-open me-1 text-primary"></i> <?= esc($entry['room_number']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted); opacity: 0.3; font-size: 16px;">—</span>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php endif; ?>

<?= $this->endSection() ?>
