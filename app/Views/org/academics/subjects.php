<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Course Subjects Directory<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php 
$currentProgram = null;
if (!empty($selected_program_id) && !empty($programs)) {
    foreach($programs as $p) {
        if ($p['id'] == $selected_program_id) {
            $currentProgram = $p;
            break;
        }
    }
}
?>

<section class="view-section active">

<?php if(!$currentProgram): ?>
    <!-- ========================================================================= -->
    <!-- LEVEL 1: ACADEMIC BRANCHES & PROGRAMS DIRECTORY                          -->
    <!-- ========================================================================= -->
    <div class="view-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
        <div>
            <h2 style="margin: 0; font-size: 22px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-graduation-cap" style="color: #7C3AED;"></i> Course Subjects by Branch
            </h2>
            <div style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">
                Select an academic branch to view, configure, and manage semester-wise course subjects & syllabus
            </div>
        </div>
        <div style="display: flex; gap: 10px;">
            <button class="btn btn-primary" onclick="openModal()" style="background: #7C3AED; border: none; padding: 10px 18px; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(124, 58, 237, 0.25);">
                <i class="fa-solid fa-plus-circle"></i> Add Subject
            </button>
        </div>
    </div>

    <!-- Overview Metrics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 14px; padding: 18px 20px; display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(124, 58, 237, 0.1); color: #7C3AED; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fa-solid fa-diagram-project"></i>
            </div>
            <div>
                <div style="font-size: 12px; color: var(--text-secondary); font-weight: 600; text-transform: uppercase;">Total Programs</div>
                <div style="font-size: 22px; font-weight: 800; color: var(--text-primary);"><?= count($programs ?? []) ?></div>
            </div>
        </div>
        <div style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 14px; padding: 18px 20px; display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.1); color: #059669; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fa-solid fa-book-bookmark"></i>
            </div>
            <div>
                <div style="font-size: 12px; color: var(--text-secondary); font-weight: 600; text-transform: uppercase;">Total Subjects</div>
                <div style="font-size: 22px; font-weight: 800; color: var(--text-primary);"><?= count($subjects ?? []) ?></div>
            </div>
        </div>
        <div style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 14px; padding: 18px 20px; display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(59, 130, 246, 0.1); color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div>
                <div style="font-size: 12px; color: var(--text-secondary); font-weight: 600; text-transform: uppercase;">Active Semesters</div>
                <div style="font-size: 22px; font-weight: 800; color: var(--text-primary);"><?= count($semesters ?? []) ?></div>
            </div>
        </div>
    </div>

    <!-- Program Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px;">
        <?php if(!empty($programs)): foreach($programs as $prog): ?>
        <div style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 16px; padding: 22px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 4px 20px rgba(0,0,0,0.03); transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 30px rgba(124, 58, 237, 0.1)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.03)';">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <span style="background: rgba(124, 58, 237, 0.1); color: #7C3AED; font-weight: 800; font-size: 12px; padding: 4px 10px; border-radius: 6px; letter-spacing: 0.5px;">
                        <?= esc($prog['code']) ?>
                    </span>
                    <span style="font-size: 12px; font-weight: 600; color: var(--text-secondary); background: var(--bg-main, #f1f5f9); padding: 4px 10px; border-radius: 6px;">
                        Dept: <?= esc($prog['department_code'] ?? 'General') ?>
                    </span>
                </div>
                
                <h3 style="margin: 0 0 8px 0; font-size: 17px; font-weight: 700; color: var(--text-primary); line-height: 1.4;">
                    <?= esc($prog['name']) ?>
                </h3>
                
                <div style="font-size: 13px; color: var(--text-secondary); margin-bottom: 18px;">
                    <?= esc($prog['department_name'] ?? 'Department of Engineering') ?>
                </div>

                <!-- Stats Badges -->
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; padding: 12px; background: var(--bg-main, #f8fafc); border-radius: 10px; margin-bottom: 20px; text-align: center;">
                    <div>
                        <div style="font-size: 16px; font-weight: 800; color: #7C3AED;"><?= $prog['total_subjects'] ?></div>
                        <div style="font-size: 11px; color: var(--text-secondary); font-weight: 600;">Subjects</div>
                    </div>
                    <div>
                        <div style="font-size: 16px; font-weight: 800; color: #059669;"><?= $prog['total_credits'] ?></div>
                        <div style="font-size: 11px; color: var(--text-secondary); font-weight: 600;">Credits</div>
                    </div>
                    <div>
                        <div style="font-size: 16px; font-weight: 800; color: #2563EB;"><?= esc($prog['total_semesters'] ?? 8) ?></div>
                        <div style="font-size: 11px; color: var(--text-secondary); font-weight: 600;">Semesters</div>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <a href="<?= base_url('org/academics/subjects?program_id=' . $prog['id']) ?>" class="btn btn-primary" style="flex: 1; background: #7C3AED; border: none; padding: 10px 14px; font-weight: 700; font-size: 13px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; color: #fff; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.2);">
                    <i class="fa-solid fa-eye"></i> View & Manage Subjects
                </a>
                <button type="button" onclick="openModalForProgram(<?= $prog['id'] ?>)" class="btn btn-outline" title="Quick Add Subject" style="padding: 10px 14px; border-radius: 10px; font-weight: 700; border: 1px solid var(--border-color); background: var(--card-bg, #fff);">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
        </div>
        <?php endforeach; else: ?>
        <div style="grid-column: 1 / -1; padding: 40px; text-align: center; background: var(--card-bg, #fff); border-radius: 14px; border: 1px solid var(--border-color);">
            <i class="fa-solid fa-graduation-cap" style="font-size: 36px; color: var(--text-secondary); margin-bottom: 12px;"></i>
            <h3>No Academic Programs Found</h3>
            <p style="color: var(--text-secondary);">Create an academic program in Academics &gt; Programs to begin.</p>
        </div>
        <?php endif; ?>
    </div>

<?php else: ?>
    <!-- ========================================================================= -->
    <!-- LEVEL 2: FOCUSED BRANCH CURRICULUM & SUBJECTS MANAGER                    -->
    <!-- ========================================================================= -->
    <div style="margin-bottom: 20px;">
        <a href="<?= base_url('org/academics/subjects') ?>" style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: #7C3AED; text-decoration: none; margin-bottom: 12px;">
            <i class="fa-solid fa-arrow-left"></i> Back to All Academic Branches
        </a>

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
            <div>
                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <h2 style="margin: 0; font-size: 22px; font-weight: 700; color: var(--text-primary);">
                        <?= esc($currentProgram['name']) ?>
                    </h2>
                    <span style="background: rgba(124, 58, 237, 0.1); color: #7C3AED; font-weight: 800; font-size: 12px; padding: 4px 10px; border-radius: 6px;">
                        <?= esc($currentProgram['code']) ?>
                    </span>
                </div>
                <div style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">
                    <?= esc($currentProgram['department_name'] ?? 'Department of Engineering') ?> • <?= esc($currentProgram['total_subjects']) ?> Course Subjects • <?= esc($currentProgram['total_credits']) ?> Total Credits
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <button class="btn btn-primary" onclick="openModalForProgram(<?= $currentProgram['id'] ?>)" style="background: #7C3AED; border: none; padding: 10px 18px; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(124, 58, 237, 0.25);">
                    <i class="fa-solid fa-plus-circle"></i> Add Subject to this Branch
                </button>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 14px; padding: 16px 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
        <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center; flex: 1; max-width: 750px;">
            <!-- Instant Search -->
            <div style="position: relative; flex: 1; min-width: 200px;">
                <i class="fa-solid fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 13px;"></i>
                <input type="text" id="subjectSearchInput" placeholder="Search code, title, alias in this branch..." onkeyup="applySubjectFilters()" class="form-control" style="padding-left: 36px; height: 38px; font-size: 13px; border-radius: 8px;">
            </div>

            <!-- Semester Filter -->
            <select id="filterSemester" onchange="applySubjectFilters()" class="form-control" style="width: auto; min-width: 150px; height: 38px; font-size: 13px; border-radius: 8px;">
                <option value="">All Semesters</option>
                <?php if(!empty($semesters)): foreach($semesters as $sem): ?>
                    <option value="<?= esc($sem['name']) ?>"><?= esc($sem['name']) ?></option>
                <?php endforeach; endif; ?>
            </select>

            <!-- Subject Type Filter -->
            <select id="filterType" onchange="applySubjectFilters()" class="form-control" style="width: auto; min-width: 140px; height: 38px; font-size: 13px; border-radius: 8px;">
                <option value="">All Types</option>
                <option value="Theory">Theory</option>
                <option value="Lab">Lab</option>
                <option value="Specialization">Specialization</option>
                <option value="Mini-Project">Mini-Project</option>
                <option value="Project">Project</option>
                <option value="Seminar">Seminar</option>
                <option value="Viva">Viva</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <!-- Per Page Selector -->
        <div style="display: flex; align-items: center; gap: 8px;">
            <label style="font-size: 12.5px; font-weight: 600; color: var(--text-secondary); margin: 0; white-space: nowrap;">Show:</label>
            <select id="rowsPerPage" onchange="changeRowsPerPage()" class="form-control" style="width: 75px; height: 38px; font-size: 13px; border-radius: 8px;">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="all">All</option>
            </select>
        </div>
    </div>
    
    <!-- Table Container -->
    <div class="table-container" style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden;">
        <table class="data-table" id="subjectsTable">
            <thead>
                <tr style="background: var(--bg-main, #f8fafc);">
                    <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Code</th>
                    <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Subject Title</th>
                    <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Type</th>
                    <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Semester</th>
                    <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Credits</th>
                    <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; text-transform: uppercase; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody id="subjectsTableBody">
                <?php if(!empty($subjects)): foreach($subjects as $sub): 
                    $type = $sub['subject_type'] ?? 'Theory';
                    $badgeStyle = 'background: rgba(124, 58, 237, 0.1); color: #7C3AED; border: 1px solid rgba(124, 58, 237, 0.2);';
                    if($type === 'Lab') $badgeStyle = 'background: rgba(16, 185, 129, 0.1); color: #059669; border: 1px solid rgba(16, 185, 129, 0.2);';
                    else if($type === 'Specialization') $badgeStyle = 'background: rgba(59, 130, 246, 0.1); color: #2563EB; border: 1px solid rgba(59, 130, 246, 0.2);';
                    else if($type === 'Project' || $type === 'Mini-Project') $badgeStyle = 'background: rgba(245, 158, 11, 0.1); color: #D97706; border: 1px solid rgba(245, 158, 11, 0.2);';
                    else if($type === 'Seminar' || $type === 'Viva') $badgeStyle = 'background: rgba(6, 182, 212, 0.1); color: #0891B2; border: 1px solid rgba(6, 182, 212, 0.2);';
                ?>
                <tr class="subject-row" data-code="<?= esc(strtolower($sub['code'])) ?>" data-name="<?= esc(strtolower($sub['name'])) ?>" data-short="<?= esc(strtolower($sub['short_name'] ?? '')) ?>" data-semester="<?= esc($sub['semester_name'] ?? '') ?>" data-type="<?= esc($type) ?>">
                    <td style="padding: 14px 18px;">
                        <span style="background: rgba(124, 58, 237, 0.08); color: #7C3AED; font-weight: 700; font-size: 12.5px; padding: 4px 10px; border-radius: 6px; font-family: monospace;">
                            <?= esc($sub['code']) ?>
                        </span>
                    </td>
                    <td style="padding: 14px 18px;">
                        <div style="font-weight: 700; color: var(--text-primary); font-size: 13.5px;">
                            <?= esc($sub['name']) ?>
                        </div>
                        <?php if(!empty($sub['short_name'])): ?>
                            <div style="font-size: 11.5px; color: var(--text-secondary); margin-top: 2px;">
                                Alias: <span style="font-weight: 600;"><?= esc($sub['short_name']) ?></span>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 14px 18px;">
                        <span style="<?= $badgeStyle ?> font-weight: 700; font-size: 11.5px; padding: 4px 10px; border-radius: 12px; display: inline-block;">
                            <?= esc($type) ?>
                        </span>
                    </td>
                    <td style="padding: 14px 18px; font-weight: 600; color: var(--text-primary); font-size: 13px;">
                        <?= esc($sub['semester_name']) ?>
                    </td>
                    <td style="padding: 14px 18px;">
                        <span style="background: var(--bg-main, #f1f5f9); color: var(--text-primary); font-weight: 700; font-size: 12.5px; padding: 3px 10px; border-radius: 6px;">
                            <?= esc($sub['credits']) ?> Credits
                        </span>
                    </td>
                    <td style="padding: 14px 18px; text-align: right;">
                        <div class="action-buttons" style="display: inline-flex; gap: 6px; justify-content: flex-end;">
                            <button class="btn-icon" onclick='editSub(<?= json_encode($sub) ?>)' title="Edit Subject" style="padding: 6px 10px; border-radius: 6px; border: 1px solid var(--border-color); background: none; color: var(--text-primary); cursor: pointer;"><i class="fa-solid fa-pen"></i></button>
                            <a href="<?= base_url('org/academics/subjects/delete/'.$sub['id']) ?>" class="btn-icon text-danger" onclick="return confirm('Are you sure you want to delete this subject?')" title="Delete Subject" style="padding: 6px 10px; border-radius: 6px; border: 1px solid var(--border-color); background: none; color: #DC2626; cursor: pointer;"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr id="noDataRow"><td colspan="6" style="text-align: center; padding: 30px; color: var(--text-secondary);">No subjects found for this branch.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Dynamic Pagination Footer -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 20px; border-top: 1px solid var(--border-color); background: var(--bg-main, #f8fafc); flex-wrap: wrap; gap: 12px;">
            <div id="paginationSummary" style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">
                Showing 0 to 0 of 0 subjects
            </div>
            <div id="paginationControls" style="display: flex; gap: 6px; align-items: center;">
                <!-- Dynamically populated buttons -->
            </div>
        </div>
    </div>
<?php endif; ?>

</section>

<!-- ========================================================================= -->
<!-- ADD / EDIT SUBJECT DRAWER MODAL                                           -->
<!-- ========================================================================= -->
<div class="drawer-overlay" id="addModal">
    <div class="drawer-content" style="max-width: 580px;">
        <form action="<?= base_url('org/academics/subjects/save') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="form_id">
            
            <div class="drawer-header" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); color: #fff; padding: 18px 22px;">
                <h3 id="modal_title" style="margin: 0; font-size: 18px; font-weight: 700; color: #fff;">Add Subject</h3>
                <button type="button" class="btn-close" onclick="closeModal()" style="color: #fff;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <div class="drawer-body" style="padding: 22px;">
                <!-- Core Required Fields -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label style="font-size: 12.5px; font-weight: 700;">Program <span class="text-danger">*</span></label>
                        <select name="program_id" id="form_program_id" class="form-control" required style="font-size: 13.5px;">
                            <option value="" disabled selected>-- Select Program --</option>
                            <?php if(!empty($programs)): foreach($programs as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= ($selected_program_id == $p['id']) ? 'selected' : '' ?>><?= esc($p['name']) ?> (<?= esc($p['code']) ?>)</option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="font-size: 12.5px; font-weight: 700;">Semester <span class="text-danger">*</span></label>
                        <select name="semester_id" id="form_semester_id" class="form-control" required style="font-size: 13.5px;">
                            <option value="" disabled selected>-- Select Semester --</option>
                            <?php if(!empty($semesters)): foreach($semesters as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 16px;">
                    <div class="form-group">
                        <label style="font-size: 12.5px; font-weight: 700;">Subject Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="form_code" class="form-control" required placeholder="e.g. CS101" style="font-weight: 700; font-size: 13.5px;">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 12.5px; font-weight: 700;">Subject Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="form_name" class="form-control" required placeholder="e.g. Data Structures & Algorithms" style="font-size: 13.5px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label style="font-size: 12.5px; font-weight: 700;">Short Name</label>
                        <input type="text" name="short_name" id="form_short_name" class="form-control" placeholder="e.g. DSA">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 12.5px; font-weight: 700;">Credits <span class="text-danger">*</span></label>
                        <input type="number" step="0.5" name="credits" id="form_credits" class="form-control" required placeholder="e.g. 4.0" min="0" max="20" value="4.0" style="font-weight: 700;">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 12.5px; font-weight: 700;">Subject Type</label>
                        <select name="subject_type" id="form_subject_type" class="form-control">
                            <option value="Theory">Theory</option>
                            <option value="Lab">Lab</option>
                            <option value="Specialization">Specialization</option>
                            <option value="Mini-Project">Mini-Project</option>
                            <option value="Project">Project</option>
                            <option value="Seminar">Seminar</option>
                            <option value="Viva">Viva</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Collapsible Advanced / Optional Settings -->
                <div style="margin-top: 18px; border: 1px solid var(--border-color); border-radius: 12px; overflow: hidden; background: var(--bg-main, #f8fafc);">
                    <button type="button" onclick="toggleAdvancedSettings()" style="width: 100%; padding: 12px 16px; background: none; border: none; display: flex; justify-content: space-between; align-items: center; cursor: pointer; text-align: left;">
                        <span style="font-size: 13px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-sliders" style="color: #7C3AED;"></i> Advanced Syllabus & Marks Settings <span style="font-size: 11px; font-weight: 600; color: var(--text-secondary); background: rgba(0,0,0,0.06); padding: 2px 8px; border-radius: 12px;">Optional</span>
                        </span>
                        <i id="advancedToggleIcon" class="fa-solid fa-chevron-down" style="font-size: 12px; color: var(--text-secondary); transition: transform 0.2s;"></i>
                    </button>

                    <div id="advancedSettingsContent" style="display: none; padding: 16px; border-top: 1px solid var(--border-color); background: var(--card-bg, #fff);">
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                            <div class="form-group" style="margin: 0;">
                                <label style="font-size: 12px; font-weight: 600;">No. of Sessions</label>
                                <input type="number" min="0" name="no_of_sessions" id="form_no_of_sessions" class="form-control" value="0">
                            </div>
                            <div class="form-group" style="margin: 0;">
                                <label style="font-size: 12px; font-weight: 600;">No. of Units</label>
                                <input type="number" min="0" name="no_of_units" id="form_no_of_units" class="form-control" value="0">
                            </div>
                            <div class="form-group" style="margin: 0;">
                                <label style="font-size: 12px; font-weight: 600;">No. of Outcomes</label>
                                <input type="number" min="0" name="no_of_outcomes" id="form_no_of_outcomes" class="form-control" value="0">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                            <div class="form-group" style="margin: 0;">
                                <label style="font-size: 12px; font-weight: 600;">Internal Max / Pass Marks</label>
                                <div style="display: flex; gap: 8px;">
                                    <input type="number" min="0" name="internal_max_marks" id="form_internal_max_marks" class="form-control" placeholder="Max" value="0">
                                    <input type="number" min="0" name="internal_pass_marks" id="form_internal_pass_marks" class="form-control" placeholder="Pass" value="0">
                                </div>
                            </div>
                            <div class="form-group" style="margin: 0;">
                                <label style="font-size: 12px; font-weight: 600;">External Max / Pass Marks</label>
                                <div style="display: flex; gap: 8px;">
                                    <input type="number" min="0" name="external_max_marks" id="form_external_max_marks" class="form-control" placeholder="Max" value="0">
                                    <input type="number" min="0" name="external_pass_marks" id="form_external_pass_marks" class="form-control" placeholder="Pass" value="0">
                                </div>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                            <div class="form-group" style="margin: 0;">
                                <label style="font-size: 12px; font-weight: 600;">Total Pass Marks</label>
                                <input type="number" min="0" name="total_pass_marks" id="form_total_pass_marks" class="form-control" value="0">
                            </div>
                            <div class="form-group" style="margin: 0;">
                                <label style="font-size: 12px; font-weight: 600;">Display Order</label>
                                <input type="number" min="0" name="display_order" id="form_display_order" class="form-control" value="1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="drawer-footer" style="padding: 16px 20px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeModal()" style="border-radius: 8px; font-weight: 600;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background: #7C3AED; border: none; padding: 10px 22px; border-radius: 8px; font-weight: 700; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);">
                    Save Subject
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleAdvancedSettings() {
    const box = document.getElementById('advancedSettingsContent');
    const icon = document.getElementById('advancedToggleIcon');
    if (box.style.display === 'none' || box.style.display === '') {
        box.style.display = 'block';
        icon.style.transform = 'rotate(180deg)';
    } else {
        box.style.display = 'none';
        icon.style.transform = 'rotate(0deg)';
    }
}

function openModal() {
    $('#form_id').val('');
    $('#form_code').val('');
    $('#form_name').val('');
    $('#form_short_name').val('');
    $('#form_credits').val('4.0');
    $('#form_subject_type').val('Theory');
    $('#form_no_of_sessions').val('0');
    $('#form_no_of_units').val('0');
    $('#form_no_of_outcomes').val('0');
    $('#form_internal_max_marks').val('0');
    $('#form_internal_pass_marks').val('0');
    $('#form_external_max_marks').val('0');
    $('#form_external_pass_marks').val('0');
    $('#form_total_pass_marks').val('0');
    $('#form_display_order').val('1');
    $('#modal_title').text('Add Subject');
    $('#addModal').addClass('active');
}

function openModalForProgram(progId) {
    openModal();
    $('#form_program_id').val(progId);
}

function editSub(data) {
    $('#form_id').val(data.id);
    $('#form_program_id').val(data.program_id);
    $('#form_semester_id').val(data.semester_id);
    $('#form_code').val(data.code);
    $('#form_name').val(data.name);
    $('#form_short_name').val(data.short_name);
    $('#form_credits').val(data.credits);
    $('#form_subject_type').val(data.subject_type);
    $('#form_no_of_sessions').val(data.no_of_sessions);
    $('#form_no_of_units').val(data.no_of_units);
    $('#form_no_of_outcomes').val(data.no_of_outcomes);
    $('#form_internal_max_marks').val(data.internal_max_marks);
    $('#form_internal_pass_marks').val(data.internal_pass_marks);
    $('#form_external_max_marks').val(data.external_max_marks);
    $('#form_external_pass_marks').val(data.external_pass_marks);
    $('#form_total_pass_marks').val(data.total_pass_marks);
    $('#form_display_order').val(data.display_order);
    $('#modal_title').text('Edit Subject');
    $('#addModal').addClass('active');
}

function closeModal() {
    $('#addModal').removeClass('active');
}

// ==========================================
// DYNAMIC PAGINATION & MULTI-FILTER ENGINE
// ==========================================
let currentPage = 1;
let pageSize = 10;
let matchingRows = [];

function changeRowsPerPage() {
    const val = document.getElementById('rowsPerPage') ? document.getElementById('rowsPerPage').value : '10';
    pageSize = val === 'all' ? 999999 : parseInt(val);
    currentPage = 1;
    renderSubjectPage();
}

function applySubjectFilters() {
    const searchInput = document.getElementById('subjectSearchInput');
    if (!searchInput) return;

    const search = (searchInput.value || '').trim().toLowerCase();
    const sem = document.getElementById('filterSemester') ? document.getElementById('filterSemester').value : '';
    const type = document.getElementById('filterType') ? document.getElementById('filterType').value : '';
    const allRows = document.querySelectorAll('#subjectsTableBody tr.subject-row');

    matchingRows = [];

    allRows.forEach(row => {
        const code = row.getAttribute('data-code') || '';
        const name = row.getAttribute('data-name') || '';
        const shortName = row.getAttribute('data-short') || '';
        const rowSem = row.getAttribute('data-semester') || '';
        const rowType = row.getAttribute('data-type') || '';

        const matchesSearch = !search || code.includes(search) || name.includes(search) || shortName.includes(search);
        const matchesSem = !sem || rowSem === sem;
        const matchesType = !type || rowType === type;

        if (matchesSearch && matchesSem && matchesType) {
            matchingRows.push(row);
        } else {
            row.style.display = 'none';
        }
    });

    const noDataRow = document.getElementById('noDataRow');
    if (noDataRow) {
        noDataRow.style.display = matchingRows.length === 0 ? '' : 'none';
    }

    currentPage = 1;
    renderSubjectPage();
}

function renderSubjectPage() {
    const totalRows = matchingRows.length;
    const totalPages = Math.ceil(totalRows / pageSize) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    if (currentPage < 1) currentPage = 1;

    const startIdx = (currentPage - 1) * pageSize;
    const endIdx = startIdx + pageSize;

    // Show/hide based on pagination
    matchingRows.forEach((row, index) => {
        if (index >= startIdx && index < endIdx) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });

    // Update Summary Text
    const summary = document.getElementById('paginationSummary');
    if (summary) {
        if (totalRows === 0) {
            summary.textContent = 'Showing 0 to 0 of 0 subjects';
        } else {
            const displayStart = startIdx + 1;
            const displayEnd = Math.min(endIdx, totalRows);
            summary.textContent = `Showing ${displayStart} to ${displayEnd} of ${totalRows} subjects`;
        }
    }

    // Build Pagination Controls
    const controls = document.getElementById('paginationControls');
    if (!controls) return;
    controls.innerHTML = '';

    if (totalPages <= 1) return;

    // Previous Button
    const prevBtn = document.createElement('button');
    prevBtn.type = 'button';
    prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
    prevBtn.disabled = currentPage === 1;
    prevBtn.style.cssText = 'padding: 6px 12px; border: 1px solid var(--border-color); border-radius: 6px; background: ' + (currentPage === 1 ? 'transparent' : 'var(--card-bg, #fff)') + '; color: ' + (currentPage === 1 ? 'var(--text-secondary)' : 'var(--text-primary)') + '; cursor: ' + (currentPage === 1 ? 'not-allowed' : 'pointer') + '; opacity: ' + (currentPage === 1 ? '0.5' : '1') + ';';
    prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; renderSubjectPage(); } };
    controls.appendChild(prevBtn);

    // Page Numbers
    for (let p = 1; p <= totalPages; p++) {
        if (totalPages > 7 && Math.abs(p - currentPage) > 2 && p !== 1 && p !== totalPages) {
            if (p === 2 || p === totalPages - 1) {
                const dots = document.createElement('span');
                dots.textContent = '...';
                dots.style.cssText = 'padding: 0 4px; color: var(--text-secondary);';
                controls.appendChild(dots);
            }
            continue;
        }

        const pageBtn = document.createElement('button');
        pageBtn.type = 'button';
        pageBtn.textContent = p;
        const isActive = p === currentPage;
        pageBtn.style.cssText = 'padding: 6px 12px; border-radius: 6px; font-weight: 700; font-size: 12.5px; border: 1px solid ' + (isActive ? '#7C3AED' : 'var(--border-color)') + '; background: ' + (isActive ? '#7C3AED' : 'var(--card-bg, #fff)') + '; color: ' + (isActive ? '#fff' : 'var(--text-primary)') + '; cursor: pointer; min-width: 32px;';
        pageBtn.onclick = () => { currentPage = p; renderSubjectPage(); };
        controls.appendChild(pageBtn);
    }

    // Next Button
    const nextBtn = document.createElement('button');
    nextBtn.type = 'button';
    nextBtn.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.style.cssText = 'padding: 6px 12px; border: 1px solid var(--border-color); border-radius: 6px; background: ' + (currentPage === totalPages ? 'transparent' : 'var(--card-bg, #fff)') + '; color: ' + (currentPage === totalPages ? 'var(--text-secondary)' : 'var(--text-primary)') + '; cursor: ' + (currentPage === totalPages ? 'not-allowed' : 'pointer') + '; opacity: ' + (currentPage === totalPages ? '0.5' : '1') + ';';
    nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; renderSubjectPage(); } };
    controls.appendChild(nextBtn);
}

document.addEventListener('DOMContentLoaded', applySubjectFilters);
</script>
<?= $this->endSection() ?>
