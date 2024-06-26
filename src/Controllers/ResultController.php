<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\Data\DataForRedis;
use App\Services\Response;
use App\Services\View;
use App\Services\ViewPath;

class ResultController
{
    public function viewEndTime(): never
    {
        $_SESSION["TimeEndTask"]["minutes"] = "25";
        $_SESSION["TimeEndTask"]["seconds"] = "00";
        $html = new View(ViewPath::EndTime);
        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
        (new Response((string)$templateWithContent))->echoAndDie();
    }

    public function viewResultForUser(): never
    {
        $html = new View(ViewPath::Result,
            [
                'login' => (new DataForRedis())->getDataFileForTaskByUser($_SESSION["userId"], "login"),
                'taskTransitTime' =>
                    [
                        'minute' => $this->convertToPrettyString((string)$_SESSION["TimeEndTask"]["minutes"]),
                        'second' => $this->convertToPrettyString((string)$_SESSION["TimeEndTask"]["seconds"]),
                        'resultPercentage' => $_SESSION["ResultPercentage"]
                    ],
            ]
        );

        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
        (new Response((string)$templateWithContent))->echoAndDie();
    }

    private function convertToPrettyString(string $time): string
    {
        if (strlen($time) <= 1) {
            return '0' . $time;
        }

        return $time;
    }
}