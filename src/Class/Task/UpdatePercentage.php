<?php

namespace App\Class\Task;

use App\Class\PercentageCompletion;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;

session_start();
class UpdatePercentage
{
    /**
     * @throws VetmanagerApiGatewayException
     */
    public function updatePercentageCompletion():void
    {
        $string = (new PercentageCompletion())->checkCompletedTasksForUser();
        echo substr($string, 0, -1);
    }
}