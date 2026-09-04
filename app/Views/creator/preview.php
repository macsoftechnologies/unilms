<?= $this->extend('creator/layout') ?>

<?= $this->section('page_title') ?>
Preview Course - <?= esc($course['title']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <a href="<?= base_url('creator/courses') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 mb-2 d-inline-flex align-items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Courses</span>
            </a>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(79, 70, 229, 0.1); color: var(--primary); font-weight: 700;">
                    <i class="fa-solid fa-eye me-1"></i> Student View Preview
                </span>
                <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1">
                    <?= esc($course['category']) ?> &bull; <?= $course['total_duration_minutes'] ?> mins total
                </span>
            </div>
            <h2 class="fw-bold text-dark mb-0 brand-font"><?= esc($course['title']) ?></h2>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('creator/courses/edit/' . $course['id']) ?>" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="fa-solid fa-pen-to-square me-1"></i> Edit Info
            </a>
            <a href="<?= base_url('creator/courses/builder/' . $course['id']) ?>" class="btn btn-gradient-primary rounded-pill px-3">
                <i class="fa-solid fa-photo-film me-1"></i> Studio Builder
            </a>
        </div>
    </div>

    <div class="row g-4 align-items-start">
        <!-- Left: Video Player & Active Lesson -->
        <div class="col-lg-8">
            <div class="modern-card overflow-hidden mb-3 bg-dark border-0 shadow-sm" style="border-radius: 16px;">
                <?php if ($activeLesson): ?>
                    <?php if ($activeLesson['video_type'] === 'upload' && !empty($activeLesson['video_file'])): ?>
                        <video controls style="width: 100%; aspect-ratio: 16/9; display: block; background: #000;" poster="<?= !empty($course['thumbnail']) ? base_url($course['thumbnail']) : '' ?>">
                            <source src="<?= base_url($activeLesson['video_file']) ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    <?php elseif (!empty($activeLesson['video_url'])): ?>
                        <?php
                        $embedUrl = $activeLesson['video_url'];
                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\\s]{11})/', $embedUrl, $matches)) {
                            $embedUrl = 'https://www.youtube.com/embed/' . $matches[1] . '?rel=0&modestbranding=1';
                        } elseif (strpos($embedUrl, 'vimeo.com/') !== false) {
                            $embedUrl = str_replace('vimeo.com/', 'player.vimeo.com/video/', $embedUrl);
                        }
                        ?>
                        <iframe src="<?= esc($embedUrl) ?>" style="width: 100%; aspect-ratio: 16/9; border: none; display: block;" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                    <?php else: ?>
                        <div style="aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; color: white; flex-direction: column;">
                            <i class="fa-solid fa-circle-play fa-3x mb-2 opacity-50"></i>
                            <p class="mb-0">No video stream linked for this lesson yet.</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div style="aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; color: white; flex-direction: column;">
                        <i class="fa-solid fa-film fa-3x mb-2 opacity-50"></i>
                        <p class="mb-0">No lessons added to this course yet. Use the Studio Builder to add chapters and video lessons.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Active Lesson Details -->
            <?php if ($activeLesson): ?>
                <div class="modern-card p-3 p-md-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                        <h4 class="fw-bold text-dark mb-0 brand-font"><?= esc($activeLesson['lesson_title']) ?></h4>
                        <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1">
                            <i class="fa-solid fa-clock me-1 text-primary"></i> <?= $activeLesson['duration_minutes'] ?> mins
                        </span>
                    </div>
                    <p class="text-muted small mb-0"><?= esc($course['description'] ?? '') ?></p>

                    <div class="mt-3 p-3 bg-light border rounded-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <strong class="text-dark d-block small"><i class="fa-solid fa-file-powerpoint text-primary me-1"></i> Lecture Slides & Study Companion Notes</strong>
                            <small class="text-muted">Interactive notes, architecture guidelines & tool reference</small>
                        </div>
                        <button type="button" onclick="openSlidesModal()" class="btn btn-sm btn-primary rounded-pill px-3">
                            <i class="fa-solid fa-book-open-reader me-1"></i> View Slides & Notes
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right: Chapter Playlist Accordion -->
        <div class="col-lg-4">
            <div class="modern-card p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0 brand-font">
                        <i class="fa-solid fa-list-ul text-primary me-2"></i> Course Playlist
                    </h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill"><?= count($allLessons) ?> Lessons</span>
                </div>

                <?php if (empty($chapters)): ?>
                    <p class="text-muted small mb-0 text-center py-4">No chapters created yet.</p>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($chapters as $chIdx => $ch): ?>
                            <div class="border rounded-3 overflow-hidden bg-white shadow-sm">
                                <div class="p-2 px-3 bg-light fw-bold small text-dark border-bottom d-flex justify-content-between align-items-center">
                                    <span>Ch <?= $chIdx + 1 ?>: <?= esc($ch['chapter_title']) ?></span>
                                    <span class="text-muted" style="font-size: 10px; font-weight: normal;"><?= count($ch['lessons']) ?> lessons</span>
                                </div>
                                <div class="list-group list-group-flush">
                                    <?php if (empty($ch['lessons'])): ?>
                                        <div class="p-2 px-3 small text-muted">No lessons in this chapter.</div>
                                    <?php else: ?>
                                        <?php foreach ($ch['lessons'] as $les): ?>
                                            <?php $isActive = ($activeLesson && $activeLesson['id'] == $les['id']); ?>
                                            <a href="<?= base_url('creator/courses/preview/' . $course['id'] . '?lesson=' . $les['id']) ?>" 
                                               class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2 px-3 small <?= $isActive ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' ?>" style="font-size: 12.5px;">
                                                <div class="d-flex align-items-center gap-2 overflow-hidden">
                                                    <i class="fa-solid <?= $isActive ? 'fa-circle-play text-primary' : 'fa-play text-muted' ?>" style="font-size: 11px;"></i>
                                                    <span class="text-truncate"><?= esc($les['lesson_title']) ?></span>
                                                </div>
                                                <small class="text-muted ms-2 flex-shrink-0"><?= $les['duration_minutes'] ?>m</small>
                                            </a>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Lecture Slides & Notes Modal -->
