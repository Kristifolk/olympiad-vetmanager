<?php declare(strict_types=1);

namespace App\Controllers;

use App\Class\Timer;
use App\Services\Response;
use App\Services\View;
use App\Services\ViewPath;

session_start();

class ResultController
{
    public function viewResult(): void
    {
        $html = new View(ViewPath::Result,
            [
                'taskTransitTime' =>
                    [
                        [
                            'minute' => $this->convertToPrettyString((string)$_SESSION["timeEndTask"]["minutes"]),
                            'second' => $this->convertToPrettyString((string)$_SESSION["timeEndTask"]["seconds"]),
                        ],
                    ],
            ]
        );

        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
        (new Response((string)$templateWithContent))->echo();
    }

//    private function calculateSumTimeMinuteLoad(): int
//    {
//        return (int)$_SESSION["timeEndTask-1"]["minutes"] + (int)$_SESSION["timeEndTask-2"]["minutes"];
//    }
//
//    private function calculateSumTimeSecondLoad(): int
//    {
//        return (int)$_SESSION["timeEndTask-1"]["seconds"] + (int)$_SESSION["timeEndTask-2"]["seconds"];
//    }

    private function convertToPrettyString(string $time): string
    {
        if (strlen($time) <= 1) {
            return '0' . $time;
        }
        return $time;
    }
}