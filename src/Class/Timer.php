<?php declare(strict_types=1);

namespace App\Class;

use App\Controllers\ResultController;
use App\Controllers\TasksController;
use App\Interfaces\TimeInterface;
use App\Services\ViewPath;

class Timer //implements TimeInterface
{

    public function __construct(
//        public int $timeFullMinute,
//        public int $timeFullSecond
    )
    {
    }

    public function startTime(int $idTask): void
    {
        session_start();
        $_SESSION["$idTask"] = [
            "StartTimeMinute" => 49,
            "StartTimeSecond" => 59,
        ];
        $this->getLeadTimeTaskResult($idTask);
    }

    public function getLeadTimeTaskResult(int $idTask): void
    {
        $_SESSION["$idTask"] = [
            "ResultTimeMinute" => $this->calculationTimeTransitMinute($_SESSION["EndTimeMinute"]),
            "ResultTimeSecond" => $this->calculationTimeTransitSecond($_SESSION["EndTimeMinute"])
        ];
    }

//    public function getTimeBalance(): void
//    {
//        $this->getLeadTimeTaskResult($_SESSION["EndTimeMinute"], $_SESSION["EndTimeSecond"]);

//        $_SESSION["EndTimeMinute"] =  $_SESSION["StartTimeMinute"];
//        $_SESSION["EndTimeSecond"] = $_SESSION["StartTimeMinute"];
        //(new TasksController($this->timeFullMinute, $this->timeFullSecond))->viewTaskType($path);
        //header("Location: http://localhost:8080/task");
//        if ($path == ViewPath::Result) {
//            header("Location: http://localhost:8080/results");
//            //(new ResultController($this->calculationTimeTransitMinute($timeGetMinute), $this->calculationTimeTransitSecond($timeGetSecond), 0, 0))->viewResult();
//        }
 //   }

    public function calculationTimeTransitMinute(int $timeGetMinute): int
    {
        return $_SESSION["StartTimeMinute"] - $timeGetMinute;
    }

    public function calculationTimeTransitSecond(int $timeGetSecond): int
    {
        return $_SESSION["StartTimeSecond"] - $timeGetSecond;
    }
}