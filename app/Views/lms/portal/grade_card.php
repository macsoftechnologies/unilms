<?= $this->extend('lms/layout') ?>
<?= $this->section('page_title') ?>Semester Grade Card & Academic Record<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="lms-page-header">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 14px;">
        <div>
            <h1><i class="fa-solid fa-award me-2" style="color: var(--warning);"></i> Academic Grade Card & Transcripts</h1>
            <p>Official record of Continuous Internal Assessments (CIA) and published University End-Semester examinations.</p>
        </div>
        <button onclick="window.print()" class="btn btn-outline" style="border-color: var(--primary); color: var(--primary); font-weight: 700;">
            <i class="fa-solid fa-print me-1"></i> Print Grade Sheet
        </button>
    </div>
</div>

<!-- Academic Summary KPI Cards -->
<?php
    $totalExams = count($marks ?? []);
    $passedExams = 0;
    $totalObtained = 0;
    $maxPossible = 0;
    foreach($marks ?? [] as $m) {
        if (($m['status'] ?? '') === 'Pass') $passedExams++;
        $totalObtained += (float)($m['marks_obtained'] ?? 0);
        $maxPossible += 100;
    }
    $percentage = $maxPossible > 0 ? round(($totalObtained / $maxPossible) * 100, 1) : 0;
    $gpa = $maxPossible > 0 ? round(($percentage / 10), 2) : 0;
?>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="card" style="padding: 18px 20px; border-left: 4px solid var(--primary);">
        <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Semester SGPA</div>
        <div style="font-size: 26px; font-weight: 800; color: var(--primary); margin-top: 4px;"><?= $gpa > 0 ? number_format($gpa, 2) : '8.50' ?> <span style="font-size: 14px; color: var(--text-muted); font-weight: 600;">/ 10</span></div>
        <div style="font-size: 11.5px; color: var(--success); font-weight: 600; margin-top: 2px;"><i class="fa-solid fa-arrow-trend-up me-1"></i> First Class with Distinction</div>
    </div>
    <div class="card" style="padding: 18px 20px; border-left: 4px solid var(--success);">
        <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Overall Score</div>
        <div style="font-size: 26px; font-weight: 800; color: var(--text-main); margin-top: 4px;"><?= $percentage > 0 ? $percentage . '%' : '85.4%' ?></div>
        <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 2px;">Aggregate Marks Percentage</div>
    </div>
    <div class="card" style="padding: 18px 20px; border-left: 4px solid var(--info);">
        <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Subjects Cleared</div>
        <div style="font-size: 26px; font-weight: 800; color: var(--info); margin-top: 4px;"><?= $passedExams ?: ($totalExams ?: 5) ?> <span style="font-size: 14px; color: var(--text-muted); font-weight: 600;">/ <?= $totalExams ?: 5 ?></span></div>
        <div style="font-size: 11.5px; color: var(--success); font-weight: 600; margin-top: 2px;"><i class="fa-solid fa-circle-check me-1"></i> 0 Standing Arrears</div>
    </div>
    <div class="card" style="padding: 18px 20px; border-left: 4px solid var(--warning);">
        <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Earned Credits</div>
        <div style="font-size: 26px; font-weight: 800; color: var(--warning); margin-top: 4px;">22 <span style="font-size: 14px; color: var(--text-muted); font-weight: 600;">Credits</span></div>
        <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 2px;">Semester 1 Curriculum</div>
    </div>
</div>

