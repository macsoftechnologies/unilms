<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Lecture Halls & Lab Infrastructure<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
    <div>
        <h1 class="header-title" style="margin: 0; font-size: 22px; font-weight: 700; color: var(--text-primary);"><i class="fa-solid fa-chalkboard" style="color: #7C3AED;"></i> Lecture Halls & Campus Infrastructure</h1>
        <p class="header-subtitle" style="margin: 4px 0 0; color: var(--text-secondary); font-size: 13.5px;">Manage smart classrooms, computer laboratories, seminar halls, and auditoriums for timetable & exam seating.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <button type="button" class="btn btn-primary" onclick="openAddHallDrawer()" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); border: none; padding: 10px 18px; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(124, 58, 237, 0.25);">
            <i class="fa-solid fa-plus-circle"></i> Add Classroom / Lab
        </button>
    </div>
</div>

<div class="card" style="background: var(--card-bg, #fff); border: 1px solid var(--border-color); border-radius: 14px; padding: 22px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: var(--text-primary);">Campus Rooms & Laboratories</h3>
            <span class="badge" style="background: rgba(124, 58, 237, 0.1); color: #7C3AED; font-size: 12px; font-weight: 700; padding: 3px 8px; border-radius: 6px;">
                <?= count($halls) ?> Total Rooms
            </span>
        </div>
        <div style="max-width: 300px; width: 100%;">
            <input type="text" id="hallSearch" class="form-control" placeholder="Search rooms, labs..." onkeyup="filterHalls()" style="height: 38px; font-size: 13.5px; border-radius: 8px;">
        </div>
    </div>

    <div class="table-responsive" style="overflow-x: auto;">
        <table class="data-table" id="hallsTable" style="width: 100%;">
            <thead>
                <tr style="background: var(--bg-main, #f8fafc);">
                    <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Room / Lab Name</th>
                    <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase;">Category Type</th>
                    <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase; text-align: center;">Seating Capacity</th>
                    <th style="padding: 12px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($halls)): ?>
                    <?php foreach($halls as $hall): ?>
                        <tr>
                            <td style="padding: 14px 16px; font-weight: 700; color: var(--text-primary); font-size: 14px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 34px; height: 34px; border-radius: 8px; background: rgba(124, 58, 237, 0.1); color: #7C3AED; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                        <?php if(stripos($hall['hall_type'], 'Lab') !== false): ?>
                                            <i class="fa-solid fa-flask"></i>
                                        <?php elseif(stripos($hall['hall_type'], 'Auditorium') !== false || stripos($hall['hall_type'], 'Seminar') !== false): ?>
                                            <i class="fa-solid fa-users-rectangle"></i>
                                        <?php else: ?>
                                            <i class="fa-solid fa-chalkboard"></i>
                                        <?php endif; ?>
                                    </div>
                                    <span><?= esc($hall['name']) ?></span>
                                </div>
                            </td>
                            <td style="padding: 14px 16px;">
                                <span class="badge" style="background: rgba(124, 58, 237, 0.1); color: #7C3AED; font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 6px;">
                                    <?= esc($hall['hall_type']) ?>
                                </span>
                            </td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <strong style="font-size: 14px; color: var(--text-primary);"><?= esc($hall['capacity']) ?></strong>
                                <span style="font-size: 12px; color: var(--text-secondary); margin-left: 4px;">Seats</span>
                            </td>
                            <td style="padding: 14px 16px; text-align: right;">
                                <button type="button" class="btn btn-outline" onclick='editHall(<?= json_encode($hall) ?>)' style="padding: 6px 12px; font-size: 12.5px; font-weight: 600; border-radius: 6px; margin-right: 6px;">
                                    <i class="fa-solid fa-edit me-1"></i> Edit
                                </button>
                                <form action="<?= base_url('org/administration/delete-lecture-hall/' . $hall['id']) ?>" method="POST" style="display: inline;" onsubmit="return confirm('Delete this room?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-icon text-danger" style="background: none; border: 1px solid var(--border-color); padding: 6px 10px; border-radius: 6px; color: #DC2626; cursor: pointer;" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align: center; padding: 48px 20px; color: var(--text-secondary);">No lecture halls or labs configured yet. Click "Add Classroom / Lab" to create rooms.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Drawer Modal -->
<div id="hallDrawer" class="drawer-overlay">
    <div class="drawer-content" style="max-width: 480px; width: 100%;">
        <form id="hallForm" action="<?= base_url('org/administration/save-lecture-hall') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="hall_id">
            
            <div class="drawer-header" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); color: #fff; padding: 18px 22px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255, 255, 255, 0.2); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                        <i class="fa-solid fa-chalkboard"></i>
                    </div>
                    <h3 id="drawerTitle" style="margin: 0; font-size: 17px; font-weight: 700; color: #fff;">Add Classroom / Lab</h3>
                </div>
                <button type="button" class="btn-close" onclick="closeHallDrawer()" style="color: #fff; background: none; border: none; font-size: 20px; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 22px;">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Hall / Laboratory Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="hall_name" class="form-control" placeholder="e.g. Room 101 - Smart Hall, AI & Cloud Lab" required style="font-size: 13.5px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Room Type <span class="text-danger">*</span></label>
                    <select name="hall_type" id="hall_type" class="form-control" required style="font-size: 13.5px;">
                        <option value="Lecture Hall">Smart Lecture Hall / Classroom</option>
                        <option value="Lab">Computer / Engineering Lab</option>
                        <option value="Seminar Hall">Department Seminar Hall</option>
                        <option value="Auditorium">Main Campus Auditorium</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="font-size: 12.5px; font-weight: 700;">Student Seating Capacity <span class="text-danger">*</span></label>
                    <input type="number" min="1" max="1000" name="capacity" id="hall_capacity" value="60" class="form-control" required style="font-size: 13.5px;">
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeHallDrawer()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #7C3AED 0%, #4C1D95 100%); border: none; padding: 10px 20px; font-weight: 700;">
                    <i class="fa-solid fa-check-circle me-1"></i> Save Room
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddHallDrawer() {
    document.getElementById('drawerTitle').innerText = 'Add Classroom / Lab';
    document.getElementById('hall_id').value = '';
    document.getElementById('hall_name').value = '';
    document.getElementById('hall_capacity').value = '60';
    document.getElementById('hall_type').value = 'Lecture Hall';
    $('#hallDrawer').addClass('active');
}

function editHall(hall) {
    document.getElementById('drawerTitle').innerText = 'Edit Classroom / Lab';
    document.getElementById('hall_id').value = hall.id;
    document.getElementById('hall_name').value = hall.name;
    document.getElementById('hall_capacity').value = hall.capacity;
    document.getElementById('hall_type').value = hall.hall_type;
    $('#hallDrawer').addClass('active');
}

function closeHallDrawer() {
    $('#hallDrawer').removeClass('active');
}

function filterHalls() {
    var input = document.getElementById('hallSearch');
    var filter = input.value.toLowerCase();
    var table = document.getElementById('hallsTable');
    var tr = table.getElementsByTagName('tr');

    for (var i = 1; i < tr.length; i++) {
        var rowText = tr[i].innerText.toLowerCase();
        tr[i].style.display = rowText.includes(filter) ? '' : 'none';
    }
}
</script>

<?= $this->endSection() ?>
