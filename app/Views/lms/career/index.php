<?= $this->extend('lms/career/layout') ?>
<?= $this->section('page_title') ?>Career Suite<?= $this->endSection() ?>
<?= $this->section('content') ?>

<style>
    /* ===================== CAREER SUITE HUB ===================== */
    .career-hero {
        background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
        border-radius: 24px;
        padding: 40px 44px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        margin-bottom: 36px;
        box-shadow: 0 20px 50px -12px rgba(48, 43, 99, 0.5);
    }

    .career-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(139, 92, 246, 0.25) 0%, transparent 70%);
        border-radius: 50%;
        animation: heroFloat 8s ease-in-out infinite;
    }

    .career-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: 10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(236, 72, 153, 0.2) 0%, transparent 70%);
        border-radius: 50%;
        animation: heroFloat 6s ease-in-out infinite reverse;
    }

    @keyframes heroFloat {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(20px, -20px) scale(1.1); }
    }

    .career-hero-inner {
        position: relative;
        z-index: 2;
    }

    .career-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 6px 16px;
        border-radius: 24px;
        font-size: 11px;
        font-weight: 700;
        color: #a78bfa;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 16px;
    }

    .career-hero-badge i { font-size: 10px; }

    .career-hero h1 {
        font-family: 'Outfit', sans-serif !important;
        font-size: 32px !important;
        font-weight: 800 !important;
        color: #ffffff !important;
        margin-bottom: 10px !important;
        line-height: 1.2 !important;
    }

    .career-hero h1 .gradient-text {
        background: linear-gradient(135deg, #a78bfa, #f472b6, #fb923c);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .career-hero-desc {
        font-size: 14.5px;
        color: rgba(255, 255, 255, 0.7);
        max-width: 600px;
        line-height: 1.7;
    }

    /* Quick Stats Bar */
    .career-stats-bar {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 36px;
    }

    .career-stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 22px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .career-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        border-radius: 16px 16px 0 0;
    }

    .career-stat-card:nth-child(1)::before { background: linear-gradient(90deg, #6366f1, #8b5cf6); }
    .career-stat-card:nth-child(2)::before { background: linear-gradient(90deg, #10b981, #34d399); }
    .career-stat-card:nth-child(3)::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .career-stat-card:nth-child(4)::before { background: linear-gradient(90deg, #ef4444, #f87171); }

    .career-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px -8px rgba(0, 0, 0, 0.12);
    }

    .stat-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .stat-icon-box.purple { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
    .stat-icon-box.green { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .stat-icon-box.amber { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .stat-icon-box.red { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .stat-value {
        font-family: 'Outfit', sans-serif;
        font-size: 26px;
        font-weight: 800;
        color: var(--text-main);
        line-height: 1;
    }

    .stat-label {
        font-size: 12px;
        color: var(--text-muted);
        font-weight: 600;
        margin-top: 2px;
    }

    /* Module Cards Grid */
    .career-section-title {
        font-family: 'Outfit', sans-serif;
        font-size: 18px;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 6px;
    }

    .career-section-desc {
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 24px;
    }

    .career-modules-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-bottom: 40px;
    }

    .career-module-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 28px 24px;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        cursor: pointer;
        text-decoration: none;
        display: block;
        color: inherit;
    }

    .career-module-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .career-module-card:nth-child(1)::before { background: linear-gradient(90deg, #6366f1, #a78bfa); }
    .career-module-card:nth-child(2)::before { background: linear-gradient(90deg, #ec4899, #f472b6); }
    .career-module-card:nth-child(3)::before { background: linear-gradient(90deg, #10b981, #34d399); }
    .career-module-card:nth-child(4)::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .career-module-card:nth-child(5)::before { background: linear-gradient(90deg, #06b6d4, #22d3ee); }
    .career-module-card:nth-child(6)::before { background: linear-gradient(90deg, #8b5cf6, #c084fc); }

    .career-module-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        border-color: var(--primary-light);
    }

    .module-icon-circle {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 18px;
    }

    .module-icon-circle.m1 { background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(167, 139, 250, 0.15)); color: #6366f1; }
    .module-icon-circle.m2 { background: linear-gradient(135deg, rgba(236, 72, 153, 0.15), rgba(244, 114, 182, 0.15)); color: #ec4899; }
    .module-icon-circle.m3 { background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(52, 211, 153, 0.15)); color: #10b981; }
    .module-icon-circle.m4 { background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(251, 191, 36, 0.15)); color: #f59e0b; }
    .module-icon-circle.m5 { background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(34, 211, 238, 0.15)); color: #06b6d4; }
    .module-icon-circle.m6 { background: linear-gradient(135deg, rgba(139, 92, 246, 0.15), rgba(192, 132, 252, 0.15)); color: #8b5cf6; }

    .module-title {
        font-family: 'Outfit', sans-serif;
        font-size: 16px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 8px;
    }

    .module-desc {
        font-size: 12.5px;
        color: var(--text-muted);
        line-height: 1.65;
        margin-bottom: 20px;
    }

    .module-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 18px;
    }

    .module-tag {
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        background: var(--bg-canvas);
        border: 1px solid var(--border);
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .module-cta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--primary);
        transition: gap 0.2s ease;
    }

    .career-module-card:hover .module-cta {
        gap: 12px;
    }

    .module-cta i {
        font-size: 11px;
        transition: transform 0.2s ease;
    }

    .career-module-card:hover .module-cta i {
        transform: translateX(4px);
    }

    /* Getting Started Section */
    .getting-started-box {
        background: linear-gradient(135deg, #fdf4ff, #fce7f3, #ede9fe);
        border: 1px solid rgba(139, 92, 246, 0.15);
        border-radius: 20px;
        padding: 32px;
        display: flex;
        align-items: center;
        gap: 32px;
    }

    html.dark-mode .getting-started-box {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.08), rgba(236, 72, 153, 0.06), rgba(99, 102, 241, 0.08));
        border-color: rgba(139, 92, 246, 0.2);
    }

    .gs-steps {
        display: flex;
        gap: 24px;
        flex: 1;
    }

    .gs-step {
        flex: 1;
        display: flex;
        gap: 14px;
        align-items: flex-start;
    }

    .gs-step-num {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
        flex-shrink: 0;
        box-shadow: 0 4px 12px var(--primary-glow);
    }

    .gs-step-title {
        font-family: 'Outfit', sans-serif;
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 3px;
    }

    .gs-step-desc {
        font-size: 11.5px;
        color: var(--text-muted);
        line-height: 1.55;
    }

    @media (max-width: 1024px) {
        .career-stats-bar { grid-template-columns: repeat(2, 1fr); }
        .career-modules-grid { grid-template-columns: 1fr; }
        .gs-steps { flex-direction: column; }
    }
</style>

<!-- Career Suite Hero Banner -->
<div class="career-hero">
    <div class="career-hero-inner">
        <div class="career-hero-badge">
            <i class="fa-solid fa-sparkles"></i>
            <span>Career & Employability Suite</span>
        </div>
        <h1>Your Launchpad to <span class="gradient-text">Dream Career</span></h1>
        <p class="career-hero-desc">
            From crafting ATS-optimized resumes to acing mock interviews and landing placement offers — 
            everything you need to be industry-ready, all in one platform.
        </p>
    </div>
</div>

<!-- Quick Stats Bar -->
<div class="career-stats-bar">
    <div class="career-stat-card">
        <div class="stat-icon-box purple"><i class="fa-solid fa-file-circle-check"></i></div>
        <div>
            <div class="stat-value">87<small style="font-size: 14px; color: var(--text-muted);">/100</small></div>
            <div class="stat-label">Resume ATS Score</div>
        </div>
    </div>
    <div class="career-stat-card">
        <div class="stat-icon-box green"><i class="fa-solid fa-microphone-lines"></i></div>
        <div>
            <div class="stat-value">12</div>
            <div class="stat-label">Mock Interviews Done</div>
        </div>
    </div>
    <div class="career-stat-card">
        <div class="stat-icon-box amber"><i class="fa-solid fa-building"></i></div>
        <div>
            <div class="stat-value">5</div>
            <div class="stat-label">Drives Applied</div>
        </div>
    </div>
    <div class="career-stat-card">
        <div class="stat-icon-box red"><i class="fa-solid fa-eye"></i></div>
        <div>
            <div class="stat-value">234</div>
            <div class="stat-label">Portfolio Views</div>
        </div>
    </div>
</div>

<!-- Modules Grid -->
<div class="career-section-title">Explore Your Career Toolkit</div>
<p class="career-section-desc">Choose a module to get started on your career journey. Each tool is designed to give you an edge.</p>

<div class="career-modules-grid">
    <!-- Module 1 -->
    <a href="<?= base_url('lms/career/resume') ?>" class="career-module-card">
        <div class="module-icon-circle m1"><i class="fa-solid fa-file-lines"></i></div>
        <div class="module-title">AI-Powered ATS Resume Builder</div>
        <div class="module-desc">Build a recruiter-ready resume with AI suggestions, ATS score optimization, and professional templates.</div>
        <div class="module-tags">
            <span class="module-tag">AI Powered</span>
            <span class="module-tag">ATS Score</span>
            <span class="module-tag">PDF Export</span>
        </div>
        <div class="module-cta">Launch Builder <i class="fa-solid fa-arrow-right"></i></div>
    </a>

    <!-- Module 2 -->
    <a href="<?= base_url('lms/career/mock-interview') ?>" class="career-module-card">
        <div class="module-icon-circle m2"><i class="fa-solid fa-microphone-lines"></i></div>
        <div class="module-title">AI Mock Interview Simulator</div>
        <div class="module-desc">Practice technical & HR interviews with AI-powered feedback, scoring, and company-specific question banks.</div>
        <div class="module-tags">
            <span class="module-tag">Technical</span>
            <span class="module-tag">HR Round</span>
            <span class="module-tag">AI Feedback</span>
        </div>
        <div class="module-cta">Start Practice <i class="fa-solid fa-arrow-right"></i></div>
    </a>

    <!-- Module 3 -->
    <a href="<?= base_url('lms/career/portfolio') ?>" class="career-module-card">
        <div class="module-icon-circle m5"><i class="fa-solid fa-globe"></i></div>
        <div class="module-title">Digital Web Portfolio</div>
        <div class="module-desc">Create a stunning personal portfolio website with projects, skills, and achievements — shareable via link & QR.</div>
        <div class="module-tags">
            <span class="module-tag">Public Link</span>
            <span class="module-tag">QR Code</span>
            <span class="module-tag">Analytics</span>
        </div>
        <div class="module-cta">Build Portfolio <i class="fa-solid fa-arrow-right"></i></div>
    </a>
</div>

<!-- Getting Started -->
<div class="career-section-title">🚀 Getting Started</div>
<p class="career-section-desc">Follow these 3 steps to maximize your placement readiness.</p>

<div class="getting-started-box">
    <div class="gs-steps">
        <div class="gs-step">
            <div class="gs-step-num">1</div>
            <div>
                <div class="gs-step-title">Build Your Resume</div>
                <div class="gs-step-desc">Start with the AI Resume Builder to create an ATS-optimized resume that gets noticed.</div>
            </div>
        </div>
        <div class="gs-step">
            <div class="gs-step-num">2</div>
            <div>
                <div class="gs-step-title">Practice Interviews</div>
                <div class="gs-step-desc">Run mock interview sessions and use AI feedback to improve your responses.</div>
            </div>
        </div>
        <div class="gs-step">
            <div class="gs-step-num">3</div>
            <div>
                <div class="gs-step-title">Apply & Get Placed</div>
                <div class="gs-step-desc">Apply to placement drives with 1-click and track your shortlisting status live.</div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
