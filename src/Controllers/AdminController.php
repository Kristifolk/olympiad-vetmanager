<?php

namespace App\Controllers;

use App\Services\Data\DataForRedis;
use App\Services\Response;
use App\Services\View;
use App\Services\ViewPath;

class AdminController
{

    public function viewResult(): never
    {
        $dataResultUser = (new DataForRedis())->getDataAllUsers();

//        foreach ($dataResultUser as $singleUserResult) {
//            if() {
//                $userId = $singleUserResult[];
//                (new PercentageCompletion())->calculateResultsForUserAndStore($userId);
//            }
//        }

        $html = new View(ViewPath::AdminPanel, ['resultTask' => $dataResultUser, 'resultMarks' => $this->getResultTrueMarks((array)$dataResultUser)]);

        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
        (new Response((string)$templateWithContent))->echoAndDie();
    }

    private function getResultTrueMarks(array $data): float
    {
        $result = 0;

        foreach ($data as $value) {
            if (isset($value[2]["done"]) && $value[2]["done"] == "true") {
                $result += $value[2]["marks"];
            }
        }

        return $result;
    }
}