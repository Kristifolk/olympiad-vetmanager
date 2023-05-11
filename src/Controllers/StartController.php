<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\Response;
use App\Services\View;
use App\Services\ViewPath;
use JsonException;

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
     * @throws JsonException
     */
    public function viewTasksPreparation(): void
    {
        $html = new View(ViewPath::TasksPreparation);
        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
        (new Response((string)$templateWithContent))->echo();
    }
}