<?php declare(strict_types=1);

namespace App\Controllers;

use tFPDF;

class CertificateController
{
    public function show()
    {

    }

    public function create()
    {

    }

    public function store()
    {

    }

    public function getCertificate(): void
    {
        $pdf = new tFPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
        $pdf->SetFont('DejaVu', '', 14);

        $pdf->Image(IMG_FON_CERTIFICATE, 0, 0, $pdf->GetPageWidth(), $pdf->GetPageHeight());

        $text = "Сертификат";
        $pdf->Cell(40, 10, $text);
        $pdf->SetFont('DejaVu', '', 34);
        $text = "Вы молодцы";
        $pdf->Cell(40, 10, $text);
        $pdf->Output();
    }
}