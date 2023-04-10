<?php declare(strict_types=1);

namespace App\Class;

use Otis22\VetmanagerRestApi\Query\Builder;
use VetmanagerApiGateway\ApiGateway;
use VetmanagerApiGateway\DO\DTO\ComboManualName;
use VetmanagerApiGateway\DO\DTO\DAO;
use VetmanagerApiGateway\DO\DTO\DAO\MedicalCardsByClient;
use VetmanagerApiGateway\DO\DTO\DAO\Client;
use VetmanagerApiGateway\DO\DTO\DAO\ComboManualItem;
use VetmanagerApiGateway\DO\DTO\DAO\Invoice;
use VetmanagerApiGateway\DO\DTO\DAO\MedicalCard;
use VetmanagerApiGateway\DO\DTO\DAO\Pet;
use VetmanagerApiGateway\DO\Enum\ComboManualName\Name;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseEmptyException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseException;

class PercentageCompletion
{
    private ApiGateway $apiGateway;
    private array $medicalCardIds;
    private int $numberOfTasksCompleted = 0;
    private int $numberOfTasksFailed = 0;

    /** @throws VetmanagerApiGatewayRequestException */
    public function __construct(
        string $domainName = 'devmel',
        string $apiKey = '31af0669fd1bcd6d145410795a6ef4f7'
    )
    {
        $this->apiGateway = ApiGateway::fromDomainAndApiKey(
            $domainName,
            $apiKey,
            true,
        );
    }

    /** @throws VetmanagerApiGatewayException */
    public function checkCompletedTasksForUserInPercents(): float
    {

        $client = $this->getClientDaoByName(
            $_SESSION['NameClient'],
            $_SESSION['PatronymicClient'],
            $_SESSION['SurnameClient']
        );

        $pet = $this->getPetDaoByAliasAndClient($_SESSION['AnimalName'],
            $_SESSION['NameClient'],
            $_SESSION['PatronymicClient'],
            $_SESSION['SurnameClient']);

        $medcard = $this->getMedicalCardDaoByName($pet);
//        $medcardsByPet = $pet->medicalCards;

        $diagnoses = $this->getAnimalDiagnosisForMedicalCard($medcard);


        $this->calculateResults(
            [
                $this->checkClientIsAdded($client),

                /*ADD PET*/

                $this->checkPetIsAdded($pet),
                $this->checkTypePetIsAdded($pet, "dog"),
                $this->checkGenderPetIsAdded($pet, "124585"),
                $this->checkDateOfBirthPetIsAdded($pet, "124585"),/**/
                $this->checkBreedPetIsAdded($pet, "корело-финская лайка"),
                $this->checkColorPetIsAdded($pet, $_SESSION['AnimalColor']),

                /*ADD MEDICARE*/

                $this->getIdMedicalCardIsAdded($medcard),
                //$this->checkPurposeAppointmentIsAdded($medcards, "Первичный"),/**/
                //$this->checkTextTemplateIsAdded($medcard, "Абсцесс"),
                //$this->checkTextTemplate("Больно где-то"),
                $this->checkResultAppointmentIsAdded($medcard, "Повторный прием"),/**/
                $this->checkAnimalDiagnosisIsAdded((array)$diagnoses, (string)$_SESSION['Diagnose']),
                $this->checkTypeAnimalDiagnosisIsAdded((array)$diagnoses, "Окончательные"),/**/

                /*Creating Invoice*/

                $this->checkInitialAppointmentForInvoice($_SESSION['Diagnose']),
                $this->checkInitialOpeningOfAbscessForInvoice($_SESSION['Diagnose']),
                $this->checkInitialSanitationOfTheWoundForInvoice($_SESSION['Diagnose']),
                $this->checkInitialInjectionInvoice("тип иньекции"),
                $this->checkInitialInjectionInvoice("тип иньекции"),
                $this->checkInitialPaymentTypeForInvoice($_SESSION['Diagnose']),

                /*Coupon application*/

                $this->checkInitialCouponApplicationForInvoice($_SESSION['Diagnose']),

                /*Repeat Appointment*/

                $this->checkRepeatAppointmentToTheClinic(),
                $this->checkCreateInvoiceUsingCoupon()
            ]
        );
//         $_SESSION["ResultPercentage"] = $this->numberOfTasksCompleted . "%";
//        return $_SESSION["ResultPercentage"];
        return $this->calculatePercentageResults();
    }

