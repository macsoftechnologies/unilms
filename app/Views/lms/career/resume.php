<?= $this->extend('lms/career/layout') ?>
<?= $this->section('page_title') ?>AI Resume Builder<?= $this->endSection() ?>
<?= $this->section('content') ?>

<style>
    .resume-hero {
        background: linear-gradient(135deg, #1e1b4b 0%, #3730a3 50%, #6366f1 100%);
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

    .resume-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px);
        background-size: 18px 18px;
    }

    .resume-hero-left { position: relative; z-index: 2; }

    .resume-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.2);
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        color: #a5b4fc;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 14px;
    }

    .resume-hero h1 {
        font-family: 'Outfit', sans-serif !important;
        font-size: 26px !important;
        font-weight: 800 !important;
        color: #fff !important;
        margin-bottom: 8px !important;
    }

    .resume-hero p { font-size: 13.5px; color: rgba(255,255,255,0.7); max-width: 500px; }

    /* ATS Score Ring */
    .ats-ring-box {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .ats-ring {
        width: 130px;
        height: 130px;
        position: relative;
    }

    .ats-ring svg {
        width: 130px;
        height: 130px;
        transform: rotate(-90deg);
    }

    .ats-ring-bg { fill: none; stroke: rgba(255,255,255,0.12); stroke-width: 10; }

    .ats-ring-progress {
        fill: none;
        stroke: url(#atsGradient);
        stroke-width: 10;
        stroke-linecap: round;
        stroke-dasharray: 345;
        stroke-dashoffset: 45;
        transition: stroke-dashoffset 1.5s ease;
    }

    .ats-ring-label {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .ats-ring-value {
        font-family: 'Outfit', sans-serif;
        font-size: 36px;
        font-weight: 800;
        color: #fff;
        line-height: 1;
    }

    .ats-ring-text {
        font-size: 10px;
        font-weight: 700;
        color: #a5b4fc;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 2px;
    }

    /* Template Gallery */
    .section-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .section-title {
        font-family: 'Outfit', sans-serif;
        font-size: 18px;
        font-weight: 800;
        color: var(--text-main);
    }

    .section-subtitle {
        font-size: 12.5px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .templates-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 36px;
    }

    .template-card {
        background: var(--surface);
        border: 2px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .template-card.selected { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-glow); }

    .template-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px -8px rgba(0,0,0,0.12); }

    .template-preview {
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    .template-mini-resume {
        width: 100%;
        height: 100%;
        border-radius: 8px;
        padding: 12px;
        font-size: 6px;
        line-height: 1.4;
        position: relative;
    }

    .tmr-clean { background: #f8fafc; border: 1px solid #e2e8f0; }
    .tmr-modern { background: linear-gradient(135deg, #1e293b, #334155); color: #e2e8f0; }
    .tmr-executive { background: #fffbeb; border: 1px solid #fde68a; }
    .tmr-creative { background: linear-gradient(135deg, #fdf4ff, #fce7f3); }

    .tmr-header {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 6px;
        padding-bottom: 4px;
        border-bottom: 1px solid rgba(0,0,0,0.08);
    }

    .tmr-avatar {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    }

    .tmr-name { font-weight: 800; font-size: 8px; }
    .tmr-line { height: 3px; background: rgba(0,0,0,0.06); border-radius: 2px; margin: 3px 0; }
    .tmr-line.w60 { width: 60%; }
    .tmr-line.w80 { width: 80%; }
    .tmr-line.w40 { width: 40%; }
    .tmr-line.w70 { width: 70%; }

    .tmr-modern .tmr-header { border-bottom-color: rgba(255,255,255,0.15); }
    .tmr-modern .tmr-line { background: rgba(255,255,255,0.12); }

    .template-info {
        padding: 14px 16px;
        border-top: 1px solid var(--border);
    }

    .template-name {
        font-family: 'Outfit', sans-serif;
        font-size: 13px;
        font-weight: 700;
        color: var(--text-main);
    }

    .template-tag {
        font-size: 10px;
        font-weight: 600;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .selected-check {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--primary);
        color: #fff;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 11px;
    }

    .template-card.selected .selected-check { display: flex; }

    /* Resume Builder Split Pane */
    .resume-builder-pane {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 28px;
        margin-bottom: 36px;
    }

    .builder-form-side {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 28px;
    }

    .form-section {
        margin-bottom: 24px;
    }

    .form-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
    }

    .form-section-title {
        font-family: 'Outfit', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-section-title i { color: var(--primary); font-size: 14px; }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 12px;
    }

    .form-group { margin-bottom: 12px; }

    .form-group label {
        display: block;
        font-size: 11.5px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 6px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--bg-canvas);
        color: var(--text-main);
        font-size: 13px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: border-color 0.2s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-glow);
    }

    .skill-tags-box {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .skill-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 8px;
        background: rgba(99, 102, 241, 0.08);
        border: 1px solid rgba(99, 102, 241, 0.2);
        color: var(--primary);
        font-size: 11.5px;
        font-weight: 700;
    }

    .skill-tag .remove {
        cursor: pointer;
        font-size: 10px;
        opacity: 0.6;
        transition: opacity 0.2s;
    }

    .skill-tag .remove:hover { opacity: 1; }

    /* Preview Side */
    .preview-side {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 28px;
        position: sticky;
        top: 100px;
    }

    .preview-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .preview-title {
        font-family: 'Outfit', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .preview-actions { display: flex; gap: 8px; }

    .resume-preview-frame {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 32px 28px;
        min-height: 500px;
        box-shadow: 0 4px 20px -4px rgba(0,0,0,0.08);
        color: #0f172a;
    }

    .rp-header {
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #6366f1;
    }

    .rp-name {
        font-family: 'Outfit', sans-serif;
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .rp-subtitle {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .rp-contact {
        display: flex;
        justify-content: center;
        gap: 16px;
        margin-top: 8px;
        font-size: 10.5px;
        color: #64748b;
    }

    .rp-contact span { display: flex; align-items: center; gap: 4px; }
    .rp-contact i { color: #6366f1; font-size: 10px; }

    .rp-section { margin-bottom: 16px; }

    .rp-section-title {
        font-family: 'Outfit', sans-serif;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #6366f1;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 4px;
        margin-bottom: 10px;
    }

    .rp-item { margin-bottom: 10px; }

    .rp-item-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
    }

    .rp-item-title {
        font-size: 12.5px;
        font-weight: 700;
        color: #0f172a;
    }

    .rp-item-date {
        font-size: 10.5px;
        color: #94a3b8;
        font-weight: 600;
    }

    .rp-item-sub {
        font-size: 11px;
        color: #64748b;
        font-style: italic;
    }

    .rp-item-desc {
        font-size: 11px;
        color: #475569;
        line-height: 1.6;
        margin-top: 4px;
    }

    .rp-skills-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .rp-skill-pill {
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        background: #eef2ff;
        color: #4338ca;
        border: 1px solid #c7d2fe;
    }

    /* AI Suggestions Panel */
    .ai-suggestions-bar {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.06), rgba(236, 72, 153, 0.04));
        border: 1px solid rgba(99, 102, 241, 0.15);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 32px;
    }

    .ai-suggestion-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
    }

    .ai-sparkle-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: linear-gradient(135deg, #6366f1, #a78bfa);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .ai-suggestion-title {
        font-family: 'Outfit', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
    }

    .ai-suggestion-items {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .ai-suggestion-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 11.5px;
        color: var(--text-muted);
        line-height: 1.5;
    }

    .ai-suggestion-item i {
        color: #10b981;
        font-size: 12px;
        margin-top: 2px;
        flex-shrink: 0;
    }

    @media (max-width: 1024px) {
        .resume-builder-pane { grid-template-columns: 1fr; }
        .templates-row { grid-template-columns: repeat(2, 1fr); }
        .ai-suggestion-items { grid-template-columns: 1fr; }
    }
</style>

<!-- Hero with ATS Score -->
<div class="resume-hero">
    <div class="resume-hero-left">
        <div class="resume-hero-badge"><i class="fa-solid fa-sparkles"></i> AI-Powered</div>
        <h1>ATS Resume Builder</h1>
        <p>Craft an interview-winning resume with AI suggestions, ATS optimization, and professional templates.</p>
    </div>
    <div class="ats-ring-box">
        <div class="ats-ring">
            <svg viewBox="0 0 120 120">
                <defs>
                    <linearGradient id="atsGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#a78bfa"/>
                        <stop offset="100%" style="stop-color:#10b981"/>
                    </linearGradient>
                </defs>
                <circle cx="60" cy="60" r="55" class="ats-ring-bg"/>
                <circle cx="60" cy="60" r="55" class="ats-ring-progress"/>
            </svg>
            <div class="ats-ring-label">
                <div class="ats-ring-value">87</div>
                <div class="ats-ring-text">ATS Score</div>
            </div>
        </div>
    </div>
</div>

<!-- AI Suggestions Bar -->
<div class="ai-suggestions-bar">
    <div class="ai-suggestion-header">
        <div class="ai-sparkle-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
        <div>
            <div class="ai-suggestion-title">AI Improvement Suggestions</div>
            <div style="font-size: 11.5px; color: var(--text-muted);">Based on analysis of 50,000+ successful resumes</div>
        </div>
    </div>
    <div class="ai-suggestion-items">
        <div class="ai-suggestion-item">
            <i class="fa-solid fa-lightbulb"></i>
            <span>Add 2-3 more <strong>quantifiable achievements</strong> in your experience section to boost ATS score by ~8 points.</span>
        </div>
        <div class="ai-suggestion-item">
            <i class="fa-solid fa-lightbulb"></i>
            <span>Include keywords like <strong>"REST API", "CI/CD", "Agile"</strong> — they appear in 78% of matching job descriptions.</span>
        </div>
        <div class="ai-suggestion-item">
            <i class="fa-solid fa-lightbulb"></i>
            <span>Your <strong>skills section</strong> is strong. Consider adding a "Certifications" section for extra credibility.</span>
        </div>
    </div>
</div>

<!-- Template Gallery -->
<div class="section-bar">
    <div>
        <div class="section-title">Choose Template</div>
        <div class="section-subtitle">Select a professional template for your resume</div>
    </div>
</div>

<div class="templates-row">
    <div class="template-card selected" onclick="selectTemplate(this)">
        <div class="selected-check"><i class="fa-solid fa-check"></i></div>
        <div class="template-preview">
            <div class="template-mini-resume tmr-clean">
                <div class="tmr-header"><div class="tmr-avatar"></div><div class="tmr-name">Aarav Patel</div></div>
                <div class="tmr-line w80"></div><div class="tmr-line w60"></div><div class="tmr-line w70"></div>
                <div class="tmr-line w40"></div><div class="tmr-line w80"></div>
            </div>
        </div>
        <div class="template-info">
            <div class="template-name">Clean Professional</div>
            <div class="template-tag">Best for freshers</div>
        </div>
    </div>
    <div class="template-card" onclick="selectTemplate(this)">
        <div class="selected-check"><i class="fa-solid fa-check"></i></div>
        <div class="template-preview">
            <div class="template-mini-resume tmr-modern">
                <div class="tmr-header"><div class="tmr-avatar"></div><div class="tmr-name" style="color: #e2e8f0;">Aarav Patel</div></div>
                <div class="tmr-line w80"></div><div class="tmr-line w60"></div><div class="tmr-line w70"></div>
                <div class="tmr-line w40"></div><div class="tmr-line w80"></div>
            </div>
        </div>
        <div class="template-info">
            <div class="template-name">Modern Dark</div>
            <div class="template-tag">Standout design</div>
        </div>
    </div>
    <div class="template-card" onclick="selectTemplate(this)">
        <div class="selected-check"><i class="fa-solid fa-check"></i></div>
        <div class="template-preview">
            <div class="template-mini-resume tmr-executive">
                <div class="tmr-header"><div class="tmr-avatar" style="background: linear-gradient(135deg, #f59e0b, #d97706);"></div><div class="tmr-name">Aarav Patel</div></div>
                <div class="tmr-line w80"></div><div class="tmr-line w60"></div><div class="tmr-line w70"></div>
                <div class="tmr-line w40"></div><div class="tmr-line w80"></div>
            </div>
        </div>
        <div class="template-info">
            <div class="template-name">Executive Gold</div>
            <div class="template-tag">Premium classic</div>
        </div>
    </div>
    <div class="template-card" onclick="selectTemplate(this)">
        <div class="selected-check"><i class="fa-solid fa-check"></i></div>
        <div class="template-preview">
            <div class="template-mini-resume tmr-creative">
                <div class="tmr-header"><div class="tmr-avatar" style="background: linear-gradient(135deg, #ec4899, #8b5cf6);"></div><div class="tmr-name">Aarav Patel</div></div>
                <div class="tmr-line w80"></div><div class="tmr-line w60"></div><div class="tmr-line w70"></div>
                <div class="tmr-line w40"></div><div class="tmr-line w80"></div>
            </div>
        </div>
        <div class="template-info">
            <div class="template-name">Creative Blush</div>
            <div class="template-tag">For designers</div>
        </div>
    </div>
</div>

<!-- Resume Builder Split Pane -->
<div class="resume-builder-pane">
    <!-- Form Side -->
    <div class="builder-form-side">
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-title"><i class="fa-solid fa-user"></i> Personal Details</div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" value="Aarav Patel" placeholder="Your full name">
                </div>
                <div class="form-group">
                    <label>Professional Title</label>
                    <input type="text" value="Full Stack Developer" placeholder="e.g., Software Engineer">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" value="aarav.patel@email.com">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" value="+91 98765 43210">
                </div>
            </div>
            <div class="form-group">
                <label>LinkedIn / GitHub</label>
                <input type="url" value="linkedin.com/in/aaravpatel" placeholder="Your profile URL">
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-title"><i class="fa-solid fa-graduation-cap"></i> Education</div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Degree</label>
                    <input type="text" value="B.Tech Computer Science">
                </div>
                <div class="form-group">
                    <label>Institution</label>
                    <input type="text" value="Apex Institute of Technology">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>CGPA / Percentage</label>
                    <input type="text" value="8.7 / 10">
                </div>
                <div class="form-group">
                    <label>Duration</label>
                    <input type="text" value="2022 – 2026">
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-title"><i class="fa-solid fa-briefcase"></i> Experience</div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Role</label>
                    <input type="text" value="Software Engineering Intern">
                </div>
                <div class="form-group">
                    <label>Company</label>
                    <input type="text" value="TechCorp Solutions Pvt. Ltd.">
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea rows="3" placeholder="Describe your responsibilities...">Built RESTful APIs using Node.js and Express, reducing response time by 40%. Implemented CI/CD pipelines with Jenkins and Docker for automated deployments.</textarea>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-title"><i class="fa-solid fa-code"></i> Skills</div>
            </div>
            <div class="skill-tags-box">
                <span class="skill-tag">JavaScript <span class="remove">&times;</span></span>
                <span class="skill-tag">React.js <span class="remove">&times;</span></span>
                <span class="skill-tag">Node.js <span class="remove">&times;</span></span>
                <span class="skill-tag">Python <span class="remove">&times;</span></span>
                <span class="skill-tag">MySQL <span class="remove">&times;</span></span>
                <span class="skill-tag">Docker <span class="remove">&times;</span></span>
                <span class="skill-tag">Git <span class="remove">&times;</span></span>
                <span class="skill-tag">AWS <span class="remove">&times;</span></span>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 8px;">
            <button class="btn btn-primary"><i class="fa-solid fa-wand-magic-sparkles"></i> AI Optimize</button>
            <button class="btn btn-outline"><i class="fa-solid fa-plus"></i> Add Section</button>
        </div>
    </div>

    <!-- Preview Side -->
    <div class="preview-side">
        <div class="preview-header">
            <div class="preview-title"><i class="fa-solid fa-eye"></i> Live Preview</div>
            <div class="preview-actions">
                <button class="btn btn-sm btn-outline"><i class="fa-solid fa-download"></i> PDF</button>
                <button class="btn btn-sm btn-primary"><i class="fa-solid fa-share-nodes"></i> Share</button>
            </div>
        </div>
        <div class="resume-preview-frame">
            <div class="rp-header">
                <div class="rp-name">Aarav Patel</div>
                <div class="rp-subtitle">Full Stack Developer</div>
                <div class="rp-contact">
                    <span><i class="fa-solid fa-envelope"></i> aarav.patel@email.com</span>
                    <span><i class="fa-solid fa-phone"></i> +91 98765 43210</span>
                    <span><i class="fa-brands fa-linkedin"></i> linkedin.com/in/aaravpatel</span>
                </div>
            </div>

            <div class="rp-section">
                <div class="rp-section-title">Education</div>
                <div class="rp-item">
                    <div class="rp-item-header">
                        <div class="rp-item-title">B.Tech Computer Science & Engineering</div>
                        <div class="rp-item-date">2022 – 2026</div>
                    </div>
                    <div class="rp-item-sub">Apex Institute of Technology • CGPA: 8.7/10</div>
                </div>
            </div>

            <div class="rp-section">
                <div class="rp-section-title">Experience</div>
                <div class="rp-item">
                    <div class="rp-item-header">
                        <div class="rp-item-title">Software Engineering Intern</div>
                        <div class="rp-item-date">Jun 2025 – Aug 2025</div>
                    </div>
                    <div class="rp-item-sub">TechCorp Solutions Pvt. Ltd.</div>
                    <div class="rp-item-desc">
                        • Built RESTful APIs using Node.js and Express, reducing response time by 40%<br>
                        • Implemented CI/CD pipelines with Jenkins and Docker for automated deployments<br>
                        • Collaborated with a team of 8 to deliver 3 microservices ahead of schedule
                    </div>
                </div>
            </div>

            <div class="rp-section">
                <div class="rp-section-title">Projects</div>
                <div class="rp-item">
                    <div class="rp-item-header">
                        <div class="rp-item-title">UniLMS — Learning Management System</div>
                        <div class="rp-item-date">2024</div>
                    </div>
                    <div class="rp-item-desc">
                        • Built a full-stack LMS platform with CodeIgniter 4, MySQL, and vanilla JS<br>
                        • Implemented RBAC, video streaming, and real-time quiz engine
                    </div>
                </div>
            </div>

            <div class="rp-section">
                <div class="rp-section-title">Skills</div>
                <div class="rp-skills-list">
                    <span class="rp-skill-pill">JavaScript</span>
                    <span class="rp-skill-pill">React.js</span>
                    <span class="rp-skill-pill">Node.js</span>
                    <span class="rp-skill-pill">Python</span>
                    <span class="rp-skill-pill">MySQL</span>
                    <span class="rp-skill-pill">Docker</span>
                    <span class="rp-skill-pill">Git</span>
                    <span class="rp-skill-pill">AWS</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectTemplate(card) {
    document.querySelectorAll('.template-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
}
</script>

<?= $this->endSection() ?>
