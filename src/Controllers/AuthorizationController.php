<?php

namespace App\Controllers;

use App\Services\Data\DataForJonFile;
use App\Services\Data\DataForRedis;
use App\Services\Response;
use App\Services\Task\TaskCollection;
use App\Services\View;
use App\Services\ViewPath;
use Exception;
use JsonException;

session_start();

class AuthorizationController
{
    public function viewAuthentication(): void
    {
        $this->defaultSessionData();
        $html = new View(ViewPath::ModalAuthorizationWindow);
        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
        (new Response((string)$templateWithContent))->echo();
    }

    /**
     * @throws Exception
     */
    public function registerUser(string $firstName, string $lastName, string $middleName): void
    {
        if (empty($firstName) || empty($lastName) || empty($middleName)) {
            throw new Exception('Not valid user data');
        }

        $userId = (new DataForJonFile())->getAvailableUserId();
        $_SESSION["userId"] = $userId;

        if (empty($userId)) {
            throw new Exception('No logins available');
        }

        $loginAndPassword = $this->previewLoginAndPasswordForId($userId);
        $template = $this->getTemplateForTask();
        $testData = $this->loadDataTask($template);

        $fullNameParticipant = [
            "firstName" => $firstName,
            "lastName" => $lastName,
            "middleName" => $middleName
        ];
        $_SESSION["participantData"] = $fullNameParticipant;

        $userData = array_merge($fullNameParticipant, $loginAndPassword, $testData);
        (new DataForRedis())->putNewDataFileForTaskArray($userId, $userData);
    }


    /**
     * @throws JsonException
     * @throws Exception
     */
    private function loadDataTask(array $template): array
    {
        return (new TaskCollection($template))->generateData();
    }

    /**
     * @throws JsonException
     */
    private function previewLoginAndPasswordForId(int $userId): array
    {
        return (new DataForJonFile())->getLoginAndPasswordAndTemplateForUserId($userId);
    }

    /**
     * @throws JsonException
     */
    private function getTemplateForTask(): array
    {
        return (new DataForJonFile())->getTemplateTask();
    }

    private function defaultSessionData(): void
    {
        $_SESSION["ResultPercentage"] = '0%';
        $_SESSION["TimeEndTask"] = ["minutes" => "00", "seconds" => "00"];
    }
}