    private function calculateResults(array $checkAddingClientToTheProgram): void
    {
        foreach ($checkAddingClientToTheProgram as $result) {
            if ($result) {
                $this->numberOfTasksCompleted++;
            } else {
                $this->numberOfTasksFailed++;
            }
        }
    }

    private function calculatePercentageResults(): float
    {
        return round($this->numberOfTasksCompleted / ($this->numberOfTasksCompleted + $this->numberOfTasksFailed), 2);
    }

    private function getClientDaoByName(string $firstName, string $middleName, string $lastName): ?Client
    {
        $clients = Client::getByPagedQuery(
            $this->apiGateway,
            (new Builder ())
                ->where('first_name', $firstName)
                ->where('middle_name', $middleName)
                ->where('last_name', $lastName)
                ->top(1)
        );

        return !empty($clients) ? $clients[0] : null;
    }

    private function getPetDaoByAliasAndClient(string $aliasPet, string $firstName, string $middleName, string $lastName): ?Pet
    {
        $pets = Pet::getByPagedQuery(
            $this->apiGateway,
            (new Builder())
                ->where('alias', $aliasPet)
                ->top(1)
        );

        if (empty($pets) ||
            $pets[0]->client->firstName != $firstName ||
            $pets[0]->client->middleName != $middleName ||
            $pets[0]->client->lastName != $lastName) {
            return null;
        }

        return $pets[0];
    }

