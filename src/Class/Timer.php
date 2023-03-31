<?php declare(strict_types=1);

namespace App\Class;

use App\Controllers\ResultController;
use App\Controllers\TasksController;
use App\Interfaces\TimeInterface;
use App\Services\ViewPath;

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

    public function storeTaskValue(int $id, ?string $option): void
    {
        $_SESSION["timeEndTask-$id"] = $this->getTimerValues();

        if ($id == '2' or $option == 'result') {
            header('Location: /result');
        } else {

            header('Location: /task?id=2');
        }
    }

    public function convertTimeOnMinuteAndSecond(int $timeDifference): array
    {
        return [
            'minutes' => round($timeDifference / 60),
            'seconds' => $timeDifference % 60
        ];
    }
}