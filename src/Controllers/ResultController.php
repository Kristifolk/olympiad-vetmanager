<?php

namespace App\Controllers;

use App\Services\Response;
use App\Services\View;
use App\Services\ViewPath;

class ResultController
{
    public function viewResult(): void
    {
        $html = new View(ViewPath::Result,
            [
                'fullTransitTime' =>
                    [
                        'minute' => 20,
                        'second' => 10,
                    ],
                'taskTransitTime' =>
                    [
                        [
                            'numberTask' => 1,
                            'minute' => 5,
                            'second' => 10,
                        ],
                        [
                            'numberTask' => 2,
                            'minute' => 45,
                            'second' => 2,
                        ],
                    ]
            ]
        );

        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
        (new Response($templateWithContent))->echo();
    }
}