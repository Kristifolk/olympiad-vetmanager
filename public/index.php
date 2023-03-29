<?php
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "config.php";


use App\Class\Timer;
use App\Controllers\ResultController;
use App\Controllers\StartController;
use App\Controllers\TasksController;
use App\Services\Response;
use App\Services\View;
use App\Services\ViewPath;





if (isset($_SERVER['REQUEST_URI'])) {
    try {
        match ($_SERVER['REQUEST_URI']) {
            '/' => (new StartController())->viewStart(),
            '/tasks_preparation' => (new StartController())->viewTasksPreparation(),
            '/task?id=1' => (new TasksController($_GET['id']))->viewTaskType(ViewPath::FirstTypeTask),
            '/task?id=2' => (new TasksController($_GET['id']))->viewTaskType(ViewPath::SecondTypeTask),
            '/results' => (new ResultController())->viewResult(),
            default => throw new \Exception('Unexpected match value'),
        };
    } catch (Exception $e) {
        //$html = new View(ViewPath::NotFound);
        //(new Response( $html))->echo();
    }
}

//if(isset($_GET['task-number'])) {
//
//    try {
//        match ($_GET['task-number']) {
//            '1' => (new Timer(49, 59))->getTimeBalance((int)mb_substr($_GET['timer-minute'], 0, -1), $_GET['timer-second'], ViewPath::SecondTypeTask),
//            '2' => (new Timer(49, 59))->getTimeBalance((int)mb_substr($_GET['timer-minute'], 0, -1), $_GET['timer-second'], ViewPath::Result),
//            //'result' => (new Timer(49, 59))->viewResult($_GET['timer-minute'], $_GET['timer-second'])
//            '1-result' => (new ResultController((int)mb_substr($_GET['timer-minute'], 0, -1), $_GET['timer-second'], 0, 0))->viewResult(),
//            'all-result' => (new ResultController((int)mb_substr($_GET['timer-minute'], 0, -1), $_GET['timer-second'], (int)mb_substr($_GET['timer-minute'], 0, -1), $_GET['timer-second']))->viewResult(),
//            //'result' => (new Timer(49, 59))->viewResult($_GET['timer-minute'], $_GET['timer-second'])
//        };
//    } catch (Exception $e) {
//        //$html = new View(ViewPath::NotFound);
//        //(new Response( $html))->echo();
//    }
//}

exit(0);