<?= $this->extend('lms/layout') ?>

<?= $this->section('page_title') ?>
Watch - <?= esc($course['title']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0" style="max-width: 1360px; margin: 0 auto; position: relative;">
    <!-- Top Breadcrumb & Metadata Bar -->
    <div style="margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                <a href="<?= base_url('lms/materials') ?>" class="btn btn-sm btn-outline" style="padding: 5px 12px; font-size: 12px; border-radius: 8px;">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Materials
                </a>
                <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                    <?= esc($course['category'] ?? 'Computer Science') ?>
                </span>
            </div>
            <h2 style="font-size: 20px; font-weight: 800; margin: 0 0 4px; color: var(--text-main); font-family: 'Outfit', sans-serif;">
                <?= esc($course['title']) ?>
            </h2>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <span class="badge" style="background: var(--surface); color: var(--text-muted); border: 1px solid var(--border); font-size: 12px; font-weight: 600; padding: 6px 12px; border-radius: 8px;">
                <i class="fa-solid fa-clock me-1 text-primary"></i> <?= $course['total_duration_minutes'] ?> mins total
            </span>
            <span class="badge" style="background: var(--surface); color: var(--text-muted); border: 1px solid var(--border); font-size: 12px; font-weight: 600; padding: 6px 12px; border-radius: 8px;">
                <i class="fa-solid fa-video me-1 text-primary"></i> <?= count($allLessons) ?> Lectures
            </span>
        </div>
    </div>

    <!-- =========================================================================
         TOP SECTION: VIDEO PLAYER (LEFT) + PLAYLIST ACCORDION (RIGHT)
    ========================================================================= -->
    <div style="display: grid; grid-template-columns: 2.2fr 1fr; gap: 24px; align-items: stretch; margin-bottom: 24px;">
        <!-- Left: Video Player -->
        <div>
            <div class="card" style="padding: 0; overflow: hidden; border-radius: 18px; background: #000; box-shadow: 0 10px 30px rgba(0,0,0,0.2); border: 1px solid var(--border); height: 100%; display: flex; flex-direction: column; justify-content: center;">
                <?php if ($activeLesson): ?>
                    <?php if ($activeLesson['video_type'] === 'upload' && !empty($activeLesson['video_file'])): ?>
                        <video controls style="width: 100%; aspect-ratio: 16/9; display: block; background: #000;" poster="<?= !empty($course['thumbnail']) ? base_url($course['thumbnail']) : '' ?>">
                            <source src="<?= base_url($activeLesson['video_file']) ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    <?php elseif (!empty($activeLesson['video_url'])): ?>
                        <?php
                        $embedUrl = $activeLesson['video_url'];
                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\\s]{11})/', $embedUrl, $matches)) {
                            $embedUrl = 'https://www.youtube.com/embed/' . $matches[1] . '?rel=0&modestbranding=1';
                        } elseif (strpos($embedUrl, 'vimeo.com/') !== false) {
                            $embedUrl = str_replace('vimeo.com/', 'player.vimeo.com/video/', $embedUrl);
                        }
                        ?>
                        <iframe src="<?= esc($embedUrl) ?>" style="width: 100%; aspect-ratio: 16/9; border: none; display: block;" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                    <?php else: ?>
                        <div style="aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; color: white; flex-direction: column;">
                            <i class="fa-solid fa-circle-play fa-3x mb-2 opacity-50"></i>
                            <p class="mb-0">No video stream linked for this lesson.</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right: Course Playlist Accordion -->
        <div>
            <div class="card" style="border-radius: 18px; padding: 18px; border: 1px solid var(--border); background: var(--surface); box-shadow: var(--shadow-sm); height: 100%; display: flex; flex-direction: column; box-sizing: border-box;">
                <div style="margin: 0 0 12px; display: flex; align-items: center; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid var(--border);">
                    <span style="font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 800; color: var(--text-main);">
                        <i class="fa-solid fa-list-ul me-2" style="color: var(--primary);"></i> Course Playlist
                    </span>
                    <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11px; font-weight: 700;">
                        <?= count($allLessons) ?> Lectures
                    </span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px; overflow-y: auto; max-height: 380px; padding-right: 4px;">
                    <?php foreach ($chapters as $chIdx => $ch): ?>
                        <div style="border: 1px solid var(--border); border-radius: 12px; overflow: hidden;">
                            <div style="padding: 10px 14px; background: var(--bg-canvas); font-weight: 700; font-size: 12.5px; color: var(--text-main); border-bottom: 1px solid var(--border);">
                                Ch <?= $chIdx + 1 ?>: <?= esc($ch['chapter_title']) ?>
                            </div>
                            <div>
                                <?php foreach ($ch['lessons'] as $les): ?>
                                    <?php $isActive = ($activeLesson && $activeLesson['id'] == $les['id']); ?>
                                    <a href="<?= base_url('lms/materials/watch/' . $course['id'] . '?lesson=' . $les['id']) ?>" 
                                       style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; text-decoration: none; font-size: 12px; border-bottom: 1px solid var(--border); background: <?= $isActive ? 'rgba(99, 102, 241, 0.08)' : 'transparent' ?>; color: <?= $isActive ? 'var(--primary)' : 'var(--text-main)' ?>; font-weight: <?= $isActive ? '700' : '400' ?>; transition: all 0.15s ease;">
                                        <div style="display: flex; align-items: center; gap: 8px; overflow: hidden;">
                                            <i class="fa-solid <?= $isActive ? 'fa-circle-play text-primary' : 'fa-play' ?>" style="font-size: 11px; flex-shrink: 0; color: <?= $isActive ? 'var(--primary)' : 'var(--text-muted)' ?>;"></i>
                                            <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= esc($les['lesson_title']) ?></span>
                                        </div>
                                        <small style="color: var(--text-muted); font-size: 10.5px; flex-shrink: 0; margin-left: 8px;"><?= $les['duration_minutes'] ?>m</small>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Lesson Header Bar -->
    <?php if ($activeLesson): ?>
        <div class="card" style="border-radius: 16px; padding: 18px 22px; margin-bottom: 20px; border: 1px solid var(--border); background: var(--surface);">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
                <div>
                    <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11px; font-weight: 700; margin-bottom: 4px;">
                        Currently Watching
                    </span>
                    <h3 style="margin: 0; font-size: 19px; font-weight: 800; font-family: 'Outfit', sans-serif; color: var(--text-main);">
                        <?= esc($activeLesson['lesson_title']) ?>
                    </h3>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 12.5px; color: var(--text-muted); background: var(--bg-canvas); padding: 6px 14px; border-radius: 8px; border: 1px solid var(--border);">
                        <i class="fa-solid fa-clock me-1 text-primary"></i> <?= $activeLesson['duration_minutes'] ?> mins
                    </span>
                    <button type="button" onclick="switchTab('slides')" class="btn btn-sm btn-outline" style="font-size: 12px; padding: 6px 14px; border-radius: 8px;">
                        <i class="fa-solid fa-file-powerpoint text-warning me-1"></i> Slides & Notes
                    </button>
                    <button type="button" onclick="toggleMessenger()" class="btn btn-sm btn-primary" style="font-size: 12px; padding: 6px 14px; border-radius: 8px;">
                        <i class="fa-solid fa-comments me-1"></i> Open Study Messenger
                    </button>
                </div>
            </div>
        </div>

        <!-- =========================================================================
             BOTTOM SECTION: FULL-WIDTH STRUCTURED COURSE TABS
        ========================================================================= -->
        <div class="card" style="border-radius: 18px; padding: 0; overflow: hidden; border: 1px solid var(--border); background: var(--surface); box-shadow: var(--shadow-sm); margin-bottom: 30px;">
            <!-- Navigation Tabs (FULL WIDTH) -->
            <div style="display: flex; border-bottom: 1px solid var(--border); background: var(--bg-canvas); overflow-x: auto;">
                <button type="button" class="lesson-tab-btn active" id="tabBtn-overview" onclick="switchTab('overview')">
                    <i class="fa-solid fa-circle-info me-2"></i> Overview & Objectives
                </button>
                <button type="button" class="lesson-tab-btn" id="tabBtn-slides" onclick="switchTab('slides')">
                    <i class="fa-solid fa-book-open-reader me-2"></i> Lecture Slides & Tool Guide
                </button>
                <button type="button" class="lesson-tab-btn" id="tabBtn-qa" onclick="switchTab('qa')">
                    <i class="fa-solid fa-circle-question me-2"></i> Course Q&A <span class="badge" style="background: rgba(99, 102, 241, 0.15); color: var(--primary); font-size: 10px; margin-left: 4px;">2</span>
                </button>
                <button type="button" class="lesson-tab-btn" id="tabBtn-notes" onclick="switchTab('notes')">
                    <i class="fa-solid fa-book-bookmark me-2" style="color: #ec4899;"></i> My Study Journal (Self-Study)
                </button>
                <button type="button" class="lesson-tab-btn" id="tabBtn-reviews" onclick="switchTab('reviews')">
                    <i class="fa-solid fa-star me-2 text-warning"></i> Reviews & Ratings <span class="badge" style="background: rgba(245, 158, 11, 0.15); color: #d97706; font-size: 10px; margin-left: 4px;">4.9 ★</span>
                </button>
            </div>

            <!-- Tab 1: Overview & Learning Goals -->
            <div id="tabContent-overview" class="lesson-tab-content" style="padding: 26px; display: block;">
                <h4 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800; color: var(--text-main); margin-bottom: 10px;">
                    Lesson Abstract & Theoretical Context
                </h4>
                <p style="font-size: 13.5px; color: var(--text-muted); line-height: 1.6; margin-bottom: 22px;">
                    This module covers key architectural paradigms, algorithmic complexities, and scalable engineering practices. Students analyze structural abstractions and verify implementations using containerized local development stacks.
                </p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
                    <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 12px; padding: 16px;">
                        <span style="font-size: 11px; font-weight: 700; color: var(--primary); text-transform: uppercase;">OBE Course Mapping</span>
                        <div style="font-size: 14px; font-weight: 700; color: var(--text-main); margin-top: 4px;">
                            <i class="fa-solid fa-graduation-cap me-1 text-primary"></i> CO2 &rarr; PO5 (Modern Tools)
                        </div>
                    </div>
                    <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 12px; padding: 16px;">
                        <span style="font-size: 11px; font-weight: 700; color: #0284c7; text-transform: uppercase;">Hands-On Lab Tooling</span>
                        <div style="font-size: 14px; font-weight: 700; color: var(--text-main); margin-top: 4px;">
                            <i class="fa-solid fa-cube me-1 text-info"></i> Docker + VS Code Sandbox
                        </div>
                    </div>
                    <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 12px; padding: 16px;">
                        <span style="font-size: 11px; font-weight: 700; color: #059669; text-transform: uppercase;">Assessment Model</span>
                        <div style="font-size: 14px; font-weight: 700; color: var(--text-main); margin-top: 4px;">
                            <i class="fa-solid fa-circle-check me-1 text-success"></i> In-Video Checkpoint Quiz
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Lecture Slides & Tool Guide -->
            <div id="tabContent-slides" class="lesson-tab-content" style="padding: 26px; display: none;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <h4 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800; color: var(--text-main); margin: 0 0 2px;">
                            Lecture Companion Slides & Commands
                        </h4>
                        <small style="color: var(--text-muted);">Interactive module notes, architecture blueprints, and terminal guides</small>
                    </div>
                    <button type="button" onclick="openSlidesModal()" class="btn btn-sm btn-primary" style="font-size: 12.5px; padding: 7px 16px; border-radius: 8px;">
                        <i class="fa-solid fa-up-right-and-down-left-from-center me-1"></i> Open Fullscreen Slides Viewer
                    </button>
                </div>

                <!-- Slide Cards in 2-Column Responsive Layout -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 16px;">
                    <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 14px; padding: 18px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <strong style="font-size: 13.5px; color: var(--primary);">
                                <i class="fa-solid fa-layer-group me-1"></i> Core Theoretical Foundations
                            </strong>
                            <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); font-size: 10.5px;">Key Takeaways</span>
                        </div>
                        <ul style="padding-left: 18px; font-size: 12.5px; color: var(--text-main); line-height: 1.6; margin: 0;">
                            <li>Modular decomposition of state management and asynchronous lifecycle pipelines.</li>
                            <li>Guarantees optimized logarithmic $\mathcal{O}(\log N)$ execution time and low API latency.</li>
                        </ul>
                    </div>

                    <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 14px; padding: 18px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <strong style="font-size: 13.5px; color: #0284c7;">
                                <i class="fa-solid fa-terminal me-1"></i> CLI & Container Commands
                            </strong>
                            <span class="badge" style="background: rgba(2, 132, 199, 0.1); color: #0284c7; font-size: 10.5px;">Tool Guide</span>
                        </div>
                        <div style="background: #0f172a; color: #38bdf8; font-family: monospace; padding: 10px 14px; border-radius: 8px; font-size: 12px; line-height: 1.4; margin-bottom: 8px;">
                            $ docker-compose up -d --build<br>
                            $ curl -X GET http://localhost:8080/api/v1/healthcheck
                        </div>
                        <small style="color: var(--text-muted); font-size: 11.5px;">Verified for VS Code Remote Containers & Postman Workspaces.</small>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Clean Course Q&A Thread -->
            <div id="tabContent-qa" class="lesson-tab-content" style="padding: 26px; display: none;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <h4 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800; margin: 0; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-circle-question" style="color: var(--primary);"></i> Course Questions & Answers
                        </h4>
                        <small style="color: var(--text-muted);">Have a doubt about this lesson? Ask here and get help from instructors and peers.</small>
                    </div>
                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); font-size: 11.5px; font-weight: 700; padding: 5px 12px; border-radius: 12px;">
                        <i class="fa-solid fa-check-double me-1"></i> Instructor Moderated
                    </span>
                </div>

                <!-- Post New Question Box -->
                <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 14px; padding: 16px; margin-bottom: 22px;">
                    <div style="display: flex; gap: 14px; align-items: flex-start;">
                        <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; flex-shrink: 0;">
                            <?= strtoupper(substr(session('first_name') ?: (session('user_name') ?: 'A'), 0, 1)) ?>
                        </div>
                        <div style="flex: 1;">
                            <textarea id="qaInput" rows="2" class="form-control" placeholder="Ask a question about this lecture video (e.g. at 04:15 how do we evaluate polynomial bounds?)..." style="width: 100%; border: 1px solid var(--border); border-radius: 10px; padding: 12px; font-size: 13px; background: var(--surface); color: var(--text-main); resize: vertical; margin-bottom: 10px; font-family: inherit;"></textarea>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 12px; color: var(--text-muted);">
                                    <i class="fa-solid fa-clock me-1"></i> Tag current video timestamp automatically
                                </span>
                                <button type="button" onclick="postQaQuestion()" class="btn btn-primary" style="font-size: 12.5px; padding: 6px 18px; border-radius: 8px;">
                                    <i class="fa-solid fa-paper-plane me-1"></i> Post Question
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Q&A Threads List -->
                <div id="qaThreadsContainer" style="display: flex; flex-direction: column; gap: 16px;">
                    <!-- Question 1 -->
                    <div style="border: 1px solid var(--border); border-radius: 14px; padding: 16px 18px; background: var(--bg-canvas);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: #0284c7; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px;">
                                    D
                                </div>
                                <div>
                                    <strong style="font-size: 13px; color: var(--text-main);">Diya Reddy</strong>
                                    <span class="badge" style="background: rgba(2, 132, 199, 0.1); color: #0284c7; font-size: 10.5px; margin-left: 6px;">26CSE002</span>
                                </div>
                            </div>
                            <small style="color: var(--text-muted); font-size: 11px;">10 mins ago &bull; <span class="text-primary fw-bold">@ 04:12</span></small>
                        </div>
                        <p style="font-size: 13px; color: var(--text-main); line-height: 1.5; margin: 0 0 10px;">
                            ❓ <em>"In today's lecture on Master Theorem, how do we distinguish Case 2 from Case 3 when the polynomial gap $\epsilon$ is very close to 0?"</em>
                        </p>

                        <!-- Answer 1 -->
                        <div style="margin-left: 18px; padding: 12px 14px; background: var(--surface); border-left: 3px solid var(--primary); border-radius: 0 8px 8px 0; margin-top: 8px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <strong style="font-size: 12.5px; color: var(--primary);">Dr. Rajesh Sharma (Faculty)</strong>
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); font-size: 10px;">Instructor Answer</span>
                                </div>
                                <small style="color: var(--text-muted); font-size: 10.5px;">5 mins ago</small>
                            </div>
                            <p style="font-size: 12.5px; color: var(--text-main); margin: 0; line-height: 1.45;">
                                💡 <em>"Case 2 applies when $f(n) = \Theta(n^{\log_b a} \log^k n)$. If $f(n)$ grows strictly asymptotically faster by a polynomial factor $n^\epsilon$, then use Case 3 with the regularity condition $a f(n/b) \le c f(n)$."</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 4: Private Study Notes Journal (Self-Study) -->
            <div id="tabContent-notes" class="lesson-tab-content" style="padding: 26px; display: none;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <h4 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800; margin: 0; color: var(--text-main);">
                            📝 Private Study Journal & Self-Study Notebook
                        </h4>
                        <small style="color: var(--text-muted);">Personal revision notes, active recall bookmarks, and key formulas.</small>
                    </div>
                    <button type="button" onclick="alert('Note successfully saved to your offline LMS study binder!')" class="btn btn-sm btn-primary" style="font-size: 12px; padding: 6px 16px; border-radius: 8px; background: #ec4899; border-color: #ec4899;">
                        <i class="fa-solid fa-bookmark me-1"></i> Save to Binder
                    </button>
                </div>

                <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 14px; padding: 18px; margin-bottom: 16px;">
                    <textarea rows="4" class="form-control" placeholder="Write your personal study takeaways (e.g., Master Theorem formula, Docker compose commands to test before exam)..." style="width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 12px; font-size: 13px; background: var(--surface); color: var(--text-main); resize: vertical; font-family: inherit;">• Master Theorem formula: T(n) = aT(n/b) + f(n)
