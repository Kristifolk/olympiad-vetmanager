<?php
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "config.php";


use App\Class\PercentageCompletion;
use App\Class\Task\UpdateData;
use App\Class\Timer;
use App\Controllers\ResultController;
use App\Controllers\StartController;
use App\Controllers\TasksController;
use App\File\FileData;
use App\Services\Response;
use App\Services\View;
use App\Services\ViewPath;

if (isset($_SERVER['REQUEST_URI'])) {
    try {
        match ($_SERVER['REQUEST_URI']) {
            '/' => (new StartController())->viewInstructions(),
            '/tasks_preparation' => (new StartController())->viewTasksPreparation(),
            '/start' => (new Timer())->startTimer(),
            '/task?id=1'=> (new TasksController($_GET['id']))->viewTask(),
            '/store?id=1' => (new Timer())->storeTaskValue(),
            '/result' => (new ResultController())->viewResult(),
            '/update_percentage_completion' => (new UpdateData())->updatePercentageCompletion(),
            '/update_time' => (new UpdateData())->updateTimeForTimerJS(),
            //'/resources/file/userCollection.json' => (new FileData)->checkLoginUserInToFile('dfsef'),
            default => throw new \Exception('Unexpected match value'),
        };
    } catch (Exception $e) {
        $html = new View(ViewPath::NotFound);
        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
        (new Response((string)$templateWithContent))->echo();
    }
}

exit(0);