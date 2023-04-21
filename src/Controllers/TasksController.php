<?php declare(strict_types=1);

namespace App\Controllers;

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

        $html = new View(
            $path,
            [
                'fullNameClient' => $_SESSION['FullNameClient'],
                'lastAndFirstNameClient' => $_SESSION['LastAndFirstNameClient'],
                'animalName' => $_SESSION['AnimalName'],
                'animalColor' => $_SESSION['AnimalColorGenitiveBase'],
                'animalAge' => $_SESSION['DateOfBirth'],
                'breed' => $_SESSION['Breed']['title']
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