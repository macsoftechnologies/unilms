<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Front Office & Reception<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <div>
            <h2><i class="fa-solid fa-headset"></i> Front Office & Reception Hub</h2>
            <div style="display: flex; align-items: center; gap: 12px; margin-top: 4px;">
                <p style="margin: 0; color: var(--text-muted); font-size: 13px;">Campus gate passes, visitor logs, phone communications, postal dispatch, and front-desk enquiries.</p>
                <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: #10b981; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; font-size: 11px;">
                    <span style="width: 7px; height: 7px; background: #10b981; border-radius: 50%; display: inline-block; box-shadow: 0 0 6px #10b981;"></span>
                    <span id="live_status_txt">Live Sync Active</span>
                </span>
            </div>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="<?= base_url('org/front-office/visitors') ?>" class="btn btn-primary"><i class="fa-solid fa-person-walking"></i> Visitors Book</a>
            <a href="<?= base_url('org/front-office/calls') ?>" class="btn btn-outline"><i class="fa-solid fa-phone"></i> Call Logs</a>
            <a href="<?= base_url('org/front-office/postal') ?>" class="btn btn-outline"><i class="fa-solid fa-envelopes-bulk"></i> Postal Dispatch</a>
            <a href="<?= base_url('org/front-office/enquiries') ?>" class="btn btn-outline"><i class="fa-solid fa-clipboard-question"></i> Enquiries</a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="card" style="padding: 20px; border-radius: 12px; border-left: 4px solid #4f46e5;">
            <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Today's Visitors</div>
            <div id="stat_today_visitors" style="font-size: 28px; font-weight: 700; color: var(--text-primary); margin-top: 6px; transition: color 0.3s;"><?= $today_visitors ?></div>
            <div style="font-size: 12px; color: #10b981; margin-top: 4px;"><i class="fa-solid fa-building-user me-1"></i> <span id="stat_currently_inside"><?= $currently_inside ?></span> currently on campus</div>
        </div>

        <div class="card" style="padding: 20px; border-radius: 12px; border-left: 4px solid #0ea5e9;">
            <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Phone Calls Today</div>
            <div id="stat_today_calls" style="font-size: 28px; font-weight: 700; color: var(--text-primary); margin-top: 6px; transition: color 0.3s;"><?= $today_calls ?></div>
            <div style="font-size: 12px; color: <?= $pending_followups > 0 ? '#ef4444' : '#64748b' ?>; margin-top: 4px;">
                <i class="fa-solid fa-bell me-1"></i> <span id="stat_pending_followups"><?= $pending_followups ?></span> callbacks due
            </div>
        </div>

        <div class="card" style="padding: 20px; border-radius: 12px; border-left: 4px solid #f59e0b;">
            <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Postal / Courier Logs</div>
            <div style="font-size: 28px; font-weight: 700; color: var(--text-primary); margin-top: 6px;"><?= $today_postal ?></div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;"><i class="fa-solid fa-box me-1"></i> Dispatches recorded</div>
        </div>

        <div class="card" style="padding: 20px; border-radius: 12px; border-left: 4px solid #10b981;">
            <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Open Enquiries</div>
            <div style="font-size: 28px; font-weight: 700; color: var(--text-primary); margin-top: 6px;"><?= $active_enquiries ?></div>
            <div style="font-size: 12px; color: #4f46e5; margin-top: 4px;"><i class="fa-solid fa-user-plus me-1"></i> Prospective admissions</div>
        </div>
    </div>

    <!-- Quick Activity Grids -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px;">
        <!-- Recent Visitors -->
        <div class="card" style="padding: 20px; border-radius: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 15px; font-weight: 700; margin: 0; color: var(--text-primary);">
                    <i class="fa-solid fa-person-walking me-2" style="color: #4f46e5;"></i> Recent Visitors
                </h3>
                <a href="<?= base_url('org/front-office/visitors') ?>" style="font-size: 12px; color: #4f46e5; text-decoration: none; font-weight: 600;">View All</a>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Visitor</th>
                            <th>Purpose</th>
                            <th>In Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($recent_visitors)): foreach($recent_visitors as $v): ?>
                        <tr>
                            <td>
                                <strong><?= esc($v['visitor_name']) ?></strong>
                                <div style="font-size: 11px; color: var(--text-muted);"><?= esc($v['phone']) ?></div>
                            </td>
                            <td><?= esc($v['purpose']) ?></td>
                            <td style="font-size: 12px;"><?= date('H:i', strtotime($v['in_time'])) ?></td>
                            <td>
                                <?php if(empty($v['out_time'])): ?>
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">Inside</span>
                                <?php else: ?>
                                    <span class="badge" style="background: rgba(100, 116, 139, 0.12); color: #64748b;">Out</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="4" style="text-align: center; color: var(--text-muted);">No visitors logged today.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Phone Logs -->
        <div class="card" style="padding: 20px; border-radius: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 15px; font-weight: 700; margin: 0; color: var(--text-primary);">
                    <i class="fa-solid fa-phone me-2" style="color: #0ea5e9;"></i> Recent Phone Communications
                </h3>
                <a href="<?= base_url('org/front-office/calls') ?>" style="font-size: 12px; color: #4f46e5; text-decoration: none; font-weight: 600;">View All</a>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Caller</th>
                            <th>Type</th>
                            <th>Purpose</th>
                            <th>Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($recent_calls)): foreach($recent_calls as $c): ?>
                        <tr>
                            <td>
                                <strong><?= esc($c['caller_name']) ?></strong>
                                <div style="font-size: 11px; color: var(--text-muted);"><?= esc($c['phone_number']) ?></div>
                            </td>
                            <td>
                                <span class="badge" style="background: <?= $c['call_type'] === 'Incoming' ? 'rgba(16, 185, 129, 0.12)' : 'rgba(59, 130, 246, 0.12)' ?>; color: <?= $c['call_type'] === 'Incoming' ? '#10b981' : '#3b82f6' ?>;">
                                    <?= esc($c['call_type']) ?>
                                </span>
                            </td>
                            <td><?= esc($c['purpose']) ?></td>
                            <td><?= esc($c['call_result']) ?></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="4" style="text-align: center; color: var(--text-muted);">No call logs recorded today.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    // Live Autoloader for Front Office Dashboard
    setInterval(function() {
        fetch('<?= base_url('org/front-office/api-live-stats') ?>')
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update stat counts with subtle animation
                    updateStatWithPulse('stat_today_visitors', data.today_visitors);
                    updateStatWithPulse('stat_currently_inside', data.currently_inside);
                    updateStatWithPulse('stat_today_calls', data.today_calls);
                    updateStatWithPulse('stat_pending_followups', data.pending_followups);

                    // Update live indicator timestamp
                    const txt = document.getElementById('live_status_txt');
                    if (txt) txt.innerText = 'Live Sync: ' + data.timestamp;
                }
            })
            .catch(err => console.debug('Live sync heartbeat: idle'));
    }, 15000); // 15 seconds poll interval

    function updateStatWithPulse(elementId, newVal) {
        const el = document.getElementById(elementId);
        if (el && el.innerText != newVal) {
            el.innerText = newVal;
            el.style.transform = 'scale(1.15)';
            el.style.color = '#10b981';
            setTimeout(() => {
                el.style.transform = 'scale(1)';
                el.style.color = '';
            }, 600);
        }
    }
</script>
<?= $this->endSection() ?>
