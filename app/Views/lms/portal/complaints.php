<?= $this->extend('lms/layout') ?>
<?= $this->section('page_title') ?>Student Grievances & Helpdesk<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="lms-page-header">
    <h1><i class="fa-solid fa-bullhorn me-2" style="color: var(--danger);"></i> Student Grievance & Complaint Redressal</h1>
    <p>Direct communication channel with department heads, facility managers, and the Internal Complaints Committee (ICC).</p>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: var(--success); padding: 12px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
        <i class="fa-solid fa-circle-check me-2"></i> <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 1fr 1.4fr; gap: 24px;">
    
    <!-- Lodge Complaint Card -->
    <div class="card" style="padding: 24px; border: 1px solid var(--border); border-radius: 16px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px;">
            <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(239, 68, 68, 0.1); color: var(--danger); display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0; color: var(--text-main);">Lodge New Grievance</h3>
                <span style="font-size: 12px; color: var(--text-muted);">Confidential Redressal Ticket</span>
            </div>
        </div>

        <form action="<?= base_url('lms/complaints/submit') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-size: 12.5px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-main);">Category *</label>
                <select name="category" class="form-control" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; background: var(--surface);">
                    <option value="Academic">Academic & Syllabus Evaluation</option>
                    <option value="Infrastructure">Classroom & Lab Infrastructure</option>
                    <option value="Hostel">Hostel & Mess Amenities</option>
                    <option value="Transport">Campus Transport & Route Timing</option>
                    <option value="Anti-Ragging">Anti-Ragging & Campus Safety</option>
                    <option value="Fee & Accounts">Fee Accounts & Billing</option>
                    <option value="Other">Other Miscellaneous Request</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-size: 12.5px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-main);">Subject / Summary *</label>
                <input type="text" name="subject" class="form-control" required placeholder="Brief title of the issue..." style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; background: var(--surface);">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-size: 12.5px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-main);">Detailed Description *</label>
                <textarea name="description" class="form-control" required placeholder="Provide specific details, room/lab numbers, or dates..." style="width: 100%; height: 90px; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; background: var(--surface); resize: vertical;"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; font-weight: 700; padding: 11px; background: var(--danger); border-color: var(--danger);">
                <i class="fa-solid fa-paper-plane me-1"></i> Submit Ticket
            </button>
        </form>
    </div>

    <!-- Ticket History Table -->
    <div class="card" style="padding: 24px; border: 1px solid var(--border); border-radius: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
            <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0; color: var(--text-main);">
                <i class="fa-solid fa-clock-rotate-left me-2" style="color: var(--primary);"></i> My Ticket History
            </h3>
            <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11px; padding: 3px 8px; border-radius: 10px; font-weight: 700;">
                <?= count($complaints ?? []) ?> Tickets
            </span>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 10px 14px;">Category</th>
                        <th style="padding: 10px 14px;">Subject</th>
                        <th style="padding: 10px 14px;">Date</th>
                        <th style="padding: 10px 14px; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($complaints)): foreach($complaints as $c): ?>
                        <tr>
                            <td style="padding: 12px 14px;">
                                <span class="badge" style="background: rgba(99, 102, 241, 0.08); color: var(--primary); font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 8px;">
                                    <?= esc($c['category']) ?>
                                </span>
                            </td>
                            <td style="padding: 12px 14px;">
                                <strong style="color: var(--text-main); font-size: 13px;"><?= esc($c['subject']) ?></strong>
                            </td>
                            <td style="padding: 12px 14px; font-size: 12px; color: var(--text-muted);"><?= date('d M Y', strtotime($c['created_at'])) ?></td>
                            <td style="padding: 12px 14px; text-align: center;">
                                <?php if(($c['status'] ?? '') == 'Resolved' || ($c['status'] ?? '') == 'Closed'): ?>
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); padding: 4px 10px; border-radius: 12px; font-weight: 800; font-size: 11px;">
                                        <i class="fa-solid fa-circle-check me-1"></i> Resolved
                                    </span>
                                <?php else: ?>
                                    <span class="badge" style="background: rgba(245, 158, 11, 0.12); color: var(--warning); padding: 4px 10px; border-radius: 12px; font-weight: 800; font-size: 11px;">
                                        <i class="fa-solid fa-spinner me-1"></i> In Progress
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 28px; color: var(--text-muted); font-size: 13px;">
                                <i class="fa-solid fa-circle-check" style="font-size: 28px; color: var(--success); opacity: 0.5; margin-bottom: 8px; display: block;"></i>
                                No active grievances or unresolved complaint tickets.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
