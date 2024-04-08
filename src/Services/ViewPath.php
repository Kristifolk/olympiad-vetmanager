<?php

namespace App\Services;
enum ViewPath: string
{
    case Start = "view/start.php";
    case TasksPreparation = "view/tasks_preparation.php";
    case FirstTypeTask = "view/type_task/first_type_task.php";
    case SecondTypeTask = "view/type_task/second_type_task.php";
    case Result = "view/result.php";
    case EndTime = "view/end_time.php";
    case NotFound = "view/not_found.php";
    case TemplateContent = "view/template.php";
    case AdminPanel = "view/admin_panel.php";
    case TemplateCertificate = "view/certificate.php";
    case Debug = "view/debug.php";
    case TemplateContentTask = "view/template_tasks.php";
    case TimerContent = "view/component/timer.php";
    case PercentageCompletionContent = "view/component/percentage_completion.php";
    case ModalAuthorizationWindow = "view/component/authorization_modal.php";
}