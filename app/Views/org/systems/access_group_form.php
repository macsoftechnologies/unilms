<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?><?= $group ? 'Edit Access Group' : 'Create Access Group' ?><?= $this->endSection() ?>
<?= $this->section('content') ?>

<style>
.perm-card {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: var(--bg-main);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s ease;
    user-select: none;
    color: var(--text-muted);
}
.perm-card:hover {
    border-color: var(--primary);
    background: rgba(109, 40, 217, 0.02);
}
.perm-card.active {
    border-color: var(--primary);
    background: rgba(109, 40, 217, 0.08);
    color: var(--primary);
    font-weight: 600;
}
.perm-card input[type="checkbox"] {
    display: none;
}
.perm-indicator {
    width: 18px;
    height: 18px;
    border-radius: 4px;
    border: 2px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}
.perm-card.active .perm-indicator {
    background: var(--primary);
    border-color: var(--primary);
}
.perm-card.active .perm-indicator::after {
    content: '\f00c';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    color: white;
    font-size: 10px;
}
.module-header {
    background: linear-gradient(135deg, rgba(109, 40, 217, 0.05) 0%, rgba(109, 40, 217, 0.01) 100%);
    padding: 16px 20px;
    border-radius: 10px;
    margin-bottom: 24px;
    border: 1px solid rgba(109, 40, 217, 0.1);
    display: flex;
    align-items: center;
    gap: 12px;
}
.module-header i {
    font-size: 20px;
    color: var(--primary);
}
.module-header h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: var(--text-main);
    text-transform: uppercase;
    letter-spacing: 1px;
}
.area-section {
    margin-bottom: 32px;
    padding-left: 16px;
    border-left: 2px solid var(--border-color);
}
.area-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.btn-toggle-area {
    background: transparent;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
    padding: 4px 10px;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-toggle-area:hover {
    background: var(--bg-main);
    color: var(--text-main);
    border-color: var(--text-muted);
}
.grid-layout {
    display: grid; 
    grid-template-columns: 350px 1fr; 
    gap: 32px;
    align-items: start;
}
@media (max-width: 1024px) {
    .grid-layout {
        grid-template-columns: 1fr;
    }
}
.search-container {
    background: var(--bg-main);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 10px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
}
.search-container input {
    border: none;
    background: transparent;
    width: 100%;
    outline: none;
    font-size: 14px;
}
.area-title { cursor: pointer; }
.area-chevron { transition: transform 0.3s ease; }
.area-collapsed .area-chevron { transform: rotate(-90deg); }
</style>

<section class="view-section active">
<div class="view-header">
    <div>
        <a href="<?= base_url('org/systems/access-groups') ?>" style="color: var(--text-muted); text-decoration: none; font-size: 14px; margin-bottom: 8px; display: inline-block;"><i class="fa-solid fa-arrow-left"></i> Back to Access Groups</a>
        <h2 style="margin: 0;"><?= $group ? 'Edit Access Group: <span style="color:var(--primary);">' . esc($group['name']) . '</span>' : 'Access Group Settings' ?></h2>
    </div>
</div>

<form action="<?= base_url('org/systems/access-groups/save') ?>" method="POST">
    <?= csrf_field() ?>
    <?php if($group): ?>
        <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
    <?php endif; ?>
    
    <div class="grid-layout">
        
        <!-- Left: Sticky Group Info -->
        <div style="position: sticky; top: 100px;">
            <div class="widget" style="border: 1px solid rgba(0,0,0,0.05);">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(109, 40, 217, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 20px;">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 18px;">Group Settings</h3>
                <div class="form-group">
                    <label style="font-weight: 600;">Group Name <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" class="form-control" required value="<?= $group ? esc($group['name']) : '' ?>" placeholder="e.g. Finance Team, Professors">
                </div>
                <div class="form-group">
                    <label style="font-weight: 600;">Description</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Briefly describe what members of this group are allowed to do."><?= $group ? esc($group['description']) : '' ?></textarea>
                </div>
                
                <?php if(!$group && !empty($all_groups)): ?>
                <div class="form-group" style="margin-top: 24px;">
                    <label style="font-weight: 600;"><i class="fa-solid fa-clone" style="color:var(--primary)"></i> Clone Permissions</label>
                    <select class="form-control" onchange="clonePermissions(this.value)">
                        <option value="">-- Start from scratch --</option>
                        <?php foreach($all_groups as $g): ?>
                            <option value="<?= $g['id'] ?>"><?= esc($g['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                
                <div style="margin-top: 32px; padding-top: 24px; border-top: 1px dashed var(--border-color);">
                    <p style="font-size: 12px; color: var(--text-muted); text-align: center; line-height: 1.5;">
                        Carefully select the exact permissions on the right. Staff assigned to this group will inherit all checked privileges immediately.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Right: Permissions Checklist -->
        <div class="widget" style="border: 1px solid rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--border-color);">
                <h3 style="margin: 0; font-size: 18px;">Select Permissions</h3>
                <div style="display: flex; gap: 8px;">
                    <button type="button" class="btn btn-outline" onclick="selectAll()" style="padding: 6px 12px; font-size: 12px; font-weight: 600;"><i class="fa-solid fa-check-double"></i> Select All</button>
                    <button type="button" class="btn btn-outline" onclick="deselectAll()" style="padding: 6px 12px; font-size: 12px; font-weight: 600;"><i class="fa-solid fa-eraser"></i> Clear</button>
                </div>
            </div>
            
            <div class="search-container">
                <i class="fa-solid fa-search" style="color: var(--text-muted)"></i>
                <input type="text" id="permSearch" placeholder="Search permissions (e.g. Upload, Create, View)..." onkeyup="filterPermissions()">
            </div>
            
            <?php
            // Group permissions by module_area
            $grouped = [];
            foreach ($permissions as $perm) {
                $grouped[$perm['module']][$perm['module_area']][] = $perm;
            }
            ?>
            
            <?php foreach($grouped as $module => $areas): ?>
                <div style="margin-bottom: 40px;">
                    <div class="module-header">
                        <i class="<?= $module == 'cms' ? 'fa-solid fa-school' : 'fa-solid fa-laptop-code' ?>"></i>
                        <h4><?= $module == 'cms' ? 'CMS — College Management' : 'LMS — Learning System' ?></h4>
                    </div>
                    
                    <?php foreach($areas as $area => $perms): ?>
                        <div class="area-section">
                            <div class="area-title area-collapsed" onclick="toggleAccordion(this)">
                                <div>
                                    <i class="fa-solid fa-chevron-down area-chevron" style="margin-right: 8px;"></i>
                                    <span><?= str_replace('_', ' ', $area) ?></span>
                                </div>
                                <button type="button" class="btn-toggle-area" onclick="event.stopPropagation(); toggleArea('<?= $module . '_' . $area ?>')">Toggle All</button>
                            </div>
                            <div class="area-grid-wrapper" style="display: none;">
                                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 12px;" class="area-grid area-<?= $module . '_' . $area ?>">
                                    <?php foreach($perms as $perm): ?>
                                        <?php $isChecked = in_array($perm['id'], $assigned_perms); ?>
                                        <label class="perm-card <?= $isChecked ? 'active' : '' ?>" onclick="syncCardState(this)">
                                            <div class="perm-indicator"></div>
                                            <input type="checkbox" name="permission_ids[]" value="<?= $perm['id'] ?>" class="perm-checkbox" <?= $isChecked ? 'checked' : '' ?>>
                                            <span><?= esc($perm['label']) ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div style="position: fixed; bottom: 0; left: 250px; right: 0; background: white; padding: 16px 32px; box-shadow: 0 -4px 12px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; z-index: 1000; border-top: 1px solid var(--border-color);">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="background: rgba(109, 40, 217, 0.1); color: var(--primary); padding: 8px 16px; border-radius: 20px; font-weight: bold; font-size: 14px;">
                <span id="selectedCount">0</span> Selected
            </div>
            <button type="button" class="btn btn-outline btn-sm" onclick="showOnlySelected()" id="btnShowSelected">View Only Selected</button>
        </div>
        <button type="submit" class="btn btn-primary" style="padding: 10px 24px; font-size: 15px;">
            <i class="fa-solid fa-check" style="margin-right: 8px;"></i> <?= $group ? 'Save Changes' : 'Create Access Group' ?>
        </button>
    </div>
</form>

<script>
function syncCardState(labelElement) {
    // Timeout needed because click event fires before checkbox state is actually updated in DOM
    setTimeout(() => {
        const checkbox = $(labelElement).find('input[type="checkbox"]');
        if (checkbox.is(':checked')) {
            $(labelElement).addClass('active');
        } else {
            $(labelElement).removeClass('active');
        }
    }, 10);
    setTimeout(updateCount, 15);
}

function updateCount() {
    $('#selectedCount').text($('.perm-checkbox:checked').length);
}

const groupPermMap = <?= isset($group_permissions_map) ? $group_permissions_map : '{}' ?>;
function clonePermissions(groupId) {
    deselectAll();
    if(groupId && groupPermMap[groupId]) {
        groupPermMap[groupId].forEach(id => {
            $('input[value="'+id+'"]').prop('checked', true).closest('.perm-card').addClass('active');
        });
        updateCount();
    }
}

function toggleAccordion(element) {
    let title = $(element);
    let wrapper = title.next('.area-grid-wrapper');
    
    // Check if we are currently searching; if so, we might not want to force close everything else,
    // but the user requested classic accordion behavior.
    if (title.hasClass('area-collapsed')) {
        // Close others
        $('.area-title').not(title).addClass('area-collapsed');
        $('.area-grid-wrapper').not(wrapper).slideUp(200);
        
        // Open this
        title.removeClass('area-collapsed');
        wrapper.slideDown(200);
    } else {
        // Close this
        title.addClass('area-collapsed');
        wrapper.slideUp(200);
    }
}

function filterPermissions() {
    let query = $('#permSearch').val().toLowerCase();
    let viewingSelected = $('#btnShowSelected').hasClass('active');
    
    $('.area-section').each(function() {
        let hasVisible = false;
        let section = $(this);
        
        section.find('.perm-card').each(function() {
            let text = $(this).text().toLowerCase();
            let isChecked = $(this).find('.perm-checkbox').is(':checked');
            
            let matchSearch = text.indexOf(query) > -1;
            let matchSelected = !viewingSelected || isChecked;
            
            if (matchSearch && matchSelected) {
                $(this).show();
                hasVisible = true;
            } else {
                $(this).hide();
            }
        });
        
        if (hasVisible) {
            section.show();
            // Automatically expand if searching
            if (query !== '' || viewingSelected) {
                section.find('.area-grid-wrapper').show();
                section.find('.area-title').removeClass('area-collapsed');
            }
        } else {
            section.hide();
        }
    });
}

function showOnlySelected() {
    $('#btnShowSelected').toggleClass('active');
    if($('#btnShowSelected').hasClass('active')) {
        $('#btnShowSelected').text('View All').removeClass('btn-outline').addClass('btn-primary');
    } else {
        $('#btnShowSelected').text('View Only Selected').removeClass('btn-primary').addClass('btn-outline');
    }
    filterPermissions();
}

$(document).ready(function() {
    updateCount();
});

function selectAll() {
    $('.perm-checkbox').prop('checked', true);
    $('.perm-card').addClass('active');
    updateCount();
}

function deselectAll() {
    $('.perm-checkbox').prop('checked', false);
    $('.perm-card').removeClass('active');
    updateCount();
}

function toggleArea(area) {
    var section = $('.area-' + area);
    var checkboxes = section.find('.perm-checkbox');
    var allChecked = checkboxes.length === checkboxes.filter(':checked').length;
    
    checkboxes.prop('checked', !allChecked);
    if (!allChecked) {
        section.find('.perm-card').addClass('active');
    } else {
        section.find('.perm-card').removeClass('active');
    }
    updateCount();
}
</script>

</section>
<?= $this->endSection() ?>
