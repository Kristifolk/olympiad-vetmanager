<?php declare(strict_types=1);

namespace App\Services\Task;

use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;

session_start();

class Timer
{
    public function startTimer(): void
    {
        $_SESSION["StartTime"] = time();
        header('Location: /task?id=1');
    }

    /**
     * @return array{minutes:int, seconds:int}
     */
    public function getTimerValues(): array
    {
        $currentTime = time();
        $timeDifference = $currentTime - $_SESSION["StartTime"];

        return $this->convertTimeOnMinuteAndSecond($timeDifference);
    }

    public function getStringTime(): string
    {
        $arrayTime = $this->getTimerValues();

        return $arrayTime["minutes"] . ":" . $arrayTime["seconds"];
    }

    /**
     * @throws VetmanagerApiGatewayException
     * @throws \JsonException
     */
    public function storeTaskValue(): void
    {
        $_SESSION["ResultPercentage"] = (new PercentageCompletion())->checkCompletedTasksForUserInPercents() . '%';
        (new PercentageCompletion())->storePercentageCompletionIntoFile();
        $_SESSION["TimeEndTask"] = $this->getTimerValues();
        header('Location: /result');
    }

    /**
     * @throws VetmanagerApiGatewayException
     * @throws \JsonException
     */
    public function storeTaskValueForEndTime(): void
    {
        $_SESSION["ResultPercentage"] = (new PercentageCompletion())->checkCompletedTasksForUserInPercents() . '%';
        (new PercentageCompletion())->storePercentageCompletionIntoFile();
        $_SESSION["TimeEndTask"] = $this->getTimerValues();
        header('Location: /end_time');
    }

    private function convertTimeOnMinuteAndSecond(int $timeDifference): array
    {
        return [
            'minutes' => $this->beautifulTimeForJS((int)round($timeDifference / 60)),
            'seconds' => $this->beautifulTimeForJS($timeDifference % 60)
        ];
    }

    private function beautifulTimeForJS(int $time): string
    {
        if ($time < 10) {
            return "0" . $time;
        }

        return (string)$time;
    }
}