<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>CO-PO Mapping Matrix<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
<div class="view-header">
    <h2>CO-PO Mapping Matrix</h2>
    <p style="color:var(--text-muted); font-size: 14px; margin: 4px 0 0;">Map Course Outcomes to Programme Outcomes (1=Low, 2=Medium, 3=High).</p>
</div>

<div class="stat-card" style="margin-bottom: 24px; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 14px; padding: 18px 22px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <form action="" method="GET" style="display: flex; gap: 16px; align-items: center; margin: 0; flex: 1; max-width: 480px;">
            <label style="font-size: 13px; font-weight: 700; color: var(--text-secondary); white-space: nowrap;">
                <i class="fa-solid fa-book" style="color: #7C3AED; margin-right: 6px;"></i> Subject:
            </label>
            <?php if(empty($subjects)): ?>
                <span style="font-size: 13px; color: var(--text-secondary);">No subjects created yet.</span>
            <?php else: ?>
                <select name="subject_id" class="form-control" onchange="this.form.submit()" style="font-size: 13.5px; font-weight: 600; padding: 8px 12px; border-radius: 8px; flex: 1;">
                    <?php foreach($subjects as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $s['id'] == $selected_subject_id ? 'selected' : '' ?>><?= esc($s['code']) ?> — <?= esc($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </form>

        <?php if($selected_subject_id): ?>
        <div>
            <?php if ($approval_status === 'approved'): ?>
                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); padding: 8px 16px; border-radius: 20px; font-weight: 600;">
                    <i class="fa-solid fa-check-circle"></i> Approved by HOD
                </span>
            <?php elseif ($approval_status === 'rejected'): ?>
                <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); padding: 8px 16px; border-radius: 20px; font-weight: 600;">
                    <i class="fa-solid fa-times-circle"></i> Rejected with Notes
                </span>
            <?php elseif ($approval_status === 'pending_hod'): ?>
                <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); padding: 8px 16px; border-radius: 20px; font-weight: 600;">
                    <i class="fa-solid fa-clock"></i> Pending HOD Review
                </span>
            <?php else: ?>
                <span class="badge" style="background: rgba(100,116,139,0.1); color: #64748b; padding: 6px 14px; border-radius: 20px; font-weight: 600; font-size: 12px;">Draft Matrix</span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($approval_notes)): ?>
        <div style="margin-top: 12px; font-size: 13px; color: var(--text-muted); background: var(--bg-main); padding: 8px 12px; border-radius: 6px;">
            <strong>HOD Review Notes:</strong> <?= esc($approval_notes) ?>
        </div>
    <?php endif; ?>
</div>

<?php if(empty($subjects)): ?>
    <div style="background: var(--card-bg); border: 2px dashed var(--border-color); border-radius: 16px; padding: 48px 24px; text-align: center; margin-top: 20px;">
        <div style="width: 60px; height: 60px; margin: 0 auto 14px; border-radius: 50%; background: rgba(124,58,237,0.1); color: #7C3AED; display: flex; align-items: center; justify-content: center; font-size: 26px;">
            <i class="fa-solid fa-book"></i>
        </div>
        <h3 style="margin: 0 0 8px; font-size: 18px; font-weight: 700; color: var(--text-primary);">Create Your First Course Subject</h3>
        <p style="margin: 0 0 20px; font-size: 14px; color: var(--text-secondary); max-width: 460px; margin-inline: auto;">
            Before mapping Course Outcomes (COs) to Programme Outcomes (POs), add your curriculum subjects (e.g. Data Structures, Database Systems).
        </p>
        <a href="<?= base_url('org/academics/subjects') ?>" class="btn btn-primary" style="background: #7C3AED; border: none; padding: 11px 22px; font-size: 13px; font-weight: 700; text-decoration: none; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-plus-circle"></i> Create Course Subject
        </a>
    </div>
<?php endif; ?>

<?php if($selected_subject_id): ?>
    <?php if(empty($cos) || empty($pos)): ?>
        <div class="stat-card" style="text-align:center; padding: 40px; color: var(--text-muted);">
            Please ensure that both COs and POs are defined before attempting to map them.
        </div>
    <?php else: ?>
        <form action="<?= base_url('org/obe/mapping/save') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="subject_id" value="<?= $selected_subject_id ?>">
            <div class="table-container" style="margin-bottom: 24px; overflow-x: auto;">
                <table class="data-table" style="min-width: 800px;">
                    <thead>
                        <tr>
                            <th style="width: 150px; background: var(--bg-main);">CO \ PO</th>
                            <?php foreach($pos as $po): ?>
                                <th style="text-align: center;" title="<?= esc($po['description']) ?>"><?= esc($po['code']) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cos as $co): ?>
                        <tr>
                            <td style="font-weight: 600; background: var(--bg-main);" title="<?= esc($co['description']) ?>"><?= esc($co['code']) ?></td>
                            <?php foreach($pos as $po): ?>
                                <?php 
                                    $val = isset($mappings[$co['id']][$po['id']]) ? $mappings[$co['id']][$po['id']] : '';
                                ?>
                                <td style="text-align: center; padding: 4px;">
                                    <select name="mapping[<?= $co['id'] ?>][<?= $po['id'] ?>]" style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 4px; text-align: center; background: <?= $val ? 'rgba(109, 40, 217, 0.05)' : '#fff' ?>;">
                                        <option value="">-</option>
                                        <option value="1" <?= $val == 1 ? 'selected' : '' ?>>1</option>
                                        <option value="2" <?= $val == 2 ? 'selected' : '' ?>>2</option>
                                        <option value="3" <?= $val == 3 ? 'selected' : '' ?>>3</option>
                                    </select>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 28px;"><i class="fa-solid fa-floppy-disk"></i> Save & Submit for HOD</button>
            </div>
        </form>

        <?php if (session('is_org_admin') || session('has_hod_role')): ?>
            <div class="stat-card" style="margin-top: 24px; border-left: 4px solid var(--primary);">
                <h4 style="margin: 0 0 12px 0;"><i class="fa-solid fa-user-shield"></i> HOD Departmental Oversight & Approval</h4>
                <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                    <form action="<?= base_url('org/obe/approveMapping') ?>" method="POST" style="margin: 0;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="subject_id" value="<?= $selected_subject_id ?>">
                        <button type="submit" class="btn btn-primary" style="background: #10b981; border: none; padding: 8px 20px;">
                            <i class="fa-solid fa-check"></i> Approve CO-PO Mapping
                        </button>
                    </form>

                    <button type="button" class="btn btn-outline" style="border-color: #ef4444; color: #ef4444; padding: 8px 20px;" onclick="document.getElementById('rejectNotesBox').style.display='block';">
                        <i class="fa-solid fa-times"></i> Reject with Notes
                    </button>
                </div>

                <div id="rejectNotesBox" style="display: none; margin-top: 16px;">
                    <form action="<?= base_url('org/obe/rejectMapping') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="subject_id" value="<?= $selected_subject_id ?>">
                        <div class="form-group">
                            <label>Feedback & Revision Notes for Faculty</label>
                            <textarea name="feedback_notes" class="form-control" rows="2" placeholder="Explain why mapping requires revision..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="background: #ef4444; border: none; padding: 8px 20px;">Confirm Rejection</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>

</section>
<?= $this->endSection() ?>
