<?php

namespace App\Controllers;

use App\Services\Data\DataForJonFile;
use App\Services\Data\DataForRedis;
use App\Services\Response;
use App\Services\Task\TaskCollection;
use App\Services\View;
use App\Services\ViewPath;
use Exception;

session_start();

class AuthorizationController
{
    public function viewAuthentication(): void
    {
        $html = new View(ViewPath::ModalAuthorizationWindow);
        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
        (new Response((string)$templateWithContent))->echo();
    }

    /**
     * @throws Exception
     */
    public function storeNotEmptyNameInSession(string $firstName, string $lastName, string $middleName): void
    {
        if (empty($firstName) || empty($lastName) || empty($middleName)) {
            throw new Exception('Not valid user data');
        }

        if (!$this->checkAvailableUserId()) {
            throw new Exception('No logins available');
        }
        var_dump($this->loadDataTask());
        (new DataForRedis())->putNewDataFileForTask($this->loadDataTask());

        $_SESSION["participantData"] = [
            "firstName" => $firstName,
            "lastName" => $lastName,
            "middleName" => $middleName
        ];
    }

    private function checkAvailableUserId(): bool
    {
        return true;
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    private function loadDataTask()
    {
        $taskData = new TaskCollection();
        $taskData->defaultSessionData();
        $taskData->generateAnimalAge();
        $taskData->generateAnimalColor();
        $taskData->generateAnimalName();
        $taskData->generateBreedPet();
        $taskData->generateFullNameClient();
        $taskData->generateLastAndFirstNameClient();


        $userData = (new DataForJonFile())->getAvailableUserId();
        var_dump($userData);
//        $_SESSION["UserId"] = $userData[]['userId'];
//        $_SESSION["TestLogin"] = $userData[]['login'];
//        $_SESSION["TestPassword"] = $userData[]['password'];

        return (new DataForJonFile())->defaultDataUser($_SESSION["participantData"]);
    }
}