<?php /** @noinspection PhpSameParameterValueInspection */
declare(strict_types=1);

namespace App\Services\Task;

use App\Services\Data\DataForRedis;
use DateInterval;
use JsonException;
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
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseEmptyException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseException;

class PercentageCompletion
{
    private ApiGateway $apiGateway;
    private int $numberOfTasksCompleted = 0;
    private int $numberOfTasksFailed = 0;

    /** @throws VetmanagerApiGatewayRequestException */
    public function __construct(
    )
    {
        $this->apiGateway = ApiGateway::fromDomainAndApiKey(
            API_DOMAIN,
            API_KEY,
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
        $client = $this->getClientDaoByName(
            (string)$_SESSION['NameClient'],
            (string)$_SESSION['PatronymicClient'],
            (string)$_SESSION['SurnameClient']
        );

        $pet = $this->getPetDaoByAliasAndClient(
            (string)$_SESSION['AnimalName'],
            (string)$_SESSION['NameClient'],
            (string)$_SESSION['PatronymicClient'],
            (string)$_SESSION['SurnameClient']
        );

        $medicalCard = $this->getMedicalCardDaoByName($pet, "Первичный");

        $diagnoses = $this->getAnimalDiagnosisForMedicalCard($medicalCard);
        $invoice = $this->getInvoiceForClient($medicalCard);
        return [
            "add_client" => $this->checkClientIsAdded($client),

            /*ADD PET*/

            "alias:done" => $this->checkPetIsAdded($pet),
            "type:done" => $this->checkTypePetIsAdded($pet, "dog"),
            "gender:done" => $this->checkGenderPetIsAdded($pet, $_SESSION['AnimalGender']),
            "dateOfBirth:done" => $this->checkDateOfBirthPetIsAdded($pet, $_SESSION['TotalYearsEnglish']),
            "breed:done" => $this->checkBreedPetIsAdded($pet, $_SESSION['Breed']['title']),
            "color:done" => $this->checkColorPetIsAdded($pet, $_SESSION['AnimalColor']),

            /*ADD MEDICARE*/

            "purpose_appointment:done" => $this->checkPurposeAppointmentIsAdded($medicalCard),
            "text_template:done" => $this->checkTextTemplateIsAdded($medicalCard),
            "result_appointment:done" => $this->checkResultAppointmentIsAdded($medicalCard, "Повторный прием"),
            "animal_diagnosis:done" => $this->checkAnimalDiagnosisIsAdded((array)$diagnoses, "Абсцесс "),
            "type_animal_diagnosis:done" => $this->checkTypeAnimalDiagnosisIsAdded((array)$diagnoses, "Окончательные"),

            /*Creating Invoice*/

            "appointment_invoice:done" => $this->checkInitialAppointmentForInvoice($invoice),
            "opening_of_abscess:done" => $this->checkInitialGoodInvoice($invoice, "Вскрытие абсцесса"),
            "sanitation_of_wound:done" => $this->checkInitialGoodInvoice($invoice, "Санация раны"),
            "injection_analgesic_antipyretic:done" => $this->checkInitialGoodInvoice($invoice, "Обезболивающий жаропонижающий"),
            "injection_antibiotic:done" => $this->checkInitialGoodInvoice($invoice, "Антибиотик"),
            "payment_type:done" => $this->checkInitialPaymentTypeForInvoice($invoice),

            /*Coupon application*/

            "add_coupon:done" => $this->checkInitialCouponApplicationForInvoice($invoice),

            /*Repeat Appointment*/

            "add_repeat_appointment:done" => $this->checkRepeatAppointmentToTheClinic($client, $pet),
        ];
    }

    /**
     * @throws JsonException
     * @throws VetmanagerApiGatewayException
     */
//    public function storePercentageCompletionIntoFile(): void
//    {
//        $userId = (int)$_SESSION["UserId"];
//        $dataUser = (new DataForRedis())->getDataForUserId($userId);
//        $arrayResult = $this->calculateCompletedTaskItem();
//
//        $practicianData = $dataUser[0];
//        $loginAndPassword = $dataUser[1];
//        $taskArray = $dataUser[2];
//
//        foreach ($arrayResult as $key => $value) {
//            if ($value) {
//                $taskArray[$key]["done"] = "true";
//            }
//        }
//
//        (new DataForRedis())->putNewDataFileForTask($taskArray, $loginAndPassword, $practicianData, $userId); #TODO
//    }

    public function storePercentageCompletionIntoRedis(): void
    {
        $userId = (int)$_SESSION["userId"];
        $dataUser = (new DataForRedis())->getDataFileForTaskByArray($userId);
        $arrayResult = $this->calculateCompletedTaskItem();

        foreach ($arrayResult as $key => $value) {
            if ($value) {
                $dataUser[$key] = "true";
            }
        }
        (new DataForRedis())->deleteKeyUser($userId);
        (new DataForRedis())->putNewDataFileForTaskArray($userId, $dataUser);
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

    /**
     * @throws VetmanagerApiGatewayException
     */
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

    /**
     * @throws VetmanagerApiGatewayException
     * @throws VetmanagerApiGatewayRequestException
     * @throws VetmanagerApiGatewayResponseException
     * @throws VetmanagerApiGatewayResponseEmptyException
     */
    private function getPetDaoByAliasAndClient(string $aliasPet, string $firstName, string $middleName, string $lastName): ?Pet
    {
        $pets = Pet::getByPagedQuery(
            $this->apiGateway,
            (new Builder())
                ->where('alias', $aliasPet)
                ->top(10)
        );

        if (empty($pets)) {
            return null;
        }

        foreach ($pets as $pet) {

            if ($pet->client->firstName == $firstName &&
                $pet->client->middleName == $middleName &&
                $pet->client->lastName == $lastName) {
                return $pet;
            }
        }

        return null;
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

    private function getAnimalDiagnosisForMedicalCard(?MedicalCard $medicalCard): ?array
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
        if (is_null($medicalCard) || is_null($medicalCard->invoice)) {
            return null;
        }

        return Invoice::getById($this->apiGateway, $medicalCard->invoiceId);
    }

    private function checkClientIsAdded(?Client $client): bool
    {
        return (bool)$client;
    }


    /*ADD PET*/

    private function checkPetIsAdded(?Pet $pet): bool
    {
        return (bool)$pet;
    }

    private function checkTypePetIsAdded(?Pet $pet, string $petTypeAsString): bool
    {
        if (is_null($pet)) {
            return false;
        }

        return $pet->type->type == $petTypeAsString;
    }

    private function checkGenderPetIsAdded(?Pet $pet, string $gender): bool
    {
        if (is_null($pet)) {
            return false;
        }

        return $pet->sex->value == $gender;
    }

    private function checkDateOfBirthPetIsAdded(?Pet $pet, string $animalDateLast): bool
    {
        if (is_null($pet)) {
            return false;
        }

        $dateOfBirthForPet = $pet->birthday;

        if (empty($dateOfBirthForPet)) {
            return false;
        }

        $allowedInterval = new DateInterval('P7D');
        $expectedDateTime = date('Y-m-d', strtotime("-" . "$animalDateLast"));

        if ($dateOfBirthForPet->format('Y-m-d') - $expectedDateTime <= $allowedInterval) {
            return true;
        }

        return false;
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
    private function checkColorPetIsAdded(?Pet $pet, string $animalColorTitle): bool
    {
        if (is_null($pet) || empty($pet->colorId)) {
            return false;
        }

        $comboManualNameId = DAO\ComboManualName::getIdByNameAsEnum($this->apiGateway, Name::PetColors);

        $animalColor = ComboManualItem::getByQueryBuilder(
            $this->apiGateway,
            (new Builder())
                ->where('title', $animalColorTitle)
                ->where('combo_manual_id', (string)$comboManualNameId)
        );

        if ($animalColor[0]->value == (string)$pet->colorId) {
            return true;
        }

        return false;
    }


    /*ADD MEDICARE*/

    private function checkPurposeAppointmentIsAdded(?MedicalCard $medicalCard): bool
    {
        if (is_null($medicalCard)) {
            return false;
        }

        return (bool)$medicalCard;
    }

    /**
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

        return false;
    }

    /**
     */
    private function checkTypeAnimalDiagnosisIsAdded(array $diagnoses, string $nameTypeDiagnoseForPet): bool
    {
        if (empty($diagnoses)) {
            return false;
        }

        if ($diagnoses[1] == $nameTypeDiagnoseForPet) {
            return true;
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
    private function checkInitialGoodInvoice(?Invoice $invoice, string $injectionTitle): bool
    {
        if (is_null($invoice)) {
            return false;
        }

        $invoiceDocuments = $invoice->invoiceDocuments;

        $dataInjection = DAO\Good::getByQueryBuilder(
            $this->apiGateway,
            (new Builder())
                ->where('title', $injectionTitle),
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

        if ($invoice->status->value == "exec") {
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

        if ($invoiceDocuments[0]->discountCause == "Скидка: 10%, Купон: Я профессионал") {
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

        $repeatAdmissions = DAO\AdmissionFromGetAll::getByQueryBuilder(
            $this->apiGateway,
            (new Builder())
                ->where('client_id', (string)$client->id)
                ->where('patient_id', (string)$pet->id),
            10
        );

        foreach ($repeatAdmissions as $admission) {
            $statusValue = $admission->status->value;
            if ($statusValue == "save" || $statusValue == "not_approved") {
                return true;
            }
        }

        return false;
    }
}