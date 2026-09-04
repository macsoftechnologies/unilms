<?= $this->extend('lms/layout') ?>
<?= $this->section('page_title') ?>My Attendance<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <h1 class="header-title">My Attendance Dashboard</h1>
    <p class="header-subtitle">Track your presence and view subject-wise attendance breakdowns.</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">

    <!-- Overall Overview -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <div class="card" style="text-align: center;">
            <h2 style="margin-top: 0; font-size: 16px; margin-bottom: 24px;">Overall Attendance</h2>
            
            <?php 
                $pct = $overall['percentage'];
                $color_class = 'success';
                if ($pct < 75) $color_class = 'danger';
                else if ($pct < 85) $color_class = 'warning';
            ?>
            
            <div style="position: relative; width: 150px; height: 150px; margin: 0 auto 24px;">
                <svg viewBox="0 0 36 36" style="width: 100%; height: 100%; transform: rotate(-90deg);">
                    <path style="stroke: var(--bg-main); stroke-width: 3.8; fill: none;" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path style="stroke: var(--<?= $color_class ?>); stroke-width: 3.8; fill: none; stroke-dasharray: <?= $pct ?>, 100; stroke-linecap: round; transition: stroke-dasharray 1s ease-out;" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                    <div style="font-size: 28px; font-weight: 700; color: var(--<?= $color_class ?>);"><?= $pct ?>%</div>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; border-top: 1px solid var(--border-color); padding-top: 16px;">
                <div>
                    <div style="color: var(--success); font-weight: 700; font-size: 20px;"><?= $overall['present'] ?></div>
                    <div style="font-size: 12px; color: var(--text-muted);">Present</div>
                </div>
                <div>
                    <div style="color: var(--warning); font-weight: 700; font-size: 20px;"><?= $overall['late'] ?></div>
                    <div style="font-size: 12px; color: var(--text-muted);">Late</div>
                </div>
                <div>
                    <div style="color: var(--danger); font-weight: 700; font-size: 20px;"><?= $overall['absent'] ?></div>
                    <div style="font-size: 12px; color: var(--text-muted);">Absent</div>
                </div>
            </div>
        </div>
        
        <!-- Recent History -->
        <div class="card">
            <h2 style="margin-top: 0; font-size: 16px; margin-bottom: 16px;">Recent Sessions</h2>
            <?php if(empty($recent_history)): ?>
                <p style="color: var(--text-muted); font-size: 13px; text-align: center;">No attendance history available.</p>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <?php foreach($recent_history as $h): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                            <div>
                                <div style="font-weight: 600; font-size: 14px;"><?= esc($h['subject_name']) ?></div>
                                <div style="font-size: 12px; color: var(--text-muted);"><?= date('d/m/Y', strtotime($h['session_date'])) ?></div>
                            </div>
                            <?php 
                                $bg = 'rgba(16, 185, 129, 0.1)';
                                $col = 'var(--success)';
                                if($h['status'] === 'Absent') { $bg = 'rgba(239, 68, 68, 0.1)'; $col = 'var(--danger)'; }
                                if($h['status'] === 'Late') { $bg = 'rgba(245, 158, 11, 0.1)'; $col = 'var(--warning)'; }
                            ?>
                            <span class="badge" style="background: <?= $bg ?>; color: <?= $col ?>;"><?= $h['status'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Subject Wise Breakdown -->
    <div>
        <div class="card">
            <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 24px;">Subject-wise Breakdown</h2>
            
            <?php if(empty($subject_stats)): ?>
                <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                    <i class="fa-solid fa-chart-bar" style="font-size: 48px; opacity: 0.5; margin-bottom: 16px;"></i>
                    <p>No subject attendance data available.</p>
                </div>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <?php foreach($subject_stats as $stat): ?>
                        <?php 
                            $pct = $stat['percentage'];
                            $color_class = 'success';
                            if ($pct < 75) $color_class = 'danger';
                            else if ($pct < 85) $color_class = 'warning';
                        ?>
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <div style="font-weight: 600;"><?= esc($stat['subject']['name']) ?></div>
                                <div style="font-weight: 700; color: var(--<?= $color_class ?>);"><?= $pct ?>%</div>
                            </div>
                            
                            <div style="width: 100%; height: 8px; background: var(--bg-main); border-radius: 4px; overflow: hidden; margin-bottom: 4px;">
                                <div style="width: <?= $pct ?>%; height: 100%; background: var(--<?= $color_class ?>); border-radius: 4px;"></div>
                            </div>
                            
                            <div style="display: flex; gap: 16px; font-size: 12px; color: var(--text-muted);">
                                <span><strong style="color: var(--text-primary);"><?= $stat['total'] ?></strong> Sessions</span>
                                <span><strong style="color: var(--success);"><?= $stat['present'] ?></strong> Present</span>
                                <span><strong style="color: var(--warning);"><?= $stat['late'] ?></strong> Late</span>
                                <span><strong style="color: var(--danger);"><?= $stat['absent'] ?></strong> Absent</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
