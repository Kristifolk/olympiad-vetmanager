<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\Data\DataForRedis;
use App\Services\Response;
use App\Services\Task\PercentageCompletion;
use App\Services\Task\Timer;
use App\Services\View;
use App\Services\ViewPath;
use InvalidArgumentException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;

class TasksController
{

    public function __construct(
        public int $idTask,
    )
    {
    }

    private function getView(): ViewPath
    {
        return match ($this->idTask) {
            1 => ViewPath::FirstTypeTask,
            2 => ViewPath::SecondTypeTask,
            default => throw new InvalidArgumentException()
        };
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function viewTask(): void
    {
        $time = (new Timer())->getTimerAsArray();
        $path = $this->getView();
        $_SESSION["ResultPercentage"] = (new PercentageCompletion())->checkCompletedTasksForUserInPercents() . '%';

        $redis = new DataForRedis();

        $html = new View(
            $path,
            [
                'fullNameClient' => $redis->getDataFileForTaskByUser($_SESSION["userId"], 'add_client:meaning'),
                'lastAndFirstNameClient' => $_SESSION['LastAndFirstNameClient'],
                'animalName' => $redis->getDataFileForTaskByUser($_SESSION["userId"], 'alias:meaning'),
                'animalColor' => $redis->getDataFileForTaskByUser($_SESSION["userId"], 'color:meaning'),
                'animalAge' => $redis->getDataFileForTaskByUser($_SESSION["userId"], 'dateOfBirth:meaning'),
                'gender' => $redis->getDataFileForTaskByUser($_SESSION["userId"], 'gender:meaning'),
                'breed' => $redis->getDataFileForTaskByUser($_SESSION["userId"], 'breed:meaning'),
                'login' => $redis->getDataFileForTaskByUser($_SESSION["userId"], 'login'),
                'password' => $redis->getDataFileForTaskByUser($_SESSION["userId"], 'password')
            ]
        );
        $timerHtml = new View(ViewPath::TimerContent,
            [
                'minutes' => $time['minutes'],
                'seconds' => $time['seconds']
            ]
        );
        $percentageCompletionHtml = new View(ViewPath::PercentageCompletionContent,
            [
                'percentageCompletion' => $_SESSION["ResultPercentage"]
            ]
        );
        $templateWithContentTask = new View(ViewPath::TemplateContentTask,
            [
                'task' => $html,
                'timer' => $timerHtml,
                'percentageCompletion' => $percentageCompletionHtml,
                'taskNumber' => $this->idTask
            ]
        );

        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $templateWithContentTask]);
        (new Response((string)$templateWithContent))->echo();
    }
}