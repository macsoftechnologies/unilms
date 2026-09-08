<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($quiz['title']) ?> - Online CBT Exam</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: rgba(79, 70, 229, 0.08);
            --bg-canvas: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --border-light: #f1f5f9;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 14px rgba(0, 0, 0, 0.06);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-canvas);
            color: var(--text-main);
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .exam-header {
            background: var(--card-bg);
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            z-index: 20;
            gap: 16px;
        }

        .exam-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .exam-badge {
            background: var(--primary-light);
            color: var(--primary);
            font-size: 11px;
            font-weight: 800;
            padding: 4px 8px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            flex-shrink: 0;
        }

        .exam-title {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            font-size: 15.5px;
            font-weight: 700;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .timer-badge {
            background: #fff;
            border: 1.5px solid var(--border);
            padding: 6px 14px;
            border-radius: 24px;
            font-weight: 700;
            font-size: 14.5px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            box-shadow: var(--shadow-sm);
            flex-shrink: 0;
        }

        .timer-badge.warning {
            color: var(--danger);
            border-color: rgba(239, 68, 68, 0.4);
            background: rgba(239, 68, 68, 0.06);
            animation: pulseWarning 1.2s infinite;
        }

        @keyframes pulseWarning {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.03); }
        }

        .exam-body {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        .sidebar {
            width: 250px;
            background: var(--card-bg);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .sidebar-header {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            font-weight: 700;
            font-size: 12.5px;
            color: var(--text-muted);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--bg-canvas);
        }

        .sidebar-header strong {
            color: var(--text-main);
        }

        .question-grid {
            padding: 16px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            overflow-y: auto;
            flex: 1;
            align-content: flex-start;
        }

        .q-nav-btn {
            aspect-ratio: 1;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--bg-canvas);
            font-weight: 700;
            font-size: 12.5px;
            color: var(--text-muted);
            font-family: inherit;
            cursor: pointer;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .q-nav-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: #fff;
        }

        .q-nav-btn.answered {
            background: var(--success);
            color: #fff;
            border-color: var(--success);
        }

        .q-nav-btn.active {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px var(--primary);
            background: #fff;
            color: var(--primary);
        }

        .q-nav-btn.answered.active {
            background: var(--success);
            color: #fff;
            box-shadow: 0 0 0 2px #059669;
        }

        .submit-panel {
            padding: 16px;
            border-top: 1px solid var(--border);
            background: var(--card-bg);
        }

        .main-content {
            flex: 1;
            overflow-y: auto;
            padding: 28px 36px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .content-container {
            width: 100%;
            max-width: 760px;
        }

        .question-card {
            background: var(--card-bg);
            border-radius: 14px;
            padding: 24px 28px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            display: none;
        }

        .question-card.active {
            display: block;
        }

        .q-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-light);
            color: var(--text-muted);
            font-size: 12.5px;
            font-weight: 600;
        }

        .marks-badge {
            background: var(--primary-light);
            color: var(--primary);
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 11.5px;
        }

        .q-text {
            font-size: 15.5px;
            line-height: 1.55;
            margin-bottom: 22px;
            font-weight: 600;
            color: var(--text-main);
        }

        .options-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .option-label {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.15s ease;
            font-size: 13.5px;
            color: var(--text-main);
            background: var(--bg-canvas);
        }

        .option-label:hover {
            border-color: var(--primary);
            background: #fff;
        }

        .option-label.selected {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 700;
        }

        .opt-indicator {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 1.5px solid var(--border);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.15s ease;
        }

        .option-label.selected .opt-indicator {
            border-color: var(--primary);
            background: var(--primary);
        }

        .opt-indicator::after {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #fff;
            display: none;
        }

        .option-label.selected .opt-indicator::after {
            display: block;
        }

        input[type="radio"] {
            display: none;
        }

        .nav-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            gap: 12px;
        }

        .btn {
            padding: 9px 18px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.15s ease;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-outline {
            background: #fff;
            border-color: var(--border);
            color: var(--text-main);
        }

        .btn-outline:hover:not(:disabled) {
            border-color: var(--text-muted);
            background: var(--bg-canvas);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover:not(:disabled) {
            background: var(--primary-hover);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover:not(:disabled) {
            background: #059669;
        }

        .btn:disabled {
            opacity: 0.45;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

    <div class="exam-header">
        <div class="exam-brand">
            <span class="exam-badge">CBT Portal</span>
            <h1 class="exam-title" title="<?= esc($quiz['title']) ?>"><?= esc($quiz['title']) ?></h1>
        </div>
        <?php if($quiz['time_limit_minutes'] > 0): ?>
            <div class="timer-badge" id="timerDisplay">
                <i class="fa-regular fa-clock"></i> <span id="timeText">--:--:--</span>
            </div>
        <?php else: ?>
            <div class="timer-badge">
                <i class="fa-solid fa-infinity"></i> Unlimited
            </div>
        <?php endif; ?>
    </div>

    <div class="exam-body">
        <div class="sidebar">
            <div class="sidebar-header">
                <span>Questions</span>
                <span><strong id="answeredCount">0</strong> / <?= count($questions) ?> Answered</span>
            </div>
            <div class="question-grid">
                <?php foreach($questions as $idx => $q): ?>
                    <button class="q-nav-btn <?= $q['saved_option_id'] ? 'answered' : '' ?> <?= $idx === 0 ? 'active' : '' ?>" onclick="goToQuestion(<?= $idx ?>)" id="nav-btn-<?= $idx ?>">
                        <?= $idx + 1 ?>
                    </button>
                <?php endforeach; ?>
            </div>
            
            <div class="submit-panel">
                <form action="<?= base_url('lms/quizzes/submit') ?>" method="POST" id="submitForm" onsubmit="return confirmSubmit()">
                    <?= csrf_field() ?>
                    <input type="hidden" name="attempt_id" value="<?= esc($attempt['uuid'] ?? $attempt['id']) ?>">
                    <button type="submit" class="btn btn-success" style="width: 100%; justify-content: center;"><i class="fa-solid fa-paper-plane me-1"></i> Submit Exam</button>
                </form>
            </div>
        </div>

        <div class="main-content">
            <div class="content-container">
                <?php foreach($questions as $idx => $q): ?>
                    <div class="question-card <?= $idx === 0 ? 'active' : '' ?>" id="q-card-<?= $idx ?>">
                        <div class="q-meta">
                            <span>Question <?= $idx + 1 ?> of <?= count($questions) ?></span>
                            <span class="marks-badge"><?= $q['marks'] ?> Marks</span>
                        </div>
                        
                        <div class="q-text">
                            <?= nl2br(esc($q['question_text'])) ?>
                        </div>

                        <div class="options-container">
                            <?php foreach($q['options'] as $opt): ?>
                                <?php $is_selected = ($q['saved_option_id'] == $opt['id']); ?>
                                <label class="option-label <?= $is_selected ? 'selected' : '' ?>" id="lbl-<?= $q['id'] ?>-<?= $opt['id'] ?>">
                                    <div class="opt-indicator"></div>
                                    <input type="radio" name="q_<?= $q['id'] ?>" value="<?= $opt['id'] ?>" <?= $is_selected ? 'checked' : '' ?> onchange="saveAnswer(<?= $idx ?>, <?= $q['id'] ?>, <?= $opt['id'] ?>)">
                                    <span><?= esc($opt['option_text']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="nav-buttons">
                    <button class="btn btn-outline" id="btnPrev" onclick="goToQuestion(currentQuestion - 1)" disabled><i class="fa-solid fa-chevron-left"></i> Previous</button>
                    <button class="btn btn-primary" id="btnNext" onclick="goToQuestion(currentQuestion + 1)">Next <i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const totalQuestions = <?= count($questions) ?>;
        let currentQuestion = 0;
        let answeredCount = <?= count(array_filter($questions, fn($q) => $q['saved_option_id'] !== null)) ?>;
        const attemptId = '<?= esc($attempt['uuid'] ?? $attempt['id']) ?>';
        
        document.getElementById('answeredCount').innerText = answeredCount;

        function goToQuestion(idx) {
            if (idx < 0 || idx >= totalQuestions) return;
            
            // Hide current
            document.getElementById('q-card-' + currentQuestion).classList.remove('active');
            document.getElementById('nav-btn-' + currentQuestion).classList.remove('active');
            
            // Show new
            currentQuestion = idx;
            document.getElementById('q-card-' + currentQuestion).classList.add('active');
            document.getElementById('nav-btn-' + currentQuestion).classList.add('active');
            
            // Update buttons
            document.getElementById('btnPrev').disabled = (currentQuestion === 0);
            
            if (currentQuestion === totalQuestions - 1) {
                document.getElementById('btnNext').style.display = 'none';
            } else {
                document.getElementById('btnNext').style.display = 'inline-flex';
            }
        }

        function saveAnswer(qIdx, questionId, optionId) {
            // Update UI instantly
            const labels = document.querySelectorAll(`input[name="q_${questionId}"]`);
            labels.forEach(radio => {
                const lbl = radio.closest('label');
                lbl.classList.remove('selected');
                if (radio.checked) lbl.classList.add('selected');
            });

            const navBtn = document.getElementById('nav-btn-' + qIdx);
            if (!navBtn.classList.contains('answered')) {
                navBtn.classList.add('answered');
                answeredCount++;
                document.getElementById('answeredCount').innerText = answeredCount;
            }

            // AJAX Save
            const formData = new FormData();
            formData.append('attempt_id', attemptId);
            formData.append('question_id', questionId);
            formData.append('option_id', optionId);

            fetch('<?= base_url('lms/quizzes/save_answer') ?>', {
                method: 'POST',
                body: formData
            }).catch(console.error);
        }

        function confirmSubmit() {
            if (answeredCount < totalQuestions) {
                return confirm(`You have only answered ${answeredCount} out of ${totalQuestions} questions. Are you sure you want to submit?`);
            }
            return confirm('Are you sure you want to submit your exam?');
        }

        // Timer Logic
        <?php if($quiz['time_limit_minutes'] > 0): ?>
            let timeRemaining = <?= $time_remaining ?>;
            const timerDisplay = document.getElementById('timerDisplay');
            const timeText = document.getElementById('timeText');

            function updateTimer() {
                if (timeRemaining <= 0) {
                    timeText.innerText = "00:00:00";
                    document.getElementById('submitForm').submit();
                    return;
                }

                const h = Math.floor(timeRemaining / 3600);
                const m = Math.floor((timeRemaining % 3600) / 60);
                const s = Math.floor(timeRemaining % 60);

                timeText.innerText = 
                    String(h).padStart(2, '0') + ':' + 
                    String(m).padStart(2, '0') + ':' + 
                    String(s).padStart(2, '0');

                if (timeRemaining < 300) { // 5 mins warning
                    timerDisplay.classList.add('warning');
                }

                timeRemaining--;
            }

            setInterval(updateTimer, 1000);
            updateTimer();
        <?php endif; ?>
        
        // Block exiting page accidentally
        window.onbeforeunload = function(e) {
            return "Are you sure you want to leave? Your exam is still in progress.";
        };
        
        document.getElementById('submitForm').addEventListener('submit', function() {
            window.onbeforeunload = null;
        });
    </script>
</body>
</html>

