<?= $this->extend('creator/layout') ?>

<?= $this->section('page_title') ?>
<?= !empty($course) ? 'Edit Course - ' . esc($course['title']) : 'Create Course' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0" style="max-width: 860px;">
    <!-- Top Back & Header -->
    <div class="mb-4">
        <a href="<?= base_url('creator/courses') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 mb-2 d-inline-flex align-items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Courses</span>
        </a>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge rounded-pill px-3 py-1" style="background: rgba(79, 70, 229, 0.1); color: var(--primary); font-size: 12px; font-weight: 700;">
                <?= !empty($course) ? '✏️ EDITING COURSE' : '🚀 NEW COURSE' ?>
            </span>
        </div>
        <h2 class="fw-bold text-dark mb-1 brand-font"><?= !empty($course) ? 'Edit Course Details' : 'Create New Video Course' ?></h2>
        <p class="text-muted mb-0" style="font-size: 0.95rem;">Configure course title, domain category, learning objectives, and promotional thumbnail.</p>
    </div>

    <div class="modern-card p-4 p-md-5">
        <form action="<?= base_url('creator/courses/saveCourse') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <?php if (!empty($course)): ?>
                <input type="hidden" name="id" value="<?= $course['id'] ?>">
            <?php endif; ?>

            <!-- Section 1: Basic Info -->
            <div class="mb-4">
                <label class="form-label fw-bold text-dark mb-1">
                    Course Title <span class="text-danger">*</span>
                </label>
                <input type="text" name="title" class="form-control form-control-lg rounded-3 border" placeholder="e.g. Full Stack Web Development with React & Node.js" value="<?= esc($course['title'] ?? '') ?>" required style="font-size: 1.05rem;">
                <div class="form-text text-muted small mt-1">Make your course title clear, descriptive, and student-friendly.</div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark mb-1">
                        Domain Category <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="categoryInput" name="category" class="form-control rounded-3" placeholder="e.g. Computer Science" value="<?= esc($course['category'] ?? '') ?>" required>
                    
                    <!-- Quick Category Suggestions -->
                    <div class="d-flex flex-wrap gap-1 mt-2">
                        <span class="badge bg-light text-secondary border cursor-pointer cat-pill" onclick="setCategory('Computer Science')">+ Computer Science</span>
                        <span class="badge bg-light text-secondary border cursor-pointer cat-pill" onclick="setCategory('Data Science')">+ Data Science</span>
                        <span class="badge bg-light text-secondary border cursor-pointer cat-pill" onclick="setCategory('AI & ML')">+ AI & ML</span>
                        <span class="badge bg-light text-secondary border cursor-pointer cat-pill" onclick="setCategory('Web Development')">+ Web Dev</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark mb-1">Course Thumbnail</label>
                    <div class="p-3 border rounded-3 bg-light text-center" style="border-style: dashed !important; border-width: 2px !important; min-height: 130px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <input type="file" id="thumbnailFileInput" name="thumbnail_file" class="form-control d-none" accept="image/jpeg,image/png,image/webp" onchange="previewThumbnail(this)">
                        
                        <?php $hasThumb = !empty($course['thumbnail']); ?>
                        <div id="thumbPreviewBox" class="mb-2" style="<?= $hasThumb ? '' : 'display: none;' ?>">
                            <img id="thumbImg" 
                                 <?= $hasThumb ? 'src="' . base_url($course['thumbnail']) . '"' : '' ?> 
                                 class="rounded-3 shadow-sm border" 
                                 style="max-height: 110px; max-width: 100%; object-fit: cover;" 
                                 alt="Thumbnail"
                                 onerror="this.parentElement.style.display='none'; document.getElementById('uploadPrompt').style.display='block';">
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-0.5" style="font-size: 11px;" onclick="removeThumbnail()">
                                    <i class="fa-solid fa-trash me-1"></i> Change Image
                                </button>
                            </div>
                        </div>

                        <div id="uploadPrompt" style="<?= $hasThumb ? 'display: none;' : '' ?>">
                            <i class="fa-solid fa-cloud-arrow-up fa-2x text-secondary mb-1 opacity-50"></i>
                            <div class="small fw-semibold text-dark">Click to upload thumbnail</div>
                            <small class="text-muted d-block mb-2" style="font-size: 11px;">Recommended: 16:9 ratio (JPEG, PNG, WebP)</small>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="document.getElementById('thumbnailFileInput').click()">
                                <i class="fa-solid fa-image me-1"></i> Choose Image File
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(!session()->get('creator_org_id') && !empty($organizations)): ?>
            <div class="mb-4">
                <label class="form-label fw-bold text-dark mb-1">Target College / Organization Scope</label>
                <select name="org_id" class="form-select rounded-3">
                    <option value="">🌐 Global (Master Curriculum — Available to All Colleges)</option>
                    <?php foreach($organizations as $org): ?>
                        <option value="<?= $org['id'] ?>" <?= (!empty($course['org_id']) && $course['org_id'] == $org['id']) ? 'selected' : '' ?>>🏢 <?= esc($org['name']) ?> (<?= esc($org['code']) ?>)</option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text text-muted small">Choose a specific organization or publish globally across the entire platform.</div>
            </div>
            <?php endif; ?>

            <div class="mb-4">
                <label class="form-label fw-bold text-dark mb-1">Course Summary & Syllabus Overview</label>
                <textarea name="description" class="form-control rounded-3" rows="4" placeholder="Briefly describe what students will learn in this curriculum, key competencies, and prerequisites..."><?= esc($course['description'] ?? '') ?></textarea>
            </div>

            <hr class="my-4" style="opacity: 0.1;">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <a href="<?= base_url('creator/courses') ?>" class="btn btn-light rounded-pill px-4 py-2 fw-semibold">
                    <i class="fa-solid fa-xmark me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                    <?= !empty($course) ? '<i class="fa-solid fa-floppy-disk"></i> Save Course Changes' : 'Continue to Studio Builder <i class="fa-solid fa-arrow-right"></i>' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.cursor-pointer {
    cursor: pointer;
    transition: all 0.15s ease;
}
.cat-pill:hover {
    background-color: var(--primary-light) !important;
    color: var(--primary) !important;
    border-color: var(--primary) !important;
}
</style>

<script>
function setCategory(cat) {
    document.getElementById('categoryInput').value = cat;
}

function previewThumbnail(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const thumbImg = document.getElementById('thumbImg');
            thumbImg.src = e.target.result;
            document.getElementById('thumbPreviewBox').style.display = 'block';
            document.getElementById('uploadPrompt').style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function removeThumbnail() {
    const fileInput = document.getElementById('thumbnailFileInput');
    if (fileInput) fileInput.value = '';
    const thumbImg = document.getElementById('thumbImg');
    if (thumbImg) thumbImg.removeAttribute('src');
    document.getElementById('thumbPreviewBox').style.display = 'none';
    document.getElementById('uploadPrompt').style.display = 'block';
}
</script>
<?= $this->endSection() ?>


