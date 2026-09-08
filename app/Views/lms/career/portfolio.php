<?= $this->extend('lms/career/layout') ?>
<?= $this->section('page_title') ?>Digital Portfolio<?= $this->endSection() ?>
<?= $this->section('content') ?>

<style>
    .portfolio-hero {
        background: linear-gradient(135deg, #164e63 0%, #0891b2 50%, #22d3ee 100%);
        border-radius: 24px;
        padding: 32px 36px;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .portfolio-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px);
        background-size: 18px 18px;
    }

    .portfolio-hero-left { position: relative; z-index: 2; }

    .portfolio-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.2);
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        color: #a5f3fc;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 14px;
    }

    .portfolio-hero h1 {
        font-family: 'Outfit', sans-serif !important;
        font-size: 26px !important;
        font-weight: 800 !important;
        color: #fff !important;
        margin-bottom: 8px !important;
    }

    .portfolio-hero p { font-size: 13.5px; color: rgba(255,255,255,0.7); max-width: 500px; }

    .share-controls {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        gap: 12px;
        align-items: center;
    }

    .qr-placeholder {
        width: 110px;
        height: 110px;
        border-radius: 16px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        padding: 8px;
    }

    .qr-inner {
        width: 100%;
        height: 100%;
        border: 2px solid #0891b2;
        border-radius: 8px;
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 2px;
        padding: 6px;
    }

    .qr-cell { border-radius: 1px; }
    .qr-cell.dark { background: #0f172a; }
    .qr-cell.light { background: #e2e8f0; }

    .share-url-box {
        display: flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.2);
        padding: 7px 14px;
        border-radius: 10px;
        font-size: 11px;
        color: #a5f3fc;
        font-weight: 600;
    }

    .share-url-box i { font-size: 10px; cursor: pointer; }

    /* Analytics Row */
    .analytics-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 32px;
    }

    .analytics-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: all 0.3s ease;
    }

    .analytics-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px -6px rgba(0,0,0,0.1); }

    .ac-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .ac-icon.views { background: rgba(6, 182, 212, 0.1); color: #06b6d4; }
    .ac-icon.clicks { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
    .ac-icon.recruiter { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .ac-icon.downloads { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }

    .ac-value {
        font-family: 'Outfit', sans-serif;
        font-size: 22px;
        font-weight: 800;
        color: var(--text-main);
        line-height: 1;
    }

    .ac-label { font-size: 11.5px; color: var(--text-muted); font-weight: 600; margin-top: 2px; }

    .ac-change {
        font-size: 10px;
        font-weight: 700;
        margin-top: 2px;
    }

    .ac-change.up { color: #10b981; }
    .ac-change.down { color: #ef4444; }

    /* Theme Selector */
    .section-title {
        font-family: 'Outfit', sans-serif;
        font-size: 18px;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 6px;
    }

    .section-subtitle {
        font-size: 12.5px;
        color: var(--text-muted);
        margin-bottom: 20px;
    }

    .themes-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 32px;
    }

    .theme-card {
        border: 2px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .theme-card.selected { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-glow); }
    .theme-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px -6px rgba(0,0,0,0.12); }

    .theme-preview {
        height: 120px;
        position: relative;
        overflow: hidden;
    }

    .tp-minimal { background: linear-gradient(135deg, #f8fafc, #e2e8f0); }
    .tp-developer { background: linear-gradient(135deg, #0f172a, #1e293b); }
    .tp-creative { background: linear-gradient(135deg, #fdf4ff, #fce7f3, #ede9fe); }
    .tp-academic { background: linear-gradient(135deg, #fffbeb, #fef3c7); }

    .tp-mock-layout {
        position: absolute;
        inset: 12px;
        border-radius: 8px;
        padding: 10px;
    }

    .tp-mock-nav {
        height: 4px;
        background: rgba(0,0,0,0.08);
        border-radius: 2px;
        margin-bottom: 8px;
        width: 60%;
    }

    .tp-mock-hero {
        height: 30px;
        background: rgba(0,0,0,0.04);
        border-radius: 6px;
        margin-bottom: 8px;
    }

    .tp-mock-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4px;
    }

    .tp-mock-card {
        height: 20px;
        background: rgba(0,0,0,0.04);
        border-radius: 4px;
    }

    .tp-developer .tp-mock-nav { background: rgba(255,255,255,0.12); }
    .tp-developer .tp-mock-hero { background: rgba(255,255,255,0.08); }
    .tp-developer .tp-mock-card { background: rgba(255,255,255,0.06); }

    .theme-info {
        padding: 12px 14px;
        background: var(--surface);
        border-top: 1px solid var(--border);
    }

    .theme-name {
        font-family: 'Outfit', sans-serif;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--text-main);
    }

    .theme-tag {
        font-size: 10px;
        color: var(--text-muted);
        font-weight: 600;
    }

    .theme-selected-check {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: var(--primary);
        color: #fff;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        z-index: 2;
    }

    .theme-card.selected .theme-selected-check { display: flex; }

    /* Portfolio Builder */
    .portfolio-builder {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 32px;
    }

    .pb-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 24px;
        border-bottom: 1px solid var(--border);
        background: var(--bg-canvas);
    }

    .pb-toolbar-title {
        font-family: 'Outfit', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pb-toolbar-actions { display: flex; gap: 8px; }

    .pb-sections-list {
        width: 100%;
    }

    .pb-section-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-light);
        transition: background 0.2s;
    }

    .pb-section-item:hover { background: var(--bg-canvas); }

    .pb-drag-handle {
        color: var(--text-muted);
        cursor: grab;
        font-size: 14px;
        opacity: 0.4;
    }

    .pb-section-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .pb-si-about { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
    .pb-si-projects { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .pb-si-skills { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .pb-si-experience { background: rgba(236, 72, 153, 0.1); color: #ec4899; }
    .pb-si-education { background: rgba(6, 182, 212, 0.1); color: #06b6d4; }
    .pb-si-contact { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }

    .pb-section-info { flex: 1; }

    .pb-section-name {
        font-family: 'Outfit', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
    }

    .pb-section-desc {
        font-size: 11.5px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .pb-section-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
    }

    .pb-section-status.complete { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .pb-section-status.draft { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .pb-section-status.empty { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .pb-section-actions {
        display: flex;
        gap: 6px;
    }

    .pb-edit-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: var(--surface);
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.2s;
    }

    .pb-edit-btn:hover { border-color: var(--primary); color: var(--primary); }

    /* Projects Preview */
    .projects-preview {
        margin-bottom: 32px;
    }

    .projects-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .project-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .project-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px -8px rgba(0,0,0,0.12); }

    .project-thumb {
        height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        position: relative;
    }

    .project-thumb-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 50%, rgba(0,0,0,0.5) 100%);
    }

    .project-body { padding: 18px; }

    .project-name {
        font-family: 'Outfit', sans-serif;
        font-size: 14.5px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 6px;
    }

    .project-desc {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 12px;
    }

    .project-tech-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .project-tech-tag {
        padding: 2px 8px;
        border-radius: 5px;
        font-size: 9.5px;
        font-weight: 700;
        background: rgba(99, 102, 241, 0.08);
        color: var(--primary);
    }

    @media (max-width: 1024px) {
        .analytics-row { grid-template-columns: repeat(2, 1fr); }
        .themes-row { grid-template-columns: repeat(2, 1fr); }
        .projects-grid { grid-template-columns: 1fr; }
    }
</style>

<!-- Hero with QR Code -->
<div class="portfolio-hero">
    <div class="portfolio-hero-left">
        <div class="portfolio-hero-badge"><i class="fa-solid fa-globe"></i> Digital Portfolio</div>
        <h1>Your Web Portfolio</h1>
        <p>Create a stunning personal portfolio with projects, skills, and achievements. Share it with recruiters via a public link or QR code.</p>
    </div>
    <div class="share-controls">
        <div class="qr-placeholder">
            <div class="qr-inner">
                <?php
                $qr = [1,1,1,0,1,1,1, 1,0,1,0,1,0,1, 1,1,1,0,1,1,1, 0,0,0,1,0,0,0, 1,0,1,1,1,0,1, 0,1,0,0,0,1,0, 1,1,1,0,1,1,1];
                foreach($qr as $cell): ?>
                    <div class="qr-cell <?= $cell ? 'dark' : 'light' ?>"></div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="share-url-box">
            <i class="fa-solid fa-link"></i>
            unilms.io/p/aarav-patel
            <i class="fa-solid fa-copy" title="Copy URL"></i>
        </div>
    </div>
</div>

<!-- Analytics Row -->
<div class="analytics-row">
    <div class="analytics-card">
        <div class="ac-icon views"><i class="fa-solid fa-eye"></i></div>
        <div>
            <div class="ac-value">234</div>
            <div class="ac-label">Total Views</div>
            <div class="ac-change up">↑ 23% this week</div>
        </div>
    </div>
    <div class="analytics-card">
        <div class="ac-icon clicks"><i class="fa-solid fa-mouse-pointer"></i></div>
        <div>
            <div class="ac-value">89</div>
            <div class="ac-label">Project Clicks</div>
            <div class="ac-change up">↑ 12% this week</div>
        </div>
    </div>
    <div class="analytics-card">
        <div class="ac-icon recruiter"><i class="fa-solid fa-user-tie"></i></div>
        <div>
            <div class="ac-value">14</div>
            <div class="ac-label">Recruiter Visits</div>
            <div class="ac-change up">↑ 8% this week</div>
        </div>
    </div>
    <div class="analytics-card">
        <div class="ac-icon downloads"><i class="fa-solid fa-download"></i></div>
        <div>
            <div class="ac-value">7</div>
            <div class="ac-label">Resume Downloads</div>
            <div class="ac-change down">↓ 3% this week</div>
        </div>
    </div>
</div>

<!-- Theme Selector -->
<div class="section-title">Portfolio Theme</div>
<p class="section-subtitle">Choose a visual theme for your public portfolio page</p>

<div class="themes-row">
    <div class="theme-card selected" onclick="selectTheme(this)">
        <div class="theme-selected-check"><i class="fa-solid fa-check"></i></div>
        <div class="theme-preview tp-minimal">
            <div class="tp-mock-layout"><div class="tp-mock-nav"></div><div class="tp-mock-hero"></div><div class="tp-mock-grid"><div class="tp-mock-card"></div><div class="tp-mock-card"></div></div></div>
        </div>
        <div class="theme-info"><div class="theme-name">Minimal Clean</div><div class="theme-tag">Professional & simple</div></div>
    </div>
    <div class="theme-card" onclick="selectTheme(this)">
        <div class="theme-selected-check"><i class="fa-solid fa-check"></i></div>
        <div class="theme-preview tp-developer">
            <div class="tp-mock-layout"><div class="tp-mock-nav"></div><div class="tp-mock-hero"></div><div class="tp-mock-grid"><div class="tp-mock-card"></div><div class="tp-mock-card"></div></div></div>
        </div>
        <div class="theme-info"><div class="theme-name">Developer Dark</div><div class="theme-tag">For engineers & devs</div></div>
    </div>
    <div class="theme-card" onclick="selectTheme(this)">
        <div class="theme-selected-check"><i class="fa-solid fa-check"></i></div>
        <div class="theme-preview tp-creative">
            <div class="tp-mock-layout"><div class="tp-mock-nav"></div><div class="tp-mock-hero"></div><div class="tp-mock-grid"><div class="tp-mock-card"></div><div class="tp-mock-card"></div></div></div>
        </div>
        <div class="theme-info"><div class="theme-name">Creative Blush</div><div class="theme-tag">For designers</div></div>
    </div>
    <div class="theme-card" onclick="selectTheme(this)">
        <div class="theme-selected-check"><i class="fa-solid fa-check"></i></div>
        <div class="theme-preview tp-academic">
            <div class="tp-mock-layout"><div class="tp-mock-nav"></div><div class="tp-mock-hero"></div><div class="tp-mock-grid"><div class="tp-mock-card"></div><div class="tp-mock-card"></div></div></div>
        </div>
        <div class="theme-info"><div class="theme-name">Academic Gold</div><div class="theme-tag">For researchers</div></div>
    </div>
</div>

<!-- Portfolio Builder -->
<div class="section-title">Portfolio Sections</div>
<p class="section-subtitle">Drag to reorder, click edit to modify content</p>

<div class="portfolio-builder">
    <div class="pb-toolbar">
        <div class="pb-toolbar-title"><i class="fa-solid fa-layer-group"></i> Section Manager</div>
        <div class="pb-toolbar-actions">
            <button class="btn btn-sm btn-outline"><i class="fa-solid fa-plus"></i> Add Section</button>
            <button class="btn btn-sm btn-primary"><i class="fa-solid fa-rocket"></i> Publish</button>
        </div>
    </div>
    <div class="pb-sections-list">
        <div class="pb-section-item">
            <i class="fa-solid fa-grip-vertical pb-drag-handle"></i>
            <div class="pb-section-icon pb-si-about"><i class="fa-solid fa-user"></i></div>
            <div class="pb-section-info">
                <div class="pb-section-name">About Me</div>
                <div class="pb-section-desc">Introduction, bio, profile photo</div>
            </div>
            <span class="pb-section-status complete"><i class="fa-solid fa-check-circle"></i> Complete</span>
            <div class="pb-section-actions">
                <button class="pb-edit-btn"><i class="fa-solid fa-pen"></i></button>
                <button class="pb-edit-btn"><i class="fa-solid fa-eye"></i></button>
            </div>
        </div>
        <div class="pb-section-item">
            <i class="fa-solid fa-grip-vertical pb-drag-handle"></i>
            <div class="pb-section-icon pb-si-projects"><i class="fa-solid fa-diagram-project"></i></div>
            <div class="pb-section-info">
                <div class="pb-section-name">Projects</div>
                <div class="pb-section-desc">3 projects added with descriptions</div>
            </div>
            <span class="pb-section-status complete"><i class="fa-solid fa-check-circle"></i> Complete</span>
            <div class="pb-section-actions">
                <button class="pb-edit-btn"><i class="fa-solid fa-pen"></i></button>
                <button class="pb-edit-btn"><i class="fa-solid fa-eye"></i></button>
            </div>
        </div>
        <div class="pb-section-item">
            <i class="fa-solid fa-grip-vertical pb-drag-handle"></i>
            <div class="pb-section-icon pb-si-skills"><i class="fa-solid fa-chart-bar"></i></div>
            <div class="pb-section-info">
                <div class="pb-section-name">Skills & Expertise</div>
                <div class="pb-section-desc">8 skills with proficiency levels</div>
            </div>
            <span class="pb-section-status complete"><i class="fa-solid fa-check-circle"></i> Complete</span>
            <div class="pb-section-actions">
                <button class="pb-edit-btn"><i class="fa-solid fa-pen"></i></button>
                <button class="pb-edit-btn"><i class="fa-solid fa-eye"></i></button>
            </div>
        </div>
        <div class="pb-section-item">
            <i class="fa-solid fa-grip-vertical pb-drag-handle"></i>
            <div class="pb-section-icon pb-si-experience"><i class="fa-solid fa-briefcase"></i></div>
            <div class="pb-section-info">
                <div class="pb-section-name">Experience</div>
                <div class="pb-section-desc">1 internship added</div>
            </div>
            <span class="pb-section-status draft"><i class="fa-solid fa-clock"></i> Draft</span>
            <div class="pb-section-actions">
                <button class="pb-edit-btn"><i class="fa-solid fa-pen"></i></button>
                <button class="pb-edit-btn"><i class="fa-solid fa-eye"></i></button>
            </div>
        </div>
        <div class="pb-section-item">
            <i class="fa-solid fa-grip-vertical pb-drag-handle"></i>
            <div class="pb-section-icon pb-si-education"><i class="fa-solid fa-graduation-cap"></i></div>
            <div class="pb-section-info">
                <div class="pb-section-name">Education</div>
                <div class="pb-section-desc">Add your academic background</div>
            </div>
            <span class="pb-section-status empty"><i class="fa-solid fa-exclamation-circle"></i> Empty</span>
            <div class="pb-section-actions">
                <button class="pb-edit-btn"><i class="fa-solid fa-pen"></i></button>
            </div>
        </div>
        <div class="pb-section-item">
            <i class="fa-solid fa-grip-vertical pb-drag-handle"></i>
            <div class="pb-section-icon pb-si-contact"><i class="fa-solid fa-envelope"></i></div>
            <div class="pb-section-info">
                <div class="pb-section-name">Contact</div>
                <div class="pb-section-desc">Email, LinkedIn, GitHub links</div>
            </div>
            <span class="pb-section-status complete"><i class="fa-solid fa-check-circle"></i> Complete</span>
            <div class="pb-section-actions">
                <button class="pb-edit-btn"><i class="fa-solid fa-pen"></i></button>
                <button class="pb-edit-btn"><i class="fa-solid fa-eye"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- Projects Preview -->
<div class="projects-preview">
    <div class="section-title">Your Projects</div>
    <p class="section-subtitle">These projects are showcased on your public portfolio</p>

    <div class="projects-grid">
        <div class="project-card">
            <div class="project-thumb" style="background: linear-gradient(135deg, #1e1b4b, #4338ca);">
                <div class="project-thumb-overlay"></div>
                <i class="fa-solid fa-graduation-cap" style="color: rgba(255,255,255,0.3);"></i>
            </div>
            <div class="project-body">
                <div class="project-name">UniLMS — Learning Platform</div>
                <div class="project-desc">Full-stack LMS with RBAC, video streaming, timetable engine, and real-time quiz system.</div>
                <div class="project-tech-tags">
                    <span class="project-tech-tag">CodeIgniter 4</span>
                    <span class="project-tech-tag">MySQL</span>
                    <span class="project-tech-tag">JavaScript</span>
                </div>
            </div>
        </div>
        <div class="project-card">
            <div class="project-thumb" style="background: linear-gradient(135deg, #064e3b, #10b981);">
                <div class="project-thumb-overlay"></div>
                <i class="fa-solid fa-cart-shopping" style="color: rgba(255,255,255,0.3);"></i>
            </div>
            <div class="project-body">
                <div class="project-name">E-Commerce API</div>
                <div class="project-desc">RESTful API with JWT auth, payment gateway integration, and order management.</div>
                <div class="project-tech-tags">
                    <span class="project-tech-tag">Node.js</span>
                    <span class="project-tech-tag">Express</span>
                    <span class="project-tech-tag">MongoDB</span>
                </div>
            </div>
        </div>
        <div class="project-card">
            <div class="project-thumb" style="background: linear-gradient(135deg, #831843, #ec4899);">
                <div class="project-thumb-overlay"></div>
                <i class="fa-solid fa-robot" style="color: rgba(255,255,255,0.3);"></i>
            </div>
            <div class="project-body">
                <div class="project-name">AI Chatbot</div>
                <div class="project-desc">Conversational AI bot with NLP, sentiment analysis, and multi-language support.</div>
                <div class="project-tech-tags">
                    <span class="project-tech-tag">Python</span>
                    <span class="project-tech-tag">TensorFlow</span>
                    <span class="project-tech-tag">Flask</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectTheme(card) {
    document.querySelectorAll('.theme-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
}
</script>

<?= $this->endSection() ?>