• Case 1: f(n) = O(n^(log_b(a) - epsilon)) -> T(n) = Theta(n^log_b(a))
• Docker command: docker-compose up -d</textarea>
                </div>
            </div>

            <!-- Tab 5: Student Reviews & Course Ratings -->
            <div id="tabContent-reviews" class="lesson-tab-content" style="padding: 26px; display: none;">
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 28px; align-items: start;">
                    <!-- Left: Rating Summary Card -->
                    <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 16px; padding: 22px; text-align: center;">
                        <div style="font-size: 42px; font-weight: 900; color: var(--text-main); line-height: 1; font-family: 'Outfit', sans-serif;">
                            4.9
                        </div>
                        <div style="color: #f59e0b; font-size: 18px; margin: 6px 0;">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star-half-stroke"></i>
                        </div>
                        <div style="font-size: 12.5px; color: var(--text-muted); font-weight: 600; margin-bottom: 16px;">
                            Course Rating &bull; 128 Reviews
                        </div>

                        <!-- Progress Bars for Ratings -->
                        <div style="display: flex; flex-direction: column; gap: 6px; font-size: 11.5px; text-align: left;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="width: 24px;">5 ★</span>
                                <div style="flex: 1; height: 6px; background: var(--border); border-radius: 3px; overflow: hidden;">
                                    <div style="width: 88%; height: 100%; background: #f59e0b;"></div>
                                </div>
                                <span style="width: 28px; text-align: right; color: var(--text-muted);">88%</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="width: 24px;">4 ★</span>
                                <div style="flex: 1; height: 6px; background: var(--border); border-radius: 3px; overflow: hidden;">
                                    <div style="width: 10%; height: 100%; background: #f59e0b;"></div>
                                </div>
                                <span style="width: 28px; text-align: right; color: var(--text-muted);">10%</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="width: 24px;">3 ★</span>
                                <div style="flex: 1; height: 6px; background: var(--border); border-radius: 3px; overflow: hidden;">
                                    <div style="width: 2%; height: 100%; background: #f59e0b;"></div>
                                </div>
                                <span style="width: 28px; text-align: right; color: var(--text-muted);">2%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Verified Student Reviews List -->
                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 14px; padding: 16px 18px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 30px; height: 30px; border-radius: 50%; background: #6366f1; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 11px;">
                                        A
                                    </div>
                                    <div>
                                        <strong style="font-size: 13px; color: var(--text-main);">Ananya Sharma</strong>
                                        <div style="color: #f59e0b; font-size: 11px;">
                                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                                <small style="color: var(--text-muted); font-size: 11px;">2 days ago</small>
                            </div>
                            <p style="font-size: 12.5px; color: var(--text-main); margin: 0; line-height: 1.5;">
                                "The interactive checkpoints and Docker tool setups made complex algorithms so much easier to grasp. Great course layout!"
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- =========================================================================
     FLOATING BOTTOM-RIGHT MESSENGER / COLLABORATION HUB (SLACK / WHATSAPP STYLE)
