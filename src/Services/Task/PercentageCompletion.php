<?php declare(strict_types=1);

namespace App\Services\Task;

use App\Services\Data;
use DateInterval;
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
        string $domainName = 'deviproff',
        string $apiKey = 'd7d4e868c36d0961c6b1d90a5797e00b'
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
            (string)$_SESSION['NameClient'],
            (string)$_SESSION['PatronymicClient'],
            (string)$_SESSION['SurnameClient']
        );

        $pet = $this->getPetDaoByAliasAndClient((string)$_SESSION['AnimalName'],
            (string)$_SESSION['NameClient'],
            (string)$_SESSION['PatronymicClient'],
            (string)$_SESSION['SurnameClient']);

        $medicalCard = $this->getMedicalCardDaoByName($pet, "Первичный");

        $diagnoses = $this->getAnimalDiagnosisForMedicalCard($medicalCard);
        $invoice = $this->getInvoiceForClient($medicalCard);
        return [
            "add_client" => $this->checkClientIsAdded($client),

            /*ADD PET*/

            "alias" => $this->checkPetIsAdded($pet),
            "type" => $this->checkTypePetIsAdded($pet, "dog"),
            "gender" => $this->checkGenderPetIsAdded($pet, $_SESSION['AnimalGender']),
            "dateOfBirth" => $this->checkDateOfBirthPetIsAdded($pet, $_SESSION['TotalYearsEnglish']),
            "breed" => $this->checkBreedPetIsAdded($pet, $_SESSION['Breed']['title']),
            "color" => $this->checkColorPetIsAdded($pet, $_SESSION['AnimalColor']),

            /*ADD MEDICARE*/

            "purpose_appointment" => $this->checkPurposeAppointmentIsAdded($medicalCard),
            "text_template" => $this->checkTextTemplateIsAdded($medicalCard),
            "result_appointment" => $this->checkResultAppointmentIsAdded($medicalCard, "Повторный прием"),
            "animal_diagnosis" => $this->checkAnimalDiagnosisIsAdded((array)$diagnoses, "Абсцесс "),
            "type_animal_diagnosis" => $this->checkTypeAnimalDiagnosisIsAdded((array)$diagnoses, "Окончательные"),

            /*Creating Invoice*/

            "appointment_invoice" => $this->checkInitialAppointmentForInvoice($invoice),
            "opening_of_abscess" => $this->checkInitialGoodInvoice($invoice, "Вскрытие абсцесса"),
            "sanitation_of_wound" => $this->checkInitialGoodInvoice($invoice, "Санация раны"),
            "injection_analgesic_antipyretic" => $this->checkInitialGoodInvoice($invoice, "Обезболивающий жаропонижающий"),
            "injection_antibiotic" => $this->checkInitialGoodInvoice($invoice, "Антибиотик"),
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
                ->top(10)
        );

        if (empty($pets))
        {
            return null;
        }

        foreach ($pets as $pet){

            if ($pet->client->firstName == $firstName &&
                $pet->client->middleName == $middleName &&
                $pet->client->lastName == $lastName)
            {
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

        return $pet->sex->value == $gender;
    }

    private function checkDateOfBirthPetIsAdded(?Pet $pet, string $animalDateLast): bool
    {
        if (is_null($pet)) {
            return false;
        }

        $dateOfBirthForPet = $pet->birthday;

        if (is_null($dateOfBirthForPet)) {
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
    private function checkColorPetIsAdded(?Pet $pet, string $animalColor): bool
    {
        if (is_null($pet) || is_null($pet->colorId)) {
            return false;
        }

        $colorAsComboManualItem = ComboManualItem::getByPetColorId($this->apiGateway, $pet->colorId);

        return $animalColor == $colorAsComboManualItem->title;
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
            if($admission->status->value == "save") {
                return true;
            }
        }

        return false;
    }
}