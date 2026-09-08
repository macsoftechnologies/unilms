<?= $this->extend('lms/career/layout') ?>

<?= $this->section('page_title') ?>Video Masterclasses (Blended)<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    /* Hero Card */
    .learn-hero-card {
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.12) 0%, rgba(124, 58, 237, 0.18) 50%, rgba(219, 39, 119, 0.1) 100%), var(--surface);
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 36px 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 32px;
        margin-bottom: 36px;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    html.dark-mode .learn-hero-card {
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.2) 0%, rgba(124, 58, 237, 0.25) 50%, rgba(219, 39, 119, 0.15) 100%), var(--surface);
    }

    .hero-left {
        max-width: 700px;
    }

    .hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 5px 14px;
        border-radius: 20px;
        background: rgba(99, 102, 241, 0.15);
        border: 1px solid rgba(99, 102, 241, 0.3);
        color: var(--primary);
        font-size: 11.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 14px;
    }

    .hero-title {
        font-size: 30px !important;
        font-weight: 800 !important;
        line-height: 1.25 !important;
        color: var(--text-main) !important;
        margin-bottom: 10px !important;
    }

    .hero-title .highlight {
        background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-desc {
        font-size: 14px;
        line-height: 1.6;
        color: var(--text-muted);
        margin-bottom: 22px;
    }

    .hero-stats-row {
        display: flex;
        align-items: center;
        gap: 28px;
        flex-wrap: wrap;
    }

    .hero-stat-item {
        display: flex;
        flex-direction: column;
    }

    .hero-stat-val {
        font-family: 'Outfit', sans-serif;
        font-size: 22px;
        font-weight: 800;
        color: var(--text-main);
    }

    .hero-stat-lbl {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Quick Continue Watch Box */
    .hero-right-action {
        background: var(--surface-elevated);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 22px;
        width: 320px;
        flex-shrink: 0;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .c-watch-tag {
        font-size: 11px;
        font-weight: 800;
        color: #ec4899;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .c-watch-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 8px;
        line-height: 1.35;
    }

    .c-watch-meta {
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .btn-resume-play {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 10px;
        border-radius: 12px;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: #ffffff !important;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
        transition: all 0.2s;
    }

    .btn-resume-play:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
    }

    /* Section Headers */
    .section-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 22px;
    }

    .section-heading-title {
        font-size: 20px !important;
        font-weight: 800 !important;
        color: var(--text-main) !important;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-heading-sub {
        font-size: 13px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    /* Courses Grid */
    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 26px;
        margin-bottom: 40px;
    }

    .course-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }

    .course-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 32px -4px rgba(99, 102, 241, 0.15);
        border-color: rgba(99, 102, 241, 0.4);
    }

    .course-cover {
        height: 180px;
        position: relative;
        background: linear-gradient(135deg, #1e1b4b, #312e81);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .course-cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .course-card:hover .course-cover-img {
        transform: scale(1.05);
    }

    .course-cat-tag {
        position: absolute;
        top: 14px;
        left: 14px;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(8px);
        color: #ffffff;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .course-duration-tag {
        position: absolute;
        bottom: 14px;
        right: 14px;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(8px);
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .course-body {
        padding: 22px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .course-title {
        font-size: 16.5px !important;
        font-weight: 800 !important;
        color: var(--text-main) !important;
        margin-bottom: 8px !important;
        line-height: 1.35 !important;
    }

    .course-desc {
        font-size: 13px;
        line-height: 1.5;
        color: var(--text-muted);
        margin-bottom: 18px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .course-meta-row {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--border-light);
    }

    .course-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
    }

    .btn-watch-course {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 12px;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: #ffffff !important;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        transition: all 0.2s;
    }

    .btn-watch-course:hover {
        box-shadow: 0 6px 18px rgba(99, 102, 241, 0.5);
        transform: translateY(-2px);
    }

    /* Study Materials */
    .materials-subject-box {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
    }

    .mat-subj-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .mat-items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 14px;
    }

    .mat-item-card {
        background: var(--surface-elevated);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: all 0.2s;
        text-decoration: none;
        color: var(--text-main) !important;
    }

    .mat-item-card:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    }

    .mat-icon-wrap {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(99, 102, 241, 0.12);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .mat-info-title {
        font-size: 13.5px;
        font-weight: 700;
        line-height: 1.25;
        margin-bottom: 3px;
    }

    .mat-info-type {
        font-size: 11px;
        color: var(--text-muted);
        font-weight: 600;
    }

    /* Suite Quick Cards Grid */
    .suite-quick-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-top: 40px;
    }

    .suite-quick-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 22px;
        text-decoration: none;
        color: var(--text-main) !important;
        display: flex;
        align-items: flex-start;
        gap: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        transition: all 0.25s;
    }

    .suite-quick-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.15);
    }

    .sqc-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 18px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .sqc-title {
        font-size: 15px;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .sqc-desc {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.4;
    }

    @media (max-width: 900px) {
        .learn-hero-card {
            flex-direction: column;
            padding: 24px;
        }
        .hero-right-action {
            width: 100%;
        }
    }
</style>

<!-- =========================================================================
     WELCOME HERO BANNER
========================================================================= -->
<section class="learn-hero-card">
    <div class="hero-left">
        <div class="hero-eyebrow">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
            <span>Blended Video Masterclasses</span>
        </div>
        <h1 class="hero-title">Welcome back, <span class="highlight"><?= esc(session('first_name') ?: (session('user_name') ?: 'Student')) ?></span>! 🚀</h1>
        <p class="hero-desc">
            Explore your high-definition video masterclasses, interactive chapter streams, lecture handouts, and industry placement tracks in one unified suite.
        </p>
        <div class="hero-stats-row">
            <div class="hero-stat-item">
                <span class="hero-stat-val"><?= count($publishedCourses) ?></span>
                <span class="hero-stat-lbl">Masterclasses</span>
            </div>
            <div class="hero-stat-item">
                <span class="hero-stat-val"><?= $totalMaterials ?></span>
                <span class="hero-stat-lbl">Subject Notes</span>
            </div>
            <div class="hero-stat-item">
                <span class="hero-stat-val"><?= $totalQuizzes ?></span>
                <span class="hero-stat-lbl">CBT Tests</span>
            </div>
            <div class="hero-stat-item">
                <span class="hero-stat-val"><?= $totalAssignments ?></span>
                <span class="hero-stat-lbl">Assignments</span>
            </div>
        </div>
    </div>

    <?php if (!empty($publishedCourses)): ?>
    <?php $topCourse = $publishedCourses[0]; ?>
    <div class="hero-right-action">
        <div class="c-watch-tag">
            <i class="fa-solid fa-play"></i>
            <span>Featured Masterclass</span>
        </div>
        <div class="c-watch-title"><?= esc($topCourse['title']) ?></div>
        <div class="c-watch-meta">
            <span><i class="fa-solid fa-book-open"></i> <?= $topCourse['chapter_count'] ?> Chapters</span>
            <span>•</span>
            <span><i class="fa-solid fa-video"></i> <?= $topCourse['lesson_count'] ?> Lessons</span>
        </div>
        <a href="<?= base_url('lms/materials/watch/' . ($topCourse['uuid'] ?? $topCourse['id'])) ?>" class="btn-resume-play">
            <i class="fa-solid fa-circle-play"></i>
            <span>Stream Masterclass</span>
        </a>
    </div>
    <?php endif; ?>
</section>

<!-- =========================================================================
     VIDEO MASTERCLASSES (BLENDED) - DYNAMIC FROM DATABASE
========================================================================= -->
<div class="section-header-row">
    <div>
        <h2 class="section-heading-title">
            <i class="fa-solid fa-clapperboard" style="color: var(--primary);"></i>
            <span>Curriculum Video Masterclasses</span>
        </h2>
        <p class="section-heading-sub">High-definition chapter-by-chapter lectures with structured syllabus tracks</p>
    </div>
</div>

<?php if (!empty($publishedCourses)): ?>
<div class="courses-grid">
    <?php foreach ($publishedCourses as $idx => $course): ?>
    <div class="course-card">
        <div class="course-cover">
            <?php if (!empty($course['thumbnail'])): ?>
                <img src="<?= base_url($course['thumbnail']) ?>" alt="<?= esc($course['title']) ?>" class="course-cover-img">
            <?php else: ?>
                <div style="background: linear-gradient(135deg, <?= $idx % 2 == 0 ? '#4f46e5, #7c3aed' : '#2563eb, #06b6d4' ?>); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 38px;">
                    <i class="fa-solid fa-film"></i>
                </div>
            <?php endif; ?>
            <span class="course-cat-tag"><?= esc($course['category'] ?: 'Blended Learning') ?></span>
            <span class="course-duration-tag">
                <i class="fa-regular fa-clock"></i>
                <span><?= esc($course['total_duration_minutes'] ?: '120') ?> mins</span>
            </span>
        </div>

        <div class="course-body">
            <h3 class="course-title"><?= esc($course['title']) ?></h3>
            <p class="course-desc"><?= esc($course['description'] ?: 'Comprehensive blended learning curriculum designed for hands-on mastery and theoretical rigor.') ?></p>
            
            <div class="course-meta-row">
                <div class="course-meta-item">
                    <i class="fa-solid fa-folder-tree" style="color: var(--primary);"></i>
                    <span><?= $course['chapter_count'] ?> Chapters</span>
                </div>
                <div class="course-meta-item">
                    <i class="fa-solid fa-circle-play" style="color: #ec4899;"></i>
                    <span><?= $course['lesson_count'] ?> Lessons</span>
                </div>
                <div class="course-meta-item">
                    <i class="fa-solid fa-certificate" style="color: #10b981;"></i>
                    <span>Accredited</span>
                </div>
            </div>

            <div style="margin-top: auto;">
                <a href="<?= base_url('lms/materials/watch/' . ($course['uuid'] ?? $course['id'])) ?>" class="btn-watch-course">
                    <i class="fa-solid fa-circle-play"></i>
                    <span>Start Masterclass</span>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div style="text-align: center; padding: 50px 20px; background: var(--surface); border: 1px dashed var(--border); border-radius: 18px; margin-bottom: 30px;">
    <i class="fa-solid fa-video-slash" style="font-size: 40px; color: var(--text-muted); margin-bottom: 12px; display: block;"></i>
    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 4px;">No Video Masterclasses Published Yet</h3>
    <p style="font-size: 13px; color: var(--text-muted);">Your department instructors are currently uploading syllabus videos.</p>
</div>
<?php endif; ?>

<!-- =========================================================================
     SUBJECT NOTES & STUDY MATERIALS
========================================================================= -->
<?php if (!empty($grouped_materials)): ?>
<div class="section-header-row" style="margin-top: 36px;">
    <div>
        <h2 class="section-heading-title">
            <i class="fa-solid fa-book-bookmark" style="color: #06b6d4;"></i>
            <span>Subject Notes & Lecture Slides</span>
        </h2>
        <p class="section-heading-sub">Download verified PDFs, handouts, and presentation slides</p>
    </div>
</div>

<?php foreach ($grouped_materials as $subjectName => $items): ?>
<div class="materials-subject-box">
    <div class="mat-subj-title">
        <i class="fa-solid fa-graduation-cap" style="color: var(--primary);"></i>
        <span><?= esc($subjectName) ?></span>
        <span style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); margin-left: auto;"><?= count($items) ?> Resources</span>
    </div>
    <div class="mat-items-grid">
        <?php foreach ($items as $item): ?>
        <?php 
            $resUrl = !empty($item['file_path']) ? base_url($item['file_path']) : (!empty($item['content_url']) ? esc($item['content_url']) : '#');
            $resType = !empty($item['type']) ? strtoupper($item['type']) : 'PDF';
        ?>
        <a href="<?= $resUrl ?>" target="_blank" class="mat-item-card">
            <div class="mat-icon-wrap">
                <?php if (($item['type'] ?? '') === 'youtube'): ?>
                    <i class="fa-brands fa-youtube" style="color: #ef4444;"></i>
                <?php elseif (($item['type'] ?? '') === 'link'): ?>
                    <i class="fa-solid fa-link" style="color: #06b6d4;"></i>
                <?php else: ?>
                    <i class="fa-regular fa-file-pdf"></i>
                <?php endif; ?>
            </div>
            <div>
                <div class="mat-info-title"><?= esc($item['title'] ?? 'Material') ?></div>
                <div class="mat-info-type"><?= esc($resType) ?> • <?= !empty($item['created_at']) ? date('M d, Y', strtotime($item['created_at'])) : 'Recent' ?></div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>

