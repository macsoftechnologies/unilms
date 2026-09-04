<?= $this->extend('creator/layout') ?>

<?= $this->section('page_title') ?>
Course Studio - My Video Courses
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Compute quick dashboard stats
$totalCourses = count($courses);
$publishedCount = 0;
$draftCount = 0;
$totalLessons = 0;
$totalDuration = 0;

foreach ($courses as $c) {
    if ($c['status'] === 'published') {
        $publishedCount++;
    } else {
        $draftCount++;
    }
    $totalLessons += (int)($c['lesson_count'] ?? 0);
    $totalDuration += (int)($c['total_duration_minutes'] ?? 0);
}
?>

<!-- Header & Quick Action -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white border shadow-sm mb-2">
            <span class="d-inline-block rounded-circle bg-success" style="width: 8px; height: 8px;"></span>
            <span class="text-secondary small fw-semibold">Creator Dashboard</span>
        </div>
        <h2 class="fw-bold text-dark mb-1 brand-font">My Video Courses</h2>
        <p class="text-muted mb-0" style="font-size: 0.95rem;">Manage, design curriculums, and publish video lectures with companion slides.</p>
    </div>
    <a href="<?= base_url('creator/courses/create') ?>" class="btn btn-gradient-primary rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2">
        <i class="fa-solid fa-circle-plus"></i>
        <span>Create New Course</span>
    </a>
</div>

<!-- Studio Stats Metrics Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="modern-card p-3 h-100 d-flex align-items-center gap-3">
            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="background: rgba(79, 70, 229, 0.1); color: var(--primary); width: 44px; height: 44px; font-size: 1.15rem;">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold" style="font-size: 11px !important;">Total Courses</div>
                <div class="brand-font" style="font-size: 18px; font-weight: 800; color: var(--text-dark);"><?= $totalCourses ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="modern-card p-3 h-100 d-flex align-items-center gap-3">
            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="background: rgba(16, 185, 129, 0.1); color: #10b981; width: 44px; height: 44px; font-size: 1.15rem;">
                <i class="fa-solid fa-globe"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold" style="font-size: 11px !important;">Live Published</div>
                <div class="brand-font" style="font-size: 18px; font-weight: 800; color: var(--text-dark);"><?= $publishedCount ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="modern-card p-3 h-100 d-flex align-items-center gap-3">
            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="background: rgba(6, 182, 212, 0.1); color: #0891b2; width: 44px; height: 44px; font-size: 1.15rem;">
                <i class="fa-solid fa-video"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold" style="font-size: 11px !important;">Video Lessons</div>
                <div class="brand-font" style="font-size: 18px; font-weight: 800; color: var(--text-dark);"><?= $totalLessons ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="modern-card p-3 h-100 d-flex align-items-center gap-3">
            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="background: rgba(245, 158, 11, 0.1); color: #d97706; width: 44px; height: 44px; font-size: 1.15rem;">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold" style="font-size: 11px !important;">Total Runtime</div>
                <div class="brand-font" style="font-size: 18px; font-weight: 800; color: var(--text-dark);"><?= $totalDuration ?> <span class="text-muted" style="font-size: 12px; font-weight: normal;">mins</span></div>
            </div>
        </div>
    </div>
</div>

<!-- Search, Filter & View Controls -->
<div class="modern-card p-3 mb-4">
    <div class="row g-2 align-items-center justify-content-between">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted ps-3">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" id="courseSearchInput" class="form-control bg-light border-start-0 ps-0" placeholder="Search by course title or category...">
            </div>
        </div>
        <div class="col-md-7 d-flex justify-content-md-end align-items-center gap-2 flex-wrap">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-secondary filter-btn active" data-filter="all">All (<?= $totalCourses ?>)</button>
                <button type="button" class="btn btn-sm btn-outline-secondary filter-btn" data-filter="published">Published (<?= $publishedCount ?>)</button>
                <button type="button" class="btn btn-sm btn-outline-secondary filter-btn" data-filter="draft">Drafts (<?= $draftCount ?>)</button>
            </div>
            <div class="btn-group ms-2" role="group">
                <button type="button" class="btn btn-sm btn-light border view-toggle-btn active" id="btnViewCards" title="Grid Cards View">
                    <i class="fa-solid fa-grip"></i>
                </button>
                <button type="button" class="btn btn-sm btn-light border view-toggle-btn" id="btnViewTable" title="Table View">
                    <i class="fa-solid fa-list"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<?php if (empty($courses)): ?>
    <div class="modern-card p-5 text-center my-4">
        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background: rgba(79, 70, 229, 0.08); color: var(--primary);">
            <i class="fa-solid fa-clapperboard fa-2x"></i>
        </div>
        <h4 class="fw-bold text-dark mb-2 brand-font">No Courses Created Yet</h4>
        <p class="text-muted mb-4" style="max-width: 480px; margin: 0 auto;">Get started by creating your first video course curriculum. Organize lectures into structured chapters with interactive assessments.</p>
        <a href="<?= base_url('creator/courses/create') ?>" class="btn btn-gradient-primary rounded-pill px-4 py-2">
            <i class="fa-solid fa-plus me-1"></i> Create Your First Course
        </a>
    </div>
