<?php

use App\Controllers\AdminController;
use App\Controllers\AuthorizationController;
use App\Controllers\CertificateController;
use App\Controllers\ResultController;
use App\Controllers\StartController;
use App\Controllers\TasksController;
use App\Services\Response;
use App\Services\Task\Timer;
use App\Services\Task\UpdateData;
use App\Services\View;
use App\Services\ViewPath;

session_start();

if (isset($_SERVER['REQUEST_URI'])) {
    match ($_SERVER['REQUEST_URI']) {
        '/' => (new StartController())->viewInstructions(),
        '/authorization' => (new AuthorizationController())->viewAuthentication(),
        '/authorization_participant' => (new AuthorizationController())->registerUser(
            trim($_POST['last-name']),
            trim($_POST['first-name']),
            trim($_POST['middle-name']),
            trim($_POST['email'])
        ),
        '/admin_vetmanager_info_olympiad' => (new AdminController())->viewResult(),
        default => function () {}, // #TODO Delete
    };

    if (isset($_SESSION['userId'])) {
        match ($_SERVER['REQUEST_URI']) {
            '/tasks_preparation' => (new StartController())->viewTasksPreparation(),
            '/start' => (new Timer())->startTimer(),
            '/task?id=1', '/task?id=2' => (new TasksController($_GET['id']))->viewTask(),
            '/store' => (new Timer())->storeTaskValueForResult(),
            '/result' => (new ResultController())->viewResultForUser(),
            '/update_percentage_completion' => (new UpdateData())->updatePercentageCompletion(),
            '/update_time' => (new UpdateData())->updateTimeForTimerJS(),
            '/store_end_time' => (new Timer())->storeTaskValueForEndTime(),
            '/end_time' => (new ResultController())->viewEndTime(),
            '/certificate' => (new CertificateController())->getCertificate(),
            default => function () {}, // #TODO Delete
        };
    }
}

$html = new View(ViewPath::NotFound);
$templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html]);
(new Response((string)$templateWithContent))->echoAndDie();
