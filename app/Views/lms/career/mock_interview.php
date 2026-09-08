<?= $this->extend('lms/career/layout') ?>
<?= $this->section('page_title') ?>Mock Interviews<?= $this->endSection() ?>
<?= $this->section('content') ?>

<style>
    .interview-hero {
        background: linear-gradient(135deg, #831843 0%, #be185d 50%, #ec4899 100%);
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

    .interview-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px);
        background-size: 18px 18px;
    }

    .interview-hero-left { position: relative; z-index: 2; }

    .interview-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.2);
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        color: #fbcfe8;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 14px;
    }

    .interview-hero h1 {
        font-family: 'Outfit', sans-serif !important;
        font-size: 26px !important;
        font-weight: 800 !important;
        color: #fff !important;
        margin-bottom: 8px !important;
    }

    .interview-hero p { font-size: 13.5px; color: rgba(255,255,255,0.7); max-width: 500px; }

    .hero-stats-row {
        position: relative;
        z-index: 2;
        display: flex;
        gap: 20px;
    }

    .hero-mini-stat {
        text-align: center;
        padding: 14px 20px;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 16px;
        backdrop-filter: blur(10px);
    }

    .hero-mini-stat-val {
        font-family: 'Outfit', sans-serif;
        font-size: 28px;
        font-weight: 800;
        color: #fff;
        line-height: 1;
    }

    .hero-mini-stat-label {
        font-size: 10px;
        font-weight: 600;
        color: #fbcfe8;
        margin-top: 4px;
    }

    /* Interview Type Selector */
    .type-selector-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 32px;
    }

    .type-card {
        background: var(--surface);
        border: 2px solid var(--border);
        border-radius: 16px;
        padding: 24px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .type-card.selected {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-glow);
        background: rgba(99, 102, 241, 0.03);
    }

    .type-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px -8px rgba(0,0,0,0.1); }

    .type-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin: 0 auto 14px;
    }

    .type-icon.tech { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
    .type-icon.hr { background: rgba(236, 72, 153, 0.1); color: #ec4899; }
    .type-icon.behav { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .type-icon.case { background: rgba(16, 185, 129, 0.1); color: #10b981; }

    .type-name {
        font-family: 'Outfit', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 4px;
    }

    .type-desc {
        font-size: 11.5px;
        color: var(--text-muted);
        line-height: 1.5;
    }

    .type-check {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: var(--primary);
        color: #fff;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 10px;
    }

    .type-card.selected .type-check { display: flex; }

    /* Company Selector */
    .section-header {
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

    .company-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 12px;
        margin-bottom: 32px;
    }

    .company-chip {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 16px 12px;
        background: var(--surface);
        border: 2px solid var(--border);
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .company-chip:hover { border-color: var(--primary-light); transform: translateY(-2px); }
    .company-chip.active { border-color: var(--primary); background: rgba(99, 102, 241, 0.04); }

    .company-logo-circle {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 800;
        color: #fff;
    }

    .company-chip-name {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-main);
        text-align: center;
    }

    /* Interview Simulation Area */
    .interview-sim-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        margin-bottom: 36px;
    }

    .sim-main-area {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 28px;
    }

    .sim-question-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .sim-q-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 8px;
        background: rgba(99, 102, 241, 0.08);
        color: var(--primary);
        font-size: 11px;
        font-weight: 700;
    }

    .sim-timer {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 10px;
        background: rgba(239, 68, 68, 0.08);
        color: #ef4444;
        font-size: 13px;
        font-weight: 700;
        font-family: 'Outfit', monospace;
    }

    .sim-question-text {
        font-family: 'Outfit', sans-serif;
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
        line-height: 1.55;
        margin-bottom: 24px;
        padding: 20px;
        background: var(--bg-canvas);
        border: 1px solid var(--border);
        border-radius: 14px;
    }

    .sim-answer-area {
        margin-bottom: 20px;
    }

    .sim-answer-area textarea {
        width: 100%;
        padding: 16px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--bg-canvas);
        color: var(--text-main);
        font-size: 13.5px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        line-height: 1.7;
        resize: vertical;
        min-height: 140px;
    }

    .sim-answer-area textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-glow);
    }

    .answer-options-bar {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
    }

    .answer-opt-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--surface);
        color: var(--text-muted);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .answer-opt-btn:hover { border-color: var(--primary); color: var(--primary); }
    .answer-opt-btn.active { border-color: var(--primary); color: var(--primary); background: rgba(99, 102, 241, 0.06); }

    .sim-nav-buttons { display: flex; justify-content: space-between; }

    /* Feedback Sidebar */
    .feedback-sidebar {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 24px;
    }

    .feedback-title {
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .feedback-title i { color: #f59e0b; }

    .feedback-score-ring {
        width: 100px;
        height: 100px;
        margin: 0 auto 20px;
        position: relative;
    }

    .feedback-score-ring svg {
        width: 100px;
        height: 100px;
        transform: rotate(-90deg);
    }

    .fsr-bg { fill: none; stroke: var(--border); stroke-width: 8; }
    .fsr-fill { fill: none; stroke: #10b981; stroke-width: 8; stroke-linecap: round; stroke-dasharray: 251; stroke-dashoffset: 63; }

    .fsr-label {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .fsr-value {
        font-family: 'Outfit', sans-serif;
        font-size: 28px;
        font-weight: 800;
        color: #10b981;
    }

    .fsr-text {
        font-size: 9px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
    }

    .feedback-metrics { margin-bottom: 20px; }

    .fb-metric {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--border-light);
    }

    .fb-metric:last-child { border-bottom: none; }

    .fb-metric-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
    }

    .fb-metric-bar-wrap {
        width: 100px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .fb-metric-bar {
        flex: 1;
        height: 6px;
        background: var(--bg-canvas);
        border-radius: 3px;
        overflow: hidden;
    }

    .fb-metric-bar-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.5s ease;
    }

    .fb-metric-val {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-main);
        min-width: 24px;
        text-align: right;
    }

    .fb-tips-box {
        background: rgba(245, 158, 11, 0.06);
        border: 1px solid rgba(245, 158, 11, 0.15);
        border-radius: 12px;
        padding: 16px;
    }

    .fb-tips-title {
        font-size: 12px;
        font-weight: 700;
        color: #f59e0b;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .fb-tip-item {
        font-size: 11.5px;
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 6px;
        padding-left: 14px;
        position: relative;
    }

    .fb-tip-item::before {
        content: '•';
        position: absolute;
        left: 0;
        color: #f59e0b;
        font-weight: 800;
    }

    /* History Table */
    .history-section {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 24px;
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;
    }

    .history-table th {
        text-align: left;
        padding: 10px 14px;
        font-size: 10.5px !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--text-muted) !important;
        border-bottom: 1px solid var(--border);
    }

    .history-table td {
        padding: 14px;
        font-size: 12.5px !important;
        border-bottom: 1px solid var(--border-light);
    }

    .score-pill {
        display: inline-flex;
        align-items: center;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
    }

    .score-pill.high { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .score-pill.mid { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .score-pill.low { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    @media (max-width: 1024px) {
        .type-selector-grid { grid-template-columns: repeat(2, 1fr); }
        .company-grid { grid-template-columns: repeat(3, 1fr); }
        .interview-sim-container { grid-template-columns: 1fr; }
        .hero-stats-row { flex-wrap: wrap; }
    }
</style>

<!-- Hero -->
<div class="interview-hero">
    <div class="interview-hero-left">
        <div class="interview-hero-badge"><i class="fa-solid fa-microphone-lines"></i> AI Mock Interview</div>
        <h1>Interview Simulator</h1>
        <p>Practice with AI-powered mock interviews tailored to your target companies. Get instant feedback and score improvements.</p>
    </div>
    <div class="hero-stats-row">
        <div class="hero-mini-stat">
            <div class="hero-mini-stat-val">12</div>
            <div class="hero-mini-stat-label">Sessions Done</div>
        </div>
        <div class="hero-mini-stat">
            <div class="hero-mini-stat-val">75%</div>
            <div class="hero-mini-stat-label">Avg Score</div>
        </div>
        <div class="hero-mini-stat">
            <div class="hero-mini-stat-val">+18%</div>
            <div class="hero-mini-stat-label">Improvement</div>
        </div>
    </div>
</div>

<!-- Interview Type Selector -->
<div class="section-header">
    <div>
        <div class="section-title">Choose Interview Type</div>
        <div class="section-subtitle">Select the type of interview you want to practice</div>
    </div>
</div>

<div class="type-selector-grid">
    <div class="type-card selected" onclick="selectType(this)">
        <div class="type-check"><i class="fa-solid fa-check"></i></div>
        <div class="type-icon tech"><i class="fa-solid fa-code"></i></div>
        <div class="type-name">Technical Round</div>
        <div class="type-desc">DSA, System Design, OOP concepts</div>
    </div>
    <div class="type-card" onclick="selectType(this)">
        <div class="type-check"><i class="fa-solid fa-check"></i></div>
        <div class="type-icon hr"><i class="fa-solid fa-comments"></i></div>
        <div class="type-name">HR Round</div>
        <div class="type-desc">Behavioral, situational, culture fit</div>
    </div>
    <div class="type-card" onclick="selectType(this)">
        <div class="type-check"><i class="fa-solid fa-check"></i></div>
        <div class="type-icon behav"><i class="fa-solid fa-brain"></i></div>
        <div class="type-name">Behavioral</div>
        <div class="type-desc">STAR method, leadership, teamwork</div>
    </div>
    <div class="type-card" onclick="selectType(this)">
        <div class="type-check"><i class="fa-solid fa-check"></i></div>
        <div class="type-icon case"><i class="fa-solid fa-chart-line"></i></div>
        <div class="type-name">Case Study</div>
        <div class="type-desc">Problem solving, analytical thinking</div>
    </div>
</div>

<!-- Company Selector -->
<div class="section-header">
    <div>
        <div class="section-title">Select Target Company</div>
        <div class="section-subtitle">Get company-specific questions from real interview databases</div>
    </div>
</div>

<div class="company-grid">
    <div class="company-chip active" onclick="selectCompany(this)">
        <div class="company-logo-circle" style="background: linear-gradient(135deg, #2563eb, #1d4ed8);">T</div>
        <div class="company-chip-name">TCS</div>
    </div>
    <div class="company-chip" onclick="selectCompany(this)">
        <div class="company-logo-circle" style="background: linear-gradient(135deg, #059669, #047857);">I</div>
        <div class="company-chip-name">Infosys</div>
    </div>
    <div class="company-chip" onclick="selectCompany(this)">
        <div class="company-logo-circle" style="background: linear-gradient(135deg, #7c3aed, #6d28d9);">W</div>
        <div class="company-chip-name">Wipro</div>
    </div>
    <div class="company-chip" onclick="selectCompany(this)">
        <div class="company-logo-circle" style="background: linear-gradient(135deg, #ea580c, #c2410c);">C</div>
        <div class="company-chip-name">Cognizant</div>
    </div>
    <div class="company-chip" onclick="selectCompany(this)">
        <div class="company-logo-circle" style="background: linear-gradient(135deg, #dc2626, #b91c1c);">G</div>
        <div class="company-chip-name">Google</div>
    </div>
    <div class="company-chip" onclick="selectCompany(this)">
        <div class="company-logo-circle" style="background: linear-gradient(135deg, #0284c7, #0369a1);">M</div>
        <div class="company-chip-name">Microsoft</div>
    </div>
</div>

<!-- Interview Simulation -->
<div class="interview-sim-container">
    <div class="sim-main-area">
        <div class="sim-question-bar">
            <div class="sim-q-badge"><i class="fa-solid fa-circle-question"></i> Question 3 of 10</div>
            <div class="sim-timer"><i class="fa-solid fa-clock"></i> 02:34</div>
        </div>

        <div class="sim-question-text">
            Explain the difference between an abstract class and an interface in Java. When would you use one over the other? Provide a real-world example.
        </div>

        <div class="answer-options-bar">
            <div class="answer-opt-btn active"><i class="fa-solid fa-keyboard"></i> Text Answer</div>
            <div class="answer-opt-btn"><i class="fa-solid fa-video"></i> Video Answer</div>
            <div class="answer-opt-btn"><i class="fa-solid fa-microphone"></i> Voice Answer</div>
        </div>

        <div class="sim-answer-area">
            <textarea placeholder="Type your answer here... Be detailed and use examples to demonstrate your understanding.">An abstract class can have both abstract and concrete methods, along with instance variables and constructors. It supports single inheritance through the 'extends' keyword.

An interface, on the other hand, defines a contract with only abstract methods (prior to Java 8), and a class can implement multiple interfaces.

Real-world example: Consider a Vehicle abstract class with common properties like speed and fuel, and an interface Chargeable for electric vehicles. A Tesla class would extend Vehicle and implement Chargeable.</textarea>
        </div>

        <div class="sim-nav-buttons">
            <button class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Previous</button>
            <div style="display: flex; gap: 8px;">
                <button class="btn btn-outline"><i class="fa-solid fa-forward"></i> Skip</button>
                <button class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Submit Answer</button>
            </div>
        </div>
    </div>

    <!-- AI Feedback Sidebar -->
    <div class="feedback-sidebar">
        <div class="feedback-title"><i class="fa-solid fa-star"></i> AI Feedback</div>

        <div class="feedback-score-ring">
            <svg viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="40" class="fsr-bg"/>
                <circle cx="50" cy="50" r="40" class="fsr-fill"/>
            </svg>
            <div class="fsr-label">
                <div class="fsr-value">75</div>
                <div class="fsr-text">Score</div>
            </div>
        </div>

        <div class="feedback-metrics">
            <div class="fb-metric">
                <span class="fb-metric-label">Accuracy</span>
                <div class="fb-metric-bar-wrap">
                    <div class="fb-metric-bar"><div class="fb-metric-bar-fill" style="width: 85%; background: #10b981;"></div></div>
                    <span class="fb-metric-val">85</span>
                </div>
            </div>
            <div class="fb-metric">
                <span class="fb-metric-label">Communication</span>
                <div class="fb-metric-bar-wrap">
                    <div class="fb-metric-bar"><div class="fb-metric-bar-fill" style="width: 72%; background: #f59e0b;"></div></div>
                    <span class="fb-metric-val">72</span>
                </div>
            </div>
            <div class="fb-metric">
                <span class="fb-metric-label">Depth</span>
                <div class="fb-metric-bar-wrap">
                    <div class="fb-metric-bar"><div class="fb-metric-bar-fill" style="width: 68%; background: #f59e0b;"></div></div>
                    <span class="fb-metric-val">68</span>
                </div>
            </div>
            <div class="fb-metric">
                <span class="fb-metric-label">Examples</span>
                <div class="fb-metric-bar-wrap">
                    <div class="fb-metric-bar"><div class="fb-metric-bar-fill" style="width: 90%; background: #10b981;"></div></div>
                    <span class="fb-metric-val">90</span>
                </div>
            </div>
        </div>

        <div class="fb-tips-box">
            <div class="fb-tips-title"><i class="fa-solid fa-lightbulb"></i> Improvement Tips</div>
            <div class="fb-tip-item">Mention Java 8+ default methods in interfaces for depth.</div>
            <div class="fb-tip-item">Add a comparison table for better structure.</div>
            <div class="fb-tip-item">Great real-world example with Tesla — keep this pattern!</div>
        </div>
    </div>
</div>

<!-- History Section -->
<div class="section-header">
    <div>
        <div class="section-title">Interview History</div>
        <div class="section-subtitle">Track your progress across all mock interview sessions</div>
    </div>
</div>

<div class="history-section">
    <table class="history-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Company</th>
                <th>Questions</th>
                <th>Score</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Sep 06, 2026</td>
                <td><span style="font-weight: 600;">Technical</span></td>
                <td>TCS</td>
                <td>10 / 10</td>
                <td><span class="score-pill high">82/100</span></td>
                <td><span class="score-pill high">Completed</span></td>
            </tr>
            <tr>
                <td>Sep 04, 2026</td>
                <td><span style="font-weight: 600;">HR Round</span></td>
                <td>Infosys</td>
                <td>8 / 8</td>
                <td><span class="score-pill mid">71/100</span></td>
                <td><span class="score-pill high">Completed</span></td>
            </tr>
            <tr>
                <td>Sep 02, 2026</td>
                <td><span style="font-weight: 600;">Technical</span></td>
                <td>Google</td>
                <td>5 / 10</td>
                <td><span class="score-pill mid">65/100</span></td>
                <td><span class="score-pill mid">Partial</span></td>
            </tr>
            <tr>
                <td>Aug 30, 2026</td>
                <td><span style="font-weight: 600;">Behavioral</span></td>
                <td>Microsoft</td>
                <td>6 / 6</td>
                <td><span class="score-pill high">88/100</span></td>
                <td><span class="score-pill high">Completed</span></td>
            </tr>
        </tbody>
    </table>
</div>

<script>
function selectType(card) {
    document.querySelectorAll('.type-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
}
function selectCompany(chip) {
    document.querySelectorAll('.company-chip').forEach(c => c.classList.remove('active'));
    chip.classList.add('active');
}
</script>

<?= $this->endSection() ?>
