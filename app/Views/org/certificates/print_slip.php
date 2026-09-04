<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= esc($template['name']) ?></title>
    <style>
        body { margin: 0; padding: 30px; font-family: 'Times New Roman', Times, serif; background: #fafafa; }
        .cert-container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .no-print { text-align: center; margin-bottom: 20px; }
        .btn-print { padding: 8px 24px; background: #4f46e5; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-family: sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; background: none; }
            .cert-container { box-shadow: none; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">Print Official Certificate</button>
    </div>

    <div class="cert-container">
        <?= $renderedHtml ?>
    </div>
</body>
</html>
