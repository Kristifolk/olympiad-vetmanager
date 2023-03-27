<?php

namespace App\Controllers;

use App\Services\Response;
use App\Services\View;
use App\Services\ViewPath;

class StartController
{
    public function viewStart(): void
    {
        $html = new View(ViewPath::Start);
        (new Response('success', $html, []))->echo();
    }
    public function viewTasksPreparation(): void
    {
        $html = new View(ViewPath::TasksPreparation);
        (new Response('success', $html, []))->echo();
    }
}