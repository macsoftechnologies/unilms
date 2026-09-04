<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Library Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <h2>Library Management Dashboard</h2>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="kpi-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); text-align: center;">
            <i class="fa-solid fa-book" style="font-size: 32px; color: #0056b3; margin-bottom: 10px;"></i>
            <h3><?= esc($total_books) ?></h3>
            <p style="color: #666; font-size: 14px;">Total Books</p>
        </div>
        <div class="kpi-card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); text-align: center;">
            <i class="fa-solid fa-hand-holding-hand" style="font-size: 32px; color: #e67e22; margin-bottom: 10px;"></i>
            <h3><?= esc($total_issued) ?></h3>
            <p style="color: #666; font-size: 14px;">Currently Issued</p>
        </div>
    </div>
    
    <div class="quick-actions" style="display: flex; gap: 10px;">
        <a href="<?= base_url('org/library/books') ?>" class="btn btn-outline">Manage Books</a>
        <a href="<?= base_url('org/library/members') ?>" class="btn btn-outline">Manage Members</a>
        <a href="<?= base_url('org/library/issues') ?>" class="btn btn-primary">Issue Book</a>
    </div>
</section>
<?= $this->endSection() ?>
