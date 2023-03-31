<?php declare(strict_types=1);

namespace App\Controllers;

use App\Class\Task\AuthorizationRequest;
use App\Services\Response;
use App\Services\View;
use App\Services\ViewPath;

use GuzzleHttp\Client;

session_start();

class StartController
{
    public string $loginUser;

    public function viewInstructions(): void
    {
        $html = (string)new View(ViewPath::Start);
        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
        (new Response((string)$templateWithContent))->echo();
    }

    public function viewTasksPreparation(): void
    {
        $_SESSION["TestLogin"] = $this->generateUserLogin();

        $html = new View(ViewPath::TasksPreparation, ['login' => $_SESSION["TestLogin"]]);
        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
        (new Response((string)$templateWithContent))->echo();
    }

    public function generateUserLogin(): string
    {
        $userLoginArray = $this->dataUserLogin();
        $this->loginUser = $userLoginArray[rand(0, count($userLoginArray) - 1)];
        return $this->loginUser;
    }

    public function dataUserLogin(): array
    {
        return [
            'admin',
            'admin2',
            'admin3',
            'admin4',
            'admin5',
            'admin6',
            'admin7',
            'admin8',
            'admin9',
            'admin10'
        ];
    }
}