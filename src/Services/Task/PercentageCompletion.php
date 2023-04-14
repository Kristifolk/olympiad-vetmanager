<?php declare(strict_types=1);

namespace App\Services\Task;

use App\Services\Data;
use Otis22\VetmanagerRestApi\Query\Builder;
use VetmanagerApiGateway\ApiGateway;
use VetmanagerApiGateway\DO\DTO\DAO;
use VetmanagerApiGateway\DO\DTO\DAO\Client;
use VetmanagerApiGateway\DO\DTO\DAO\ComboManualItem;
use VetmanagerApiGateway\DO\DTO\DAO\Invoice;
use VetmanagerApiGateway\DO\DTO\DAO\MedicalCard;
use VetmanagerApiGateway\DO\DTO\DAO\Pet;
use VetmanagerApiGateway\DO\Enum\ComboManualName\Name;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;

class PercentageCompletion
{
    private ApiGateway $apiGateway;
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

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function checkCompletedTasksForUserInPercents(): float
    {
        $this->calculateResults($this->calculateCompletedTaskItem());
        return $this->calculatePercentageResults();
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function calculateCompletedTaskItem(): array
    {
        $_SESSION['Diagnose'] = "Аллергический дерматит";
        $client = $this->getClientDaoByName(
            $_SESSION['NameClient'],
            $_SESSION['PatronymicClient'],
            $_SESSION['SurnameClient']
        );

        $pet = $this->getPetDaoByAliasAndClient($_SESSION['AnimalName'],
            $_SESSION['NameClient'],
            $_SESSION['PatronymicClient'],
            $_SESSION['SurnameClient']);

        $medicalCard = $this->getMedicalCardDaoByName($pet, "Первичный");

        $diagnoses = $this->getAnimalDiagnosisForMedicalCard($medicalCard);
        $invoice = $this->getInvoiceForClient($medicalCard);
        return [
            "add_client" => $this->checkClientIsAdded($client),

            /*ADD PET*/

            "alias" => $this->checkPetIsAdded($pet),
            "type" => $this->checkTypePetIsAdded($pet, "dog"),
            "gender" => $this->checkGenderPetIsAdded($pet, "mail"),
            "dateOfBirth" => $this->checkDateOfBirthPetIsAdded($pet, "124585"),/**/
            "breed" => $this->checkBreedPetIsAdded($pet, "корело-финская лайка"),
            "color" => $this->checkColorPetIsAdded($pet, $_SESSION['AnimalColor']),

            /*ADD MEDICARE*/

            "purpose_appointment" => $this->checkPurposeAppointmentIsAdded($medicalCard, "Первичный"),
            "text_template" => $this->checkTextTemplateIsAdded($medicalCard),
            "result_appointment" => $this->checkResultAppointmentIsAdded($medicalCard, "Повторный прием"),
            "animal_diagnosis" => $this->checkAnimalDiagnosisIsAdded((array)$diagnoses, (string)$_SESSION['Diagnose']),/**/
            "type_animal_diagnosis" => $this->checkTypeAnimalDiagnosisIsAdded((array)$diagnoses, "Окончательные"),

            /*Creating Invoice*/

            "appointment_invoice" => $this->checkInitialAppointmentForInvoice($invoice),
            "opening_of_abscess" => $this->checkInitialGoodInvoice($invoice, "БАК (общий)"),
            "sanitation_of_wound" => $this->checkInitialGoodInvoice($invoice, "Анализ мочи (единица)"),
            "injection_analgesic_antipyretic" => $this->checkInitialGoodInvoice($invoice, "БАК (Печеночный)"),
            "injection_antibiotic" => $this->checkInitialGoodInvoice($invoice, "Байтрил (мл)"),
            "payment_type" => $this->checkInitialPaymentTypeForInvoice($invoice),

            /*Coupon application*/

            "add_coupon" => $this->checkInitialCouponApplicationForInvoice($invoice),

            /*Repeat Appointment*/

            "add_repeat_appointment" => $this->checkRepeatAppointmentToTheClinic($client, $pet),
        ];
    }

    /**
     * @throws \JsonException
     * @throws VetmanagerApiGatewayException
     */
    public function storePercentageCompletionIntoFile(): void
    {
        $userId = (int)$_SESSION["UserId"];
        $dataUser = (new Data())->getDataForUserId($userId);
        $arrayResult = $this->calculateCompletedTaskItem();

        $practicianData = $dataUser[0];
        $loginAndPassword = $dataUser[1];
        $taskArray = $dataUser[2];

        foreach ($arrayResult as $key => $value) {
            if ($value) {
                $taskArray[$key]["done"] = "true";
            }
        }

        (new Data())->putNewDataFileForTask($taskArray, $loginAndPassword, $practicianData, $userId);
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
        $allTask = $this->numberOfTasksCompleted + $this->numberOfTasksFailed;
        return round((100 * $this->numberOfTasksCompleted / $allTask), 2);
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
    private function getMedicalCardDaoByName(?Pet $pet, string $typeAdmissionTitle): ?MedicalCard
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

        $diagnoseJson = json_decode($medicalCard->diagnose, true);

        if (is_null($diagnoseJson)) {
            $typeTextDiagnose = $medicalCard->diagnoseTypeText;

            $arrStrTextDiagnose = explode("(", $typeTextDiagnose);
            $type = substr($arrStrTextDiagnose[1], 0, -1);
            $res[] = $arrStrTextDiagnose[0];
            $res[] = $type;
            return $res;
        }

        return $diagnoseJson;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function getInvoiceForClient(?MedicalCard $medicalCard): ?Invoice
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

        return $animalColor == $colorAsComboManualItem->title;
    }


    /*ADD MEDICARE*/

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

        return $medicalCard->meetResultId == (int)$typeAdmission[0]->value;
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

        if (!empty($healingProcessForMedCard) && $position) {
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

        if ($diagnoses[0] == $diagnoseTitleForPet) {
            return true;
        }

//        foreach ($diagnoses as $diagnosis) {
//            if ($diagnosis["id"] == "118") {
//                return true;
//            }
//        }

        return false;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function checkTypeAnimalDiagnosisIsAdded(array $diagnoses, string $nameTypeDiagnoseForPet): bool
    {
        if (empty($diagnoses)) {
            return false;
        }

        if ($diagnoses[0] == $nameTypeDiagnoseForPet) {
            return true;
        }
//        $comboManualNameId = DAO\ComboManualName::getIdByNameAsEnum($this->apiGateway, Name::DiagnoseTypes);
//        $typeDiagnoses = ComboManualItem::getByQueryBuilder(
//            $this->apiGateway,
//            (new Builder())
//                ->where('title', $nameTypeDiagnoseForPet)
//                ->where('combo_manual_id', (string)$comboManualNameId)
//        );
//
//        foreach ($diagnoses as $diagnosis) {
//            if ($diagnosis["type"] === $typeDiagnoses[0]->value) {
//                return true;
//            }
//        }

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
    private function checkInitialGoodInvoice(?Invoice $invoice, string $injectionTitle): bool
    {
        if (is_null($invoice)) {
            return false;
        }

        $invoiceDocuments = $invoice->invoiceDocuments;

        $dataInjection = DAO\Good::getByQueryBuilder(
            $this->apiGateway,
            (new Builder())
                ->where('title', "БАК (общий)"),
            1
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

        $invoiceDocuments = $invoice->invoiceDocuments;

        if ($invoiceDocuments[0]->discountCause = "cupon name") {
            return true;
        }

        if ($invoice->discount == 10.0) {
            return true;
        }

        return false;
    }

    /*Repeat Appointment*/

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function checkRepeatAppointmentToTheClinic(?Client $client, ?Pet $pet): bool
    {
        if (is_null($client) || is_null($pet)) {
            return false;
        }

        $repeatAdmission = DAO\AdmissionFromGetAll::getByQueryBuilder(
            $this->apiGateway,
            (new Builder())
                ->where('client_id', (string)$client->id)
                // ->where(['pet']['id'], $pet->id)
                ->where('admission_date', '2023-04-22 15:00:00'),
            1
        );

        return (bool)$repeatAdmission;
    }
}