<?php else: ?>

    <!-- 1. GRID CARDS VIEW (Default) -->
    <div id="coursesGridView" class="row g-4 mb-4">
        <?php foreach ($courses as $c): ?>
            <div class="col-md-6 col-lg-4 course-item" data-status="<?= esc($c['status']) ?>" data-title="<?= esc(strtolower($c['title'] . ' ' . $c['category'])) ?>">
                <div class="modern-card h-100 d-flex flex-column overflow-hidden" style="border-radius: 18px;">
                    <!-- Thumbnail Box -->
                    <div style="position: relative; width: 100%; aspect-ratio: 16/9; background: #0f172a; overflow: hidden;">
                        <?php if (!empty($c['thumbnail'])): ?>
                            <img src="<?= base_url($c['thumbnail']) ?>" alt="<?= esc($c['title']) ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;" class="course-thumb-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="d-flex align-items-center justify-content-center text-white w-100 h-100" style="display: none !important; background: linear-gradient(135deg, #3730a3 0%, #1e1b4b 100%);">
                                <i class="fa-solid fa-clapperboard fa-3x opacity-50"></i>
                            </div>
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center text-white w-100 h-100" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                                <i class="fa-solid fa-clapperboard fa-3x opacity-60"></i>
                            </div>
                        <?php endif; ?>

                        <!-- Category Pill Overlay -->
                        <span class="badge position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow-sm" style="background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(8px); font-size: 11px; font-weight: 600;">
                            <?= esc($c['category'] ?: 'General') ?>
                        </span>

                        <!-- Status Pill Overlay -->
                        <span class="badge position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill shadow-sm" style="background: <?= $c['status'] === 'published' ? 'rgba(16, 185, 129, 0.9)' : 'rgba(100, 116, 139, 0.9)' ?>; backdrop-filter: blur(8px); font-size: 11px; font-weight: 700;">
                            <i class="fa-solid <?= $c['status'] === 'published' ? 'fa-circle-check' : 'fa-pen-ruler' ?> me-1"></i>
                            <?= ucfirst($c['status']) ?>
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <h5 class="fw-bold text-dark mb-2 brand-font" style="line-height: 1.35;"><?= esc($c['title']) ?></h5>
                        <p class="text-muted small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.5;">
                            <?= esc($c['description'] ?: 'No course description provided yet.') ?>
                        </p>

                        <!-- Course Stats Chips -->
                        <div class="d-flex align-items-center justify-content-between p-2 px-3 rounded-3 bg-light mb-3 text-secondary small">
                            <span><i class="fa-solid fa-folder-tree me-1 text-primary"></i> <strong><?= $c['chapter_count'] ?></strong> Ch</span>
                            <span>&bull;</span>
                            <span><i class="fa-solid fa-play-circle me-1 text-primary"></i> <strong><?= $c['lesson_count'] ?></strong> Lessons</span>
                            <span>&bull;</span>
                            <span><i class="fa-solid fa-clock me-1 text-primary"></i> <?= $c['total_duration_minutes'] ?>m</span>
                        </div>

                        <!-- Card Action Buttons -->
                        <div class="d-flex align-items-center gap-2 pt-2 border-top">
                            <a href="<?= base_url('creator/courses/preview/' . $c['id']) ?>" class="btn btn-sm btn-outline-info rounded-pill px-3 py-1 flex-grow-1" title="Preview Student View">
                                <i class="fa-solid fa-eye me-1"></i> Preview
                            </a>
                            <a href="<?= base_url('creator/courses/builder/' . $c['id']) ?>" class="btn btn-sm btn-primary rounded-pill px-3 py-1 flex-grow-1" style="background-color: var(--primary);" title="Curriculum Builder">
                                <i class="fa-solid fa-photo-film me-1"></i> Builder
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 32px; height: 32px; padding: 0;">
                                    <i class="fa-solid fa-ellipsis-vertical text-muted"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-2" style="font-size: 13px;">
                                    <li>
                                        <a class="dropdown-item rounded-2 py-2" href="<?= base_url('creator/courses/edit/' . $c['id']) ?>">
                                            <i class="fa-solid fa-pen-to-square text-secondary me-2"></i> Edit Course Info
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item rounded-2 py-2" href="#" onclick="<?= $c['status'] === 'published' 
                                            ? "showConfirmAction({ title: 'Unpublish Course?', message: 'This will hide &quot;".esc($c['title'], 'js')."&quot; from students and revert it back to Draft mode.', icon: 'fa-solid fa-eye-slash fa-2x', iconColor: '#D97706', iconBg: 'rgba(217, 119, 6, 0.12)', btnText: 'Unpublish & Make Draft', btnClass: 'btn-warning', actionUrl: '".base_url('creator/courses/togglePublish/' . $c['id'])."'}); return false;" 
                                            : "showConfirmAction({ title: 'Publish Course Live?', message: 'This will make &quot;".esc($c['title'], 'js')."&quot; immediately available to all enrolled students.', icon: 'fa-solid fa-rocket fa-2x', iconColor: '#16A34A', iconBg: 'rgba(22, 163, 74, 0.12)', btnText: '🚀 Yes, Publish Course', btnClass: 'btn-success', actionUrl: '".base_url('creator/courses/togglePublish/' . $c['id'])."'}); return false;" ?>">
                                            <i class="fa-solid <?= $c['status'] === 'published' ? 'fa-eye-slash text-warning' : 'fa-globe text-success' ?> me-2"></i>
                                            <?= $c['status'] === 'published' ? 'Unpublish Course' : 'Publish Course' ?>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <a class="dropdown-item rounded-2 py-2 text-danger" href="#" onclick="showConfirmAction({ title: 'Delete Course & Lessons?', message: 'Are you sure you want to delete &quot;<?= esc($c['title'], 'js') ?>&quot; and all of its uploaded video lessons?', icon: 'fa-solid fa-trash-can fa-2x', iconColor: '#DC2626', iconBg: 'rgba(220, 38, 38, 0.12)', btnText: 'Delete Permanently', btnClass: 'btn-danger', actionUrl: '<?= base_url('creator/courses/delete/' . $c['id']) ?>' }); return false;">
                                            <i class="fa-solid fa-trash text-danger me-2"></i> Delete Course
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- 2. TABLE VIEW (Alternate) -->
    <div id="coursesTableView" class="modern-card overflow-hidden mb-4" style="display: none;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">Course</th>
                        <th>Category</th>
                        <th class="text-center">Chapters & Lessons</th>
                        <th class="text-center">Total Duration</th>
                        <th class="text-center">Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $c): ?>
                        <tr class="course-item" data-status="<?= esc($c['status']) ?>" data-title="<?= esc(strtolower($c['title'] . ' ' . $c['category'])) ?>">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <?php if (!empty($c['thumbnail'])): ?>
                                        <div style="position: relative; width: 68px; height: 46px; flex-shrink: 0;">
                                            <img src="<?= base_url($c['thumbnail']) ?>" class="rounded-3 object-fit-cover shadow-sm w-100 h-100" alt="Thumbnail" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="rounded-3 bg-light d-flex align-items-center justify-content-center text-primary w-100 h-100" style="display: none !important;">
                                                <i class="fa-solid fa-clapperboard"></i>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="rounded-3 d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 68px; height: 46px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); font-size: 16px;">
                                            <i class="fa-solid fa-clapperboard"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0"><?= esc($c['title']) ?></h6>
                                        <small class="text-muted"><?= esc(strlen($c['description'] ?? '') > 55 ? substr($c['description'], 0, 55) . '...' : ($c['description'] ?? 'No description')) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1 rounded-pill"><?= esc($c['category'] ?: 'General') ?></span>
                            </td>
                            <td class="text-center">
                                <span class="fw-semibold"><?= $c['chapter_count'] ?></span> Ch / <span class="fw-bold text-primary"><?= $c['lesson_count'] ?></span> Lessons
                            </td>
                            <td class="text-center text-muted font-monospace small">
                                <?= $c['total_duration_minutes'] ?> mins
                            </td>
                            <td class="text-center">
                                <?php if ($c['status'] === 'published'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success rounded-pill px-2">Published</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group gap-1">
                                    <a href="<?= base_url('creator/courses/preview/' . $c['id']) ?>" class="btn btn-sm btn-outline-info rounded-pill px-2" title="Preview Student View">
                                        <i class="fa-solid fa-eye me-1"></i> Preview
                                    </a>
                                    <a href="<?= base_url('creator/courses/builder/' . $c['id']) ?>" class="btn btn-sm btn-primary rounded-pill px-3" style="background-color: var(--primary);" title="Manage Chapters & Lessons">
                                        <i class="fa-solid fa-photo-film me-1"></i> Builder
                                    </a>
                                    <a href="<?= base_url('creator/courses/edit/' . $c['id']) ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-2" title="Edit Course Details">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm <?= $c['status'] === 'published' ? 'btn-outline-warning' : 'btn-outline-success' ?> rounded-pill px-2" 
                                            onclick="<?= $c['status'] === 'published' 
                                                ? "showConfirmAction({ title: 'Unpublish Course?', message: 'This will hide &quot;".esc($c['title'], 'js')."&quot; from students and revert it back to Draft mode.', icon: 'fa-solid fa-eye-slash fa-2x', iconColor: '#D97706', iconBg: 'rgba(217, 119, 6, 0.12)', btnText: 'Unpublish & Make Draft', btnClass: 'btn-warning', actionUrl: '".base_url('creator/courses/togglePublish/' . $c['id'])."'})" 
                                                : "showConfirmAction({ title: 'Publish Course Live?', message: 'This will make &quot;".esc($c['title'], 'js')."&quot; immediately available to all enrolled students.', icon: 'fa-solid fa-rocket fa-2x', iconColor: '#16A34A', iconBg: 'rgba(22, 163, 74, 0.12)', btnText: '🚀 Yes, Publish Course', btnClass: 'btn-success', actionUrl: '".base_url('creator/courses/togglePublish/' . $c['id'])."'})" ?>"
                                            title="<?= $c['status'] === 'published' ? 'Unpublish Course' : 'Publish Course' ?>">
                                        <i class="fa-solid <?= $c['status'] === 'published' ? 'fa-eye-slash' : 'fa-globe' ?>"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2" 
                                            onclick="showConfirmAction({ title: 'Delete Course & Lessons?', message: 'Are you sure you want to delete &quot;<?= esc($c['title'], 'js') ?>&quot; and all of its uploaded video lessons?', icon: 'fa-solid fa-trash-can fa-2x', iconColor: '#DC2626', iconBg: 'rgba(220, 38, 38, 0.12)', btnText: 'Delete Permanently', btnClass: 'btn-danger', actionUrl: '<?= base_url('creator/courses/delete/' . $c['id']) ?>' })" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('courseSearchInput');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const courseItems = document.querySelectorAll('.course-item');
    const btnViewCards = document.getElementById('btnViewCards');
    const btnViewTable = document.getElementById('btnViewTable');
    const gridView = document.getElementById('coursesGridView');
    const tableView = document.getElementById('coursesTableView');

    let currentFilter = 'all';
    let currentSearch = '';

    function applyFilters() {
        courseItems.forEach(item => {
            const status = item.getAttribute('data-status');
            const title = item.getAttribute('data-title');

            const matchesStatus = (currentFilter === 'all' || status === currentFilter);
            const matchesSearch = (!currentSearch || title.includes(currentSearch));

            if (matchesStatus && matchesSearch) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            currentSearch = e.target.value.toLowerCase().trim();
            applyFilters();
        });
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active', 'btn-primary'));
            btn.classList.add('active');
            currentFilter = btn.getAttribute('data-filter');
            applyFilters();
        });
    });

    if (btnViewCards && btnViewTable && gridView && tableView) {
        btnViewCards.addEventListener('click', () => {
            btnViewCards.classList.add('active', 'bg-primary', 'text-white');
            btnViewTable.classList.remove('active', 'bg-primary', 'text-white');
            gridView.style.display = 'flex';
            tableView.style.display = 'none';
        });

        btnViewTable.addEventListener('click', () => {
            btnViewTable.classList.add('active', 'bg-primary', 'text-white');
            btnViewCards.classList.remove('active', 'bg-primary', 'text-white');
            gridView.style.display = 'none';
            tableView.style.display = 'block';
        });
    }
});
</script>
<?= $this->endSection() ?>