========================================================================= -->
<div id="floatingMessengerWidget" style="position: fixed; bottom: 24px; right: 24px; z-index: 1040; display: flex; flex-direction: column; align-items: flex-end;">
    <!-- Messenger Popup Window (Hidden by default) -->
    <div id="messengerDrawer" style="display: none; width: 380px; height: 500px; background: var(--surface); border: 1px solid var(--border); border-radius: 20px; box-shadow: 0 16px 40px rgba(0,0,0,0.25); margin-bottom: 14px; overflow: hidden; flex-direction: column; animation: slideUp 0.25s ease-out;">
        <!-- Messenger Top Bar -->
        <div style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 14px 18px; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 14px;">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <div>
                    <h5 style="font-family: 'Outfit', sans-serif; font-size: 14px; font-weight: 800; margin: 0; color: white;">
                        Study Pod & Peer Messenger
                    </h5>
                    <small style="font-size: 11px; opacity: 0.9;">4 Peers Online &bull; Active Cohort</small>
                </div>
            </div>
            <button type="button" onclick="toggleMessenger()" style="background: transparent; border: none; color: white; font-size: 16px; cursor: pointer; padding: 4px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Messenger Channel Selector Tabs -->
        <div style="display: flex; border-bottom: 1px solid var(--border); background: var(--bg-canvas);">
            <button type="button" class="chat-tab-btn active" id="chatTab-peer" onclick="switchChatChannel('peer')">
                👥 Peer Help
            </button>
            <button type="button" class="chat-tab-btn" id="chatTab-group" onclick="switchChatChannel('group')">
                🤝 Study Pod
            </button>
            <button type="button" class="chat-tab-btn" id="chatTab-project" onclick="switchChatChannel('project')">
                🚀 Project Team
            </button>
        </div>

        <!-- Chat Channel 1: Peer Teaching & Concept Chat -->
        <div id="chatView-peer" class="chat-view-body" style="flex: 1; padding: 14px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; background: var(--bg-canvas);">
            <div style="text-align: center; margin-bottom: 6px;">
                <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 10px;">#peer-tutoring-channel</span>
            </div>
            <!-- Message from Peer Diya -->
            <div style="display: flex; gap: 8px; align-items: flex-start;">
                <div style="width: 28px; height: 28px; border-radius: 50%; background: #0284c7; color: white; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0;">D</div>
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 0 12px 12px 12px; padding: 8px 12px; max-width: 80%;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                        <strong style="font-size: 11.5px; color: #0284c7;">Diya (26CSE002)</strong>
                        <small style="font-size: 9.5px; color: var(--text-muted); margin-left: 6px;">10:14 AM</small>
                    </div>
                    <p style="font-size: 12px; color: var(--text-main); margin: 0;">Anyone free to do a quick 5-min peer walkthrough on Master Theorem Case 2?</p>
                </div>
            </div>
            <!-- Message from Peer Rohan -->
            <div style="display: flex; gap: 8px; align-items: flex-start;">
                <div style="width: 28px; height: 28px; border-radius: 50%; background: #6366f1; color: white; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0;">R</div>
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 0 12px 12px 12px; padding: 8px 12px; max-width: 80%;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                        <strong style="font-size: 11.5px; color: var(--primary);">Rohan (Peer Tutor)</strong>
                        <small style="font-size: 9.5px; color: var(--text-muted); margin-left: 6px;">10:15 AM</small>
                    </div>
                    <p style="font-size: 12px; color: var(--text-main); margin: 0;">Sure Diya! Check my post in the Q&A thread, basically when $f(n) = \Theta(n^{\log_b a})$, add $\log n$.</p>
                </div>
            </div>
        </div>

        <!-- Chat Channel 2: Group Teaching & Pod Delta -->
        <div id="chatView-group" class="chat-view-body" style="flex: 1; padding: 14px; overflow-y: auto; display: none; flex-direction: column; gap: 10px; background: var(--bg-canvas);">
            <div style="text-align: center; margin-bottom: 6px;">
                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #059669; font-size: 10px;">#pod-delta-study-group</span>
            </div>
            <div style="display: flex; gap: 8px; align-items: flex-start;">
                <div style="width: 28px; height: 28px; border-radius: 50%; background: #059669; color: white; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0;">S</div>
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 0 12px 12px 12px; padding: 8px 12px; max-width: 80%;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                        <strong style="font-size: 11.5px; color: #059669;">Sneha</strong>
                        <small style="font-size: 9.5px; color: var(--text-muted); margin-left: 6px;">09:50 AM</small>
                    </div>
                    <p style="font-size: 12px; color: var(--text-main); margin: 0;">Pod Delta team: Don't forget our problem solving lab sync at 4 PM!</p>
                </div>
            </div>
        </div>

        <!-- Chat Channel 3: Project-Based Learning Capstone -->
        <div id="chatView-project" class="chat-view-body" style="flex: 1; padding: 14px; overflow-y: auto; display: none; flex-direction: column; gap: 10px; background: var(--bg-canvas);">
            <div style="text-align: center; margin-bottom: 6px;">
                <span class="badge" style="background: rgba(217, 119, 6, 0.1); color: #d97706; font-size: 10px;">#capstone-project-sprint</span>
            </div>
            <div style="display: flex; gap: 8px; align-items: flex-start;">
                <div style="width: 28px; height: 28px; border-radius: 50%; background: #d97706; color: white; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0;">K</div>
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 0 12px 12px 12px; padding: 8px 12px; max-width: 80%;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                        <strong style="font-size: 11.5px; color: #d97706;">Karan</strong>
                        <small style="font-size: 9.5px; color: var(--text-muted); margin-left: 6px;">Yesterday</small>
                    </div>
                    <p style="font-size: 12px; color: var(--text-main); margin: 0;">Pushed the container compose file for Sprint 2 to our Git repo.</p>
                </div>
            </div>
        </div>

        <!-- Messenger Input Bar -->
        <div style="padding: 10px 14px; border-top: 1px solid var(--border); background: var(--surface); display: flex; gap: 8px; align-items: center;">
            <input type="text" id="messengerTextInput" placeholder="Type a message to peers..." style="flex: 1; border: 1px solid var(--border); border-radius: 20px; padding: 8px 14px; font-size: 12.5px; background: var(--bg-canvas); color: var(--text-main); outline: none;" onkeydown="if(event.key==='Enter') sendChatMessage()">
            <button type="button" onclick="sendChatMessage()" style="width: 34px; height: 34px; border-radius: 50%; background: var(--primary); color: white; border: none; display: flex; align-items: center; justify-content: center; font-size: 13px; cursor: pointer; flex-shrink: 0; box-shadow: 0 2px 8px var(--primary-glow);">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <!-- Floating Trigger Button -->
    <button type="button" onclick="toggleMessenger()" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; border: none; padding: 12px 20px; border-radius: 30px; font-size: 13px; font-weight: 700; box-shadow: 0 8px 24px rgba(99, 102, 241, 0.4); cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);">
        <i class="fa-solid fa-comments fa-lg"></i>
        <span>Peer & Pod Chat</span>
        <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981; box-shadow: 0 0 6px #10b981;"></span>
    </button>
