<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Student Profile - <?= esc($student['roll_number']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <!-- Header Banner -->
    <div style="background: var(--card-bg, #ffffff); border: 1px solid var(--border-color, #e2e8f0); border-radius: 12px; padding: 24px; margin-bottom: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
            <div style="display: flex; gap: 20px; align-items: center;">
                <div style="width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; display: flex; align-items: center; justify-content: center; font-size: 26px; font-weight: 700; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);">
                    <?= strtoupper(substr($student['first_name'], 0, 1) . substr($student['last_name'], 0, 1)) ?>
                </div>
                <div>
                    <h2 style="margin: 0; font-size: 22px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 10px;">
                        <?= esc($student['first_name'] . ' ' . $student['last_name']) ?>
                        <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: #10b981; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 20px;">
                            <?= esc($student['status'] ?? 'Active') ?>
                        </span>
                    </h2>
                    <div style="margin-top: 6px; color: var(--text-muted); font-size: 14px; display: flex; gap: 18px; flex-wrap: wrap;">
                        <span><i class="fa-solid fa-id-badge me-1" style="color: #4f46e5;"></i> <?= esc($student['roll_number']) ?></span>
                        <span><i class="fa-solid fa-graduation-cap me-1" style="color: #4f46e5;"></i> <?= esc($student['program_name'] ?? 'N/A') ?></span>
                        <span><i class="fa-solid fa-users-rectangle me-1" style="color: #4f46e5;"></i> Cohort: <strong><?= esc($student['cohort_name'] ?? 'Unassigned') ?></strong></span>
                    </div>
                </div>
            </div>
            <div style="display: flex; gap: 8px;">
                <a href="<?= base_url('org/students/edit/' . ($student['uuid'] ?? $student['id'])) ?>" class="btn btn-primary"><i class="fa-solid fa-pen"></i> Edit Profile</a>
                <a href="<?= base_url('org/students') ?>" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Back</a>
            </div>
        </div>

        <!-- Metric Highlight Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border-color, #e2e8f0);">
            <div style="background: rgba(79, 70, 229, 0.04); border: 1px solid rgba(79, 70, 229, 0.12); border-radius: 10px; padding: 14px 18px;">
                <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Attendance Rate</div>
                <div style="font-size: 24px; font-weight: 700; color: #4f46e5; margin-top: 4px;">
                    <?= $attendance['percentage'] ?>%
                </div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                    <?= $attendance['present'] ?> / <?= $attendance['total'] ?> Sessions attended
                </div>
            </div>

            <div style="background: rgba(239, 68, 68, 0.04); border: 1px solid rgba(239, 68, 68, 0.12); border-radius: 10px; padding: 14px 18px;">
                <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Fee Balance Due</div>
                <div style="font-size: 24px; font-weight: 700; color: <?= $fee_summary['balance'] > 0 ? '#ef4444' : '#10b981' ?>; margin-top: 4px;">
                    ₹<?= number_format($fee_summary['balance'], 2) ?>
                </div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                    Paid: ₹<?= number_format($fee_summary['paid'], 2) ?> of ₹<?= number_format($fee_summary['due'], 2) ?>
                </div>
            </div>

            <div style="background: rgba(245, 158, 11, 0.04); border: 1px solid rgba(245, 158, 11, 0.12); border-radius: 10px; padding: 14px 18px;">
                <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Active Backlogs</div>
                <div style="font-size: 24px; font-weight: 700; color: <?= count($backlogs) > 0 ? '#f59e0b' : '#10b981' ?>; margin-top: 4px;">
                    <?= count($backlogs) ?>
                </div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                    Subjects requiring clearance
                </div>
            </div>

            <div style="background: rgba(16, 185, 129, 0.04); border: 1px solid rgba(16, 185, 129, 0.12); border-radius: 10px; padding: 14px 18px;">
                <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Assessments Recorded</div>
                <div style="font-size: 24px; font-weight: 700; color: #10b981; margin-top: 4px;">
                    <?= count($marks) ?>
                </div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                    Internal evaluations graded
                </div>
            </div>
        </div>
    </div>

    <!-- Tabbed Detailed Profile -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px;">
        <!-- Left Column: Personal & Bio Details -->
        <div class="card" style="padding: 24px; border-radius: 12px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px; color: var(--text-primary); border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                <i class="fa-regular fa-user me-2" style="color: #4f46e5;"></i> Personal & Biographical Details
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 14px;">
                <div>
                    <label style="font-size: 12px; color: var(--text-muted); display: block;">Email Address</label>
                    <strong><?= esc($student['email'] ?? 'Not provided') ?></strong>
                </div>
                <div>
                    <label style="font-size: 12px; color: var(--text-muted); display: block;">Phone Number</label>
                    <strong><?= esc($student['phone'] ?? 'Not provided') ?></strong>
                </div>
                <div>
                    <label style="font-size: 12px; color: var(--text-muted); display: block;">Father / Guardian</label>
                    <strong><?= esc($bio['father_name'] ?? $student['parent_name'] ?? 'N/A') ?></strong>
                </div>
                <div>
                    <label style="font-size: 12px; color: var(--text-muted); display: block;">Mother Name</label>
                    <strong><?= esc($bio['mother_name'] ?? 'N/A') ?></strong>
                </div>
                <div>
                    <label style="font-size: 12px; color: var(--text-muted); display: block;">Blood Group</label>
                    <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;"><?= esc($bio['blood_group'] ?? 'Unknown') ?></span>
                </div>
                <div>
                    <label style="font-size: 12px; color: var(--text-muted); display: block;">Aadhaar / National ID</label>
                    <strong><?= esc($bio['aadhar_number'] ?? 'N/A') ?></strong>
                </div>
                <div style="grid-column: span 2;">
                    <label style="font-size: 12px; color: var(--text-muted); display: block;">Address</label>
                    <strong><?= esc($bio['address'] ?? 'N/A') ?></strong>
                    <div style="color: var(--text-muted); font-size: 13px;">
                        <?= esc($bio['city'] ?? '') ?> <?= esc($bio['state'] ?? '') ?> <?= esc($bio['pincode'] ?? '') ?>
                    </div>
                </div>
                <div>
                    <label style="font-size: 12px; color: var(--text-muted); display: block;">Emergency Contact</label>
                    <strong><?= esc($bio['emergency_contact'] ?? 'N/A') ?></strong>
                </div>
                <div>
                    <label style="font-size: 12px; color: var(--text-muted); display: block;">Parent Phone</label>
                    <strong><?= esc($student['parent_phone'] ?? 'N/A') ?></strong>
                </div>
            </div>
        </div>

        <!-- Right Column: Parent Link & Accounts -->
        <div class="card" style="padding: 24px; border-radius: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                <h3 style="font-size: 16px; font-weight: 700; margin: 0; color: var(--text-primary);">
                    <i class="fa-solid fa-people-roof me-2" style="color: #4f46e5;"></i> Linked Parent Accounts
                </h3>
                <a href="<?= base_url('org/students/parents') ?>" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px;"><i class="fa-solid fa-link"></i> Link Parent</a>
            </div>

            <?php if(!empty($parents)): ?>
                <?php foreach($parents as $p): ?>
                <div style="padding: 12px 16px; background: rgba(79, 70, 229, 0.03); border: 1px solid rgba(79, 70, 229, 0.1); border-radius: 8px; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 600; color: var(--text-primary);"><?= esc($p['full_name']) ?> <span class="badge" style="background: #4f46e515; color: #4f46e5;"><?= esc($p['relationship'] ?? 'Parent') ?></span></div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                            <i class="fa-regular fa-envelope me-1"></i> <?= esc($p['email']) ?> | <i class="fa-solid fa-phone me-1"></i> <?= esc($p['phone']) ?>
                        </div>
                    </div>
                    <a href="<?= base_url('org/students/unlink-parent/' . ($p['uuid'] ?? $p['id'])) ?>" class="btn-icon text-danger" onclick="return confirm('Unlink this parent?')"><i class="fa-solid fa-unlink"></i></a>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 24px; color: var(--text-muted); font-size: 13px;">
                    <i class="fa-solid fa-user-xmark" style="font-size: 28px; opacity: 0.4; margin-bottom: 8px; display: block;"></i>
                    No parent account linked yet. <a href="<?= base_url('org/students/parents') ?>" style="color: #4f46e5;">Link a parent</a> to allow portal access.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Fee Ledger Tab Section -->
    <div class="card" style="padding: 24px; border-radius: 12px; margin-top: 24px;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text-primary);">
            <i class="fa-solid fa-receipt me-2" style="color: #4f46e5;"></i> Fee Ledger & Obligations
        </h3>
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Fee Structure</th>
                        <th>Amount Due</th>
                        <th>Amount Paid</th>
                        <th>Balance</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($fees)): foreach($fees as $f): ?>
                    <tr>
                        <td><strong><?= esc($f['structure_name'] ?? 'Term Fees') ?></strong></td>
                        <td>₹<?= number_format($f['amount_due'], 2) ?></td>
                        <td>₹<?= number_format($f['amount_paid'], 2) ?></td>
                        <td style="font-weight: 600; color: <?= $f['balance'] > 0 ? '#ef4444' : '#10b981' ?>;">
                            ₹<?= number_format($f['balance'], 2) ?>
                        </td>
                        <td>
                            <span class="badge" style="background: <?= $f['status'] === 'paid' ? '#10b98115' : ($f['status'] === 'partial' ? '#f59e0b15' : '#ef444415') ?>; color: <?= $f['status'] === 'paid' ? '#10b981' : ($f['status'] === 'partial' ? '#f59e0b' : '#ef4444') ?>;">
                                <?= ucfirst(esc($f['status'])) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 20px;">No fee allocations recorded.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Internal Marks Table -->
    <div class="card" style="padding: 24px; border-radius: 12px; margin-top: 24px;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text-primary);">
            <i class="fa-solid fa-award me-2" style="color: #4f46e5;"></i> Internal Marks & Continuous Assessment
        </h3>
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Evaluation Component</th>
                        <th>Max Marks</th>
                        <th>Score Awarded</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($marks)): foreach($marks as $m): ?>
                    <tr>
                        <td><strong><?= esc($m['subject_name']) ?> (<?= esc($m['subject_code']) ?>)</strong></td>
                        <td><?= esc($m['component_name']) ?></td>
                        <td><?= esc($m['max_marks']) ?></td>
                        <td style="font-weight: 700; color: #4f46e5;"><?= esc($m['score']) ?></td>
                        <td>
                            <span class="badge" style="background: <?= $m['is_locked'] ? '#10b98115' : '#f59e0b15' ?>; color: <?= $m['is_locked'] ? '#10b981' : '#f59e0b' ?>;">
                                <?= $m['is_locked'] ? 'Verified & Locked' : 'Draft' ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 20px;">No internal marks recorded yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
