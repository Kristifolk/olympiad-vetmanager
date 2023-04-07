<?php

namespace App\Class\Task;

use App\Class\PercentageCompletion;
use App\Class\Timer;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;

session_start();

class UpdateData
{
    /**
     * @throws VetmanagerApiGatewayException
     */
    public function updatePercentageCompletion(): void
    {
        $string = (new PercentageCompletion())->checkCompletedTasksForUserInPercents();
        echo substr($string, 0, -1);
    }

    public function updateTimeForTimerJS(): void
    {
        echo (new Timer())->getStringTime();
    }
}