<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\Response;
use App\Services\View;
use App\Services\ViewPath;

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
        $this->defaultSessionData();

        $_SESSION["TestLogin"] = $this->generateUserLogin();

        $html = new View(ViewPath::TasksPreparation, ['login' => $_SESSION["TestLogin"]]);
        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
        (new Response((string)$templateWithContent))->echo();
    }

    private function generateUserLogin(): string
    {
        $userLoginArray = $this->dataUserLogin();
        $this->loginUser = $userLoginArray[rand(0, count($userLoginArray) - 1)]['login'];
        $_SESSION["TestPassword"] = $userLoginArray[rand(0, count($userLoginArray) - 1)]['password'];
        return $this->loginUser;
    }


    private function dataUserLogin(): array
    {
        return [
            ['login' => 'admin1', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin2', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin3', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin4', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin5', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin6', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin7', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin8', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin9', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin10', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin11', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin12', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin13', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin14', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin15', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin16', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin17', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin18', 'password' => 'iJ1x9nfO'],
        ];
    }

    private function defaultSessionData(): void
    {
        $_SESSION["ResultPercentage"] = '0%';
        $_SESSION["TimeEndTask"] = ["minutes" => "00", "seconds" => "00"];
    }
}