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
                'fullTransitTime' =>
                    [
                        'minute' => $this->convertToPrettyString((string)$this->calculateSumTimeMinuteLoad()),
                        'second' => $this->convertToPrettyString((string)$this->calculateSumTimeSecondLoad()),
                    ],
                'taskTransitTime' =>
                    [
                        [
                            'numberTask' => 1,
                            'minute' => $this->convertToPrettyString((string)$_SESSION["timeEndTask-1"]["minutes"]),
                            'second' => $this->convertToPrettyString((string)$_SESSION["timeEndTask-1"]["seconds"]),
                        ],
                        [
                            'numberTask' => 2,
                            'minute' => $this->convertToPrettyString((string)$_SESSION["timeEndTask-2"]["minutes"]),
                            'second' => $this->convertToPrettyString((string)$_SESSION["timeEndTask-2"]["seconds"]),
                        ],
                    ],

            ]
        );

        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
        (new Response((string)$templateWithContent))->echo();
    }

    public function calculateSumTimeMinuteLoad(): int
    {
        return (int)$_SESSION["timeEndTask-1"]["minutes"] + (int)$_SESSION["timeEndTask-2"]["minutes"];
    }

    public function calculateSumTimeSecondLoad(): int
    {
        return (int)$_SESSION["timeEndTask-1"]["seconds"] + (int)$_SESSION["timeEndTask-2"]["seconds"];
    }

    public function convertToPrettyString(string $time): string
    {
        if (strlen($time) <= 1) {
            return '0' . $time;
        }
        return $time;
    }
}