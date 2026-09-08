<?= $this->extend('org/layout') ?>
<?= $this->section('page_title') ?>Hall Tickets<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="header-banner">
    <div>
        <h1 class="header-title">Hall Tickets</h1>
        <p class="header-subtitle">Generate and manage hall tickets for approved exam applications.</p>
    </div>
</div>

<div class="card" style="margin-bottom: 24px;">
    <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Generate Hall Tickets</h2>
    <?php if(!empty($pending_apps)): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Roll No</th>
                    <th>Exam</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($pending_apps as $app): ?>
                    <tr>
                        <td><?= esc($app['first_name'] . ' ' . $app['last_name']) ?></td>
                        <td><?= esc($app['roll_number']) ?></td>
                        <td><?= esc($app['exam_name']) ?></td>
                        <td><span style="color: green; font-weight: bold;"><?= esc($app['status']) ?></span></td>
                        <td>
                            <form action="<?= base_url('org/examinations/generate-hall-ticket') ?>" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="exam_application_id" value="<?= $app['id'] ?>">
                                <button type="submit" class="btn btn-primary" style="padding: 4px 10px; font-size: 12px;">Generate HT</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending approved applications require hall tickets.</p>
    <?php endif; ?>
</div>

<div class="card">
    <h2 style="margin-top: 0; font-size: 18px; margin-bottom: 16px;">Issued Hall Tickets</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>Hall Ticket No</th>
                <th>Student</th>
                <th>Roll No</th>
                <th>Exam</th>
                <th>Issue Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($tickets as $ht): ?>
                <tr>
                    <td style="font-weight: 600; color: var(--primary);"><?= esc($ht['hall_ticket_number']) ?></td>
                    <td><?= esc($ht['first_name'] . ' ' . $ht['last_name']) ?></td>
                    <td><?= esc($ht['roll_number']) ?></td>
                    <td><?= esc($ht['exam_name']) ?></td>
                    <td><?= date('d/m/Y', strtotime($ht['issue_date'])) ?></td>
                    <td>
                        <?php if($ht['status'] == 'Valid'): ?>
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: #dcfce7; color: #166534; font-weight: bold;">VALID</span>
                        <?php else: ?>
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: #fee2e2; color: #991b1b; font-weight: bold;">REVOKED</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= base_url('org/examinations/print-hall-ticket/' . ($ht['uuid'] ?? $ht['id'])) ?>" target="_blank" class="btn btn-secondary" style="padding: 4px 10px; font-size: 12px; text-decoration: none; display: inline-block;">Print PDF</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($tickets)): ?>
                <tr><td colspan="6" style="text-align:center;">No hall tickets issued yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
