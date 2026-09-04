<?= $this->extend('org/layout') ?>

<?= $this->section('title') ?>
<?= $assessment ? 'Edit Assessment' : 'Create 4-in-1 Assessment' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><?= $assessment ? 'Edit Assessment' : 'Create 4-in-1 Assessment' ?></h3>
            <p class="text-muted mb-0">Configure your assessment settings and question builder dynamically.</p>
        </div>
        <a href="<?= site_url('org/assessments') ?>" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i> Back to Assessments
        </a>
    </div>

    <form action="<?= site_url('org/assessments/save') ?>" method="POST" enctype="multipart/form-data" id="assessmentForm">
        <?= csrf_field() ?>
        <?php if ($assessment): ?>
            <input type="hidden" name="id" value="<?= $assessment['id'] ?>">
        <?php endif; ?>

        <div class="row">
            <!-- Left Column: Core Setup -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-info-circle text-primary me-2"></i> Core Assessment Information</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Assessment Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Mid-Term Algorithm Design Test" value="<?= esc($assessment['title'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Assessment Type <span class="text-danger">*</span></label>
                            <select name="assessment_type" id="assessmentTypeSelect" class="form-select border-primary" required>
                                <option value="file_upload" <?= ($assessment['assessment_type'] ?? '') === 'file_upload' ? 'selected' : '' ?>>📁 File Upload Assignment</option>
                                <option value="cbt_quiz" <?= ($assessment['assessment_type'] ?? '') === 'cbt_quiz' ? 'selected' : '' ?>>⏱️ Online CBT Timed Quiz</option>
                                <option value="interactive_video" <?= ($assessment['assessment_type'] ?? '') === 'interactive_video' ? 'selected' : '' ?>>🎥 Interactive Video Assessment (Timestamp Gates)</option>
                                <option value="essay" <?= ($assessment['assessment_type'] ?? '') === 'essay' ? 'selected' : '' ?>>✍️ Theory / Essay Exam (Word-Counter)</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Instructions & Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Provide detailed instructions or essay prompts for students..."><?= esc($assessment['description'] ?? '') ?></textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                            <select name="subject_id" class="form-select" required>
                                <?php foreach ($subjects as $s): ?>
                                    <option value="<?= $s['id'] ?>" <?= ($assessment['subject_id'] ?? '') == $s['id'] ? 'selected' : '' ?>><?= esc($s['name']) ?> (<?= esc($s['code']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Target Cohort / Section <span class="text-danger">*</span></label>
                            <select name="cohort_id" class="form-select" required>
                                <?php foreach ($cohorts as $c): ?>
                                    <option value="<?= $c['id'] ?>" <?= ($assessment['cohort_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Course Outcome (CO for OBE)</label>
                            <select name="co_id" class="form-select">
                                <option value="">-- No CO Tagging --</option>
                                <?php foreach ($cos as $co): ?>
                                    <option value="<?= $co['id'] ?>" <?= ($assessment['co_id'] ?? '') == $co['id'] ? 'selected' : '' ?>><?= esc($co['code']) ?>: <?= esc(substr($co['description'], 0, 40)) ?>...</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- DYNAMIC PANEL 1: File Upload -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 type-panel" id="panel_file_upload">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-file-upload text-primary me-2"></i> File Upload Configuration</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Allowed File Extensions</label>
                            <input type="text" name="allowed_extensions" class="form-control" placeholder=".pdf,.zip,.docx,.png" value="<?= esc($assessment['allowed_extensions'] ?? '.pdf,.zip') ?>">
                            <small class="text-muted">Comma separated format</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Max File Size (MB)</label>
                            <input type="number" name="max_file_size_mb" class="form-control" value="<?= esc($assessment['max_file_size_mb'] ?? 10) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Reference Document / Problem Statement File</label>
                            <input type="file" name="reference_attachment" class="form-control">
                            <?php if (!empty($assessment['reference_attachment'])): ?>
                                <small class="text-success mt-1 d-block"><i class="fas fa-check me-1"></i> Current file: <?= esc($assessment['reference_attachment']) ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- DYNAMIC PANEL 2: CBT Quiz & Interactive Video Questions -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 type-panel" id="panel_cbt_quiz">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-question-circle text-info me-2"></i> Inline Question Builder</h5>
                        <button type="button" class="btn btn-sm btn-outline-info rounded-pill" id="btnAddQuestion">
                            <i class="fas fa-plus me-1"></i> Add Question
                        </button>
                    </div>

                    <div id="questionsContainer">
                        <?php if (!empty($questions)): ?>
                            <?php foreach ($questions as $idx => $q): ?>
                                <div class="question-row border rounded-3 p-3 mb-3 bg-light position-relative" data-index="<?= $idx ?>">
                                    <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2 rounded-circle btn-remove-q"><i class="fas fa-times"></i></button>
                                    <div class="row g-2">
                                        <div class="col-md-8">
                                            <label class="form-label small fw-bold">Question Text</label>
                                            <input type="text" name="questions[<?= $idx ?>][question_text]" class="form-control" value="<?= esc($q['question_text']) ?>" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold">Points</label>
                                            <input type="number" step="0.5" name="questions[<?= $idx ?>][points]" class="form-control" value="<?= esc($q['points']) ?>">
                                        </div>
                                        <div class="col-md-2 video-timestamp-field" style="display:none;">
                                            <label class="form-label small fw-bold">Timestamp (Sec)</label>
                                            <input type="number" name="questions[<?= $idx ?>][timestamp_seconds]" class="form-control" value="<?= esc($q['timestamp_seconds'] ?? 0) ?>" placeholder="e.g. 120">
                                        </div>
                                        <div class="col-12 mt-2">
                                            <label class="form-label small fw-bold">Options & Correct Answer</label>
                                            <?php $opts = json_decode($q['options'], true) ?: []; ?>
                                            <div class="row g-2">
                                                <?php for ($i = 0; $i < 4; $i++): ?>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <div class="input-group-text">
                                                                <input type="radio" name="questions[<?= $idx ?>][correct_option]" value="<?= $i ?>" <?= (string)$q['correct_option'] === (string)$i ? 'checked' : '' ?>>
                                                            </div>
                                                            <input type="text" name="questions[<?= $idx ?>][options][<?= $i ?>]" class="form-control" placeholder="Option <?= chr(65 + $i) ?>" value="<?= esc($opts[$i]['text'] ?? '') ?>">
                                                        </div>
                                                    </div>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- DYNAMIC PANEL 3: Interactive Video Source -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 type-panel" id="panel_interactive_video">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-video text-danger me-2"></i> Video Source Configuration</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Video Source</label>
                            <select name="video_source_type" class="form-select">
                                <option value="youtube" <?= ($assessment['video_source_type'] ?? '') === 'youtube' ? 'selected' : '' ?>>YouTube URL</option>
                                <option value="mp4_url" <?= ($assessment['video_source_type'] ?? '') === 'mp4_url' ? 'selected' : '' ?>>Direct MP4 Video Link</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Video URL</label>
                            <input type="url" name="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=..." value="<?= esc($assessment['video_url'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <!-- DYNAMIC PANEL 4: Essay / Theory Exam -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 type-panel" id="panel_essay">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-feather-alt text-warning me-2"></i> Essay & Word Count Settings</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Minimum Words</label>
                            <input type="number" name="min_word_count" class="form-control" value="<?= esc($assessment['min_word_count'] ?? 300) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Maximum Words</label>
                            <input type="number" name="max_word_count" class="form-control" value="<?= esc($assessment['max_word_count'] ?? 1500) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Submission Mode</label>
                            <select name="submission_mode" class="form-select">
                                <option value="rich_text">Online Rich-Text Editor</option>
                                <option value="doc_upload">Word / PDF Document Upload</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings & Gates -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-cog text-secondary me-2"></i> Parameters & Gates</h5>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Max Marks</label>
                        <input type="number" step="0.5" name="max_marks" class="form-control" value="<?= esc($assessment['max_marks'] ?? 100) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Due Date & Time</label>
                        <input type="datetime-local" name="due_date" class="form-control" value="<?= !empty($assessment['due_date']) ? date('Y-m-d\TH:i', strtotime($assessment['due_date'])) : '' ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Time Limit (Minutes)</label>
                        <input type="number" name="time_limit_mins" class="form-control" value="<?= esc($assessment['time_limit_mins'] ?? 0) ?>" placeholder="0 = Untimed">
                        <small class="text-muted">Enforces student live countdown timer</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Max Attempts Allowed</label>
                        <input type="number" name="max_attempts" class="form-control" value="<?= esc($assessment['max_attempts'] ?? 1) ?>" min="1">
                    </div>

                    <hr>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="randomize_questions" value="1" id="chkRandomQ" <?= !empty($assessment['randomize_questions']) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="chkRandomQ">Randomize Question Order</label>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="allow_late_submissions" value="1" id="chkLate" <?= !empty($assessment['allow_late_submissions']) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="chkLate">Allow Late Submissions</label>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_published" value="1" id="chkPublish" <?= !empty($assessment['is_published']) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold text-success" for="chkPublish">Publish to Student Dashboards</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill shadow-sm">
                        <i class="fas fa-save me-2"></i> Save & Publish Assessment
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('assessmentTypeSelect');
    
    function switchPanels() {
        const selected = typeSelect.value;
        document.querySelectorAll('.type-panel').forEach(p => p.style.display = 'none');
        document.querySelectorAll('.video-timestamp-field').forEach(f => f.style.display = 'none');

        if (selected === 'file_upload') {
            document.getElementById('panel_file_upload').style.display = 'block';
        } else if (selected === 'cbt_quiz') {
            document.getElementById('panel_cbt_quiz').style.display = 'block';
        } else if (selected === 'interactive_video') {
            document.getElementById('panel_interactive_video').style.display = 'block';
            document.getElementById('panel_cbt_quiz').style.display = 'block';
            document.querySelectorAll('.video-timestamp-field').forEach(f => f.style.display = 'block');
        } else if (selected === 'essay') {
            document.getElementById('panel_essay').style.display = 'block';
        }
    }

    typeSelect.addEventListener('change', switchPanels);
    switchPanels();

    // Dynamic question adding
    let qCount = document.querySelectorAll('.question-row').length;
    document.getElementById('btnAddQuestion')?.addEventListener('click', function () {
        const isVideo = typeSelect.value === 'interactive_video';
        const template = `
            <div class="question-row border rounded-3 p-3 mb-3 bg-light position-relative" data-index="${qCount}">
                <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2 rounded-circle btn-remove-q"><i class="fas fa-times"></i></button>
                <div class="row g-2">
                    <div class="col-md-8">
                        <label class="form-label small fw-bold">Question Text</label>
                        <input type="text" name="questions[${qCount}][question_text]" class="form-control" placeholder="Enter question..." required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">Points</label>
                        <input type="number" step="0.5" name="questions[${qCount}][points]" class="form-control" value="1.0">
                    </div>
                    <div class="col-md-2 video-timestamp-field" style="${isVideo ? '' : 'display:none;'}">
                        <label class="form-label small fw-bold">Timestamp (Sec)</label>
                        <input type="number" name="questions[${qCount}][timestamp_seconds]" class="form-control" value="30" placeholder="e.g. 120">
                    </div>
                    <div class="col-12 mt-2">
                        <label class="form-label small fw-bold">Options & Correct Answer (Select Radio)</label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-text"><input type="radio" name="questions[${qCount}][correct_option]" value="0" checked></div>
                                    <input type="text" name="questions[${qCount}][options][0]" class="form-control" placeholder="Option A">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-text"><input type="radio" name="questions[${qCount}][correct_option]" value="1"></div>
                                    <input type="text" name="questions[${qCount}][options][1]" class="form-control" placeholder="Option B">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-text"><input type="radio" name="questions[${qCount}][correct_option]" value="2"></div>
                                    <input type="text" name="questions[${qCount}][options][2]" class="form-control" placeholder="Option C">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-text"><input type="radio" name="questions[${qCount}][correct_option]" value="3"></div>
                                    <input type="text" name="questions[${qCount}][options][3]" class="form-control" placeholder="Option D">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('questionsContainer').insertAdjacentHTML('beforeend', template);
        qCount++;
    });

    document.getElementById('questionsContainer')?.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-q')) {
            e.target.closest('.question-row').remove();
        }
    });
});
</script>
<?= $this->endSection() ?>
