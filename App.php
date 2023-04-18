<?php

use App\Controllers\AdminController;
use App\Controllers\AuthorizationController;
use App\Controllers\ResultController;
use App\Controllers\StartController;
use App\Controllers\TasksController;
use App\Services\Response;
use App\Services\Task\Timer;
use App\Services\Task\UpdateData;
use App\Services\View;
use App\Services\ViewPath;

if (isset($_SERVER['REQUEST_URI'])) {
    try {
        match ($_SERVER['REQUEST_URI']) {
            '/' => (new StartController())->viewInstructions(),
            '/authorization' => (new AuthorizationController())->viewAuthentication(),
            '/tasks_preparation' => (new StartController())->viewTasksPreparation(),
            '/start' => (new Timer())->startTimer(),
            '/task?id=1' => (new TasksController($_GET['id']))->viewTask(),
            '/store?id=1' => (new Timer())->storeTaskValue(),
            '/result' => (new ResultController())->viewResult(),
            '/update_percentage_completion' => (new UpdateData())->updatePercentageCompletion(),
            '/update_time' => (new UpdateData())->updateTimeForTimerJS(),
            '/admin_vetmanager_info_olympiad' => (new AdminController())->viewResult(),
            '/authorization_participant' => (new AuthorizationController())->validationAuthentication(
                trim($_POST['last-name']),
                trim($_POST['first-name']),
                trim($_POST['middle-name'])
            ),
            '/store_end_time' => (new Timer())->storeTaskValueForEndTime(),
            '/end_time' => (new ResultController())->viewEndTime(),
            default => throw new \Exception('Unexpected match value'),
        };
    } catch (Exception $e) {
        $html = new View(ViewPath::NotFound);
        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
        (new Response((string)$templateWithContent))->echo();
    }
}
