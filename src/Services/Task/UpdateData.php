<?php

namespace App\Services\Task;

use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;

class UpdateData
{
    /**
     * @throws VetmanagerApiGatewayException
     */
    public function updatePercentageCompletion(): void
    {
        $string = (new PercentageCompletion())->checkCompletedTasksForUserInPercents();
        $_SESSION["ResultPercentage"] = $string . "%";
        echo $string;
    }

    public function updateTimeForTimerJS(): void
    {
        echo (new Timer())->getTimerAsString();
    }
}