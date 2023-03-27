<?php
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "config.php";


use App\Controllers\ResultController;
use App\Controllers\StartController;
use App\Controllers\TasksController;
use App\Services\Response;
use App\Services\View;
use App\Services\ViewPath;

if (isset($_SERVER['REQUEST_URI'])) {
    //$page = explode("/", $_SERVER['REQUEST_URI']);
    try {
        match ($_SERVER['REQUEST_URI']) {
            '/' => (new StartController())->viewStart(),
            '/tasks_preparation' => (new StartController())->viewTasksPreparation(),
            '/task' => (new TasksController())->viewTask(),
            '/result' => (new ResultController())->viewResult(),
            default => throw new \Exception('Unexpected match value'),
        };
    } catch (Exception $e) {
        $html = new View(ViewPath::NotFound);
        (new Response('failed', $html, null))->echo();
    }
}


//if (isset($_GET['page'])) {
//    try {
//        match ($_GET['page']) {
//            'start' => (new StartController())->viewStart(),
//            'tasks_preparation' => (new StartController())->viewTasksPreparation(),
//            'task' => (new TasksController())->viewTask(),
//            'result' => (new ResultController())->viewResult(),
//        };
//    } catch (Exception $e) {
//        $html = new View(ViewPath::NotFound);
//        (new Response('failed', $html, null))->echo();
//    }
//}

exit(0);