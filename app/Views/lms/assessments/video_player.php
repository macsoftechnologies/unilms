<?= $this->extend('lms/layout') ?>

<?= $this->section('page_title') ?>
Interactive Video Assessment - <?= esc($assessment['title']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div style="max-width: 1280px; margin: 0 auto;">
    <!-- Top Breadcrumb & Header Bar -->
    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                <a href="<?= site_url('lms/assessments') ?>" class="btn btn-sm btn-outline" style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; font-size: 12px; border-radius: 8px;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Assessments
                </a>
                <span class="badge" style="background: rgba(99, 102, 241, 0.12); color: var(--primary); font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                    <i class="fa-solid fa-play-circle me-1"></i> Interactive Video Checkpoint
                </span>
            </div>
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 800; color: var(--text-main); margin: 0 0 4px;">
                <?= esc($assessment['title']) ?>
            </h2>
            <p style="color: var(--text-muted); font-size: 13px; margin: 0;">
                Watch the video. The player automatically pauses at designated checkpoint timestamps for mandatory questions.
            </p>
        </div>

        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="background: var(--surface); padding: 8px 18px; border-radius: 12px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); text-align: right;">
                <div style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Max Marks</div>
                <div style="font-size: 17px; font-weight: 800; color: var(--primary); line-height: 1.2;"><?= esc($assessment['max_marks']) ?> Pts</div>
            </div>
            <div style="background: var(--surface); padding: 8px 18px; border-radius: 12px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); text-align: right;">
                <div style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Checkpoints</div>
                <div style="font-size: 17px; font-weight: 800; color: #0284c7; line-height: 1.2;"><?= count($questions) ?> Total</div>
            </div>
        </div>
    </div>

    <?php if ($submission): ?>
        <!-- Completed Assessment Summary View -->
        <div class="card" style="text-align: center; padding: 40px 24px; border-radius: 20px; border: 1px solid var(--border); background: var(--surface); box-shadow: var(--shadow-md); max-width: 680px; margin: 30px auto;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: rgba(16, 185, 129, 0.12); color: var(--success); display: flex; align-items: center; justify-content: center; font-size: 30px; margin: 0 auto 16px;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h3 style="font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 800; color: var(--text-main); margin-bottom: 8px;">
                Interactive Assessment Completed!
            </h3>
            <p style="color: var(--text-muted); font-size: 13.5px; margin-bottom: 24px; max-width: 460px; margin-left: auto; margin-right: auto;">
                All video checkpoints were evaluated. Your score has been submitted to your academic record.
            </p>
            
            <div style="display: inline-flex; align-items: center; gap: 14px; background: var(--bg-canvas); padding: 12px 24px; border-radius: 14px; border: 1px solid var(--border); margin-bottom: 28px;">
                <span style="color: var(--text-muted); font-size: 13px; font-weight: 600;">Your Final Score:</span>
                <span style="font-size: 22px; font-weight: 800; color: var(--success);">
                    <?= esc($submission['final_marks'] ?? $submission['auto_score'] ?? 0) ?> <span style="font-size: 14px; color: var(--text-muted); font-weight: 600;">/ <?= esc($assessment['max_marks']) ?> Pts</span>
                </span>
            </div>

            <div>
                <a href="<?= site_url('lms/assessments') ?>" class="btn btn-primary" style="padding: 10px 24px; font-weight: 700; border-radius: 10px;">
                    <i class="fa-solid fa-arrow-left me-1"></i> Return to Assessments
                </a>
            </div>
        </div>
    <?php else: ?>
        <form action="<?= site_url('lms/assessments/submit/' . $assessment['id']) ?>" method="POST" id="videoQuizForm">
            <?= csrf_field() ?>
            
            <div style="display: grid; grid-template-columns: 2.1fr 1fr; gap: 24px; align-items: start;">
                <!-- Main Column: Video Player with Checkpoint Overlay -->
                <div>
                    <!-- Video Container -->
                    <div class="card" style="position: relative; padding: 0; overflow: hidden; border-radius: 18px; border: 1px solid var(--border); margin-bottom: 16px; background: #000; box-shadow: 0 12px 30px rgba(0,0,0,0.15);">
                        <div style="position: relative; width: 100%; padding-top: 56.25%;">
                            <?php 
                            $url = $assessment['video_url'] ?? '';
                            $ytId = '';
                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
                                $ytId = $matches[1];
                            }
                            ?>
                            <?php if (!empty($ytId)): ?>
                                <iframe 
                                    id="videoPlayerIframe" 
                                    src="https://www.youtube.com/embed/<?= esc($ytId) ?>?enablejsapi=1&rel=0&modestbranding=1" 
                                    style="position: absolute; top:0; left:0; width:100%; height:100%; border:0;" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                                </iframe>
                            <?php else: ?>
                                <video id="nativeVideoPlayer" controls style="position: absolute; top:0; left:0; width:100%; height:100%;">
                                    <source src="<?= esc($url) ?>" type="video/mp4">
                                    Your browser does not support HTML5 video.
                                </video>
                            <?php endif; ?>

                            <!-- Sleek Floating Checkpoint Modal on Top of Video -->
                            <div id="questionModal" style="display: none; position: absolute; top:0; left:0; width:100%; height:100%; z-index: 60; background: rgba(15, 23, 42, 0.88); backdrop-filter: blur(8px); padding: 20px; box-sizing: border-box; align-items: center; justify-content: center;">
                                <div style="max-width: 580px; width: 100%; background: var(--surface); border-radius: 16px; padding: 22px 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.5); border: 1px solid var(--border); animation: popIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <span class="badge" style="background: rgba(239, 68, 68, 0.12); color: var(--danger); font-size: 11.5px; font-weight: 800; padding: 4px 10px; border-radius: 20px;">
                                                <i class="fa-solid fa-circle-pause me-1"></i> Checkpoint Question
                                            </span>
                                            <span id="qPointsBadge" class="badge" style="background: rgba(99, 102, 241, 0.12); color: var(--primary); font-size: 11px; font-weight: 700; padding: 4px 8px; border-radius: 6px;"></span>
                                        </div>
                                        <span id="qTimeDisplay" style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); background: var(--bg-canvas); padding: 4px 10px; border-radius: 8px; border: 1px solid var(--border);"></span>
                                    </div>
                                    
                                    <h3 id="qTextDisplay" style="font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 700; color: var(--text-main); margin-bottom: 14px; line-height: 1.45;"></h3>
                                    
                                    <div id="qOptionsContainer" style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 18px;"></div>

                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <small style="color: var(--text-muted); font-size: 11.5px;">
                                            <i class="fa-solid fa-lightbulb text-warning me-1"></i> Select an answer to resume video
                                        </small>
                                        <button type="button" class="btn btn-primary" id="btnResumeVideo" style="padding: 8px 20px; font-weight: 700; font-size: 13px; border-radius: 10px;">
                                            <i class="fa-solid fa-play me-1"></i> Save & Continue
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Video Timeline Guide Card -->
                    <div class="card" style="border-radius: 14px; padding: 14px 18px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; background: var(--surface); border: 1px solid var(--border);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 34px; height: 34px; border-radius: 10px; background: rgba(99, 102, 241, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 15px;">
                                <i class="fa-solid fa-map-pin"></i>
                            </div>
                            <div>
                                <strong style="font-size: 13px; color: var(--text-main); display: block;">Interactive Timeline Pins Active</strong>
                                <small style="color: var(--text-muted); font-size: 11.5px;">The video will halt at checkpoint timestamps shown on the right panel.</small>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;" id="timelinePills">
                            <?php foreach ($questions as $idx => $q): ?>
                                <?php 
                                $mins = floor(($q['timestamp_seconds'] ?? 0) / 60);
                                $secs = str_pad(($q['timestamp_seconds'] ?? 0) % 60, 2, '0', STR_PAD_LEFT);
                                ?>
                                <span class="badge" id="pill-q-<?= $q['id'] ?>" style="background: var(--bg-canvas); color: var(--text-muted); border: 1px solid var(--border); font-size: 11px; padding: 4px 8px; border-radius: 6px;">
                                    <i class="fa-solid fa-clock me-1"></i> <?= $mins ?>:<?= $secs ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Checkpoint Progress & Interactive Tracker -->
                <div>
                    <!-- Tracker Card -->
                    <div class="card" style="border-radius: 16px; padding: 20px; border: 1px solid var(--border); background: var(--surface); box-shadow: var(--shadow-sm); margin-bottom: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; padding-bottom: 12px; border-bottom: 1px solid var(--border);">
                            <h4 style="margin: 0; font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 800; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-list-check" style="color: var(--primary);"></i> Checkpoint List
                            </h4>
                            <span class="badge" id="answeredCountBadge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); font-size: 11px; font-weight: 700; padding: 4px 8px; border-radius: 6px;">
                                0 / <?= count($questions) ?> Done
                            </span>
                        </div>

                        <!-- Questions Tracker List -->
                        <div style="display: flex; flex-direction: column; gap: 10px;" id="questionsTrackerList">
                            <?php if (empty($questions)): ?>
                                <div style="text-align: center; padding: 20px 0; color: var(--text-muted); font-size: 13px;">
                                    No checkpoint questions configured for this video.
                                </div>
                            <?php else: ?>
                                <?php foreach ($questions as $idx => $q): ?>
                                    <?php 
                                    $mins = floor(($q['timestamp_seconds'] ?? 0) / 60);
                                    $secs = str_pad(($q['timestamp_seconds'] ?? 0) % 60, 2, '0', STR_PAD_LEFT);
                                    ?>
                                    <div id="tracker-item-<?= $q['id'] ?>" class="checkpoint-tracker-item" style="border: 1px solid var(--border); border-radius: 12px; padding: 12px; background: var(--bg-canvas); transition: all 0.2s ease;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                            <div style="display: flex; align-items: center; gap: 6px;">
                                                <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 10.5px; font-weight: 700;">
                                                    Q<?= $idx + 1 ?>
                                                </span>
                                                <span style="font-size: 11.5px; font-weight: 700; color: var(--text-main);">
                                                    @ <?= $mins ?>:<?= $secs ?>
                                                </span>
                                            </div>
                                            <span id="tracker-status-<?= $q['id'] ?>" class="badge" style="background: rgba(100, 116, 139, 0.1); color: var(--text-muted); font-size: 10px; font-weight: 600;">
                                                Pending
                                            </span>
                                        </div>
                                        <p style="font-size: 12px; color: var(--text-main); margin: 0 0 6px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            <?= esc($q['question_text']) ?>
                                        </p>
                                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 11px; color: var(--text-muted);">
                                            <span><i class="fa-solid fa-star text-warning me-1"></i> <?= esc($q['marks'] ?? 1) ?> Pts</span>
                                            <span id="tracker-ans-<?= $q['id'] ?>" style="font-weight: 700; color: var(--primary);"></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Final Submission Card -->
                    <div class="card" style="border-radius: 16px; padding: 18px; border: 1px solid var(--border); background: var(--surface); box-shadow: var(--shadow-sm);">
                        <button type="submit" id="btnFinalSubmit" class="btn btn-primary" style="width: 100%; padding: 12px; font-weight: 700; font-size: 14px; border-radius: 10px; background: #10b981; border-color: #10b981; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <i class="fa-solid fa-paper-plane"></i> Submit Assessment
                        </button>
                        <p style="font-size: 11.5px; color: var(--text-muted); margin: 10px 0 0; text-align: center;">
                            Answer all checkpoints before submitting for full evaluation.
                        </p>
                    </div>
                </div>
            </div>
        </form>

        <style>
        @keyframes popIn {
            0% { transform: scale(0.92); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .checkpoint-opt-label {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: var(--bg-canvas);
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            font-size: 13px;
            color: var(--text-main);
        }
        .checkpoint-opt-label:hover {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.05);
            transform: translateY(-1px);
        }
        .checkpoint-opt-label.selected {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.1);
            font-weight: 600;
        }
        .checkpoint-opt-label input[type="radio"] {
            accent-color: var(--primary);
            width: 16px;
            height: 16px;
            margin: 0;
            cursor: pointer;
        }
        .checkpoint-tracker-item.active {
            border-color: var(--primary) !important;
            background: rgba(99, 102, 241, 0.04) !important;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.15);
        }
        .checkpoint-tracker-item.completed {
            border-color: rgba(16, 185, 129, 0.3) !important;
            background: rgba(16, 185, 129, 0.04) !important;
        }
        </style>

        <script>
        const questionsList = <?= json_encode($questions) ?>;
        const clearedQuestions = {};
        let currentActiveQuestion = null;

        function updateTrackerBadge() {
            const answeredCount = Object.keys(clearedQuestions).length;
            const badge = document.getElementById('answeredCountBadge');
            if (badge) {
                badge.textContent = `${answeredCount} / ${questionsList.length} Done`;
                if (answeredCount === questionsList.length && questionsList.length > 0) {
                    badge.style.background = 'rgba(16, 185, 129, 0.2)';
                    badge.style.color = '#059669';
                }
            }
        }

        function showQuestion(q) {
            currentActiveQuestion = q;
            const qModal = document.getElementById('questionModal');
            const qText = document.getElementById('qTextDisplay');
            const qOptions = document.getElementById('qOptionsContainer');
            const qTime = document.getElementById('qTimeDisplay');
            const qPoints = document.getElementById('qPointsBadge');

            qText.textContent = q.question_text;
            const mins = Math.floor(q.timestamp_seconds / 60);
            const secs = String(q.timestamp_seconds % 60).padStart(2, '0');
            qTime.innerHTML = `<i class="fa-solid fa-clock me-1"></i> ${mins}:${secs}`;
            if (qPoints) {
                qPoints.textContent = `+${q.marks || 1} Pts`;
            }

            // Highlight tracker item
            document.querySelectorAll('.checkpoint-tracker-item').forEach(el => el.classList.remove('active'));
            const trackerItem = document.getElementById(`tracker-item-${q.id}`);
            if (trackerItem) trackerItem.classList.add('active');
            
            let opts = [];
            try { opts = JSON.parse(q.options); } catch (e) {}

            let html = '';
            opts.forEach((opt, idx) => {
                const checked = clearedQuestions[q.id] === opt.key ? 'checked' : '';
                html += `
                    <label class="checkpoint-opt-label ${checked ? 'selected' : ''}" onclick="this.parentNode.querySelectorAll('label').forEach(l => l.classList.remove('selected')); this.classList.add('selected');">
                        <input type="radio" name="answers[${q.id}]" value="${opt.key}" ${checked}>
                        <strong style="color: var(--primary); min-width: 18px;">${opt.key}.</strong>
                        <span>${opt.text}</span>
                    </label>
                `;
            });
            qOptions.innerHTML = html;
            qModal.style.display = 'flex';
        }

        function pauseCurrentVideo() {
            const nativeVideo = document.getElementById('nativeVideoPlayer');
            if (nativeVideo) nativeVideo.pause();

            const ytIframe = document.getElementById('videoPlayerIframe');
            if (ytIframe && ytIframe.contentWindow) {
                ytIframe.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
            }
        }

        function playCurrentVideo() {
            const nativeVideo = document.getElementById('nativeVideoPlayer');
            if (nativeVideo) nativeVideo.play();

            const ytIframe = document.getElementById('videoPlayerIframe');
            if (ytIframe && ytIframe.contentWindow) {
                ytIframe.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
            }
        }

        function checkTimeAndPause(curTime) {
            questionsList.forEach(q => {
                if (q.timestamp_seconds && Math.abs(curTime - q.timestamp_seconds) <= 1 && !clearedQuestions[q.id]) {
                    pauseCurrentVideo();
                    showQuestion(q);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const videoEl = document.getElementById('nativeVideoPlayer');
            const ytIframe = document.getElementById('videoPlayerIframe');
            const qModal = document.getElementById('questionModal');
            const btnResume = document.getElementById('btnResumeVideo');

            // Native HTML5 video time updates
            if (videoEl) {
                videoEl.addEventListener('timeupdate', function () {
                    checkTimeAndPause(Math.floor(videoEl.currentTime));
                });
            }

            // YouTube IFrame postMessage listener
            if (ytIframe) {
                window.addEventListener('message', function (event) {
                    try {
                        const data = (typeof event.data === 'string') ? JSON.parse(event.data) : event.data;
                        if (data && data.event === 'infoDelivery' && data.info && typeof data.info.currentTime !== 'undefined') {
                            checkTimeAndPause(Math.floor(data.info.currentTime));
                        }
                    } catch (err) {}
                });

                // Request YouTube postMessage events
                ytIframe.addEventListener('load', function() {
                    ytIframe.contentWindow.postMessage('{"event":"listening","id":1}', '*');
                });
            }

            // Resume button handler
            btnResume.addEventListener('click', function () {
                if (currentActiveQuestion) {
                    const selected = document.querySelector(`input[name="answers[${currentActiveQuestion.id}]"]:checked`);
                    if (!selected) {
                        alert('Please select an option before continuing.');
                        return;
                    }
                    clearedQuestions[currentActiveQuestion.id] = selected.value;
                    
                    // Update Tracker Item
                    const statusBadge = document.getElementById(`tracker-status-${currentActiveQuestion.id}`);
                    if (statusBadge) {
                        statusBadge.textContent = 'Answered';
                        statusBadge.style.background = 'rgba(16, 185, 129, 0.12)';
                        statusBadge.style.color = '#059669';
                    }
                    const ansLabel = document.getElementById(`tracker-ans-${currentActiveQuestion.id}`);
                    if (ansLabel) {
                        ansLabel.textContent = `Selected: ${selected.value}`;
                    }
                    const trackerItem = document.getElementById(`tracker-item-${currentActiveQuestion.id}`);
                    if (trackerItem) {
                        trackerItem.classList.remove('active');
                        trackerItem.classList.add('completed');
                    }
                    const pill = document.getElementById(`pill-q-${currentActiveQuestion.id}`);
                    if (pill) {
                        pill.style.background = 'rgba(16, 185, 129, 0.12)';
                        pill.style.color = '#059669';
                        pill.style.borderColor = 'rgba(16, 185, 129, 0.3)';
                    }

                    updateTrackerBadge();
                    qModal.style.display = 'none';
                    playCurrentVideo();
                }
            });

            // Form Submit validation check
            const form = document.getElementById('videoQuizForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const unanswered = questionsList.filter(q => !clearedQuestions[q.id]);
                    if (unanswered.length > 0) {
                        if (!confirm(`You have ${unanswered.length} unanswered checkpoint question(s). Do you still want to submit?`)) {
                            e.preventDefault();
                        }
                    }
                });
            }
        });
        </script>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
