<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?><?= $student ? 'Edit Student - ' . esc($student['roll_number']) : 'Register New Student' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header" style="margin-bottom: 24px;">
        <div>
            <h2><i class="fa-solid fa-user-graduate"></i> <?= $student ? 'Edit Student Record' : 'Register New Student' ?></h2>
            <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 13px;">Enter student master academic credentials and biographical profile.</p>
        </div>
        <div>
            <a href="<?= base_url('org/students' . ($student ? '/profile/' . $student['id'] : '')) ?>" class="btn btn-outline">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <form action="<?= base_url('org/students/save') ?>" method="POST" class="card" style="padding: 28px; border-radius: 12px;">
        <?= csrf_field() ?>
        <?php if($student): ?>
            <input type="hidden" name="id" value="<?= $student['id'] ?>">
        <?php endif; ?>

        <!-- SECTION 1: Core Identity -->
        <h4 style="font-size: 15px; font-weight: 700; color: var(--primary, #4f46e5); margin-bottom: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
            <i class="fa-solid fa-id-card me-2"></i> 1. Academic & Identification Information
        </h4>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px;">
            <div class="form-group">
                <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Roll Number / Admission No. *</label>
                <input type="text" name="roll_number" value="<?= esc($student['roll_number'] ?? old('roll_number') ?? 'STU-' . date('Y') . '-' . rand(1000, 9999)) ?>" class="form-control" required>
            </div>

            <div class="form-group">
                <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">First Name *</label>
                <input type="text" name="first_name" value="<?= esc($student['first_name'] ?? old('first_name')) ?>" class="form-control" required placeholder="First Name">
            </div>

            <div class="form-group">
                <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Last Name</label>
                <input type="text" name="last_name" value="<?= esc($student['last_name'] ?? old('last_name')) ?>" class="form-control" placeholder="Last Name">
            </div>

            <div class="form-group">
                <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Cohort / Batch Assignment *</label>
                <select name="cohort_id" class="form-control" required>
                    <option value="">Select Cohort</option>
                    <?php foreach($cohorts as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= (($student['cohort_id'] ?? old('cohort_id')) == $c['id']) ? 'selected' : '' ?>>
                            <?= esc($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Enrollment Status</label>
                <select name="status" class="form-control">
                    <option value="Active" <?= (($student['status'] ?? 'Active') === 'Active') ? 'selected' : '' ?>>Active</option>
                    <option value="Promoted" <?= (($student['status'] ?? '') === 'Promoted') ? 'selected' : '' ?>>Promoted</option>
                    <option value="Detained" <?= (($student['status'] ?? '') === 'Detained') ? 'selected' : '' ?>>Detained</option>
                    <option value="Graduated" <?= (($student['status'] ?? '') === 'Graduated') ? 'selected' : '' ?>>Graduated</option>
                    <option value="Dropped" <?= (($student['status'] ?? '') === 'Dropped') ? 'selected' : '' ?>>Dropped Out</option>
                </select>
            </div>
        </div>

        <!-- SECTION 2: Login & Contact -->
        <h4 style="font-size: 15px; font-weight: 700; color: var(--primary, #4f46e5); margin-bottom: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
            <i class="fa-solid fa-lock me-2"></i> 2. Portal Login & Contact Details
        </h4>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px;">
            <div class="form-group">
                <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Student Email Address *</label>
                <input type="email" name="email" value="<?= esc($user['email'] ?? $student['email'] ?? old('email')) ?>" class="form-control" required placeholder="student@example.com">
            </div>

            <div class="form-group">
                <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Student Mobile Number</label>
                <input type="text" name="phone" value="<?= esc($user['phone'] ?? $student['phone'] ?? old('phone')) ?>" class="form-control" placeholder="10-digit mobile number">
            </div>

            <div class="form-group">
                <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">
                    <?= $student ? 'Reset Password (Leave blank to keep)' : 'Initial Password *' ?>
                </label>
                <input type="password" name="password" class="form-control" <?= $student ? '' : 'required' ?> placeholder="<?= $student ? '••••••••' : 'Enter login password' ?>">
            </div>
        </div>

        <!-- SECTION 3: Parent & Bio Data -->
        <h4 style="font-size: 15px; font-weight: 700; color: var(--primary, #4f46e5); margin-bottom: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
            <i class="fa-solid fa-users me-2"></i> 3. Guardian & Biographical Information
        </h4>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px;">
            <div class="form-group">
                <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Father / Guardian Name</label>
                <input type="text" name="father_name" value="<?= esc($bio['father_name'] ?? $student['parent_name'] ?? '') ?>" class="form-control" placeholder="Father or Guardian Name">
            </div>

            <div class="form-group">
                <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Parent / Guardian Phone</label>
                <input type="text" name="parent_phone" value="<?= esc($student['parent_phone'] ?? '') ?>" class="form-control" placeholder="Guardian Phone">
            </div>

            <div class="form-group">
                <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Mother Name</label>
                <input type="text" name="mother_name" value="<?= esc($bio['mother_name'] ?? '') ?>" class="form-control" placeholder="Mother Name">
            </div>

            <div class="form-group">
                <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Blood Group</label>
                <select name="blood_group" class="form-control">
                    <option value="">Select Blood Group</option>
                    <?php foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg): ?>
                        <option value="<?= $bg ?>" <?= (($bio['blood_group'] ?? '') === $bg) ? 'selected' : '' ?>><?= $bg ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Aadhaar / National ID</label>
                <input type="text" name="aadhar_number" value="<?= esc($bio['aadhar_number'] ?? '') ?>" class="form-control" placeholder="Aadhaar / Gov ID">
            </div>

            <div class="form-group">
                <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Emergency Contact</label>
                <input type="text" name="emergency_contact" value="<?= esc($bio['emergency_contact'] ?? '') ?>" class="form-control" placeholder="Emergency Phone">
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Address</label>
                <input type="text" name="address" value="<?= esc($bio['address'] ?? '') ?>" class="form-control" placeholder="Street address, house number">
            </div>

            <div class="form-group">
                <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">City</label>
                <input type="text" name="city" value="<?= esc($bio['city'] ?? '') ?>" class="form-control" placeholder="City">
            </div>

            <div class="form-group">
                <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">State</label>
                <input type="text" name="state" value="<?= esc($bio['state'] ?? '') ?>" class="form-control" placeholder="State">
            </div>

            <div class="form-group">
                <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Pincode / Postal Code</label>
                <input type="text" name="pincode" value="<?= esc($bio['pincode'] ?? '') ?>" class="form-control" placeholder="Pincode">
            </div>
        </div>

        <div style="border-top: 1px solid var(--border-color); padding-top: 20px; display: flex; justify-content: flex-end; gap: 12px;">
            <a href="<?= base_url('org/students') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                <i class="fa-solid fa-floppy-disk"></i> <?= $student ? 'Update Student Record' : 'Save Student' ?>
            </button>
        </div>
    </form>
</section>
<?= $this->endSection() ?>
