<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Correspondence Dashboard<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Correspondence Dashboard</h1>
        <p class="header-subtitle">Monitor all outgoing system communications and in-app notifications.</p>
    </div>
</div>

<div class="card">
    <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Recent System Notifications</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>Date & Time</th>
                <th>Recipient</th>
                <th>Subject / Title</th>
                <th>Message Snippet</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($recent_logs as $log): ?>
                <tr>
                    <td style="color: var(--text-muted); font-size: 13px;"><?= esc(date('d/m/Y, h:i A', strtotime($log['created_at']))) ?></td>
                    <td style="font-weight: 600;"><?= esc($log['recipient_name']) ?></td>
                    <td style="font-weight: bold; color: var(--primary);"><?= esc($log['title']) ?></td>
                    <td style="font-size: 12px; color: var(--text-muted); max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <?= esc($log['message']) ?>
                    </td>
                    <td>
                        <?php if($log['is_read']): ?>
                            <span style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">READ</span>
                        <?php else: ?>
                            <span style="background: rgba(245, 158, 11, 0.1); color: var(--warning); padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">UNREAD</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($recent_logs)): ?>
                <tr><td colspan="5" style="text-align: center;">No correspondence logs found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
