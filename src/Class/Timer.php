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
    public function getStringTime():string
    {
        $arrayTime = $this->getTimerValues();

        $stringTime = $arrayTime["minutes"] . ":" . $arrayTime["seconds"];
        return $stringTime;
    }

    public function storeTaskValue(): void
    {
        $_SESSION["TimeEndTask"] = $this->getTimerValues();
        header('Location: /result');
    }

    private function convertTimeOnMinuteAndSecond(int $timeDifference): array
    {
        return [
            'minutes' => $this->beautifulTimeForJS((int)round($timeDifference / 60)),
            'seconds' => $this->beautifulTimeForJS($timeDifference % 60)
        ];
    }

    private function beautifulTimeForJS(int $time):string
    {
        if($time < 10){
            return "0" . $time;
        }

        return (string)$time;
    }

}