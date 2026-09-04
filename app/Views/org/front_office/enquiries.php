<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Front Desk Enquiries<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <div>
            <h2><i class="fa-solid fa-clipboard-question"></i> Front Desk Admission Enquiries</h2>
            <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 13px;">Walk-in, phone, and website prospect inquiries with counselor follow-up tracking.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-primary" onclick="openEnquiryModal()"><i class="fa-solid fa-plus"></i> New Enquiry</button>
            <a href="<?= base_url('org/front-office') ?>" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Hub</a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card" style="padding: 14px 20px; margin-bottom: 20px; border-radius: 10px;">
        <form method="GET" action="<?= base_url('org/front-office/enquiries') ?>" style="display: flex; gap: 12px; align-items: center;">
            <div style="min-width: 180px;">
                <select name="status" class="form-control" style="height: 38px;">
                    <option value="">All Statuses</option>
                    <option value="New" <?= $selected_status === 'New' ? 'selected' : '' ?>>New</option>
                    <option value="Follow Up" <?= $selected_status === 'Follow Up' ? 'selected' : '' ?>>Follow Up</option>
                    <option value="Interested" <?= $selected_status === 'Interested' ? 'selected' : '' ?>>Interested</option>
                    <option value="Applied" <?= $selected_status === 'Applied' ? 'selected' : '' ?>>Applied</option>
                    <option value="Closed" <?= $selected_status === 'Closed' ? 'selected' : '' ?>>Closed</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="height: 38px;"><i class="fa-solid fa-filter"></i> Filter</button>
            <a href="<?= base_url('org/front-office/enquiries') ?>" class="btn btn-outline" style="height: 38px;">Reset</a>
        </form>
    </div>

    <!-- Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Ref #</th>
                    <th>Prospect Student</th>
                    <th>Contact</th>
                    <th>Interested Program</th>
                    <th>Lead Source</th>
                    <th>Counsellor</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($enquiries)): foreach($enquiries as $e): ?>
                <tr>
                    <td><span class="badge" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; font-weight: 700;"><?= esc($e['enquiry_number']) ?></span></td>
                    <td>
                        <strong><?= esc($e['student_name']) ?></strong>
                        <?php if(!empty($e['parent_name'])): ?>
                            <div style="font-size: 11px; color: var(--text-muted);">Parent: <?= esc($e['parent_name']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div><i class="fa-solid fa-phone me-1" style="font-size: 10px;"></i> <?= esc($e['mobile']) ?></div>
                        <?php if(!empty($e['email'])): ?>
                            <div style="font-size: 11px; color: var(--text-muted);"><i class="fa-regular fa-envelope me-1" style="font-size: 10px;"></i> <?= esc($e['email']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= esc($e['program_name'] ?? 'Undecided') ?></strong></td>
                    <td><span class="badge" style="background: rgba(100, 116, 139, 0.1); color: #475569;"><?= esc($e['enquiry_source']) ?></span></td>
                    <td><?= esc($e['counsellor_name'] ?: 'Unassigned') ?></td>
                    <td>
                        <?php
                            $stBg = '#3b82f6';
                            if ($e['status'] === 'New') $stBg = '#4f46e5';
                            elseif ($e['status'] === 'Interested') $stBg = '#10b981';
                            elseif ($e['status'] === 'Closed') $stBg = '#64748b';
                            elseif ($e['status'] === 'Sent to Admin Officer') $stBg = '#8b5cf6';
                        ?>
                        <span class="badge" style="background: <?= $stBg ?>15; color: <?= $stBg ?>; font-weight: 600;">
                            <?= esc($e['status']) ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-icon" title="Log Follow-up" onclick="openFollowupModal(<?= $e['id'] ?>, '<?= esc($e['enquiry_number']) ?>', '<?= esc($e['student_name']) ?>')">
                                <i class="fa-solid fa-comments"></i>
                            </button>
                            <?php if($e['status'] !== 'Sent to Admin Officer'): ?>
                                <a href="<?= base_url('org/front-office/enquiries/convert/' . $e['id']) ?>" class="btn-icon text-success" title="Forward as Admission Lead" onclick="return confirm('Forward this prospect as an active Lead into Admissions CRM?')">
                                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="8" style="text-align: center; padding: 30px; color: var(--text-muted);">No admission enquiries recorded yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- New Enquiry Modal -->
<div class="drawer-overlay" id="enquiryModal" style="display: none;">
    <div class="drawer-content" style="max-width: 540px;">
        <form action="<?= base_url('org/front-office/enquiries/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 700;"><i class="fa-solid fa-clipboard-question me-2" style="color: #4f46e5;"></i> New Prospect Enquiry</h3>
                <button type="button" class="btn-close" onclick="closeEnquiryModal()" style="background: none; border: none; font-size: 18px; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div class="form-group" style="grid-column: span 2;">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Student / Candidate Name *</label>
                        <input type="text" name="student_name" class="form-control" required placeholder="Candidate full name">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Mobile Number *</label>
                        <input type="text" name="mobile" class="form-control" required placeholder="10-digit number">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="prospect@example.com">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Parent / Guardian Name</label>
                        <input type="text" name="parent_name" class="form-control" placeholder="Parent Name">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Interested Program</label>
                        <select name="program_id" class="form-control">
                            <option value="">Any / General</option>
                            <?php foreach($programs as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= esc($p['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Lead Source</label>
                        <select name="enquiry_source" class="form-control">
                            <option value="Walk-In">Walk-In Desk</option>
                            <option value="Phone">Phone Inquiry</option>
                            <option value="Website">Website</option>
                            <option value="Reference">Reference</option>
                            <option value="Education Fair">Education Fair</option>
                            <option value="Social Media">Social Media</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Assign Counsellor</label>
                        <select name="assigned_counsellor" class="form-control">
                            <option value="">Unassigned</option>
                            <?php foreach($counsellors as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= esc($c['full_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Remarks / Inquiry Summary</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="Candidate background, course interest, questions"></textarea>
                    </div>
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeEnquiryModal()">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Save Enquiry</button>
            </div>
        </form>
    </div>
</div>

<!-- Follow-up Modal -->
<div class="drawer-overlay" id="followupModal" style="display: none;">
    <div class="drawer-content" style="max-width: 460px;">
        <form action="<?= base_url('org/front-office/enquiries/followup') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="enquiry_id" id="fup_enquiry_id">

            <div class="drawer-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 700;"><i class="fa-solid fa-comments me-2" style="color: #4f46e5;"></i> Log Interaction</h3>
                <button type="button" class="btn-close" onclick="closeFollowupModal()" style="background: none; border: none; font-size: 18px; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 20px;">
                <div style="background: rgba(79, 70, 229, 0.05); padding: 10px 14px; border-radius: 8px; margin-bottom: 14px;">
                    <div style="font-size: 12px; color: var(--text-muted);">Prospect:</div>
                    <strong id="fup_candidate_name" style="font-size: 14px; color: #4f46e5;"></strong>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Update Prospect Status</label>
                    <select name="status" class="form-control" required>
                        <option value="Follow Up">Follow Up</option>
                        <option value="Interested">Interested / Qualified</option>
                        <option value="Applied">Applied Online</option>
                        <option value="Closed">Closed / Not Interested</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Next Follow-up Date</label>
                    <input type="date" name="next_followup_date" class="form-control">
                </div>

                <div class="form-group">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Interaction Notes *</label>
                    <textarea name="notes" class="form-control" rows="3" required placeholder="Discussion points, prospect response, fee questions..."></textarea>
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeFollowupModal()">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Save Interaction</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEnquiryModal() { document.getElementById('enquiryModal').style.display = 'flex'; }
    function closeEnquiryModal() { document.getElementById('enquiryModal').style.display = 'none'; }

    function openFollowupModal(enqId, enqNo, name) {
        document.getElementById('fup_enquiry_id').value = enqId;
        document.getElementById('fup_candidate_name').innerText = name + ' (' + enqNo + ')';
        document.getElementById('followupModal').style.display = 'flex';
    }
    function closeFollowupModal() { document.getElementById('followupModal').style.display = 'none'; }
</script>
<?= $this->endSection() ?>
