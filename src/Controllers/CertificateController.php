<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\Data\DataForRedis;
use tFPDF;

session_start();

class CertificateController
{
    public function getCertificate(): void
    {
        $pdf = new tFPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
        $pdf->SetFont('DejaVu', '', 14);

        $pdf->Image(IMG_FON_CERTIFICATE, 0, 0, $pdf->GetPageWidth(), $pdf->GetPageHeight());

        $allDataUser = (new DataForRedis())->getDataFileForTaskByArray($_SESSION["userId"]);
        $text = $allDataUser["lastName"] . " " . $allDataUser["firstName"] . " " . $allDataUser["middleName"];
        $pdf->Cell(40, 10, $text);
        $pdf->SetFont('DejaVu', '', 34);
        $text = "Вы молодцы";
        $pdf->Cell(40, 10, $text);

        $pdf->Output('D', 'certificate.pdf');
    }
}