</div>

<!-- =========================================================================
     INTERACTIVE LECTURE SLIDES & STUDY NOTES VIEWER MODAL
========================================================================= -->
<div class="sidebar-backdrop" id="slidesModal" style="display: none; position: fixed; inset: 0; z-index: 1050; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 20px;">
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; width: 100%; max-width: 820px; max-height: 85vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 24px 50px rgba(0,0,0,0.35); animation: zoomIn 0.25s ease-out;">
        <!-- Header -->
        <div style="padding: 18px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: var(--bg-canvas);">
            <div>
                <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11px; font-weight: 700; margin-bottom: 4px;">
                    📖 Lecture Companion Slides & Notes
                </span>
                <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0; color: var(--text-main);">
                    <?= esc($activeLesson['lesson_title'] ?? 'Lecture Companion') ?>
                </h3>
            </div>
            <button type="button" onclick="closeSlidesModal()" style="background: transparent; border: none; font-size: 18px; color: var(--text-muted); cursor: pointer; padding: 4px 8px; border-radius: 8px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Body with Slide Content Tabs -->
        <div style="padding: 24px; overflow-y: auto; flex: 1; display: flex; flex-direction: column; gap: 18px;">
            <!-- Slide 1: Core Concepts & Architecture -->
            <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 14px; padding: 18px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span style="font-weight: 800; font-size: 13.5px; color: var(--primary);">
                        <i class="fa-solid fa-layer-group me-1"></i> Slide 1: Core Theoretical Foundations
                    </span>
                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success);">Key Takeaways</span>
                </div>
                <ul style="padding-left: 20px; font-size: 13px; color: var(--text-main); line-height: 1.6; margin: 0;">
                    <li><strong>Concept Overview</strong>: Modular decomposition of state management and asynchronous lifecycle pipelines.</li>
                    <li><strong>Design Rationale</strong>: Enforces strict single-responsibility principles across decoupled backend services.</li>
                    <li><strong>Complexity & Efficiency</strong>: Guarantees optimized logarithmic $\mathcal{O}(\log N)$ execution time and sub-millisecond API response benchmarks.</li>
                </ul>
            </div>

            <!-- Slide 2: Technical Tools & Developer Environment -->
            <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 14px; padding: 18px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span style="font-weight: 800; font-size: 13.5px; color: #0284C7;">
                        <i class="fa-solid fa-terminal me-1"></i> Slide 2: Technical Tools & Command Reference
                    </span>
                    <span class="badge" style="background: rgba(2, 132, 199, 0.1); color: #0284C7;">Tooling Guide</span>
                </div>
                <div style="background: #0f172a; color: #38bdf8; font-family: monospace; padding: 12px 16px; border-radius: 8px; font-size: 12px; margin-bottom: 10px; line-height: 1.5;">
                    # 1. Start Local Containerized Environment<br>
                    $ docker-compose up -d --build<br><br>
                    # 2. Test Endpoint Health in Postman / Terminal<br>
                    $ curl -X GET http://localhost:8080/api/v1/healthcheck
                </div>
                <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">
                    Integrates directly with <strong>VS Code Extensions</strong>, <strong>Postman Workspaces</strong>, and <strong>Docker Container Orchestration</strong>.
                </p>
            </div>

            <!-- Slide 3: Pedagogy & Collaborative Assessment Rubric -->
            <div style="background: var(--bg-canvas); border: 1px solid var(--border); border-radius: 14px; padding: 18px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span style="font-weight: 800; font-size: 13.5px; color: #d97706;">
                        <i class="fa-solid fa-graduation-cap me-1"></i> Slide 3: Active Learning Pedagogy & OBE CO-Mapping
                    </span>
                    <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #d97706;">Peer Learning</span>
                </div>
                <p style="font-size: 13px; color: var(--text-main); margin: 0 0 8px;">
                    This module aligns with <strong>Course Outcome CO2 & Program Outcome PO5 (Modern Tool Usage)</strong>.
                </p>
                <div style="font-size: 12px; color: var(--text-muted);">
                    <strong>Peer Activity</strong>: Review partner pull requests on GitHub and verify container health logs before the live assessment checkpoint.
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div style="padding: 14px 24px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: var(--bg-canvas);">
            <div style="font-size: 12px; color: var(--text-muted);">
                <i class="fa-solid fa-circle-check text-success me-1"></i> Verified by Course Instructor
            </div>
            <button type="button" onclick="closeSlidesModal()" class="btn btn-primary" style="padding: 6px 18px; font-size: 12.5px; border-radius: 10px;">
                Done Reading
            </button>
        </div>
    </div>