<div class="modal fade" id="creatorSlidesModal" tabindex="-1" aria-hidden="true" style="display: none; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(6px);">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header bg-light border-bottom p-3 px-4">
                <div>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1 mb-1">
                        📖 Companion Lecture Slides & Study Notes
                    </span>
                    <h5 class="modal-title fw-bold text-dark brand-font mb-0">
                        <?= esc($activeLesson['lesson_title'] ?? 'Lecture Companion') ?>
                    </h5>
                </div>
                <button type="button" class="btn-close" onclick="closeSlidesModal()"></button>
            </div>
            <div class="modal-body p-4 d-flex flex-column gap-3" style="max-height: 70vh; overflow-y: auto;">
                <div class="p-3 bg-light border rounded-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-primary small"><i class="fa-solid fa-layer-group me-1"></i> Slide 1: Core Theoretical Foundations</span>
                        <span class="badge bg-success bg-opacity-10 text-success">Key Takeaways</span>
                    </div>
                    <ul class="small text-muted mb-0 ps-3">
                        <li><strong>Concept Overview</strong>: Modular decomposition of state management and asynchronous lifecycle pipelines.</li>
                        <li><strong>Design Rationale</strong>: Enforces strict single-responsibility principles across decoupled backend services.</li>
                        <li><strong>Complexity & Efficiency</strong>: Optimized logarithmic $\mathcal{O}(\log N)$ execution time and sub-millisecond API response benchmarks.</li>
                    </ul>
                </div>

                <div class="p-3 bg-light border rounded-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-info small"><i class="fa-solid fa-terminal me-1"></i> Slide 2: Technical Tools & Developer Guide</span>
                        <span class="badge bg-info bg-opacity-10 text-info">Tooling Guide</span>
                    </div>
                    <div class="bg-dark text-info font-monospace p-2 rounded-2 small mb-2" style="font-size: 11px;">
                        # Start local containerized development stack<br>
                        $ docker-compose up -d --build
                    </div>
                    <p class="small text-muted mb-0">Integrates directly with VS Code, Postman Workspaces, and Docker Orchestration.</p>
                </div>
            </div>
            <div class="modal-footer bg-light p-3 px-4">
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-4" onclick="closeSlidesModal()">Close Viewer</button>
            </div>
        </div>
    </div>
</div>

<script>
function openSlidesModal() {
    const modal = document.getElementById('creatorSlidesModal');
    if (modal) {
        modal.classList.add('show');
        modal.style.display = 'block';
    }
}

function closeSlidesModal() {
    const modal = document.getElementById('creatorSlidesModal');
    if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
    }
}
</script>
<?= $this->endSection() ?>