<!-- =========================================================================
     CAREER & PRACTICE QUICK LAUNCHPAD
========================================================================= -->
<div class="section-header-row" style="margin-top: 36px;">
    <div>
        <h2 class="section-heading-title">
            <i class="fa-solid fa-rocket" style="color: #ec4899;"></i>
            <span>Career Suite & Professional Sandboxes</span>
        </h2>
        <p class="section-heading-sub">Direct access to AI resumes, interview preparation, and placement drives</p>
    </div>
</div>

<div class="suite-quick-grid">
    <a href="<?= base_url('lms/career/resume') ?>" class="suite-quick-card">
        <div class="sqc-icon" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
            <i class="fa-solid fa-file-invoice"></i>
        </div>
        <div>
            <div class="sqc-title">AI Resume Builder</div>
            <div class="sqc-desc">ATS keyword scanner, instant scorecards, and PDF export.</div>
        </div>
    </a>

    <a href="<?= base_url('lms/career/mock-interview') ?>" class="suite-quick-card">
        <div class="sqc-icon" style="background: linear-gradient(135deg, #db2777, #ec4899);">
            <i class="fa-solid fa-microphone-lines"></i>
        </div>
        <div>
            <div class="sqc-title">Mock Interviews</div>
            <div class="sqc-desc">Role-specific behavioral & technical questions with AI scoring.</div>
        </div>
    </a>

    <a href="<?= base_url('lms/career/portfolio') ?>" class="suite-quick-card">
        <div class="sqc-icon" style="background: linear-gradient(135deg, #0891b2, #06b6d4);">
            <i class="fa-solid fa-globe"></i>
        </div>
        <div>
            <div class="sqc-title">Digital Portfolio</div>
            <div class="sqc-desc">Personal portfolio website with live showcase, QR, and analytics.</div>
        </div>
    </a>
</div>

<?= $this->endSection() ?>
