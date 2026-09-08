<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Certificate Templates<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="view-section active">
    <div class="view-header">
        <div>
            <h2><i class="fa-solid fa-stamp"></i> Certificate & Document Templates</h2>
            <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 13px;">Design and customize Transfer Certificates (TC), Bonafide Certificates, Character Certificates, and Transcripts with dynamic shortcodes.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-primary" onclick="openTemplateModal()"><i class="fa-solid fa-plus"></i> New Template</button>
            <a href="<?= base_url('org/certificates/generate') ?>" class="btn btn-outline"><i class="fa-solid fa-print"></i> Issue Certificate</a>
        </div>
    </div>

    <!-- Templates Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px;">
        <?php if(!empty($templates)): foreach($templates as $t): ?>
        <div class="card" style="padding: 20px; border-radius: 12px; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <div>
                        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: var(--text-primary);"><?= esc($t['name']) ?></h3>
                        <span class="badge" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; margin-top: 4px;">
                            <?= esc($t['category']) ?> • <?= esc($t['page_size']) ?> (<?= esc($t['orientation']) ?>)
                        </span>
                    </div>
                    <a href="<?= base_url('org/certificates/delete/' . ($t['uuid'] ?? $t['id'])) ?>" class="btn-icon text-danger" onclick="return confirm('Delete this template?')" title="Delete">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </div>

                <div style="background: #F8FAFC; border: 1px dashed var(--border-color); border-radius: 6px; padding: 12px; font-size: 11px; color: var(--text-muted); max-height: 120px; overflow: hidden; margin-bottom: 16px;">
                    <?= strip_tags(substr($t['html_content'], 0, 160)) ?>...
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-color); padding-top: 14px;">
                <a href="<?= base_url('org/certificates/generate?template_id=' . ($t['uuid'] ?? $t['id'])) ?>" class="btn btn-primary" style="padding: 6px 14px; font-size: 13px;">
                    <i class="fa-solid fa-file-signature me-1"></i> Issue for Student
                </a>
            </div>
        </div>
        <?php endforeach; else: ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted);">No certificate templates found.</div>
        <?php endif; ?>
    </div>
</section>

<!-- Template Modal -->
<div class="drawer-overlay" id="templateModal" style="display: none;">
    <div class="drawer-content" style="max-width: 650px;">
        <form action="<?= base_url('org/certificates/templates/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="drawer-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 700;"><i class="fa-solid fa-stamp me-2" style="color: #4f46e5;"></i> Document Template Builder</h3>
                <button type="button" class="btn-close" onclick="closeTemplateModal()" style="background: none; border: none; font-size: 18px; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div class="form-group" style="grid-column: span 2;">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Template Name *</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Migration Certificate">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Category</label>
                        <select name="category" class="form-control">
                            <option value="Student">Student Academic</option>
                            <option value="Staff">Staff / Faculty</option>
                            <option value="General">General Campus</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">Page Size & Orientation</label>
                        <select name="page_size" class="form-control">
                            <option value="A4">A4 Portrait</option>
                            <option value="Letter">Letter Portrait</option>
                            <option value="A4-Landscape">A4 Landscape</option>
                        </select>
                    </div>
                </div>

                <!-- Shortcode chips -->
                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 6px;">Available Data Placeholders (Click to insert):</label>
                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                        <span class="badge" style="cursor: pointer; background: #EEF2FF; color: #4F46E5;" onclick="insertTag('{{student_name}}')">{{student_name}}</span>
                        <span class="badge" style="cursor: pointer; background: #EEF2FF; color: #4F46E5;" onclick="insertTag('{{roll_number}}')">{{roll_number}}</span>
                        <span class="badge" style="cursor: pointer; background: #EEF2FF; color: #4F46E5;" onclick="insertTag('{{program_name}}')">{{program_name}}</span>
                        <span class="badge" style="cursor: pointer; background: #EEF2FF; color: #4F46E5;" onclick="insertTag('{{academic_year}}')">{{academic_year}}</span>
                        <span class="badge" style="cursor: pointer; background: #EEF2FF; color: #4F46E5;" onclick="insertTag('{{issue_date}}')">{{issue_date}}</span>
                        <span class="badge" style="cursor: pointer; background: #EEF2FF; color: #4F46E5;" onclick="insertTag('{{organization_name}}')">{{organization_name}}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">HTML Content & Layout *</label>
                    <textarea name="html_content" id="template_html" class="form-control" rows="8" required placeholder="<div style='text-align: center;'>...</div>"></textarea>
                </div>
            </div>

            <div class="drawer-footer" style="padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeTemplateModal()">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Save Template</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openTemplateModal() { document.getElementById('templateModal').style.display = 'flex'; }
    function closeTemplateModal() { document.getElementById('templateModal').style.display = 'none'; }

    function insertTag(tag) {
        const txt = document.getElementById('template_html');
        txt.value += ' ' + tag + ' ';
        txt.focus();
    }
</script>
<?= $this->endSection() ?>
