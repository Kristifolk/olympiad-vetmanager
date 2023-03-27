<?php

namespace App\Services;
enum ViewPath: string
{
    case Start = __DIR__ . "/../../view/start.php";
    case TasksPreparation = __DIR__ . "/../../view/tasks_preparation.php";
    case Task = __DIR__ . "/../../view/task.php";
    case Result = __DIR__ . "/../../view/result.php";
    case NotFound = __DIR__ . "/../../view/not_found.php";
}