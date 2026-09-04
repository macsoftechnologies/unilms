<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Programme Outcomes (POs) & OBE Setup<?= $this->endSection() ?>
<?= $this->section('content') ?>

<section class="view-section active">
    <!-- Header Banner -->
    <div style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 16px; padding: 22px 26px; margin-bottom: 22px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 46px; height: 46px; border-radius: 12px; background: linear-gradient(135deg, #7C3AED, #4F46E5); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 22px; box-shadow: 0 4px 14px rgba(124, 58, 237, 0.3);">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <h2 style="font-size: 20px; font-weight: 800; color: var(--text-primary); margin: 0 0 4px; letter-spacing: -0.3px;">
                    Programme Outcomes (POs) & PSOs
                </h2>
                <div style="color: var(--text-secondary); font-size: 13px;">
                    NAAC Metric 2.6.1 & NBA Washington Accord Outcome Framework • Define & Attain Graduate Attributes
                </div>
            </div>
        </div>

        <?php if($selected_program_id): ?>
        <div style="display: flex; gap: 10px;">
            <button type="button" onclick="openAddOutcomeModal()" class="btn btn-primary" style="background: #7C3AED; border: none; padding: 10px 18px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(124, 58, 237, 0.25); border-radius: 10px;">
                <i class="fa-solid fa-plus-circle"></i> Add Outcome
            </button>
            <button type="button" onclick="document.getElementById('loadTemplateModal').style.display='flex'" class="btn btn-outline" style="padding: 10px 18px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; border-radius: 10px;">
                <i class="fa-solid fa-bolt" style="color: #7C3AED;"></i> 1-Click Load Template
            </button>
        </div>
        <?php endif; ?>
    </div>

    <!-- Filter & Configuration Toolbar -->
    <div style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 14px; padding: 16px 20px; margin-bottom: 22px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <!-- Program Selector -->
        <form action="" method="GET" style="display: flex; align-items: center; gap: 12px; flex: 1; max-width: 480px;">
            <label style="font-size: 13px; font-weight: 700; color: var(--text-secondary); white-space: nowrap;">
                <i class="fa-solid fa-certificate" style="color: #7C3AED; margin-right: 6px;"></i> Degree / Program:
            </label>
            <?php if(empty($programs)): ?>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 13px; color: var(--text-secondary);">No programs found.</span>
                    <a href="<?= base_url('org/academics/programs') ?>" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px; font-weight: 600; text-decoration: none;">
                        + Create Program
                    </a>
                </div>
            <?php else: ?>
                <select name="program_id" class="form-control" onchange="this.form.submit()" style="font-size: 13.5px; font-weight: 600; padding: 8px 12px; border-radius: 8px; flex: 1;">
                    <?php foreach($programs as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= $p['id'] == $selected_program_id ? 'selected' : '' ?>>
                            <?= esc($p['name']) ?> (<?= esc($p['code']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </form>

        <!-- NAAC Target Benchmark -->
        <form action="<?= base_url('org/obe/settings/save') ?>" method="POST" style="display: flex; align-items: center; gap: 10px;">
            <?= csrf_field() ?>
            <label style="font-size: 13px; font-weight: 700; color: var(--text-secondary); white-space: nowrap;">
                <i class="fa-solid fa-bullseye" style="color: #059669; margin-right: 4px;"></i> NAAC Target Benchmark:
            </label>
            <div style="display: flex; align-items: center; position: relative; width: 100px;">
                <input type="text" inputmode="numeric" pattern="[0-9]*" name="obe_attainment_threshold" class="form-control" value="<?= esc($attainment_threshold) ?>" placeholder="60" required style="padding: 7px 24px 7px 10px; font-size: 13px; font-weight: 700; border-radius: 8px; text-align: center;">
                <span style="position: absolute; right: 10px; font-size: 12px; font-weight: 700; color: var(--text-secondary); pointer-events: none;">%</span>
            </div>
            <button type="submit" class="btn btn-outline" style="padding: 7px 14px; font-size: 12px; font-weight: 600; border-radius: 8px; white-space: nowrap;">
                Save Target
            </button>
        </form>
    </div>

    <?php if(empty($programs)): ?>
        <div style="background: var(--card-bg); border: 2px dashed var(--border-color); border-radius: 16px; padding: 48px 24px; text-align: center; margin-top: 20px;">
            <div style="width: 64px; height: 64px; margin: 0 auto 16px; border-radius: 50%; background: rgba(124,58,237,0.1); color: #7C3AED; display: flex; align-items: center; justify-content: center; font-size: 28px;">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <h3 style="margin: 0 0 8px; font-size: 18px; font-weight: 700; color: var(--text-primary);">Create Your First Academic Degree Program</h3>
            <p style="margin: 0 0 20px; font-size: 14px; color: var(--text-secondary); max-width: 480px; margin-inline: auto;">
                Before attaching Programme Outcomes (POs) and accreditation frameworks, create your degree program (e.g. B.Tech Computer Science, MBA).
            </p>
            <a href="<?= base_url('org/academics/programs') ?>" class="btn btn-primary" style="background: #7C3AED; border: none; padding: 11px 22px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border-radius: 10px;">
                <i class="fa-solid fa-plus-circle"></i> Create Academic Program
            </a>
        </div>
    <?php endif; ?>

    <?php if($selected_program_id): ?>
        <?php
            $poCount = 0;
            $psoCount = 0;
            foreach($pos as $p) {
                if ($p['type'] === 'PSO' || strpos($p['code'], 'PSO') !== false) {
                    $psoCount++;
                } else {
                    $poCount++;
                }
            }
        ?>
        <!-- Program Metrics Bar -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 22px;">
            <div style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; gap: 12px;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(124,58,237,0.1); color: #7C3AED; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                    <i class="fa-solid fa-certificate"></i>
                </div>
                <div style="overflow: hidden;">
                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary); letter-spacing: 0.5px;">Active Degree</div>
                    <div style="font-size: 14px; font-weight: 700; color: var(--text-primary); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= esc($selected_program['name']) ?>">
                        <?= esc($selected_program['name'] ?? 'Selected Program') ?>
                    </div>
                </div>
            </div>

            <div style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; gap: 12px;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(2,132,199,0.1); color: #0284C7; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                    <i class="fa-solid fa-award"></i>
                </div>
                <div>
                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary); letter-spacing: 0.5px;">Programme Outcomes</div>
                    <div style="font-size: 18px; font-weight: 800; color: #0284C7; margin-top: 2px;">
                        <?= $poCount ?> <span style="font-size: 12px; font-weight: 500; color: var(--text-secondary);">defined</span>
                    </div>
                </div>
            </div>

            <div style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; gap: 12px;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(16,185,129,0.1); color: #059669; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div>
                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary); letter-spacing: 0.5px;">Program Specific (PSOs)</div>
                    <div style="font-size: 18px; font-weight: 800; color: #059669; margin-top: 2px;">
                        <?= $psoCount ?> <span style="font-size: 12px; font-weight: 500; color: var(--text-secondary);">specializations</span>
                    </div>
                </div>
            </div>

            <div style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; gap: 12px;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: <?= $poCount >= 5 ? 'rgba(16,185,129,0.1)' : 'rgba(245,158,11,0.1)' ?>; color: <?= $poCount >= 5 ? '#059669' : '#D97706' ?>; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                    <i class="fa-solid <?= $poCount >= 5 ? 'fa-circle-check' : 'fa-triangle-exclamation' ?>"></i>
                </div>
                <div>
                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary); letter-spacing: 0.5px;">NAAC Criterion 2.6.1</div>
                    <div style="font-size: 13px; font-weight: 700; color: <?= $poCount >= 5 ? '#059669' : '#D97706' ?>; margin-top: 3px;">
                        <?= $poCount >= 5 ? 'Ready & Compliant' : 'Setup Incomplete' ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Full Width Clean Outcomes Table -->
        <div style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <div style="padding: 16px 22px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: rgba(0,0,0,0.01);">
                <div style="font-weight: 800; font-size: 15px; color: var(--text-primary); display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-list-check" style="color: #7C3AED;"></i> Curriculum Outcomes Directory (<?= count($pos) ?>)
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 12px; color: var(--text-secondary); font-weight: 500;">
                        Mapped into CO-PO Matrix
                    </span>
                    <button type="button" onclick="openAddOutcomeModal()" class="btn btn-primary" style="background: #7C3AED; border: none; padding: 6px 14px; font-size: 12px; font-weight: 700; border-radius: 6px;">
                        <i class="fa-solid fa-plus"></i> Add Outcome
                    </button>
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table class="data-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--bg-main, #f8fafc); border-bottom: 1px solid var(--border-color);">
                            <th style="width: 100px; padding: 14px 20px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary);">Code</th>
                            <th style="width: 90px; padding: 14px 14px; text-align: center; font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary);">Type</th>
                            <th style="padding: 14px 20px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary);">Outcome Statement / Attribute Description</th>
                            <th style="width: 100px; padding: 14px 20px; text-align: right; font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary);">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($pos)): ?>
                            <?php foreach($pos as $po): ?>
                            <tr style="border-bottom: 1px solid var(--border-color); transition: background 0.15s ease;">
                                <td style="padding: 16px 20px; vertical-align: top;">
                                    <span style="font-weight: 800; font-size: 12.5px; color: #7C3AED; background: rgba(124,58,237,0.1); padding: 5px 10px; border-radius: 6px; display: inline-block;">
                                        <?= esc($po['code']) ?>
                                    </span>
                                </td>
                                <td style="padding: 16px 14px; vertical-align: top; text-align: center;">
                                    <span style="font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 6px; background: <?= $po['type'] === 'PSO' ? 'rgba(16,185,129,0.15); color: #059669;' : 'rgba(2,132,199,0.15); color: #0284C7;' ?>">
                                        <?= esc($po['type']) ?>
                                    </span>
                                </td>
                                <td style="padding: 16px 20px; vertical-align: top; font-size: 13.5px; color: var(--text-primary); line-height: 1.65;">
                                    <?= esc($po['description']) ?>
                                </td>
                                <td style="padding: 16px 20px; vertical-align: top; text-align: right; white-space: nowrap;">
                                    <div style="display: inline-flex; gap: 6px;">
                                        <button type="button" class="btn-icon" style="background: none; border: 1px solid var(--border-color); border-radius: 6px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; color: #7C3AED; cursor: pointer;" onclick="editOutcome(<?= htmlspecialchars(json_encode($po), ENT_QUOTES, 'UTF-8') ?>)" title="Edit Outcome">
                                            <i class="fa-solid fa-pen" style="font-size: 12px;"></i>
                                        </button>
                                        <form action="<?= base_url('org/obe/po/delete/'.$po['id']) ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete outcome <?= esc($po['code']) ?>?');" style="display: inline;">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn-icon text-danger" style="background: none; border: 1px solid var(--border-color); border-radius: 6px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer;" title="Delete Outcome">
                                                <i class="fa-solid fa-trash" style="font-size: 12px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 54px 20px;">
                                    <div style="width: 56px; height: 56px; margin: 0 auto 14px; border-radius: 50%; background: rgba(124,58,237,0.1); color: #7C3AED; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                                        <i class="fa-solid fa-graduation-cap"></i>
                                    </div>
                                    <h4 style="margin: 0 0 6px; font-size: 16px; font-weight: 700; color: var(--text-primary);">No Outcomes Defined Yet</h4>
                                    <p style="margin: 0 0 18px; font-size: 13.5px; color: var(--text-secondary); max-width: 440px; margin-inline: auto;">
                                        Load standard Washington Accord / NAAC graduate attributes in 1-click or add custom outcomes using the form.
                                    </p>
                                    <button type="button" onclick="document.getElementById('loadTemplateModal').style.display='flex'" class="btn btn-primary" style="background: #7C3AED; border: none; padding: 9px 18px; font-size: 13px; font-weight: 700; border-radius: 8px;">
                                        <i class="fa-solid fa-bolt" style="margin-right: 6px;"></i> 1-Click Load NAAC Template
                                    </button>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

