<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Administration<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Administration Setup</h1>
        <p class="header-subtitle">Manage organization-level settings, locations, holidays, and bank details.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;">
    
    <div class="card" style="text-align: center; cursor: pointer; transition: transform 0.2s;" onclick="window.location='<?= base_url('org/administration/bank-details') ?>'">
        <i class="fa-solid fa-building-columns" style="font-size: 36px; color: var(--primary); margin-bottom: 16px;"></i>
        <h2 style="margin: 0 0 8px; font-size: 18px;">Bank Details</h2>
        <p style="color: #666; font-size: 14px;">Manage organization bank accounts.</p>
    </div>
    
    <div class="card" style="text-align: center; cursor: pointer; transition: transform 0.2s;" onclick="window.location='<?= base_url('org/administration/locations') ?>'">
        <i class="fa-solid fa-map-location-dot" style="font-size: 36px; color: var(--primary); margin-bottom: 16px;"></i>
        <h2 style="margin: 0 0 8px; font-size: 18px;">Locations</h2>
        <p style="color: #666; font-size: 14px;">Manage campuses, buildings, and rooms.</p>
    </div>
    
    <div class="card" style="text-align: center; cursor: pointer; transition: transform 0.2s;" onclick="window.location='<?= base_url('org/administration/agents') ?>'">
        <i class="fa-solid fa-users-viewfinder" style="font-size: 36px; color: var(--primary); margin-bottom: 16px;"></i>
        <h2 style="margin: 0 0 8px; font-size: 18px;">Agents / Counselors</h2>
        <p style="color: #666; font-size: 14px;">Manage admission agents.</p>
    </div>
    
    <div class="card" style="text-align: center; cursor: pointer; transition: transform 0.2s;" onclick="window.location='<?= base_url('org/administration/holidays') ?>'">
        <i class="fa-solid fa-umbrella-beach" style="font-size: 36px; color: var(--primary); margin-bottom: 16px;"></i>
        <h2 style="margin: 0 0 8px; font-size: 18px;">Holidays</h2>
        <p style="color: #666; font-size: 14px;">Define academic and public holidays.</p>
    </div>

</div>

<?= $this->endSection() ?>
