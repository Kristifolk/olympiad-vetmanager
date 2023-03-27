<?php

namespace App\Controllers;

use App\Services\Response;
use App\Services\View;
use App\Services\ViewPath;

class TasksController
{
    public function viewTask(): void
    {
        $html = new View(ViewPath::Task);
        (new Response('success', $html, []))->echo();
    }
}