</section>

<!-- Outcome Add/Edit Modal (Right Drawer / Modal) -->
<div id="outcomeModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.55); backdrop-filter:blur(5px); align-items:center; justify-content:center;">
    <div style="background:var(--card-bg, #fff); border-radius:16px; padding:28px 32px; max-width:560px; width:92%; box-shadow:0 20px 60px rgba(0,0,0,0.3); animation: modalFadeIn 0.25s ease;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; padding-bottom:14px; border-bottom:1px solid var(--border-color);">
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:38px; height:38px; border-radius:10px; background:rgba(124,58,237,0.12); color:#7C3AED; display:flex; align-items:center; justify-content:center; font-size:16px;">
                    <i class="fa-solid fa-award"></i>
                </div>
                <div>
                    <h3 id="formHeading" style="margin:0; font-size:17px; font-weight:800; color:var(--text-primary);">Add New Outcome</h3>
                    <span style="font-size:12px; color:var(--text-secondary);"><?= esc($selected_program['name'] ?? '') ?></span>
                </div>
            </div>
            <button onclick="closeOutcomeModal()" style="background:none; border:none; font-size:20px; color:var(--text-secondary); cursor:pointer;">&times;</button>
        </div>

        <form action="<?= base_url('org/obe/po/save') ?>" method="POST" id="outcomeForm">
            <?= csrf_field() ?>
            <input type="hidden" name="program_id" value="<?= $selected_program_id ?>">
            <input type="hidden" name="po_id" id="form_po_id" value="">

            <div style="margin-bottom: 16px;">
                <label style="font-size: 12.5px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; display: block;">Classification</label>
                <select name="type" id="form_type" class="form-control" required style="font-size: 13.5px; font-weight: 600; padding: 10px 12px; border-radius: 8px;">
                    <option value="PO">PO — Programme Outcome (Standard)</option>
                    <option value="PSO">PSO — Programme Specific Outcome</option>
                </select>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="font-size: 12.5px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; display: block;">Code / Identifier</label>
                <input type="text" name="code" id="form_code" class="form-control" placeholder="e.g. PO1, PO12, PSO1" required style="font-size: 13.5px; font-weight: 700; padding: 10px 12px; border-radius: 8px;">
            </div>

            <div style="margin-bottom: 22px;">
                <label style="font-size: 12.5px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; display: block;">Outcome Statement / Attribute Description</label>
                <textarea name="description" id="form_description" class="form-control" rows="5" placeholder="Define the graduate capability, engineering knowledge, or specialization attributes..." required style="font-size: 13.5px; line-height: 1.55; padding: 12px; border-radius: 8px; resize: vertical;"></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeOutcomeModal()" class="btn btn-outline" style="padding:9px 18px; font-size:13px; font-weight:600; border-radius:8px;">
                    Cancel
                </button>
                <button type="submit" id="formSubmitBtn" class="btn btn-primary" style="background:#7C3AED; border:none; padding:9px 22px; font-size:13px; font-weight:700; border-radius:8px; display:inline-flex; align-items:center; gap:6px; box-shadow:0 4px 12px rgba(124,58,237,0.3);">
                    Save Outcome
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 1-Click Load NAAC/NBA Template Modal -->
<div id="loadTemplateModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.55); backdrop-filter:blur(5px); align-items:center; justify-content:center;">
    <div style="background:var(--card-bg, #fff); border-radius:16px; padding:28px 32px; max-width:540px; width:92%; box-shadow:0 20px 60px rgba(0,0,0,0.3); animation: modalFadeIn 0.25s ease;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:40px; height:40px; border-radius:10px; background:rgba(124,58,237,0.12); color:#7C3AED; display:flex; align-items:center; justify-content:center; font-size:18px;">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <div>
                    <h3 style="margin:0; font-size:17px; font-weight:800; color:var(--text-primary);">Load Standard Outcomes</h3>
                    <span style="font-size:12px; color:var(--text-secondary);">NAAC Metric 2.6 & AICTE Graduate Attributes</span>
                </div>
            </div>
            <button onclick="document.getElementById('loadTemplateModal').style.display='none'" style="background:none; border:none; font-size:20px; color:var(--text-secondary); cursor:pointer;">&times;</button>
        </div>

        <form action="<?= base_url('org/obe/po/load-template') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="program_id" value="<?= $selected_program_id ?>">

            <div style="margin-bottom:18px;">
                <label style="font-size:13px; font-weight:700; display:block; margin-bottom:8px; color: var(--text-primary);">Select Accreditation Framework / Degree Template</label>
                <select name="template_key" class="form-control" style="font-size:13.5px; font-weight:600; padding:10px 12px; border-radius:8px;">
                    <?php foreach($templates as $key => $tpl): ?>
                        <option value="<?= $key ?>" <?= $key === $detected_template ? 'selected' : '' ?>>
                            <?= esc($tpl['name']) ?> (<?= count($tpl['outcomes']) ?> Outcomes)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="background:rgba(124,58,237,0.06); border:1px solid rgba(124,58,237,0.18); border-radius:10px; padding:14px 16px; margin-bottom:20px; font-size:12.5px; color:var(--text-primary); line-height:1.5;">
                <div style="font-weight:700; margin-bottom:4px; color:#7C3AED;"><i class="fa-solid fa-circle-info"></i> What happens when you load:</div>
                <ul style="margin:0; padding-left:18px; color:var(--text-secondary);">
                    <li>Loads standard PO1 through PO12 graduate attributes into <strong><?= esc($selected_program['name'] ?? 'this program') ?></strong>.</li>
                    <li>Existing matching codes (PO1, PO2) are preserved and safely updated.</li>
                </ul>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="document.getElementById('loadTemplateModal').style.display='none'" class="btn btn-outline" style="padding:9px 18px; font-size:13px; font-weight:600; border-radius:8px;">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary" style="background:#7C3AED; border:none; padding:9px 20px; font-size:13px; font-weight:700; border-radius:8px; display:inline-flex; align-items:center; gap:6px; box-shadow:0 4px 12px rgba(124,58,237,0.3);">
                    <i class="fa-solid fa-bolt"></i> Populate Outcomes Now
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddOutcomeModal() {
    document.getElementById('formHeading').innerText = 'Add New Outcome';
    document.getElementById('form_po_id').value = '';
    document.getElementById('form_type').value = 'PO';
    document.getElementById('form_code').value = '';
    document.getElementById('form_description').value = '';
    document.getElementById('formSubmitBtn').innerText = 'Save Outcome';
    document.getElementById('outcomeModal').style.display = 'flex';
}

function editOutcome(po) {
    document.getElementById('formHeading').innerText = 'Edit Outcome (' + po.code + ')';
    document.getElementById('form_po_id').value = po.id;
    document.getElementById('form_type').value = po.type;
    document.getElementById('form_code').value = po.code;
    document.getElementById('form_description').value = po.description;
    document.getElementById('formSubmitBtn').innerText = 'Update Outcome';
    document.getElementById('outcomeModal').style.display = 'flex';
}

function closeOutcomeModal() {
    document.getElementById('outcomeModal').style.display = 'none';
}
</script>

<?= $this->endSection() ?>
