<?= $this->extend('creator/layout') ?>

<?= $this->section('page_title') ?>
Studio Builder - <?= esc($course['title']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <!-- Top Course Header & Studio Controls -->
    <div class="modern-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <a href="<?= base_url('creator/courses') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 mb-2 d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>All Courses</span>
                </a>
                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                    <span class="badge rounded-pill px-3 py-1" style="background: rgba(79, 70, 229, 0.1); color: var(--primary); font-size: 11.5px; font-weight: 700;">
                        <i class="fa-solid fa-layer-group me-1"></i> <?= esc($course['category'] ?: 'General') ?>
                    </span>
                    <span class="badge rounded-pill px-3 py-1 <?= $course['status'] === 'published' ? 'bg-success text-white' : 'bg-secondary text-white' ?>" style="font-size: 11.5px; font-weight: 700;">
                        <i class="fa-solid <?= $course['status'] === 'published' ? 'fa-circle-check' : 'fa-pen-ruler' ?> me-1"></i>
                        <?= ucfirst($course['status']) ?>
                    </span>
                    <span class="text-muted small">
                        <i class="fa-solid fa-clock me-1 text-primary"></i> Total Runtime: <strong><?= $course['total_duration_minutes'] ?> mins</strong>
                    </span>
                </div>
                <h3 class="fw-bold text-dark mb-0 brand-font"><?= esc($course['title']) ?></h3>
            </div>

            <!-- Action Controls -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="<?= base_url('creator/courses/preview/' . ($course['uuid'] ?? $course['id'])) ?>" class="btn btn-outline-info rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" target="_blank">
                    <i class="fa-solid fa-eye"></i>
                    <span>Student View</span>
                </a>
                <a href="<?= base_url('creator/courses/edit/' . ($course['uuid'] ?? $course['id'])) ?>" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Edit Info</span>
                </a>
                <button type="button" class="btn btn-outline-primary rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addChapterModal">
                    <i class="fa-solid fa-folder-plus"></i>
                    <span>+ Add Chapter</span>
                </button>
                <button type="button" 
                        class="btn <?= $course['status'] === 'published' ? 'btn-success' : 'btn-gradient-primary' ?> rounded-pill px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2"
                        onclick="<?= $course['status'] === 'published' 
                            ? "showConfirmAction({ title: 'Unpublish Course?', message: 'This will hide the course from students and revert it back to Draft mode.', icon: 'fa-solid fa-eye-slash fa-2x', iconColor: '#D97706', iconBg: 'rgba(217, 119, 6, 0.12)', btnText: 'Unpublish & Make Draft', btnClass: 'btn-warning', actionUrl: '".base_url('creator/courses/togglePublish/' . ($course['uuid'] ?? $course['id']))."'})" 
                            : "showConfirmAction({ title: 'Publish Course Live?', message: 'This will make your chapters and video lessons immediately available to all enrolled students.', icon: 'fa-solid fa-rocket fa-2x', iconColor: '#16A34A', iconBg: 'rgba(22, 163, 74, 0.12)', btnText: '🚀 Yes, Publish Course', btnClass: 'btn-success', actionUrl: '".base_url('creator/courses/togglePublish/' . ($course['uuid'] ?? $course['id']))."'})" ?>">
                    <i class="fa-solid <?= $course['status'] === 'published' ? 'fa-circle-check' : 'fa-rocket' ?>"></i>
                    <span><?= $course['status'] === 'published' ? 'Live on Portal' : 'Publish Course' ?></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Curriculum Modules & Lesson List -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark mb-0 brand-font">
                <i class="fa-solid fa-list-check text-primary me-2"></i> Course Curriculum Outline
            </h5>
            <span class="badge bg-light text-secondary border px-3 py-1 rounded-pill fw-semibold">
                <?= count($chapters) ?> <?= count($chapters) === 1 ? 'Chapter' : 'Chapters' ?> Created
            </span>
        </div>

        <?php if (empty($chapters)): ?>
            <div class="modern-card p-5 text-center bg-white my-3">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 72px; height: 72px; background: rgba(79, 70, 229, 0.08); color: var(--primary);">
                    <i class="fa-solid fa-folder-open fa-2x"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1 brand-font">No Chapters Added Yet</h5>
                <p class="text-muted mb-4" style="max-width: 440px; margin: 0 auto;">Organize your curriculum into chapters and modules, then attach video lectures and study slides to each.</p>
                <div>
                    <button type="button" class="btn btn-gradient-primary rounded-pill px-4 py-2" data-bs-toggle="modal" data-bs-target="#addChapterModal">
                        <i class="fa-solid fa-folder-plus me-1"></i> Add First Chapter
                    </button>
                </div>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column gap-4">
                <?php foreach ($chapters as $chIdx => $ch): ?>
                    <div class="modern-card overflow-hidden">
                        <!-- Chapter Header Bar -->
                        <div class="p-3 p-md-4 bg-white border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 36px; height: 36px; background: var(--primary-gradient); font-size: 14px;">
                                    <?= $chIdx + 1 ?>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-0 brand-font"><?= esc($ch['chapter_title']) ?></h5>
                                    <?php if (!empty($ch['description'])): ?>
                                        <small class="text-muted"><?= esc($ch['description']) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" data-bs-toggle="modal" data-bs-target="#editChapterModal_<?= $ch['id'] ?>" title="Edit Chapter">
                                    <i class="fa-solid fa-pen" style="font-size: 11px;"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addLessonModal_<?= $ch['id'] ?>">
                                    <i class="fa-solid fa-circle-plus"></i> + Add Video Lesson
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"
                                        onclick="showConfirmAction({ title: 'Delete Chapter?', message: 'Are you sure you want to delete chapter &quot;<?= esc($ch['chapter_title'], 'js') ?>&quot; and all of its lessons?', icon: 'fa-solid fa-trash-can fa-2x', iconColor: '#DC2626', iconBg: 'rgba(220, 38, 38, 0.12)', btnText: 'Delete Chapter', btnClass: 'btn-danger', actionUrl: '<?= base_url('creator/courses/deleteChapter/' . ($ch['uuid'] ?? $ch['id'])) ?>' })" title="Delete Chapter">
                                    <i class="fa-solid fa-trash" style="font-size: 11px;"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Lessons in this Chapter -->
                        <div class="p-3 p-md-4 bg-light">
                            <?php if (empty($ch['lessons'])): ?>
                                <div class="p-4 bg-white rounded-3 border text-center text-muted small">
                                    <i class="fa-solid fa-film mb-2 fa-lg opacity-40 d-block"></i>
                                    No lessons added in this chapter yet. Click <strong>"+ Add Video Lesson"</strong> above to attach a video stream.
                                </div>
                            <?php else: ?>
                                <div class="row g-2">
                                    <?php foreach ($ch['lessons'] as $lIdx => $les): ?>
                                        <div class="col-12">
                                            <div class="p-3 bg-white border rounded-3 d-flex justify-content-between align-items-center flex-wrap gap-2 shadow-sm">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="rounded-3 d-flex align-items-center justify-content-center text-primary" style="width: 38px; height: 38px; background: rgba(79, 70, 229, 0.1); font-size: 1.1rem;">
                                                        <i class="fa-solid fa-circle-play"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark"><?= esc($les['lesson_title']) ?></div>
                                                        <div class="d-flex align-items-center gap-2 flex-wrap text-muted small mt-1">
                                                            <span class="badge bg-light text-secondary border px-2 py-0.5 rounded-pill">
                                                                <i class="fa-solid fa-clock me-1"></i> <?= $les['duration_minutes'] ?> mins
                                                            </span>
                                                            <?php if (!empty($les['video_url'])): ?>
                                                                <span class="badge rounded-pill px-2 py-0.5" style="background: rgba(239, 68, 68, 0.1); color: #dc2626;">
                                                                    <i class="fa-brands fa-youtube me-1"></i> Stream URL
                                                                </span>
                                                            <?php elseif (!empty($les['video_file'])): ?>
                                                                <span class="badge bg-success-subtle text-success border px-2 py-0.5 rounded-pill">
                                                                    <i class="fa-solid fa-file-video me-1"></i> MP4 Video File
                                                                </span>
                                                            <?php endif; ?>
                                                            <?php if (!empty($les['notes_file'])): ?>
                                                                <span class="badge bg-primary-subtle text-primary border px-2 py-0.5 rounded-pill">
                                                                    <i class="fa-solid fa-file-pdf me-1"></i> Slides/Notes Attached
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center gap-2">
                                                    <a href="<?= base_url('creator/courses/preview/' . ($course['uuid'] ?? $course['id']) . '?lesson=' . ($les['uuid'] ?? $les['id'])) ?>" class="btn btn-sm btn-outline-info rounded-pill px-3 py-1" target="_blank">
                                                        <i class="fa-solid fa-eye me-1"></i> Test Play
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" data-bs-toggle="modal" data-bs-target="#editLessonModal_<?= $les['id'] ?>" title="Edit Lesson">
                                                        <i class="fa-solid fa-pen" style="font-size: 11px;"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;"
                                                            onclick="showConfirmAction({ title: 'Delete Lesson?', message: 'Are you sure you want to delete lesson &quot;<?= esc($les['lesson_title'], 'js') ?>&quot;?', icon: 'fa-solid fa-trash-can fa-2x', iconColor: '#DC2626', iconBg: 'rgba(220, 38, 38, 0.12)', btnText: 'Delete Lesson', btnClass: 'btn-danger', actionUrl: '<?= base_url('creator/courses/deleteLesson/' . ($les['uuid'] ?? $les['id'])) ?>' })" title="Delete Lesson">
                                                        <i class="fa-solid fa-trash" style="font-size: 11px;"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Edit Lesson Modal for this Lesson -->
                                        <div class="modal fade" id="editLessonModal_<?= $les['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content rounded-4 border-0 shadow-lg p-3">
                                                    <form action="<?= base_url('creator/courses/saveLesson') ?>" method="POST" enctype="multipart/form-data">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                                        <input type="hidden" name="chapter_id" value="<?= $ch['id'] ?>">
                                                        <input type="hidden" name="lesson_id" value="<?= $les['id'] ?>">
                                                        
                                                        <div class="modal-header border-0 pb-0">
                                                            <div>
                                                                <div class="badge rounded-pill px-3 py-1 mb-1" style="background: rgba(79, 70, 229, 0.1); color: var(--primary);">
                                                                    Edit Lesson &bull; <?= esc($ch['chapter_title']) ?>
                                                                </div>
                                                                <h4 class="fw-bold brand-font text-dark mb-0">Edit Video Lesson</h4>
                                                            </div>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <div class="modal-body py-4">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold text-dark">Lesson Title <span class="text-danger">*</span></label>
                                                                <input type="text" name="lesson_title" class="form-control rounded-3" value="<?= esc($les['lesson_title']) ?>" required>
                                                            </div>

                                                            <div class="row g-3 mb-3">
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold text-dark">Video Source Method</label>
                                                                    <select name="video_type" class="form-select rounded-3">
                                                                        <option value="url" <?= $les['video_type'] === 'url' ? 'selected' : '' ?>>Online Video URL (YouTube / Vimeo / CDN)</option>
                                                                        <option value="upload" <?= $les['video_type'] === 'upload' ? 'selected' : '' ?>>Upload Video File (.mp4 / .webm)</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold text-dark">Estimated Duration (Minutes)</label>
                                                                    <input type="number" name="duration_minutes" class="form-control rounded-3" value="<?= $les['duration_minutes'] ?>" min="1">
                                                                </div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold text-dark">Video Stream URL</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg-light"><i class="fa-solid fa-link text-muted"></i></span>
                                                                    <input type="url" name="video_url" class="form-control" value="<?= esc($les['video_url']) ?>" placeholder="https://www.youtube.com/watch?v=...">
                                                                </div>
                                                                <div class="form-text text-muted small">Supports YouTube, Vimeo, or direct cloud MP4 video links.</div>
                                                            </div>

                                                            <div class="row g-3">
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold text-dark">Replace Video File (Optional)</label>
                                                                    <input type="file" name="video_file" class="form-control rounded-3" accept="video/mp4,video/webm">
                                                                    <?php if (!empty($les['video_file'])): ?>
                                                                        <small class="text-success d-block mt-1"><i class="fa-solid fa-check me-1"></i> Current: <?= basename($les['video_file']) ?></small>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold text-dark">Replace Slides / Notes File (Optional)</label>
                                                                    <input type="file" name="notes_file" class="form-control rounded-3" accept=".pdf,.ppt,.pptx">
                                                                    <?php if (!empty($les['notes_file'])): ?>
                                                                        <small class="text-primary d-block mt-1"><i class="fa-solid fa-file-pdf me-1"></i> Current: <?= basename($les['notes_file']) ?></small>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 fw-semibold">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Edit Chapter Modal -->
                    <div class="modal fade" id="editChapterModal_<?= $ch['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow-lg p-3">
                                <form action="<?= base_url('creator/courses/saveChapter') ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                    <input type="hidden" name="chapter_id" value="<?= $ch['id'] ?>">

                                    <div class="modal-header border-0 pb-0">
                                        <h4 class="fw-bold brand-font text-dark mb-0">
                                            <i class="fa-solid fa-pen-to-square text-primary me-2"></i> Edit Chapter
                                        </h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body py-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-dark">Chapter Title <span class="text-danger">*</span></label>
                                            <input type="text" name="chapter_title" class="form-control rounded-3" value="<?= esc($ch['chapter_title']) ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold text-dark">Chapter Outline / Summary</label>
                                            <textarea name="description" class="form-control rounded-3" rows="2"><?= esc($ch['description']) ?></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold text-dark">Sequence Order</label>
                                            <input type="number" name="order_seq" class="form-control rounded-3" value="<?= $ch['order_seq'] ?? ($chIdx + 1) ?>">
                                        </div>
                                    </div>

                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-gradient-primary rounded-pill px-4 fw-semibold">Update Chapter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Add Lesson Modal for this Chapter -->
                    <div class="modal fade" id="addLessonModal_<?= $ch['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content rounded-4 border-0 shadow-lg p-3">
                                <form action="<?= base_url('creator/courses/saveLesson') ?>" method="POST" enctype="multipart/form-data">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                    <input type="hidden" name="chapter_id" value="<?= $ch['id'] ?>">
                                    
                                    <div class="modal-header border-0 pb-0">
                                        <div>
                                            <div class="badge rounded-pill px-3 py-1 mb-1" style="background: rgba(79, 70, 229, 0.1); color: var(--primary);">
                                                Chapter <?= $chIdx + 1 ?>: <?= esc($ch['chapter_title']) ?>
                                            </div>
                                            <h4 class="fw-bold brand-font text-dark mb-0">Add Video Lecture Lesson</h4>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body py-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-dark">Lesson Title <span class="text-danger">*</span></label>
                                            <input type="text" name="lesson_title" class="form-control rounded-3" placeholder="e.g. Lesson 1: Component Architecture & Lifecycle" required>
                                        </div>

                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-dark">Video Source Method</label>
                                                <select name="video_type" class="form-select rounded-3">
                                                    <option value="url">Online Video URL (YouTube / Vimeo / CDN)</option>
                                                    <option value="upload">Upload Video File (.mp4 / .webm)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-dark">Estimated Duration (Minutes)</label>
                                                <input type="number" name="duration_minutes" class="form-control rounded-3" value="15" min="1">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold text-dark">Video Stream URL</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fa-solid fa-link text-muted"></i></span>
                                                <input type="url" name="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                                            </div>
                                            <div class="form-text text-muted small">Supports YouTube, Vimeo, or direct cloud MP4 video links.</div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-dark">Or Upload Local Video File</label>
                                                <input type="file" name="video_file" class="form-control rounded-3" accept="video/mp4,video/webm">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-dark">Lecture Slides / Notes File</label>
                                                <input type="file" name="notes_file" class="form-control rounded-3" accept=".pdf,.ppt,.pptx">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 fw-semibold">Save Lesson</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add Chapter Modal -->
<div class="modal fade" id="addChapterModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg p-3">
            <form action="<?= base_url('creator/courses/saveChapter') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="course_id" value="<?= $course['id'] ?>">

                <div class="modal-header border-0 pb-0">
                    <h4 class="fw-bold brand-font text-dark mb-0">
                        <i class="fa-solid fa-folder-plus text-primary me-2"></i> Add Course Chapter
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Chapter Title <span class="text-danger">*</span></label>
                        <input type="text" name="chapter_title" class="form-control rounded-3" placeholder="e.g. Chapter 1: Introduction to Frameworks" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Chapter Outline / Summary</label>
                        <textarea name="description" class="form-control rounded-3" rows="2" placeholder="Brief outline of concepts covered in this chapter..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Sequence Order</label>
                        <input type="number" name="order_seq" class="form-control rounded-3" value="<?= count($chapters) + 1 ?>">
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient-primary rounded-pill px-4 fw-semibold">Create Chapter</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

