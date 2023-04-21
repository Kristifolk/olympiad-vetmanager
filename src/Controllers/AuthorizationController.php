<?php

namespace App\Controllers;

use App\Services\Response;
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
            throw new Exception('');
        }

        $_SESSION["participantData"] = [
            "firstName" => $firstName,
            "lastName" => $lastName,
            "middleName" => $middleName
        ];
    }
}