<?php declare(strict_types=1);

namespace App\Controllers;

use Fpdf\Fpdf;

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

//    public function getCertificate()
//    {
//        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
//         $pdf->setPrintHeader(false);
//         $pdf->setPrintFooter(false);
//         $pdf->SetMargins(20, 25, 25);
//         // устанавливаем отступы (20 мм - слева, 25 мм - сверху, 25 мм - справа)
//         $pdf->AddPage();
//         // создаем первую страницу, на которой будет содержимое
//         $pdf->SetXY(90, 10);
//         // устанавливаем координаты вывода текста в рамке:
//         // 90 мм - отступ от левого края бумаги, 10 мм - от верхнего
//         $pdf->SetDrawColor(0, 0, 200);
//         // устанавливаем цвет рамки (синий)
//         $pdf->SetTextColor(0, 200, 0);
//         // устанавливаем цвет текста (зеленый)
//         $pdf->Cell(30, 6, 'кпмыупку', 1, 1, 'C');
//         // выводим ячейку с надписью шириной 30 мм и высотой 6 мм.
//         $pdf->Output('doc.pdf', 'I');
//

//    }


    public function getCertificate(): void
    {

        $pdf = new FPDF('L', 'mm', 'A4');
        MakeFont(FONT_CERTIFICATE, 'cp125');
        // MakeFont(FONT_CERTIFICATE,'.afm','cp1252');
        $pdf->AddPage();
        //$pdf->AddFont('Arial','B',16);
        $pdf->Image(IMG_FON_CERTIFICATE, 0, 0, $pdf->GetPageWidth(), $pdf->GetPageHeight());
        $pdf->SetFont('Arial', 'B', 16);

        $text = "ериекаврвк";
        $pdf->Cell(40, 10, $text);
        $pdf->Output();

        //$pdf->AddFont('MainFont','','119379869a251bdd6a14438b3c5514f2_arial.php');
        //$pdf->AddPage();
        // выбираем шрифт для текста.
        // $pdf->SetFont('MainFont','',35);
    }

//    public function getCertificate()
//    {
//        $html = new View(ViewPath::TemplateCertificate);
//
//        $dompdf = new Dompdf();
//
//        $dompdf->loadHtml($html, 'UTF-8');
//
//        $dompdf->setPaper('A4', 'landscape');
//
////        $dompdf->set_option('isFontSubsettingEnabled', true);
////        $dompdf->set_option('defaultMediaType', 'all');
////
//        $dompdf->render();
//
//        $dompdf->stream();
//    }
}