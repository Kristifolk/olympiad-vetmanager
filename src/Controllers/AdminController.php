<?php

namespace App\Controllers;

use App\Services\Data;
use App\Services\Response;
use App\Services\View;
use App\Services\ViewPath;
use JsonException;

class AdminController
{
    /**
     * @throws JsonException
     */
    public function viewResult(): void
    {
        $dataResultUser = (new Data())->getDataFromJsonFile(USER_TASKS_PATH);
        $html = new View(ViewPath::AdminPanel, ['resultTask' => $dataResultUser, 'resultMarks' => $this->getResultTrueMarks($dataResultUser)]);

        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
        (new Response((string)$templateWithContent))->echo();
    }

    private function getResultTrueMarks(array $data): float
    {
        $result = 0;

        foreach ($data as $value) {
            if ($value[2]["done"] == "true") {
                $result += $value[2]["marks"];
            }
        }

        return $result;
    }
}