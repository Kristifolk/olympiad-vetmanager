<?php

namespace App\Class\Task;


use Otis22\VetmanagerRestApi\Query\Builder;
use VetmanagerApiGateway\ApiGateway;
use VetmanagerApiGateway\DTO\DAO\Client;
use VetmanagerApiGateway\DTO\DAO\ComboManualItem;
use VetmanagerApiGateway\DTO\DAO\ComboManualName;
use VetmanagerApiGateway\DTO\DAO\Medcard;
use VetmanagerApiGateway\DTO\DAO\Pet;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;


class TaskCompletion
{
    private int $idClient;
    private int $idPet;
    private int $idMedicalCard;

    public function __construct(
        private string $domainName = 'devmel',
        private string $apiKey = '31af0669fd1bcd6d145410795a6ef4f7'
    )
    {
    }

    /**
     * @throws VetmanagerApiGatewayRequestException
     */
    private function getApiGateway(): ApiGateway
    {
        return ApiGateway::fromDomainAndApiKey(
            $this->domainName,
            $this->apiKey,
            true,
        );
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function checkInitialAppointment()
    {
//        $client =// Client::fromRequestGetById($this->getApiGateway(), 1);
//        return $client->;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function getIdClientToTheProgram(
        string $firstName,
        string $middleName,
        string $lastName,
    ): bool
    {
        $clients = Client::fromRequestGetByPagedQuery(
            $this->getApiGateway(),
            (new Builder ())
                ->where('first_name', $firstName)
                ->where('middle_name', $middleName)
                ->where('last_name', $lastName)
                ->top(1)
        );

        if (count($clients) == 1) {
            $this->idClient = $clients[0]->id;
            return true;
        }

        return false;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function checkAddingPetToTheProgram(
        string $aliasPet,
        string $animalColor,
        string $animalDateOfBirth,
    ): bool
    {
        $pets = Pet::fromRequestGetByPagedQuery(
            $this->getApiGateway(),
            (new Builder())
                ->where('alias', $aliasPet)
                ->where('color_id', $this->getColorIdByName($animalColor))
                ->where('birthday', $animalDateOfBirth)
//                ->where('first_name', $firstName)
//                ->where('middle_name', $middleName)
//                ->where('last_name', $lastName)
                ->top(1)
        );

        if (count($pets) == 1) {
            $this->idPet = $pets[0]->id;
            return true;
        }

        return false;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function getColorIdByName(string $colorName): int
    {
        $idOfColorsInComboManual = ComboManualName::fromRequestGetByPagedQuery(
            $this->getApiGateway(),
            (new Builder())
                ->where('name', 'pet_colors')
                ->top(1)
        )[0]->id;

        $colors = ComboManualItem::fromRequestGetByPagedQuery($this->getApiGateway(),
            (new Builder())
                ->where('combo_manual_id', $idOfColorsInComboManual)
                ->where('title', $colorName)
                ->top(1));

        return $colors[0]->id;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function checkAddingMedicalCardToTheProgram(): bool
    {
        if (!isset($this->idClient) || !isset($this->idPet)) {
            return false;
        }

        $medicalCards = Medcard::fromRequestGetByPagedQuery($this->getApiGateway(),
            (new Builder())
                ->where('client_id', $this->idClient)
                ->where('pet_id', $this->idPet)
                ->top(1)
        );

        if (count($medicalCards)) {
            $this->idMedicalCard = $medicalCards[0]->id;
            return true;
        }

        return false;
    }


    /**
     * @throws VetmanagerApiGatewayException
     */
    public function checkNoteTheComplaint(): bool
    {
        if (!isset($this->idMedicalCard)) {
            return false;
        }

        $medicalCards = Medcard::fromRequestGetById($this->getApiGateway(), $this->idMedicalCard);
        $complaint = $medicalCards->description;

        if (empty($complaint)) {
            return false;
        }

        return true;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function checkAnimalDiagnosis(int $idDiagnoseForPet): bool
    {

        $medicalCards = Medcard::fromRequestGetById($this->getApiGateway(), $this->idMedicalCard);
        $diagnose = $medicalCards->diagnose;
        //Выборка id диагноза из медкарт
        $diagnose = substr($diagnose, 1);
        $diagnose = substr($diagnose, 1, -2);
        $result = explode('},{', $diagnose);

        if (count($result) > 1) {
            //Много, так нельзя
            return false;
        }
        $strResult = $result[0];
        $arrResult = explode(',', $strResult);
        $idDiagnose = (int)substr($arrResult[0], 5);

        if (empty($idDiagnose) || $idDiagnose != $idDiagnoseForPet) {
            return false;
        }

        return true;
    }

    public function checkCreateInvoiceUsingCoupon(): bool
    {
//        $admissions = Client::fromRequestGetByQueryBuilder(
//            $this->getApiGateway(),
//            (new Builder ())
//                ->where('client_id', $idClient)
//                ->top(1)
//        );
//
//        if (count($admissions) == 0) {
//            return false;
//        }
//
//        return true;
    }

    public function checkRepeatAppointmentToTheClinic(): bool
    {
//        $admissions = Client::fromRequestGetByQueryBuilder(
//            $this->getApiGateway(),
//            (new Builder ())
//                ->where('client_id', $idClient)
//                ->top(1)
//        );
//
//        return $this->activateArrayResultStatus($admissions);
    }

//    public function activateArrayResultStatus(array $resultFunction): bool
//    {
//        if (count($resultFunction) == 0) {
//            return false;
//        }
//
//        return true;
//    }
//
//    public function checkAddingClientToTheProgram(): bool
//    {
//        $clients =
//        if (count($resultFunction) == 0) {
//            return false;
//        }
//
//        return true;
//    }
//
//    /**
//     * @throws VetmanagerApiGatewayException
//     */
//    public function activateArrayResultClients(
//        string $firstName,
//        string $middleName,
//        string $lastName,
//    ): bool
//    {
//        if (count($this->checkAddingClientToTheProgram($firstName, $middleName, $lastName)) == 0) {
//            return false;
//        }
//
//        return true;
//    }
}