</div>

<style>
.lesson-tab-btn {
    flex: 1;
    padding: 14px 18px;
    background: transparent;
    border: none;
    border-bottom: 2px solid transparent;
    font-size: 13.5px;
    font-weight: 600;
    color: var(--text-muted);
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
}
.lesson-tab-btn:hover {
    color: var(--text-main);
    background: rgba(99, 102, 241, 0.04);
}
.lesson-tab-btn.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
    background: var(--surface);
    font-weight: 700;
}

.chat-tab-btn {
    flex: 1;
    padding: 8px 6px;
    background: transparent;
    border: none;
    border-bottom: 2px solid transparent;
    font-size: 11.5px;
    font-weight: 700;
    color: var(--text-muted);
    cursor: pointer;
    transition: all 0.15s ease;
}
.chat-tab-btn.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
    background: var(--surface);
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes zoomIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
</style>

<script>
let currentChatChannel = 'peer';

function switchTab(tabKey) {
    document.querySelectorAll('.lesson-tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.lesson-tab-content').forEach(c => c.style.display = 'none');

    const activeBtn = document.getElementById(`tabBtn-${tabKey}`);
    const activeContent = document.getElementById(`tabContent-${tabKey}`);

    if (activeBtn) activeBtn.classList.add('active');
    if (activeContent) activeContent.style.display = 'block';
}

