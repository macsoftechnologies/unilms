<?= $this->extend('lms/layout') ?>

<?= $this->section('page_title') ?>
Student Self Service Hub
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="lms-page-header">
    <h1><i class="fa-solid fa-receipt me-2" style="color: var(--primary);"></i> Student Self Service Hub</h1>
    <p>Single-window gateway for digital certificate generation, exam registration, grievance submission, and campus amenities.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px;">
    
    <!-- 1. Certificates & Documents -->
    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; border: 1px solid var(--border); border-radius: 16px; padding: 24px; transition: transform 0.2s ease, box-shadow 0.2s ease;">
        <div>
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(99, 102, 241, 0.12); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 22px;">
                    <i class="fa-solid fa-certificate"></i>
                </div>
                <div>
                    <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0; color: var(--text-main);">Official Certificates</h3>
                    <span style="font-size: 12px; color: var(--text-muted);">Bonafide, Transfer, Conduct & NOC</span>
                </div>
            </div>
            <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5; margin-bottom: 20px;">
                Apply for digitally signed academic credentials, Bonafide certificates for visa/passports, and official letters from the registrar.
            </p>
        </div>
        <a href="<?= base_url('lms/certificates') ?>" class="btn btn-outline" style="width: 100%; text-align: center; font-weight: 700; border-color: var(--primary); color: var(--primary);">
            <i class="fa-solid fa-arrow-right me-1"></i> Request Certificate
        </a>
    </div>

    <!-- 2. Exam Applications -->
    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; border: 1px solid var(--border); border-radius: 16px; padding: 24px; transition: transform 0.2s ease, box-shadow 0.2s ease;">
        <div>
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.12); color: var(--success); display: flex; align-items: center; justify-content: center; font-size: 22px;">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <div>
                    <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0; color: var(--text-main);">Exam Applications</h3>
                    <span style="font-size: 12px; color: var(--text-muted);">Semester & Supplementary Exams</span>
                </div>
            </div>
            <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5; margin-bottom: 20px;">
                Enroll for end-semester examinations, register for backlog re-tests, and generate admit hall ticket verification passes.
            </p>
        </div>
        <a href="<?= base_url('lms/exam-applications') ?>" class="btn btn-outline" style="width: 100%; text-align: center; font-weight: 700; border-color: var(--success); color: var(--success);">
            <i class="fa-solid fa-arrow-right me-1"></i> Apply for Exams
        </a>
    </div>

    <!-- 3. Semester Grade Card -->
    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; border: 1px solid var(--border); border-radius: 16px; padding: 24px; transition: transform 0.2s ease, box-shadow 0.2s ease;">
        <div>
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(245, 158, 11, 0.12); color: var(--warning); display: flex; align-items: center; justify-content: center; font-size: 22px;">
                    <i class="fa-solid fa-award"></i>
                </div>
                <div>
                    <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0; color: var(--text-main);">Grade Card & Marks</h3>
                    <span style="font-size: 12px; color: var(--text-muted);">CIA Internal + External GPA</span>
                </div>
            </div>
            <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5; margin-bottom: 20px;">
                View continuous assessment scores, end-term university examination grades, credits earned, and download printable grade sheets.
            </p>
        </div>
        <a href="<?= base_url('lms/grade-card') ?>" class="btn btn-outline" style="width: 100%; text-align: center; font-weight: 700; border-color: var(--warning); color: var(--warning);">
            <i class="fa-solid fa-arrow-right me-1"></i> View Grade Card
        </a>
    </div>

    <!-- 4. Grievance & Complaint Desk -->
    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; border: 1px solid var(--border); border-radius: 16px; padding: 24px; transition: transform 0.2s ease, box-shadow 0.2s ease;">
        <div>
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(239, 68, 68, 0.12); color: var(--danger); display: flex; align-items: center; justify-content: center; font-size: 22px;">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <div>
                    <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0; color: var(--text-main);">Grievance Desk</h3>
                    <span style="font-size: 12px; color: var(--text-muted);">Academic, Infra & Helpdesk</span>
                </div>
            </div>
            <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5; margin-bottom: 20px;">
                Submit confidential complaints or queries regarding academic matters, campus facilities, or ragging prevention with SLA tracking.
            </p>
        </div>
        <a href="<?= base_url('lms/complaints') ?>" class="btn btn-outline" style="width: 100%; text-align: center; font-weight: 700; border-color: var(--danger); color: var(--danger);">
            <i class="fa-solid fa-arrow-right me-1"></i> Lodge Complaint
        </a>
    </div>

    <!-- 5. Hostel Registration -->
    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; border: 1px solid var(--border); border-radius: 16px; padding: 24px; transition: transform 0.2s ease, box-shadow 0.2s ease;">
        <div>
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(6, 182, 212, 0.12); color: var(--info); display: flex; align-items: center; justify-content: center; font-size: 22px;">
                    <i class="fa-solid fa-bed"></i>
                </div>
                <div>
                    <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0; color: var(--text-main);">Hostel & Residence</h3>
                    <span style="font-size: 12px; color: var(--text-muted);">Room Allotment & Dining</span>
                </div>
            </div>
            <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5; margin-bottom: 20px;">
                Apply for campus residential accommodation, select room types (AC/Non-AC), and check allotment verification status.
            </p>
        </div>
        <a href="<?= base_url('lms/hostel') ?>" class="btn btn-outline" style="width: 100%; text-align: center; font-weight: 700; border-color: var(--info); color: var(--info);">
            <i class="fa-solid fa-arrow-right me-1"></i> Hostel Portal
        </a>
    </div>

    <!-- 6. Campus Transport -->
    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; border: 1px solid var(--border); border-radius: 16px; padding: 24px; transition: transform 0.2s ease, box-shadow 0.2s ease;">
        <div>
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(148, 163, 184, 0.15); color: var(--text-main); display: flex; align-items: center; justify-content: center; font-size: 22px;">
                    <i class="fa-solid fa-bus"></i>
                </div>
                <div>
                    <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; margin: 0; color: var(--text-main);">Transit & Bus Pass</h3>
                    <span style="font-size: 12px; color: var(--text-muted);">Route Selection & Digital Pass</span>
                </div>
            </div>
            <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5; margin-bottom: 20px;">
                Subscribe to university shuttle bus routes, register designated pickup halts, and track active commute passes.
            </p>
        </div>
        <a href="<?= base_url('lms/transport') ?>" class="btn btn-outline" style="width: 100%; text-align: center; font-weight: 700; border-color: var(--text-muted); color: var(--text-main);">
            <i class="fa-solid fa-arrow-right me-1"></i> Transport Pass
        </a>
    </div>

</div>
<?= $this->endSection() ?>
