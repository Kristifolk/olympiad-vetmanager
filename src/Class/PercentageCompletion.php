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
use VetmanagerApiGateway\DO\Enum\Pet\Sex;
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

        $diagnoses = $this->getAnimalDiagnosisForMedicalCard($medcard);
        $invoice = $this->getInvoiceForClient($medcard);

        $this->calculateResults(
            [
                $this->checkClientIsAdded($client),

                /*ADD PET*/

                $this->checkPetIsAdded($pet),
                $this->checkTypePetIsAdded($pet, "dog"),
                $this->checkGenderPetIsAdded($pet, "mail"),
                $this->checkDateOfBirthPetIsAdded($pet, "124585"),/**/
                $this->checkBreedPetIsAdded($pet, "корело-финская лайка"),
                $this->checkColorPetIsAdded($pet, $_SESSION['AnimalColor']),

                /*ADD MEDICARE*/

                $this->getIdMedicalCardIsAdded($medcard),
                $this->checkPurposeAppointmentIsAdded($medcard, "Первичный"),
                $this->checkTextTemplateIsAdded($medcard),
                $this->checkResultAppointmentIsAdded($medcard, "Повторный прием"),
                $this->checkAnimalDiagnosisIsAdded((array)$diagnoses, (string)$_SESSION['Diagnose']),
                $this->checkTypeAnimalDiagnosisIsAdded((array)$diagnoses, "Окончательные"),

                /*Creating Invoice*/

                $this->checkInitialAppointmentForInvoice($invoice),
                $this->checkInitialInjectionInvoice($invoice, "Байтрил (мл)"),
                $this->checkInitialInjectionInvoice($invoice, "Байтрил (мл)"),
                $this->checkInitialInjectionInvoice($invoice, "Байтрил (мл)"),
                $this->checkInitialInjectionInvoice($invoice, "Байтрил (мл)"),
                $this->checkInitialPaymentTypeForInvoice($invoice),

                /*Coupon application*/

                $this->checkInitialCouponApplicationForInvoice($invoice),

                /*Repeat Appointment*/

                $this->checkRepeatAppointmentToTheClinic($client),
                //$this->checkCreateInvoiceUsingCoupon()
            ]
        );
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
        return round(($this->numberOfTasksCompleted + $this->numberOfTasksFailed) / $this->numberOfTasksCompleted, 2);
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

    private function getAnimalDiagnosisForMedicalCard(?MedicalCard $medicalCard): mixed
    {
        if (is_null($medicalCard)) {
            return null;
        }

        return json_decode($medicalCard->diagnose, true);
    }

    /**
     * @throws VetmanagerApiGatewayException
     * @throws VetmanagerApiGatewayRequestException
     * @throws VetmanagerApiGatewayResponseException
     * @throws VetmanagerApiGatewayResponseEmptyException
     */
    private function getInvoiceForClient(?MedicalCard $medicalCard): Invoice|null
    {
        if (is_null($medicalCard)) {
            return null;
        }

        if (is_null($medicalCard->invoice)) {
            return null;
        }

        $invoise = Invoice::getById($this->apiGateway, $medicalCard->invoiceId);
        return $invoise;
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
        if (is_null($pet)) {
            return false;
        }

        return $pet->sex == $gender;
    }

    private function checkDateOfBirthPetIsAdded(?Pet $pet, string $dateOfBirth): bool
    {
        if (is_null($pet)) {
            return false;
        }

        return $pet->birthday == $dateOfBirth;
    }

    private function checkBreedPetIsAdded(?Pet $pet, string $breedPet): bool
    {
        if (is_null($pet)) {
            return false;
        }

        return $pet->breed->title == $breedPet;
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

        if ($animalColor !== $colorAsComboManualItem->title) {
            return false;
        }

        return true;
    }


    /*ADD MEDICARE*/

    public function getIdMedicalCardIsAdded(?MedicalCard $medicalCard): bool
    {
        return (bool)$medicalCard;
    }

    private function checkPurposeAppointmentIsAdded(?MedicalCard $medicalCard, string $typeAdmissionTitle): bool
    {
        if (is_null($medicalCard)) {
            return false;
        }

        $comboManualNameId = DAO\ComboManualName::getIdByNameAsEnum($this->apiGateway, Name::AdmissionType);

        $typeAdmission = ComboManualItem::getByQueryBuilder(
            $this->apiGateway,
            (new Builder())
                ->where('title', $typeAdmissionTitle)
                ->where('combo_manual_id', (string)$comboManualNameId)
        );

        if ($medicalCard->meetResultId == (int)$typeAdmission[0]->value) {
            return true;
        }

        return false;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function checkTextTemplateIsAdded(?MedicalCard $medicalCard): bool
    {
        if (is_null($medicalCard)) {
            return false;
        }

        $healingProcessForMedCard = $medicalCard->description;
        $healingProcess = "Назначения при абсцессе";

        $position = strripos($healingProcessForMedCard, $healingProcess);

        if (!empty($healingProcessForMedCard) && (bool)$position) {
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

        if ($medicalCard->meetResultId == (int)$typeAdmission[0]->value) {
            return true;
        }

        return false;
    }

    private function checkAnimalDiagnosisIsAdded(array $diagnoses, string $diagnoseTitleForPet): bool
    {
        if (empty($diagnoses)) {
            return false;
        }

        // $diagnosisData = MedicalCard::getAll()

        //$diagnosisData =
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
        $typeDiagnoses = ComboManualItem::getByQueryBuilder(
            $this->apiGateway,
            (new Builder())
                ->where('title', $nameTypeDiagnoseForPet)
                ->where('combo_manual_id', (string)$comboManualNameId)
        );

        foreach ($diagnoses as $diagnosis) {
            if ($diagnosis["type"] === $typeDiagnoses[0]->value) {
                return true;
            }
        }

        return false;
    }

    /*Creating Invoice*/

    private function checkInitialAppointmentForInvoice(?Invoice $invoice): bool
    {
        return (bool)$invoice;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function checkInitialInjectionInvoice(?Invoice $invoice, string $injectionTitle): bool
    {
        if (is_null($invoice)) {
            return false;
        }

        $invoiceDocuments = $invoice->invoiceDocuments;

        $dataInjection = DAO\Good::getByQueryBuilder(
            $this->apiGateway,
            (new Builder())
                ->where('title', $injectionTitle)
        );

        if (empty($dataInjection)) {
            return false;
        }

        foreach ($invoiceDocuments as $invoiceDocument) {
            if ($invoiceDocument->goodId == $dataInjection[0]->id) {
                return true;
            }
        }

        return false;
    }

    private function checkInitialPaymentTypeForInvoice(?Invoice $invoice): bool
    {
        if (is_null($invoice)) {
            return false;
        }

        if ($invoice->status == "exec") {
            return true;
        }

        return false;
    }

    /*Coupon application*/

    private function checkInitialCouponApplicationForInvoice(?Invoice $invoice): bool
    {
        if (is_null($invoice)) {
            return false;
        }

        if ($invoice->discount == 10.0) {
            return true;
        }

        return false;
    }

    /*Repeat Appointment*/

    private function checkRepeatAppointmentToTheClinic(?Client $client): bool
    {
//        $repeatAdmission = DAO\AdmissionFromGetAll::q
        return false;
    }

//    private function checkCreateInvoiceUsingCoupon(): bool
//    {
//        if (!isset($this->idOfClient) || !isset($this->idOfPet)) {
//            return false;
//        }
//
//        $invoices = Invoice::getByPagedQuery($this->apiGateway,
//            (new Builder())
//                ->where('client_id', (string)$this->idOfClient)
//                ->where('pet_id', (string)$this->idOfPet)
//                ->where('amount', "1100.0000000000")
//                ->top(1)
//        );
//
//        if (count($invoices) == 0) {
//            return false;
//        }
//
//        return true;
//    }
}