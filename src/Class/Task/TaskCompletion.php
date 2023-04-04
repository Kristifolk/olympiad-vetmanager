<?php

namespace App\Class\Task;


use Otis22\VetmanagerRestApi\Query\Builder;
use VetmanagerApiGateway\ApiGateway;
use VetmanagerApiGateway\DTO\DAO\Client;
use VetmanagerApiGateway\DTO\DAO\ComboManualItem;
use VetmanagerApiGateway\DTO\DAO\ComboManualName;
use VetmanagerApiGateway\DTO\DAO\Invoice;
use VetmanagerApiGateway\DTO\DAO\Medcard;
use VetmanagerApiGateway\DTO\DAO\Pet;
use VetmanagerApiGateway\DTO\Enum\ComboManualName\Name;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseEmptyException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseException;


class TaskCompletion
{
    private int $idClient;
    private int $idPet;
    private int $idMedicalCard;

    public function __construct(
        readonly string $domainName = 'devmel',
        readonly string $apiKey = '31af0669fd1bcd6d145410795a6ef4f7'
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
//    public function checkInitialAdmission(string $date)
//    {
//        if (!isset($idMedicalCard)) {
//            return false;
//        }
//        create_date
//        $medicalCard = Medcard::fromRequestGetById($this->getApiGateway(), $idMedicalCard);
//        return true;
//    }

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
        $comboManualIdForColors = $this->getColorIdByName($animalColor);
        //$comboManualIdForColors = ComboManualName::getIdFromNameAsEnum($this->getApiGateway(), Name::PetColors);

        $pets = Pet::fromRequestGetByPagedQuery(
            $this->getApiGateway(),
            (new Builder())
                ->where('alias', $aliasPet)
                // ->where('color_id', (string)$comboManualIdForColors)
//                ->where('birthday', $animalDateOfBirth)
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
    private function getColorIdByName(string $colorName): string
    {
        $idOfColorsInComboManual = 8;
//        $idOfColorsInComboManual = ComboManualName::fromRequestGetByPagedQuery(
//            $this->getApiGateway(),
//            (new Builder())
//                ->where('name', 'pet_colors')
//                ->top(1)
//        )[0]->id;

        $colors = ComboManualItem::fromRequestGetByPagedQuery(
            $this->getApiGateway(),
            (new Builder())
                ->where('combo_manual_id', (string)$idOfColorsInComboManual)
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
                ->where('client_id', (string)$this->idClient)
                ->where('pet_id', (string)$this->idPet)
                ->top(1)
        );

        if (count($medicalCards) == 1) {
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
    public function checkAnimalDiagnosis(string $nameDiagnoseForPet): bool
    {
        if (!isset($this->idMedicalCard)) {
            return false;
        }

        $idDiagnoseFromName = $this->getDiagnosisIdByName($nameDiagnoseForPet);

        if (empty($idDiagnoseFromName)) {
            return false;
        }

        $medicalCards = Medcard::fromRequestGetById($this->getApiGateway(), (string)$this->idMedicalCard);


        $diagnoseStr = $medicalCards->diagnose;
        $diagnoseStr = substr($diagnoseStr, 1);
        $diagnoseStr = substr($diagnoseStr, 1, -2);

        $arrayIdAndType = explode('},{', $diagnoseStr);

        $arrayIdDiagnose = [];

        for ($i = 0; $i < count($arrayIdAndType); $i++) {
            $str1 = $arrayIdAndType[$i];
            $arrayNameAndValue = explode(',', $str1);

            $arrayIdDiagnose[] = substr($arrayNameAndValue[0], 5);
        }

        if (!in_array($idDiagnoseFromName, $arrayIdDiagnose, true)) {
            return false;
        }

        return true;
    }

    public function getDiagnosisIdByName(string $nameDiagnoseForPet): string
    {
        return '118';
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function checkCreateInvoiceUsingCoupon(): bool
    {
        if (!isset($this->idClient) || !isset($this->idPet)) {
            return false;
        }

        $invoices = Invoice::fromRequestGetByPagedQuery($this->getApiGateway(),
            (new Builder())
                ->where('client_id', (string)$this->idClient)
                ->where('pet_id', (string)$this->idPet)
                ->where('amount', "1100.0000000000")
                ->top(1)
        );

        if (count($invoices) == 0) {
            return false;
        }

        return true;
    }

//    public function checkRepeatAppointmentToTheClinic(): bool
//    {
////        $admissions = Client::fromRequestGetByQueryBuilder(
////            $this->getApiGateway(),
////            (new Builder ())
////                ->where('client_id', $idClient)
////                ->top(1)
////        );
////
////        return $this->activateArrayResultStatus($admissions);
//    }
}