<?= $this->extend('parent/layout') ?>
<?= $this->section('page_title') ?>Login<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div style="max-width: 400px; margin: 80px auto;">
    <div class="card" style="text-align: center; padding: 40px 24px;">
        <div style="background: var(--primary); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
            <i class="fa-solid fa-user-group" style="color: white; font-size: 24px;"></i>
        </div>
        <h1 style="margin-top: 0; font-size: 24px;">Parent Portal Login</h1>
        <p style="color: var(--text-muted); margin-bottom: 24px;">Sign in to view your child's progress.</p>
        
        <form action="<?= base_url('parent/authenticate') ?>" method="POST" style="text-align: left;">
            <?= csrf_field() ?>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px;">Registered Mobile No. or Child's Roll Number</label>
                <input type="text" name="parent_identifier" placeholder="e.g. 9876543210 or STU-2026-0001" required autofocus style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; box-sizing: border-box;">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 16px;">Login to Portal</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
