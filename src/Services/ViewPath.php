<?php

namespace App\Services;
enum ViewPath: string
{
    case Start = __DIR__ . "/../../view/start.php";
    case TasksPreparation = __DIR__ . "/../../view/tasks_preparation.php";
    case FirstTypeTask = __DIR__ . "/../../view/type_task/first_type_task.php";
    case Result = __DIR__ . "/../../view/result.php";
    case EndTime = __DIR__ . "/../../view/end_time.php";
    case NotFound = __DIR__ . "/../../view/not_found.php";
    case TemplateContent = __DIR__ . "/../../view/template.php";
    case AdminPanel = __DIR__ . "/../../view/admin_panel.php";
    case TemplateCertificate = __DIR__ . "/../../view/certificate.php";
    case Debug = __DIR__ . "/../../view/debug.php";
    case TemplateContentTask = __DIR__ . "/../../view/template_tasks.php";
    case TimerContent = __DIR__ . "/../../view/component/timer.php";
    case PercentageCompletionContent = __DIR__ . "/../../view/component/percentage_completion.php";
    case ModalAuthorizationWindow = __DIR__ . "/../../view/component/authorization_modal.php";
}