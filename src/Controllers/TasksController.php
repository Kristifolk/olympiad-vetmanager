<?php declare(strict_types=1);

namespace App\Controllers;

use App\Class\PercentageCompletion;
use App\Class\Timer;
use App\Services\Response;
use App\Services\View;
use App\Services\ViewPath;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;

readonly class TasksController
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
            default => throw new \InvalidArgumentException()
        };
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function viewTask(): void
    {
        $time = (new Timer())->getTimerValues();
        $path = $this->getView();

        $html = new View(
            $path,
            [
                'fullNameClient' => $_SESSION['FullNameClient'],
                'lastAndFirstNameClient' => $_SESSION['LastAndFirstNameClient'],
                'animalName' => $_SESSION['AnimalName'],
                'animalColor' => $_SESSION['AnimalColor'],
                'animalAge' => $_SESSION['AnimalAge']
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
                'percentageCompletion' => (new PercentageCompletion())->checkCompletedTasksForUser()
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