function toggleMessenger() {
    const drawer = document.getElementById('messengerDrawer');
    if (drawer) {
        if (drawer.style.display === 'none' || drawer.style.display === '') {
            drawer.style.display = 'flex';
            setTimeout(() => {
                const input = document.getElementById('messengerTextInput');
                if (input) input.focus();
            }, 100);
        } else {
            drawer.style.display = 'none';
        }
    }
}

function switchChatChannel(chKey) {
    currentChatChannel = chKey;
    document.querySelectorAll('.chat-tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.chat-view-body').forEach(v => v.style.display = 'none');

    const activeBtn = document.getElementById(`chatTab-${chKey}`);
    const activeView = document.getElementById(`chatView-${chKey}`);

    if (activeBtn) activeBtn.classList.add('active');
    if (activeView) activeView.style.display = 'flex';
}

function sendChatMessage() {
    const input = document.getElementById('messengerTextInput');
    const text = input ? input.value.trim() : '';
    if (!text) return;

    const activeView = document.getElementById(`chatView-${currentChatChannel}`);
    if (!activeView) return;

    const now = new Date();
    const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    const msgEl = document.createElement('div');
    msgEl.style.cssText = 'display: flex; gap: 8px; align-items: flex-start; justify-content: flex-end;';
    msgEl.innerHTML = `
        <div style="background: var(--primary); color: white; border-radius: 12px 0 12px 12px; padding: 8px 12px; max-width: 80%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                <strong style="font-size: 11px; opacity: 0.9;">You</strong>
                <small style="font-size: 9.5px; opacity: 0.8; margin-left: 6px;">${timeStr}</small>
            </div>
            <p style="font-size: 12px; margin: 0; color: white;">` + $('<div>').text(text).html() + `</p>
        </div>
    `;

    activeView.appendChild(msgEl);
    activeView.scrollTop = activeView.scrollHeight;
    input.value = '';
}

