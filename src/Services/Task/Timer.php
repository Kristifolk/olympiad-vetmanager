<?php declare(strict_types=1);

namespace App\Services\Task;

use App\Services\Data\DataForRedis;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;


class Timer
{
    public function startTimer(): never
    {
        $variant = (new DataForRedis())->getDataFileForTaskByUser($_SESSION["userId"], 'variant');

        if ((int)$variant == 1) {
            $_SESSION["StartTime"] = time();
            header('Location: /task?id=1');
        }
        if ((int)$variant == 2) {
            $_SESSION["StartTime"] = time();
            header('Location: /task?id=2');
        }
        exit();
    }

    /**
     * @return array{minutes:int, seconds:int}
     */
    public function getTimerAsArray(): array
    {
        $currentTime = time();
        $timeDifference = $currentTime - $_SESSION["StartTime"];

        return $this->convertTimeOnMinuteAndSecond($timeDifference);
    }

    public function getTimerAsString(): string
    {
        $arrayTime = $this->getTimerAsArray();
        return $arrayTime["minutes"] . ":" . $arrayTime["seconds"];
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function storeTaskValueForResult(): never
    {
        $this->storeValue();
        header('Location: /result');
        exit();
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function storeTaskValueForEndTime(): never
    {
        $this->storeValue();
        header('Location: /end_time');
        exit();
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function storeValue(): void
    {
        $userId = (int)$_SESSION["userId"];
        $_SESSION["ResultPercentage"] = (new PercentageCompletion())->checkCompletedTasksForUserInPercents($userId) . '%';
        (new PercentageCompletion())->calculateResultsForUserAndStore($userId);
        $_SESSION["TimeEndTask"] = $this->getTimerAsArray();
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