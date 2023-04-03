<?php declare(strict_types=1);

namespace App\Class;

use App\Class\Task\TaskCompletion;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;


class PercentageCompletion
{
    public int $startPercent = 0;
    public int $currentPercent = 0;

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
            $_SESSION['SurnameClient'],
            $_SESSION['PatronymicClient']
        )) {
            $this->calculatePercentageCompletion();
        }
        if ($taskCompletion->checkAddingPetToTheProgram(
            $_SESSION['AnimalName'],
            $_SESSION['AnimalColor'],
            $_SESSION['DateOfBirth']
        )) {
            $this->calculatePercentageCompletion();
        }
        if ($taskCompletion->checkAddingMedicalCardToTheProgram()) {
            $this->calculatePercentageCompletion();
        }
//        if ($taskCompletion->checkNoteTheComplaint()) {
//            $this->calculatePercentageCompletion();
//        }
//        if ($taskCompletion->checkAnimalDiagnosis($_SESSION['Diagnose'])) {
//            $this->calculatePercentageCompletion();
//        }
////        if($taskCompletion->checkCreateInvoiceUsingCoupon()) {
////            $this->calculatePercentageCompletion();
////        }
////        if($taskCompletion->checkRepeatAppointmentToTheClinic()) {
////            $this->calculatePercentageCompletion();
////        }
        return $this->currentPercent . "%";
    }

    public function getPercentageCompletion(): string
    {
        return $this->startPercent . "%";
    }

    public function calculatePercentageCompletion(): string
    {
        $this->currentPercent = ($this->currentPercent + $this->calculatePercentageCountParagraph());
        return $this->currentPercent . "%";
    }

    private function calculatePercentageCountParagraph(): float
    {
        return 100 / 16;
    }
}