    private function getAnimalDiagnosisForMedicalCard(?MedicalCard $medicalCard): mixed
    {
        if (is_null($medicalCard)) {
            return null;
        }

        return json_decode($medicalCard->diagnose, true);
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function checkClientIsAdded(?Client $client): bool
    {
        return (bool)$client;
    }


    /*ADD PET*/

    private function checkPetIsAdded(?Pet $pet): bool
    {
        return (bool)$pet;
    }

    private function checkTypePetIsAdded(?Pet $pet, string $typePet): bool
    {
        if (is_null($pet)) {
            return false;
        }

        return $pet->type->type == $typePet;
    }

    private function checkGenderPetIsAdded(?Pet $pet, string $gender): bool
    {
        if (is_null($pet) || $pet->sex != $gender) {
            return true;
        }

        return false;
    }

    private function checkDateOfBirthPetIsAdded(?Pet $pet, string $dateOfBirth,): bool
    {
        if (is_null($pet)) {
            return false;
        }

        return $pet->birthday == $dateOfBirth;
    }

    private function checkBreedPetIsAdded(?Pet $pet, string $breedPet): bool
    {
        if (is_null($pet) || $pet->breed->title != $breedPet) {
            return false;
        }

        return true;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function checkColorPetIsAdded(?Pet $pet, string $animalColor): bool
    {
        if (is_null($pet)) {
            return false;
        }

        $colorAsComboManualItem = ComboManualItem::getByPetColorId($this->apiGateway, $pet->colorId);

        if ($animalColor != $colorAsComboManualItem->title) {
            return false;
        }

        return true;
    }


    /*ADD MEDICARE*/

    public function getIdMedicalCardIsAdded(?MedicalCard $medicalCard): bool
    {
        return (bool)$medicalCard;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function getMedicalCardDaoByName(?Pet $pet, string $typeAdmissionTitle = 'Первичный'): ?MedicalCard
    {
        if (is_null($pet)) {
            return null;
        }

        $comboManualNameId = DAO\ComboManualName::getIdByNameAsEnum($this->apiGateway, Name::AdmissionType);

        $typeAdmission = ComboManualItem::getByQueryBuilder(
            $this->apiGateway,
            (new Builder())
                ->where('title', $typeAdmissionTitle)
                ->where('combo_manual_id', (string)$comboManualNameId)
        );

        foreach ($pet->medicalCards as $medCard) {
            if ($medCard->admissionTypeId == $typeAdmission[0]->value) {
                return $medCard;
            }
        }

        return null;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */

    private function checkTextTemplateIsAdded(?MedicalCard $medicalCard, string $complaintByTask): bool
    {
        if (is_null($medicalCard)) {
            return false;
        }

        $medicalCard = MedicalCard::getById($this->apiGateway, $this->idOfMedicalCard);
        $complaint = $medicalCard->description;

        if (!empty($complaint) && $complaint == $complaintByTask) {
            return true;
        }

        return false;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function checkResultAppointmentIsAdded(?MedicalCard $medicalCard, string $typeResultAdmissionTitle): bool
    {
        if (is_null($medicalCard)) {
            return false;
        }

        $comboManualNameId = DAO\ComboManualName::getIdByNameAsEnum($this->apiGateway, Name::AdmissionResult);

        $typeAdmission = ComboManualItem::getByQueryBuilder(
            $this->apiGateway,
            (new Builder())
                ->where('title', $typeResultAdmissionTitle)
                ->where('combo_manual_id', (string)$comboManualNameId)
        );

        if ($medicalCard->meetResultId == $typeAdmission[0]->value) {
            return true;
        }

        return false;
    }

    private function checkAnimalDiagnosisIsAdded(array $diagnoses, string $nameDiagnoseForPet): bool
    {
        if (empty($diagnoses)) {
            return false;
        }


        foreach ($diagnoses as $diagnosis) {
            if ($diagnosis["id"] == "118") {
                return true;
            }
        }

        return false;
    }

    private function checkTypeAnimalDiagnosisIsAdded(array $diagnoses, string $nameTypeDiagnoseForPet): bool
    {
        if (empty($diagnoses)) {
            return false;
        }

        $comboManualNameId = DAO\ComboManualName::getIdByNameAsEnum($this->apiGateway, Name::DiagnoseTypes);
        $typeDiagnoses = ComboManualItem::getByPagedQuery(
            $this->apiGateway,
            (new Builder())
                ->where('title', $nameTypeDiagnoseForPet)
                ->where('combo_manual_id', (string)$comboManualNameId)
                ->top(1)
        );

        foreach ($diagnoses as $diagnosis) {
            if ($diagnosis["type"] === $typeDiagnoses[0]->value) {
                return true;
            }
        }

        return false;
    }

    /*Creating Invoice*/


    private function checkInitialAppointmentForInvoice(mixed $Diagnose): bool
    {
        return false;
    }

    private function checkInitialOpeningOfAbscessForInvoice(mixed $Diagnose): bool
    {
        return false;
    }

    private function checkInitialSanitationOfTheWoundForInvoice(mixed $Diagnose): bool
    {
        return false;
    }

    private function checkInitialInjectionInvoice(string $typeInjection): bool
    {
        return false;
    }

    private function checkInitialPaymentTypeForInvoice(mixed $Diagnose): bool
    {
        return false;
    }

    /*Coupon application*/

    private function checkInitialCouponApplicationForInvoice(mixed $Diagnose): bool
    {
        return false;
    }

    /*Repeat Appointment*/

    private function checkRepeatAppointmentToTheClinic(): bool
    {
        return false;
    }

    private function checkCreateInvoiceUsingCoupon(): bool
    {
        if (!isset($this->idOfClient) || !isset($this->idOfPet)) {
            return false;
        }

        $invoices = Invoice::getByPagedQuery($this->apiGateway,
            (new Builder())
                ->where('client_id', (string)$this->idOfClient)
                ->where('pet_id', (string)$this->idOfPet)
                ->where('amount', "1100.0000000000")
                ->top(1)
        );

        if (count($invoices) == 0) {
            return false;
        }

        return true;
    }

//    private function checkRepeatAppointmentToTheClinic(): bool
//    {
//        $admissions = Client::fromRequestGetByQueryBuilder(
//            $this->apiGateway,
//            (new Builder ())
//                ->where('client_id', $idClient)
//                ->top(1)
//        );
//
//        return $this->activateArrayResultStatus($admissions);
//    }
}