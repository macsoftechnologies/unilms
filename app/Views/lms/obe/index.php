<?= $this->extend('lms/layout') ?>
<?= $this->section('page_title') ?>Outcome-Based Education (OBE) Matrix<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="lms-page-header">
    <h1><i class="fa-solid fa-bullseye me-2" style="color: var(--primary);"></i> Outcome-Based Education (OBE) Matrix</h1>
    <p>Official NBA/NAAC accreditation framework mapping your graduate competencies (POs/PSOs) and Subject-level Course Outcomes (COs).</p>
</div>

<!-- Program Outcomes (POs) Grid -->
<div class="card" style="margin-bottom: 24px; padding: 24px; border: 1px solid var(--border); border-radius: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; margin: 0; color: var(--text-main);">
            <i class="fa-solid fa-award me-2" style="color: var(--primary);"></i> Program Outcomes (POs & PSOs)
        </h2>
        <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 11.5px; padding: 4px 10px; border-radius: 12px; font-weight: 700;">
            Washington Accord Tier-1
        </span>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px;">
        <?php if(!empty($pos)): foreach($pos as $po): ?>
        <?php 
            $parts = explode(':', $po['description'] ?? '', 2);
            $poTitle = count($parts) > 1 ? trim($parts[0]) : ($po['title'] ?? 'Program Outcome');
            $poDesc = count($parts) > 1 ? trim($parts[1]) : ($po['description'] ?? '');
        ?>
        <div style="border: 1px solid var(--border); border-radius: 12px; padding: 18px; background: var(--bg-canvas); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 15px; color: var(--primary);"><?= esc($po['code'] ?? 'PO1') ?></span>
                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 8px;">
                        Accredited
                    </span>
                </div>
                <div style="font-weight: 700; font-size: 13.5px; margin-bottom: 6px; color: var(--text-main);"><?= esc($poTitle) ?></div>
                <p style="font-size: 12.5px; color: var(--text-muted); line-height: 1.5; margin: 0;"><?= esc($poDesc) ?></p>
            </div>
        </div>
        <?php endforeach; else: ?>
            <div style="border: 1px solid var(--border); border-radius: 12px; padding: 18px; background: var(--bg-canvas);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 15px; color: var(--primary);">PO1: Engineering Knowledge</span>
                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 8px;">Accredited</span>
                </div>
                <p style="font-size: 12.5px; color: var(--text-muted); line-height: 1.5; margin: 0;">Apply mathematics, computing models, and foundational algorithms to solve engineering challenges.</p>
            </div>
            <div style="border: 1px solid var(--border); border-radius: 12px; padding: 18px; background: var(--bg-canvas);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 15px; color: var(--primary);">PO2: Problem Analysis</span>
                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 8px;">Accredited</span>
                </div>
                <p style="font-size: 12.5px; color: var(--text-muted); line-height: 1.5; margin: 0;">Identify, research, and analyze substantial engineering problems reaching substantiated conclusions.</p>
            </div>
            <div style="border: 1px solid var(--border); border-radius: 12px; padding: 18px; background: var(--bg-canvas);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 15px; color: var(--primary);">PO3: Design & Development</span>
                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 8px;">Accredited</span>
                </div>
                <p style="font-size: 12.5px; color: var(--text-muted); line-height: 1.5; margin: 0;">Design complex software architectures and system processes meeting safety and scalability standards.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Course Outcomes (COs) by Subject -->
<div class="card" style="padding: 24px; border: 1px solid var(--border); border-radius: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; margin: 0; color: var(--text-main);">
            <i class="fa-solid fa-book-open me-2" style="color: var(--primary);"></i> Enrolled Subject Course Outcomes (COs)
        </h2>
    </div>

    <?php if(!empty($subjects)): foreach($subjects as $sub): ?>
    <div style="border: 1px solid var(--border); border-radius: 12px; padding: 18px; margin-bottom: 16px; background: var(--surface);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
            <h3 style="font-family: 'Outfit', sans-serif; margin: 0; font-size: 15px; font-weight: 800; color: var(--text-main);">
                <?= esc($sub['name']) ?>
                <span style="font-size: 12.5px; color: var(--primary); font-weight: 700;">(<?= esc($sub['code'] ?? '') ?>)</span>
            </h3>
            <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); padding: 4px 10px; border-radius: 12px; font-weight: 700; font-size: 11px;">
                <?= count($sub['cos'] ?? []) ?> Defined COs
            </span>
        </div>

        <?php if(!empty($sub['cos'])): ?>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <?php foreach($sub['cos'] as $co): ?>
                <div style="background: var(--bg-canvas); padding: 12px 16px; border-radius: 8px; display: flex; gap: 14px; align-items: flex-start; border: 1px solid var(--border);">
                    <span style="font-weight: 800; color: var(--primary); font-size: 12.5px; white-space: nowrap;">
                        <?= esc($co['code'] ?? $co['co_code'] ?? 'CO-1') ?>
                    </span>
                    <div style="flex: 1; font-size: 13px; color: var(--text-main); line-height: 1.5;">
                        <?= esc($co['description'] ?? $co['title'] ?? '') ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="background: var(--bg-canvas); padding: 12px 16px; border-radius: 8px; display: flex; flex-direction: column; gap: 8px; border: 1px solid var(--border);">
                <div style="display: flex; gap: 14px; align-items: flex-start;">
                    <span style="font-weight: 800; color: var(--primary); font-size: 12.5px; white-space: nowrap;">CO1</span>
                    <div style="flex: 1; font-size: 13px; color: var(--text-main); line-height: 1.5;">Understand core fundamentals, mathematical abstractions, and syntax paradigms.</div>
                </div>
                <div style="display: flex; gap: 14px; align-items: flex-start;">
                    <span style="font-weight: 800; color: var(--primary); font-size: 12.5px; white-space: nowrap;">CO2</span>
                    <div style="flex: 1; font-size: 13px; color: var(--text-main); line-height: 1.5;">Apply algorithmic data structures to optimize computation time and memory complexity.</div>
                </div>
                <div style="display: flex; gap: 14px; align-items: flex-start;">
                    <span style="font-weight: 800; color: var(--primary); font-size: 12.5px; white-space: nowrap;">CO3</span>
                    <div style="flex: 1; font-size: 13px; color: var(--text-main); line-height: 1.5;">Construct and evaluate laboratory experiments, proving empirical benchmark properties.</div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php endforeach; else: ?>
        <div style="text-align: center; padding: 36px; color: var(--text-muted);">
            <i class="fa-solid fa-bullseye" style="font-size: 32px; opacity: 0.3; margin-bottom: 10px; display: block;"></i>
            <p style="margin: 0; font-size: 13.5px;">No active semester subjects found in current curriculum.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
