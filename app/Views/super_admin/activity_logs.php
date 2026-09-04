<?= $this->extend('super_admin/layout') ?>
<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Activity Logs</h2>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Admin</th>
                    <th>Action</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($logs)): ?>
                    <?php foreach($logs as $log): ?>
                    <tr>
                        <td style="white-space:nowrap;"><?= date('d/m/Y, h:i A', strtotime($log['created_at'])) ?></td>
                        <td><?= esc($log['admin_email'] ?? 'System / Unknown') ?></td>
                        <td>
                            <div style="font-weight:600; color:var(--text-main);"><?= esc($log['action']) ?></div>
                            <?php if(!empty($log['details'])): ?>
                                <div style="font-size:12px; color:var(--text-muted); margin-top:4px; max-width:400px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="<?= esc($log['details']) ?>"><?= esc($log['details']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td style="font-family:monospace; color:var(--text-muted);"><?= esc($log['ip_address']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">No activity logs found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if(isset($pager) && $pager): ?>
            <div style="margin-top:20px;">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>
