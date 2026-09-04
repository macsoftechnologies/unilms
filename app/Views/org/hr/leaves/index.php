<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Leave Management<?= $this->endSection() ?>
<?= $this->section('content') ?>

<?php if(!$is_admin && $current_employee): ?>
<div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 24px;">
    <h3 style="margin-top: 0;">Apply for Leave</h3>
    <form action="<?= base_url('org/hr/leaves/apply') ?>" method="POST" style="display: flex; gap: 16px; align-items: end;">
        <?= csrf_field() ?>
        
        <div style="flex: 1;">
            <label class="form-label">Leave Type</label>
            <select name="leave_policy_id" class="form-control" required>
                <option value="">Select Leave Type...</option>
                <?php foreach($policies as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= esc($p['leave_type']) ?> (Quota: <?= $p['annual_quota'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="flex: 1;">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>

        <div style="flex: 1;">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" required>
        </div>

        <div style="flex: 2;">
            <label class="form-label">Reason</label>
            <input type="text" name="reason" class="form-control" placeholder="Brief reason" required>
        </div>

        <div>
            <button class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Submit Request</button>
        </div>
    </form>
</div>
<?php endif; ?>

<div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
    <h3 style="margin-top: 0;"><?= $is_admin ? 'All Leave Requests' : 'My Leave History' ?></h3>
    
    <table style="width: 100%; border-collapse: collapse; margin-top: 16px;">
        <thead>
            <tr style="border-bottom: 2px solid var(--border-color);">
                <?php if($is_admin): ?>
                <th style="padding: 12px 8px; text-align: left;">Employee</th>
                <?php endif; ?>
                <th style="padding: 12px 8px; text-align: left;">Leave Type</th>
                <th style="padding: 12px 8px; text-align: left;">Dates</th>
                <th style="padding: 12px 8px; text-align: left;">Reason</th>
                <th style="padding: 12px 8px; text-align: center;">Status</th>
                <?php if($is_admin): ?>
                <th style="padding: 12px 8px; text-align: right;">HR Action</th>
                <?php else: ?>
                <th style="padding: 12px 8px; text-align: left;">Remarks</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($leaves as $l): ?>
            <tr style="border-bottom: 1px solid var(--border-color);">
                <?php if($is_admin): ?>
                <td style="padding: 12px 8px; font-weight: 600;"><?= esc($l['full_name']) ?></td>
                <?php endif; ?>
                <td style="padding: 12px 8px;">
                    <span style="background: var(--bg-hover); padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                        <?= esc($l['leave_type']) ?>
                    </span>
                </td>
                <td style="padding: 12px 8px; font-size: 14px;">
                    <?= esc(date('d M', strtotime($l['start_date']))) ?> - <?= esc(date('d/m/Y', strtotime($l['end_date']))) ?>
                </td>
                <td style="padding: 12px 8px; color: var(--text-muted); font-size: 13px; max-width: 200px;"><?= esc($l['reason']) ?></td>
                
                <td style="padding: 12px 8px; text-align: center;">
                    <?php 
                        $color = 'var(--text-muted)';
                        if($l['status'] == 'Approved') $color = 'var(--success)';
                        if($l['status'] == 'Rejected') $color = 'var(--danger)';
                    ?>
                    <span style="color: <?= $color ?>; font-weight: bold;"><?= esc($l['status']) ?></span>
                </td>
                
                <?php if($is_admin): ?>
                <td style="padding: 12px 8px; text-align: right;">
                    <?php if($l['status'] == 'Pending'): ?>
                    <form action="<?= base_url('org/hr/leaves/update_status/'.$l['id']) ?>" method="POST" style="display: flex; gap: 8px; justify-content: flex-end;">
                        <?= csrf_field() ?>
                        <input type="text" name="hr_remarks" class="form-control" style="width: 150px; padding: 4px 8px; height: 32px;" placeholder="Remarks (optional)">
                        <button type="submit" name="status" value="Approved" class="btn btn-primary" style="padding: 4px 12px; height: 32px; font-size: 12px; background: var(--success); border-color: var(--success);">Approve</button>
                        <button type="submit" name="status" value="Rejected" class="btn btn-outline" style="padding: 4px 12px; height: 32px; font-size: 12px; color: var(--danger); border-color: var(--danger);">Reject</button>
                    </form>
                    <?php else: ?>
                        <span style="font-size: 12px; color: var(--text-muted);"><?= esc($l['hr_remarks'] ?: 'No remarks') ?></span>
                    <?php endif; ?>
                </td>
                <?php else: ?>
                <td style="padding: 12px 8px; font-size: 13px; color: var(--text-muted);"><?= esc($l['hr_remarks'] ?: '-') ?></td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
            
            <?php if(empty($leaves)): ?>
            <tr><td colspan="<?= $is_admin ? 6 : 5 ?>" style="padding: 32px; text-align: center; color: var(--text-muted);">No leave requests found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
