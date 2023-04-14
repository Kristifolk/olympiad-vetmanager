<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\Data;
use App\Services\Response;
use App\Services\View;
use App\Services\ViewPath;

session_start();

class StartController
{
    public function viewInstructions(): void
    {
        $html = (string)new View(ViewPath::Start);
        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
        (new Response((string)$templateWithContent))->echo();
    }

    /**
     * @throws \JsonException
     */
    public function viewTasksPreparation(): void
    {
        $this->loadDataTask();
        $html = new View(ViewPath::TasksPreparation);
        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
        (new Response((string)$templateWithContent))->echo();
    }

    /**
     * @throws \JsonException
     */
    private function loadDataTask(): void
    {
        $userData = (new Data())->getIdAndLoginAndPasswordOfParticipant($_SESSION["participantData"]);
        $_SESSION["UserId"] = $userData['userId'];
        $_SESSION["TestLogin"] = $userData['login'];
        $_SESSION["TestPassword"] = $userData['password'];

        $taskData = new TaskCollection();
        $taskData->defaultSessionData();
        $taskData->generateAnimalAge();
        $taskData->generateAnimalColor();
        $taskData->generateAnimalName();
        $taskData->generateBreedPet();
        $taskData->generateFullNameClient();
        $taskData->generateLastAndFirstNameClient();
    }
}