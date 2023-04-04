<?php declare(strict_types=1);

namespace App\Class;

use App\Class\Task\TaskCompletion;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;


class PercentageCompletion
{
    public int $startPercent = 0;
    public float $currentPercent = 0;

    public function __construct(
        public int $idTask
    )
    {
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function checkCompletedTasksForUser(): string
    {
        $taskCompletion = new TaskCompletion('devmel', '31af0669fd1bcd6d145410795a6ef4f7');


//        if($taskCompletion->checkInitialAppointment()) {
//            $this->calculatePercentageCompletion();
//        }
        if ($taskCompletion->getIdClientToTheProgram(
            $_SESSION['NameClient'],
            $_SESSION['PatronymicClient'],
            $_SESSION['SurnameClient']

        )) {
            $this->currentPercent = $this->calculatePercentageCompletion();
        }
//        if ($taskCompletion->checkAddingPetToTheProgram(
//            $_SESSION['AnimalName'],
//            $_SESSION['AnimalColor'],
//            $_SESSION['DateOfBirth']
//        )) {
//            $this->currentPercent = $this->calculatePercentageCompletion();
//        }
        if ($taskCompletion->checkAddingMedicalCardToTheProgram()) {
            $this->currentPercent = $this->calculatePercentageCompletion();
        }
        if ($taskCompletion->checkNoteTheComplaint()) {
            $this->currentPercent = $this->calculatePercentageCompletion();
        }
        if ($taskCompletion->checkAnimalDiagnosis($_SESSION['Diagnose'])) {
            $this->currentPercent = $this->calculatePercentageCompletion();
        }
        if($taskCompletion->checkCreateInvoiceUsingCoupon()) {
            $this->currentPercent = $this->calculatePercentageCompletion();
        }
////        if($taskCompletion->checkRepeatAppointmentToTheClinic()) {
////            $this->currentPercent = $this->calculatePercentageCompletion();
////
         $_SESSION["ResultPercentage"] = $this->currentPercent . "%";
        return $_SESSION["ResultPercentage"];
    }

    public function getPercentageCompletion(): string
    {
        return $this->startPercent . "%";
    }

    public function calculatePercentageCompletion(): float
    {
        return ($this->currentPercent + $this->calculatePercentageCountParagraph());
    }

    private function calculatePercentageCountParagraph(): float
    {
        return 100 / 16;
    }
}