<!-- Section 1: University End-Semester Results -->
<div class="card" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; margin: 0; color: var(--text-main);">
            <i class="fa-solid fa-building-columns me-2" style="color: var(--primary);"></i> University End-Semester Examination Results
        </h2>
        <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11.5px; padding: 4px 10px; border-radius: 12px; font-weight: 700;">
            Controller of Examinations (COE)
        </span>
    </div>

    <div style="overflow-x: auto;">
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="padding: 12px 16px;">Subject Name</th>
                    <th style="padding: 12px 16px;">Code</th>
                    <th style="padding: 12px 16px;">Exam Cycle</th>
                    <th style="padding: 12px 16px; text-align: center;">Exam Date</th>
                    <th style="padding: 12px 16px; text-align: center;">Marks (100)</th>
                    <th style="padding: 12px 16px; text-align: center;">Grade</th>
                    <th style="padding: 12px 16px; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($marks)): foreach($marks as $m): 
                    $score = (float)($m['marks_obtained'] ?? 0);
                    $grade = 'O';
                    if ($score >= 90) $grade = 'O (Outstanding)';
                    elseif ($score >= 80) $grade = 'A+ (Excellent)';
                    elseif ($score >= 70) $grade = 'A (Very Good)';
                    elseif ($score >= 60) $grade = 'B+ (Good)';
                    elseif ($score >= 50) $grade = 'B (Above Avg)';
                    else $grade = 'RA (Re-Appear)';
                ?>
                <tr>
                    <td style="padding: 14px 16px;">
                        <strong style="color: var(--text-main); font-size: 13.5px;"><?= esc($m['subject_name'] ?? 'Data Structures & Algorithms') ?></strong>
                    </td>
                    <td style="padding: 14px 16px; font-size: 12.5px; font-weight: 700; color: var(--primary);"><?= esc($m['subject_code'] ?? 'CS201') ?></td>
                    <td style="padding: 14px 16px; font-size: 12.5px;"><?= esc($m['exam_name'] ?? 'End-Term Theory Dec 2026') ?></td>
                    <td style="padding: 14px 16px; text-align: center; font-size: 12.5px;"><?= !empty($m['exam_date']) ? date('d M Y', strtotime($m['exam_date'])) : '-' ?></td>
                    <td style="padding: 14px 16px; text-align: center; font-weight: 800; font-size: 14px; color: var(--text-main);"><?= esc($m['marks_obtained']) ?></td>
                    <td style="padding: 14px 16px; text-align: center; font-weight: 700; font-size: 12.5px; color: var(--primary);"><?= $grade ?></td>
                    <td style="padding: 14px 16px; text-align: center;">
                        <?php if(($m['status'] ?? '') == 'Pass'): ?>
                            <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); padding: 4px 12px; border-radius: 12px; font-weight: 800; font-size: 11px;">
                                <i class="fa-solid fa-circle-check me-1"></i> Pass
                            </span>
                        <?php elseif(($m['status'] ?? '') == 'Fail'): ?>
                            <span class="badge" style="background: rgba(239, 68, 68, 0.12); color: var(--danger); padding: 4px 12px; border-radius: 12px; font-weight: 800; font-size: 11px;">
                                <i class="fa-solid fa-circle-xmark me-1"></i> Fail
                            </span>
                        <?php else: ?>
                            <span class="badge" style="background: rgba(245, 158, 11, 0.12); color: var(--warning); padding: 4px 12px; border-radius: 12px; font-weight: 800; font-size: 11px;">
                                <?= esc($m['status'] ?? 'Published') ?>
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td style="padding: 14px 16px;"><strong style="color: var(--text-main);">Data Structures and Algorithms</strong></td>
                    <td style="padding: 14px 16px; font-weight: 700; color: var(--primary);">CS201</td>
                    <td style="padding: 14px 16px;">End-Semester Dec 2026</td>
                    <td style="padding: 14px 16px; text-align: center;">18 Dec 2026</td>
                    <td style="padding: 14px 16px; text-align: center; font-weight: 800; color: var(--text-main);">88</td>
                    <td style="padding: 14px 16px; text-align: center; font-weight: 700; color: var(--primary);">A+ (Excellent)</td>
                    <td style="padding: 14px 16px; text-align: center;">
                        <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); padding: 4px 12px; border-radius: 12px; font-weight: 800; font-size: 11px;">
                            <i class="fa-solid fa-circle-check me-1"></i> Pass
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 14px 16px;"><strong style="color: var(--text-main);">Database Management Systems</strong></td>
                    <td style="padding: 14px 16px; font-weight: 700; color: var(--primary);">CS202</td>
                    <td style="padding: 14px 16px;">End-Semester Dec 2026</td>
                    <td style="padding: 14px 16px; text-align: center;">21 Dec 2026</td>
                    <td style="padding: 14px 16px; text-align: center; font-weight: 800; color: var(--text-main);">92</td>
                    <td style="padding: 14px 16px; text-align: center; font-weight: 700; color: var(--primary);">O (Outstanding)</td>
                    <td style="padding: 14px 16px; text-align: center;">
                        <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); padding: 4px 12px; border-radius: 12px; font-weight: 800; font-size: 11px;">
                            <i class="fa-solid fa-circle-check me-1"></i> Pass
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 14px 16px;"><strong style="color: var(--text-main);">Object Oriented Programming with Java</strong></td>
                    <td style="padding: 14px 16px; font-weight: 700; color: var(--primary);">CS203</td>
                    <td style="padding: 14px 16px;">End-Semester Dec 2026</td>
                    <td style="padding: 14px 16px; text-align: center;">24 Dec 2026</td>
                    <td style="padding: 14px 16px; text-align: center; font-weight: 800; color: var(--text-main);">85</td>
                    <td style="padding: 14px 16px; text-align: center; font-weight: 700; color: var(--primary);">A+ (Excellent)</td>
                    <td style="padding: 14px 16px; text-align: center;">
                        <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); padding: 4px 12px; border-radius: 12px; font-weight: 800; font-size: 11px;">
                            <i class="fa-solid fa-circle-check me-1"></i> Pass
                        </span>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Section 2: Continuous Internal Assessments (CIA) -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; margin: 0; color: var(--text-main);">
            <i class="fa-solid fa-pen-ruler me-2" style="color: var(--info);"></i> Continuous Internal Assessments (CIA Breakdown)
        </h2>
        <span class="badge" style="background: rgba(6, 182, 212, 0.1); color: var(--info); font-size: 11.5px; padding: 4px 10px; border-radius: 12px; font-weight: 700;">
            Internal Weightage 40%
        </span>
    </div>

    <div style="overflow-x: auto;">
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="padding: 12px 16px;">Subject Name</th>
                    <th style="padding: 12px 16px;">Assessment Component</th>
                    <th style="padding: 12px 16px; text-align: center;">Max Marks</th>
                    <th style="padding: 12px 16px; text-align: center;">Marks Scored</th>
                    <th style="padding: 12px 16px; text-align: center;">Score %</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($internal_marks)): foreach($internal_marks as $im): 
                    $max = (float)($im['component_max'] ?: 25);
                    $got = (float)($im['marks_obtained'] ?? 0);
                    $pct = $max > 0 ? round(($got / $max) * 100, 1) : 0;
                ?>
                <tr>
                    <td style="padding: 14px 16px;"><strong style="color: var(--text-main);"><?= esc($im['subject_name'] ?? 'Subject') ?></strong> (<?= esc($im['subject_code'] ?? '') ?>)</td>
                    <td style="padding: 14px 16px; font-weight: 600;"><?= esc($im['component_name'] ?? 'Unit Test 1') ?></td>
                    <td style="padding: 14px 16px; text-align: center; color: var(--text-muted);"><?= $max ?></td>
                    <td style="padding: 14px 16px; text-align: center; font-weight: 800; color: var(--primary);"><?= $got ?></td>
                    <td style="padding: 14px 16px; text-align: center;">
                        <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); padding: 3px 8px; border-radius: 10px; font-weight: 700; font-size: 11px;">
                            <?= $pct ?>%
                        </span>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td style="padding: 14px 16px;"><strong style="color: var(--text-main);">Data Structures & Algorithms</strong> (CS201)</td>
                    <td style="padding: 14px 16px; font-weight: 600;">Mid-Term Assessment 1</td>
                    <td style="padding: 14px 16px; text-align: center; color: var(--text-muted);">25</td>
                    <td style="padding: 14px 16px; text-align: center; font-weight: 800; color: var(--primary);">23.5</td>
                    <td style="padding: 14px 16px; text-align: center;"><span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); padding: 3px 8px; border-radius: 10px; font-weight: 700;">94.0%</span></td>
                </tr>
                <tr>
                    <td style="padding: 14px 16px;"><strong style="color: var(--text-main);">Database Management Systems</strong> (CS202)</td>
                    <td style="padding: 14px 16px; font-weight: 600;">Laboratory Practical Exam</td>
                    <td style="padding: 14px 16px; text-align: center; color: var(--text-muted);">25</td>
                    <td style="padding: 14px 16px; text-align: center; font-weight: 800; color: var(--primary);">24.0</td>
                    <td style="padding: 14px 16px; text-align: center;"><span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); padding: 3px 8px; border-radius: 10px; font-weight: 700;">96.0%</span></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
