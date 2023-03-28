<?php

namespace App\Services;
enum ViewPath: string
{
    case Start = __DIR__ . "/../../view/start.php";
    case TasksPreparation = __DIR__ . "/../../view/tasks_preparation.php";
    case FirstTypeTask = __DIR__ . "/../../view/first_type_task.php";
    case SecondTypeTask = __DIR__ . "/../../view/second_type_task.php";
    case Result = __DIR__ . "/../../view/result.php";
    case NotFound = __DIR__ . "/../../view/not_found.php";
    case TemplateContent = __DIR__ . "/../../view/template.php";
    case TimerContent = __DIR__ . "/../../view/component/timer.php";
}