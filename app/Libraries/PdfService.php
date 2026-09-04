<?php

namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    protected Dompdf $dompdf;
    protected Options $options;

    public function __construct()
    {
        $this->options = new Options();
        $this->options->set('isRemoteEnabled', true);
        $this->options->set('isHtml5ParserEnabled', true);
        $this->options->set('defaultFont', 'DejaVu Sans');
        $this->options->set('chroot', FCPATH);

        $this->dompdf = new Dompdf($this->options);
    }

    /**
     * Render raw HTML to PDF and stream or return binary string
     *
     * @param string $html
     * @param string $filename
     * @param bool $stream
     * @param string $paper
     * @param string $orientation
     * @return mixed
     */
    public function generate(string $html, string $filename = 'document.pdf', bool $stream = true, string $paper = 'A4', string $orientation = 'portrait')
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper($paper, $orientation);
        $this->dompdf->render();

        if ($stream) {
            $this->dompdf->stream($filename, ['Attachment' => false]);
            exit;
        }

        return $this->dompdf->output();
    }

    /**
     * Render a view file with data into PDF
     *
     * @param string $viewPath
     * @param array $data
     * @param string $filename
     * @param bool $stream
     * @param string $paper
     * @param string $orientation
     * @return mixed
     */
    public function renderView(string $viewPath, array $data = [], string $filename = 'document.pdf', bool $stream = true, string $paper = 'A4', string $orientation = 'portrait')
    {
        $html = view($viewPath, $data);
        return $this->generate($html, $filename, $stream, $paper, $orientation);
    }

    /**
     * Standard Institutional Header HTML
     *
     * @param string $title
     * @param string|null $orgName
     * @param string|null $subtitle
     * @return string
     */
    public static function getHeaderHtml(string $title, ?string $orgName = null, ?string $subtitle = null): string
    {
        $institution = $orgName ?: (session('org_name') ?: 'University Management System');
        $dateFormatted = format_date(date('Y-m-d H:i:s'), true);

        return <<<HTML
        <div style="text-align: center; border-bottom: 2px solid #2563eb; padding-bottom: 12px; margin-bottom: 20px; font-family: 'DejaVu Sans', sans-serif;">
            <h1 style="margin: 0; color: #1e293b; font-size: 20px; text-transform: uppercase;">{$institution}</h1>
            <p style="margin: 3px 0 0; color: #64748b; font-size: 11px;">{$subtitle}</p>
            <h2 style="margin: 10px 0 0; color: #2563eb; font-size: 15px; letter-spacing: 0.5px;">{$title}</h2>
            <div style="font-size: 9px; color: #94a3b8; margin-top: 4px;">Generated on: {$dateFormatted}</div>
        </div>
HTML;
    }
}
