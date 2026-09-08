<?= $this->extend('lms/career/layout') ?>
<?= $this->section('page_title') ?>Study Materials & Video Courses<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="lms-page-header">
    <h1><i class="fa-solid fa-play-circle me-2" style="color: var(--primary);"></i> Learning & Content Library</h1>
    <p>Access video masterclasses, curriculum lectures, and subject study notes.</p>
</div>

<!-- Published Video Courses Section -->
<?php if (!empty($publishedCourses)): ?>
    <div style="margin-bottom: 40px;">
        <h2 style="margin: 0 0 20px; font-size: 20px; color: var(--primary); display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-clapperboard"></i> Video Courses & Masterclasses
        </h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px;">
            <?php foreach ($publishedCourses as $c): ?>
                <div class="card" style="display: flex; flex-direction: column; overflow: hidden; padding: 0; border: 1px solid var(--border-color); border-radius: 16px;">
                    <?php if (!empty($c['thumbnail'])): ?>
                        <img src="<?= base_url($c['thumbnail']) ?>" style="width: 100%; height: 160px; object-fit: cover;" alt="Thumbnail">
                    <?php else: ?>
                        <div style="width: 100%; height: 160px; background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 40px;">
                            <i class="fa-solid fa-film"></i>
                        </div>
                    <?php endif; ?>

                    <div style="padding: 20px; display: flex; flex-direction: column; flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <span class="badge" style="background: rgba(79, 70, 229, 0.1); color: var(--primary); font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 20px;">
                                <?= esc($c['category'] ?: 'General') ?>
                            </span>
                            <small style="color: var(--text-muted); font-size: 12px;"><i class="fa-solid fa-clock me-1"></i> <?= $c['total_duration_minutes'] ?> mins</small>
                        </div>

                        <h3 style="margin: 0 0 8px; font-size: 17px; line-height: 1.4; font-weight: 700; color: var(--text-primary);">
                            <?= esc($c['title']) ?>
                        </h3>

                        <p style="font-size: 13px; color: var(--text-muted); margin: 0 0 16px; line-height: 1.5; flex-grow: 1;">
                            <?= esc(character_limiter($c['description'] ?? '', 85)) ?>
                        </p>

                        <div style="margin-top: auto; padding-top: 14px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                            <small style="color: var(--text-muted);"><i class="fa-solid fa-play-circle me-1"></i> <?= $c['lesson_count'] ?> Lessons (<?= $c['chapter_count'] ?> Ch)</small>
                            <a href="<?= base_url('lms/materials/watch/' . ($c['uuid'] ?? $c['id'])) ?>" class="btn btn-primary" style="padding: 8px 18px; font-size: 13px; border-radius: 20px;">
                                Watch Now <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Subject Study Materials Section -->
<h2 style="margin: 0 0 20px; font-size: 20px; color: var(--primary); display: flex; align-items: center; gap: 8px;">
    <i class="fa-solid fa-book-open"></i> Subject Study Materials
</h2>

<?php if(empty($grouped_materials)): ?>
    <div class="card" style="text-align: center; padding: 40px 20px; color: var(--text-muted); border-radius: 16px;">
        <div style="font-size: 40px; margin-bottom: 12px; opacity: 0.5;"><i class="fa-solid fa-folder-open"></i></div>
        <h4 style="margin: 0 0 4px; color: var(--text-primary);">No Faculty Notes Uploaded Yet</h4>
        <p style="margin: 0; font-size: 14px;">Your course instructors have not uploaded direct class documents for this semester yet.</p>
    </div>
<?php else: ?>
    <?php foreach($grouped_materials as $subject_name => $materials): ?>
        <div style="margin-bottom: 32px;">
            <h3 style="margin: 0 0 16px; font-size: 17px; color: var(--text-primary); display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-file-lines" style="color: var(--primary);"></i> <?= esc($subject_name) ?>
            </h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                <?php foreach($materials as $m): ?>
                    <div class="card" style="display: flex; flex-direction: column; border-radius: 12px;">
                        <div style="display: flex; gap: 16px; margin-bottom: 16px;">
                            <?php if($m['type'] === 'file'): ?>
                                <div style="width: 44px; height: 44px; border-radius: 8px; background: rgba(79, 70, 229, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </div>
                            <?php elseif($m['type'] === 'youtube'): ?>
                                <div style="width: 44px; height: 44px; border-radius: 8px; background: rgba(239, 68, 68, 0.1); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                                    <i class="fa-brands fa-youtube"></i>
                                </div>
                            <?php else: ?>
                                <div style="width: 44px; height: 44px; border-radius: 8px; background: rgba(100, 116, 139, 0.1); color: var(--text-primary); display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                                    <i class="fa-solid fa-link"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div>
                                <h4 style="margin: 0 0 4px; font-size: 15px; line-height: 1.3; font-weight: 600;"><?= esc($m['title']) ?></h4>
                                <div style="font-size: 12px; color: var(--text-muted);">Uploaded <?= date('d/m/Y', strtotime($m['created_at'])) ?></div>
                            </div>
                        </div>

                        <?php if($m['description']): ?>
                            <p style="font-size: 13px; color: var(--text-muted); margin: 0 0 16px; line-height: 1.5; flex-grow: 1;">
                                <?= nl2br(esc($m['description'])) ?>
                            </p>
                        <?php else: ?>
                            <div style="flex-grow: 1;"></div>
                        <?php endif; ?>

                        <div style="margin-top: auto; padding-top: 14px; border-top: 1px solid var(--border-color);">
                            <?php if($m['type'] === 'file' && $m['file_path']): ?>
                                <a href="<?= base_url($m['file_path']) ?>" target="_blank" class="btn btn-primary" style="width: 100%; font-size: 13px;"><i class="fa-solid fa-download"></i> Download File</a>
                            <?php elseif($m['external_url']): ?>
                                <a href="<?= esc($m['external_url']) ?>" target="_blank" class="btn btn-primary" style="width: 100%; font-size: 13px;"><i class="fa-solid fa-arrow-up-right-from-square"></i> Open Resource</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>
