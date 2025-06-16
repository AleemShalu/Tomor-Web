<?php

namespace App\Traits;

use Mpdf\Mpdf;

trait PDFExportTrait
{
    public function generatePDF($data, string $reportName, string $viewName, string $orientation = "L"): string
    {
        $mpdf = new Mpdf([
            'mode' => 'UTF-8',
            'display_mode' => 'fullpage',
            'orientation' => $orientation,
            'fontDir' => base_path('public/fonts'),
            'fontdata' => [
                'arabicfont' => [
                    'R' => 'Tajawal-Regular.ttf',
                    'B' => 'Tajawal-Bold.ttf',
                    'I' => 'Tajawal-Bold.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'default_font' => 'arabicfont',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_header' => 0,
            'margin_footer' => 0,
            'setAutoTopMargin' => 'pad',
            'setAutoBottomMargin' => 'pad',
        ]);
        $mpdf->showImageErrors = true;

        $headerHtml = view('export.components.header', compact('reportName'))->render();
        $footerHtml = view('export.components.footer')->render();

        $mpdf->SetHTMLHeader($headerHtml);
        $mpdf->SetHTMLFooter($footerHtml);

        $html = $this->generateHtml($data, $reportName, $viewName);
        $mpdf->WriteHTML($html);
        return $mpdf->Output('', 'S'); // Return PDF as a string
    }

    protected function generateHtml($data, $reportName, string $viewName): string
    {
        return view($viewName, compact('data', 'reportName'))->render();
    }

    /**
     * Generate a PDF response.
     *
     * @param mixed $data
     * @param string $reportName
     * @param string $viewName
     * @param string $orientation
     * @return \Illuminate\Http\Response
     */
    public function generatePDFResponse($data, $reportName, $viewName, $orientation = 'P')
    {
        $pdfContent = $this->generatePDF($data, $reportName, $viewName, $orientation);

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="report.pdf"');
    }
}
