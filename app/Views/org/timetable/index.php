<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Timetable Templates<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 class="header-title">Timetable Configuration</h1>
            <p class="header-subtitle">Create master schedule templates and build class timetables.</p>
        </div>
        <div>
            <a href="<?= base_url('org/timetable/builder') ?>" class="btn btn-outline" style="background: white;"><i class="fa-solid fa-table-cells"></i> Open Timetable Builder</a>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    
    <!-- Template List -->
    <div>
        <div class="card">
            <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Master Templates</h2>
            <?php if(empty($templates)): ?>
                <p style="color: var(--text-muted); text-align: center; padding: 20px;">No templates created. Create one on the right.</p>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Template Name</th>
                            <th>Description</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($templates as $t): ?>
                            <tr>
                                <td style="font-weight: 600; color: var(--primary);"><?= esc($t['name']) ?></td>
                                <td style="font-size: 13px;"><?= esc($t['description']) ?></td>
                                <td style="text-align: right;">
                                    <a href="<?= base_url('org/timetable/periods/'.$t['id']) ?>" class="btn btn-primary" style="padding: 4px 10px; font-size: 12px;"><i class="fa-solid fa-clock"></i> Setup Periods</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Create Template Form -->
    <div>
        <div class="card">
            <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Create New Template</h2>
            <form action="<?= base_url('org/timetable/templates/create') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label>Template Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Standard B.Tech Schedule" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fa-solid fa-plus"></i> Create Template</button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
