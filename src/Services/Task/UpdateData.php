<?php

namespace App\Services\Task;

use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;

class UpdateData
{
    /**
     * @throws VetmanagerApiGatewayException
     */
    public function updatePercentageCompletion(): never
    {
        $userId = $_SESSION['userId'];
        $string = (new PercentageCompletion())->checkCompletedTasksForUserInPercents($userId);
        $_SESSION["ResultPercentage"] = $string . "%";
        echo $string;
        exit();
    }

    public function updateTimeForTimerJS(): never
    {
        echo (new Timer())->getTimerAsString();
        exit();
    }
}