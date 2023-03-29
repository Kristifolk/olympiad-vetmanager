<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\Response;
use App\Services\View;
use App\Services\ViewPath;

class ResultController
{

    public function __construct(
    )
    {
    }

    public function viewResult(): void
    {
        $html = new View(ViewPath::Result,
            [
                'fullTransitTime' =>
                    [
                        'minute' => $this->calculateSumTimeMinuteLoad(),
                        'second' => $this->calculateSumTimeSecondLoad(),
                    ],
                'taskTransitTime' =>
                    [
                        [
                            'numberTask' => 1,
                            'minute' => $_SESSION["1"]["ResultTimeMinute"],
                            'second' => $_SESSION["1"]["ResultTimeSecond"],
                        ],
                        [
                            'numberTask' => 2,
                            'minute' => $_SESSION["2"]["ResultTimeMinute"],
                            'second' => $_SESSION["2"]["ResultTimeSecond"],
                        ],
                    ]
            ]
        );

        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
        (new Response((string)$templateWithContent))->echo();
    }

    public function calculateSumTimeMinuteLoad(): int
    {
        return $_SESSION["1"]["ResultTimeMinute"] + $_SESSION["2"]["ResultTimeMinute"];
    }

    public function calculateSumTimeSecondLoad(): int
    {
        return $_SESSION["1"]["ResultTimeSecond"] + $_SESSION["2"]["ResultTimeSecond"];
    }
}