<?php declare(strict_types=1);

namespace App\Controllers;

use App\File\FileData;
use App\Services\Response;
use App\Services\View;
use App\Services\ViewPath;

session_start();

class ResultController
{


    public function viewResult(): void
    {
        $dataResultUser = $this->getResultData();
        $html = new View(ViewPath::Result,
            [
                'taskTransitTime' =>
                    [
                        'minute' => $this->convertToPrettyString((string)$_SESSION["TimeEndTask"]["minutes"]),
                        'second' => $this->convertToPrettyString((string)$_SESSION["TimeEndTask"]["seconds"]),
                        'resultPercentage' => $_SESSION["ResultPercentage"]
                    ],
                'resultTask' => $dataResultUser,
                'resultMarks' => (string)$this->getResultTrueMarks($dataResultUser)
            ]
        );

        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
        (new Response((string)$templateWithContent))->echo();
    }

    private function convertToPrettyString(string $time): string
    {
        if (strlen($time) <= 1) {
            return '0' . $time;
        }

        return $time;
    }

    /**
     * @throws \JsonException
     */
    private function getResultData(): array
    {
        $allDataUser = (new FileData())->getDataForUserId($_SESSION["UserId"]);
        return $allDataUser[1];
    }

    private function getResultTrueMarks(array $data): float
    {
        $result = 0;

        foreach ($data as $value) {
            if ($value["done"] == "true") {
                $result += $value["marks"];
            }
        }

        return $result;
    }

}