function postQaQuestion() {
    const input = document.getElementById('qaInput');
    const text = input ? input.value.trim() : '';
    if (!text) {
        alert('Please write your question first.');
        return;
    }

    const container = document.getElementById('qaThreadsContainer');
    const newThread = document.createElement('div');
    newThread.style.cssText = 'border: 1px solid var(--border); border-radius: 14px; padding: 16px 18px; background: var(--bg-canvas); animation: fadeInUp 0.3s ease;';
    
    newThread.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: #6366f1; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px;">
                    <?= strtoupper(substr(session('first_name') ?: 'A', 0, 1)) ?>
                </div>
                <div>
                    <strong style="font-size: 13px; color: var(--text-main);"><?= esc(session('first_name') ?: 'Aarav Patel') ?></strong>
                    <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 10.5px; margin-left: 6px;"><?= esc(session('roll_number') ?: '26CSE001') ?></span>
                </div>
            </div>
            <small style="color: var(--text-muted); font-size: 11px;">Just now &bull; <span class="text-primary fw-bold">Live Question</span></small>
        </div>
        <p style="font-size: 13px; color: var(--text-main); line-height: 1.5; margin: 0;">
            ❓ ` + $('<div>').text(text).html() + `
        </p>
    `;

    if (container) {
        container.insertBefore(newThread, container.firstChild);
    }
    input.value = '';
}

function openSlidesModal() {
    const modal = document.getElementById('slidesModal');
    if (modal) {
        modal.style.display = 'flex';
    }
}

function closeSlidesModal() {
    const modal = document.getElementById('slidesModal');
    if (modal) {
        modal.style.display = 'none';
    }
}
</script>
<?= $this->endSection() ?>
