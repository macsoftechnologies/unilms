<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Bulk Student Import<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <div>
            <h2><i class="fa-solid fa-file-import"></i> Bulk Student Import (CSV)</h2>
            <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 13px;">Upload a CSV file to create multiple student accounts and master records in one batch.</p>
        </div>
        <div>
            <a href="<?= base_url('org/students') ?>" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Back to Directory</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start;">
        <!-- Upload Box -->
        <div class="card" style="padding: 24px; border-radius: 12px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text-primary);">
                <i class="fa-solid fa-cloud-arrow-up me-2" style="color: #4f46e5;"></i> Upload CSV File
            </h3>

            <form action="<?= base_url('org/students/process-import') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Default Cohort / Batch</label>
                    <select name="default_cohort_id" class="form-control">
                        <option value="">Select Cohort (if not specified in CSV)</option>
                        <?php foreach($cohorts as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">CSV Data File *</label>
                    <input type="file" name="csv_file" accept=".csv" class="form-control" required style="padding: 10px;">
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; padding: 12px;">
                        <i class="fa-solid fa-upload"></i> Process & Import Students
                    </button>
                    <a href="<?= base_url('org/students/download-sample-csv') ?>" class="btn btn-outline" style="padding: 12px;">
                        <i class="fa-solid fa-download"></i> Sample CSV
                    </a>
                </div>
            </form>
        </div>

        <!-- Instructions Card -->
        <div class="card" style="padding: 24px; border-radius: 12px; background: rgba(79, 70, 229, 0.02); border-color: rgba(79, 70, 229, 0.15);">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 14px; color: #4f46e5;">
                <i class="fa-solid fa-circle-info me-2"></i> CSV Column Specifications
            </h3>
            
            <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5; margin-bottom: 16px;">
                Ensure your CSV file contains the following column header names in the exact format below:
            </p>

            <table style="width: 100%; font-size: 12px; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border-color); text-align: left;">
                        <th style="padding: 6px 0;">Column Header</th>
                        <th style="padding: 6px 0;">Required?</th>
                        <th style="padding: 6px 0;">Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 8px 0;"><code>roll_number</code></td>
                        <td><span style="color: #ef4444; font-weight: 600;">Yes</span></td>
                        <td>Unique Roll / Registration number</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 8px 0;"><code>first_name</code></td>
                        <td><span style="color: #ef4444; font-weight: 600;">Yes</span></td>
                        <td>Student first name</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 8px 0;"><code>last_name</code></td>
                        <td>No</td>
                        <td>Student last name / surname</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 8px 0;"><code>email</code></td>
                        <td><span style="color: #ef4444; font-weight: 600;">Yes</span></td>
                        <td>Unique student login email</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 8px 0;"><code>phone</code></td>
                        <td>No</td>
                        <td>10-digit mobile number</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 8px 0;"><code>parent_name</code></td>
                        <td>No</td>
                        <td>Father / Mother / Guardian name</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 8px 0;"><code>parent_phone</code></td>
                        <td>No</td>
                        <td>Parent contact phone number</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0;"><code>cohort_id</code></td>
                        <td>Optional</td>
                        <td>ID of cohort (uses default if blank)</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 18px; padding: 12px; background: rgba(16, 185, 129, 0.1); border-radius: 8px; font-size: 12px; color: #065f46;">
                <i class="fa-solid fa-key me-1"></i> Each imported student is automatically granted a portal account with default password: <strong>welcome123</strong>.
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
