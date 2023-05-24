<?php declare(strict_types=1);

namespace App\Controllers;

use Dompdf\Dompdf;

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


//    public function getCertificate(): void
//    {
//
//        $pdf = new FPDF('L', 'mm', 'A4');
//        //MakeFont(FONT_CERTIFICATE, 'cp125');
//        // MakeFont(FONT_CERTIFICATE,'.afm','cp1252');
//        $pdf->AddPage();
//        //$pdf->AddFont('Arial','B',16);
    //       $pdf->Image(IMG_FON_CERTIFICATE, 0, 0, $pdf->GetPageWidth(), $pdf->GetPageHeight());
//        $pdf->SetFont('Arial', 'B', 16);
//
//        $text = "ериекаврвк";
//        $pdf->Cell(40, 10, $text);
//        $pdf->Output();
//
//        //$pdf->AddFont('MainFont','','119379869a251bdd6a14438b3c5514f2_arial.php');
//        //$pdf->AddPage();
//        // выбираем шрифт для текста.
//        // $pdf->SetFont('MainFont','',35);
//    }

    public function getCertificate()
    {

//        $contents = PDF;
//        $phone = '79181524517';
//
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_URL, "https://api.j12.wazapa.ru/sendwamessage/");
//
//        $client = new Client();
//        $options = ['multipart' => [
//                ['name' => 'body','contents' => $contents],
//                ['name' => 'phone','contents' => $phone],
//                ['name' => 'token','contents' => '306a06-e7b63f-e23cd2-ce148f-41c2e2']
//            ]
//        ];
//
//        $request = new Request('POST', 'https://api.j12.wazapa.ru/sendwamessage/');
//        $res = $client->sendAsync($request, $options)->wait();
//        echo $res->getBody();
//        $response = curl_exec($curl);
//        curl_close($curl);
//        echo $response;


        $dompdf = new Dompdf();

        $options = $dompdf->getOptions();
//        $options->setDefaultFont('roboto');
        $dompdf->setOptions($options);

//        $image=file_get_contents(IMG_FON_CERTIFICATE);
//        $imagedata=base64_encode($image);
        //$imgpath=$imagedata;

        $path_podpis = IMG_FON_CERTIFICATE;
        $type = pathinfo($path_podpis, PATHINFO_EXTENSION);
        $data = file_get_contents($path_podpis);
        $podpis = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $html = <<<HTML
	<!DOCTYPE html>
	<html lang="ru">
		<head>
			<meta charset="utf-8">
			<title>Test Page</title>
			<style>
			body { 
			font-family: DejaVu Sans;
            }
            
			.d {
			 width: 150px; height: 80px;
            background: linear-gradient(10deg, rgba(4,213,255,1) 11%, rgba(0,250,202,1) 50%, rgba(255,255,255,1) 91%);
			}
            </style>
		</head>
		<body>
			<p>fhftd, Мир>!</p> <img class="d" src="' . $podpis .'">
			<div class="d"></div>
		</body>
	</html>
HTML;
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->loadHtml($html, 'UTF-8');

        $dompdf->render();
        $dompdf->stream();
    }
}
//<img style="width: 150px; height: 80px" src="data:image/' . $type . ';base64,' . base64_encode($data) . '">