<?= $this->extend('lms/layout') ?>

<?= $this->section('page_title') ?>
Apply - <?= esc($posting['role_title']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0" style="max-width: 850px;">
    <div class="mb-4">
        <a href="<?= site_url('lms/internships') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 mb-2">
            <i class="fas fa-arrow-left me-1"></i> Back to Internships
        </a>
        <h3 class="fw-bold mb-1">Apply for <?= esc($posting['role_title']) ?></h3>
        <p class="text-muted mb-0"><?= esc($posting['company_name']) ?> &bull; <?= ucfirst(esc($posting['work_mode'])) ?></p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <h5 class="fw-bold text-dark mb-3"><i class="fas fa-info-circle text-primary me-2"></i> Role Description & Criteria</h5>
        <div class="p-3 bg-light rounded-3 border mb-4">
            <?= nl2br(esc($posting['description'])) ?>
        </div>

        <form action="<?= site_url('lms/internships/submitApplication') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="posting_id" value="<?= $posting['id'] ?>">

            <div class="mb-4">
                <label class="form-label fw-bold">Upload Resume (PDF format) <span class="text-danger">*</span></label>
                <input type="file" name="resume_file" accept=".pdf" class="form-control form-control-lg border-primary" required>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Cover Letter / Why are you a great fit?</label>
                <textarea name="cover_letter" class="form-control" rows="5" placeholder="Highlight your skills, background, and motivation for this role..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                <i class="fas fa-paper-plane me-2"></i> Submit Application
            </button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
