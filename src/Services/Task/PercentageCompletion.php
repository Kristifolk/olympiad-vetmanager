<?php

declare(strict_types=1);

namespace App\Services\Task;

use App\Services\Data\DataForRedis;
use DateInterval;
use Otis22\VetmanagerRestApi\Query\Builder;
use VetmanagerApiGateway\ActiveRecord\Client\AbstractClient;
use VetmanagerApiGateway\ActiveRecord\Invoice\AbstractInvoice;
use VetmanagerApiGateway\ActiveRecord\MedicalCard\AbstractMedicalCard;
use VetmanagerApiGateway\ActiveRecord\Pet\AbstractPet;
use VetmanagerApiGateway\ApiGateway;
use VetmanagerApiGateway\DTO\Admission\StatusEnum;
use VetmanagerApiGateway\DTO\ComboManualName\NameEnum;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestUrlDomainException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseException;
use VetmanagerApiGateway\DTO\Good\GoodOnlyDto;

class PercentageCompletion
{
    private ApiGateway $apiGateway;
    private int $numberOfTasksCompleted = 0;
    private int $numberOfTasksFailed = 0;
    const ALLOWED_INTERVAL = 7;


    /**
     * @throws VetmanagerApiGatewayRequestUrlDomainException
     */
    public function __construct()
    {
        $this->apiGateway = ApiGateway::fromSubdomainAndApiKey(
            API_DOMAIN,
            API_KEY,
            true,
        );
    }


    /**
     * @throws VetmanagerApiGatewayResponseException
     * @throws VetmanagerApiGatewayException
     */
    public function checkCompletedTasksForUserInPercents(int $userId): float
    {
        $taskResultsForUser = $this->calculateTaskResultsForUser($userId);
        $this->processResults($taskResultsForUser);
        $allTaskCount = $this->numberOfTasksCompleted + $this->numberOfTasksFailed;
        return round((100 * $this->numberOfTasksCompleted / $allTaskCount), 2);
    }

    /**
     * @throws VetmanagerApiGatewayException
     * @throws VetmanagerApiGatewayResponseException
     * @throws \Exception
     */
    private function calculateTaskResultsForUser(int $userId): array
    {
        $redis = new DataForRedis();
        $userFullNameAsString = $redis->getDataFileForTaskByUser($userId, 'add_client:meaning');

        if (is_null($userFullNameAsString)) {
            throw new \Exception("Empty data for user: $userId");
        }

        $arrayClientName = explode(" ", $userFullNameAsString);
        $client = $this->getClientDaoByName($arrayClientName);

        $pet = $this->getPetDaoByAliasAndClient(
            $redis->getDataFileForTaskByUser($userId, 'alias:meaning'),
            $arrayClientName
        );
        $medicalCards = $this->getMedicalCards($pet);
        $medicalCard = $this->getMedicalCardPreferablyInitial($medicalCards);

//        $medicalCards = $this->getMedicalCardDaoByName($pet, "Первичный");

        $diagnoses = $this->getAnimalDiagnosisForMedicalCard($medicalCard);
        $invoice = $this->getInvoiceForClient($medicalCard);


        return [
            "add_client:done" => $this->checkClientIsAdded($client),

            /*ADD PET*/

            "alias:done" => $this->checkPetIsAdded($pet),
            "type:done" => $this->checkTypePetIsAdded($pet, "cat"),
            "gender:done" => $this->checkGenderPetIsAdded($pet, $redis->getDataFileForTaskByUser($_SESSION['userId'], 'gender:meaning')),
            "dateOfBirth:done" => $this->checkDateOfBirthPetIsAdded($pet, $_SESSION['TotalYearsEnglish']),
            "breed:done" => $this->checkBreedPetIsAdded($pet, $redis->getDataFileForTaskByUser($_SESSION['userId'], 'breed:meaning')),
            "color:done" => $this->checkColorPetIsAdded($pet, $redis->getDataFileForTaskByUser($_SESSION['userId'], 'color:meaning')),

            /*ADD MEDICARE*/

            "purpose_appointment:done" => $this->checkPurposeAppointmentIsAdded($medicalCard),
            "text_template:done" => $this->checkTextTemplateIsAdded($medicalCard),
            "result_appointment:done" => $this->checkResultAppointmentIsAdded($medicalCard, "Повторный прием"),
            "animal_diagnosis:done" => $this->checkAnimalDiagnosisIsAdded((array)$diagnoses, $redis->getDataFileForTaskByUser($userId, 'animal_diagnosis:meaning')),
            "type_animal_diagnosis:done" => $this->checkTypeAnimalDiagnosisIsAdded((array)$diagnoses, "Окончательные"),

            /*Creating Invoice*/

            //"appointment_invoice:done" => $this->checkInitialAppointmentForInvoice($invoice),//факт существования счета, для существования мин 1 любой товар в счете, даже товар. который не по заданию. А не в админке "Указали первичный прием"
            "initial_appointment:done" => $this->checkInitialGoodInvoice($invoice, "Первичный прием"),
            "skin_treatment:done" => $this->checkInitialGoodInvoice($invoice, "Обработка кожных покровов"),
            "chlorhexidine_solution:done" => $this->checkInitialGoodInvoice($invoice, "Хлоргексидина раствор"),
            "quantity_initial_appointment:done" => $this->checkQuantityGoodsInInvoice($invoice, "Первичный прием", 1),
            "quantity_skin_treatment:done" => $this->checkQuantityGoodsInInvoice($invoice,"Обработка кожных покровов", 1),
            "quantity_chlorhexidine_solution:done" => $this->checkQuantityGoodsInInvoice($invoice,"Хлоргексидина раствор",1),
            //"payment_type:done" => $this->checkInitialPaymentTypeForInvoice($invoice)
            "payment_made:done" => $this->checkInitialPaymentTypeForInvoice($invoice), //Оплата счёта произведена (наличные или безнал)
            "change_balance:done" => $this->checkBalanceWhenPayingInvoice($invoice),

            /*Coupon application*/

            "add_coupon:done" => $this->checkInitialCouponApplicationForInvoice($invoice),

            /*Repeat Appointment*/

            "add_repeat_appointment:done" => $this->checkRepeatAppointmentToTheClinic($client, $pet),
        ];
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function calculateResultsForUserAndStore(int $userId): void
    {
        $dataUser = (new DataForRedis())->getDataFileForTaskByArray($userId);
        $arrayResult = $this->calculateTaskResultsForUser($userId);

        foreach ($arrayResult as $key => $value) {
            if ($value) {
                $dataUser[$key] = "true";
            }
        }
        (new DataForRedis())->deleteKeyUser($userId);
        (new DataForRedis())->putNewDataFileForTaskArray($userId, $dataUser);
    }

    private function processResults(array $checkAddingClientToTheProgram): void
    {
        foreach ($checkAddingClientToTheProgram as $result) {
            if ($result) {
                $this->numberOfTasksCompleted++;
            } else {
                $this->numberOfTasksFailed++;
            }
        }
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function getClientDaoByName(array $arrayClientName): ?AbstractClient
    {
        $clients = $this->apiGateway->getClient()->getByPagedQuery(
            (new Builder ())
                ->where('last_name', $arrayClientName[0])
                ->where('first_name', $arrayClientName[1])
                ->where('middle_name', $arrayClientName[2])
                ->where('status', "ACTIVE")
                ->top(1)
        );
        return !empty($clients) ? $clients[0] : null;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function getPetDaoByAliasAndClient(string $aliasPet, array $arrayClientName): ?AbstractPet
    {
        $pets = $this->apiGateway->getPet()->getByPagedQuery(
            (new Builder())
                ->where('alias', $aliasPet)
                ->top(10)
        );

        if (empty($pets)) {
            return null;
        }

        foreach ($pets as $pet) {
            if (
                $pet->getOwner()->getLastName() == $arrayClientName[0]
                && $pet->getOwner()->getFirstName() == $arrayClientName[1]
                && $pet->getOwner()->getMiddleName() == $arrayClientName[2]
            ) {
                return $pet;
            }
        }

        return null;
    }

    /**
     * @param $medicalCards AbstractMedicalCard[]
     * @throws VetmanagerApiGatewayException
     */
    //функция вернет медкарту для первичного приема,но если не указана цель "Первичный", то вернет 1ю из списка медкарт.
    //В Задании на олимпиаду клиент приходит первый раз, поэтому предполагаем, что медкарта заведена 1 шт. Код не предусматривает логику с несколькими медкартами.
    private function getMedicalCardPreferablyInitial(array $medicalCards): ?AbstractMedicalCard
    {

        if (empty($medicalCards)) {
            return null;
        }

        $typeAdmissionIdForInitial = $this->getAdmissionTypeIdForInitial();

        foreach ($medicalCards as $medCard) {
            if ($medCard->getAdmissionTypeId() == $typeAdmissionIdForInitial) {
                return $medCard;
            }
        }

        return $medicalCards[0];
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function getMedicalCards(?AbstractPet $pet): ?array //?AbstractMedicalCard
    {
        if (is_null($pet)) {
            return [];
        }

        return $pet->getMedicalCards();
    }

    private function getAnimalDiagnosisForMedicalCard(?AbstractMedicalCard $medicalCard): ?array
    {
        if (is_null($medicalCard)) {
            return null;
        }

        $diagnoseJson = json_decode($medicalCard->getDiagnoseTypeText(), true);

        if (is_null($diagnoseJson)) {
            $typeTextDiagnose = $medicalCard->getDiagnoseTypeText();

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
    private function getInvoiceForClient(?AbstractMedicalCard $medicalCard): ?AbstractInvoice
    {
        if (is_null($medicalCard) || is_null($medicalCard->getInvoice())) {
            return null;
        }

        $invoiceId = $medicalCard->getInvoiceId();
//        $invoice = $this->apiGateway->getInvoice()->getById($invoiceId);
//        return $invoice;
        return $medicalCard->getInvoice();
//        return Invoice::getById($this->apiGateway, $medicalCard->invoiceId);
    }

    private function checkClientIsAdded(?AbstractClient $client): bool
    {
        return (bool)$client;
    }


    /*ADD PET*/

    private function checkPetIsAdded(?AbstractPet $pet): bool
    {
        return (bool)$pet;
    }

    private function checkTypePetIsAdded(?AbstractPet $pet, string $petTypeAsString): bool
    {
        if (is_null($pet)) {
            return false;
        }

        return $pet->getPetType()->getType() == $petTypeAsString;
    }

    private function checkGenderPetIsAdded(?AbstractPet $pet, string $gender): bool
    {
        if (is_null($pet)) {
            return false;
        }

        return $pet->getSexAsString() == $gender;
    }

    /**
     * @throws VetmanagerApiGatewayResponseException
     */
    private function checkDateOfBirthPetIsAdded(?AbstractPet $pet, string $animalDateLast): bool
    {
        if (is_null($pet)) {
            return false;
        }

        try {
            $dateOfBirthForPet = $pet->getBirthdayAsDateTime();
        } catch (\Exception $exception) {
            $dateOfBirthForPet = null;
        }

        if (is_null($dateOfBirthForPet)) {
            return false;
        }

        $expectedDateTime = date('Y-m-d', strtotime("-" . "$animalDateLast"));
        $dateOfBirthForPet = $dateOfBirthForPet->format('Y-m-d');

        if (strtotime($dateOfBirthForPet) - strtotime($expectedDateTime) <= self::ALLOWED_INTERVAL) {
            return true;
        }

        return false;
    }

    /**
     * @throws VetmanagerApiGatewayResponseException
     */
    private function checkBreedPetIsAdded(?AbstractPet $pet, string $breedPet): bool
    {
        if (is_null($pet)) {
            return false;
        }

        return $pet->getBreed()?->getTitle() == $breedPet;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function checkColorPetIsAdded(?AbstractPet $pet, string $animalColorTitle): bool
    {
        if (is_null($pet) || empty($pet->getColorId())) {
            return false;
        }

//        $comboManualNameId = DAO\ComboManualName::getIdByNameAsEnum($this->apiGateway, Name::PetColors);
        $comboManualNameId = $this->apiGateway->getComboManualName()->getIdByNameAsEnum(NameEnum::PetColors);

        $animalColor = $this->apiGateway->getComboManualItem()->getByQueryBuilder(
//        $animalColor = ComboManualItem::getByQueryBuilder(
//            $this->apiGateway,
            (new Builder())
                ->where('title', $animalColorTitle)
                ->where('combo_manual_id', (string)$comboManualNameId)
        );

        if ($animalColor[0]->getValue() == (string)$pet->getColorId()) {
            return true;
        }

        return false;
    }


    /*ADD MEDICARE*/

    /**
     * @throws VetmanagerApiGatewayResponseException
     * @throws VetmanagerApiGatewayException
     */
    private function checkPurposeAppointmentIsAdded(?AbstractMedicalCard $medicalCard): bool
    {
        if (is_null($medicalCard)) {
            return false;
        }

        if ($medicalCard->getAdmissionTypeId() === $this->getAdmissionTypeIdForInitial()) {
            return true;
        }

        return false;
    }


    private function checkTextTemplateIsAdded(?AbstractMedicalCard $medicalCard): bool
    {
        if (is_null($medicalCard)) {
            return false;
        }

        $healingProcessForMedCard = $medicalCard->getDescription();
        $healingProcess = "Назначения при кошачьем акне";

        $position = strripos($healingProcessForMedCard, $healingProcess);

        if (!empty($healingProcessForMedCard) && $position) {
            return true;
        }

        return false;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function checkResultAppointmentIsAdded(?AbstractMedicalCard $medicalCard, string $typeResultAdmissionTitle): bool
    {
        if (is_null($medicalCard)) {
            return false;
        }

//        $comboManualNameId = DAO\ComboManualName::getIdByNameAsEnum($this->apiGateway, Name::AdmissionResult);
        $comboManualNameId = $this->apiGateway->getComboManualName()->getIdByNameAsEnum(NameEnum::AdmissionResult);

//        $typeAdmission = ComboManualItem::getByQueryBuilder(
        $typeAdmission = $this->apiGateway->getComboManualItem()->getByQueryBuilder(
//            $this->apiGateway,
            (new Builder())
                ->where('title', $typeResultAdmissionTitle)
                ->where('combo_manual_id', (string)$comboManualNameId)
        );

        if ($medicalCard->getMeetResultId() == (int)$typeAdmission[0]->getValue()) {
            return true;
        }

        return false;
    }

    private function checkAnimalDiagnosisIsAdded(array $diagnoses, string $diagnoseTitleForPet): bool
    {
        if (empty($diagnoses)) {
            return false;
        }

        if (trim($diagnoses[0]) == $diagnoseTitleForPet) {
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

        if (trim($diagnoses[1]) == $nameTypeDiagnoseForPet) {
            return true;
        }

        return false;
    }

    /*Creating Invoice*/

//    private function checkInitialAppointmentForInvoice(?AbstractInvoice $invoice): bool
//    {
//        return (bool)$invoice;
//    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function checkInitialGoodInvoice(?AbstractInvoice $invoice, string $injectionTitle): bool
    {
        if (is_null($invoice)) {
            return false;
        }

        $goodsWithSameName = $this->apiGateway->getGood()->getByQueryBuilder(
            (new Builder())
                ->where('title', $injectionTitle),
            1
        );

        if (empty($goodsWithSameName)) {
            return false;
        }

        $invoiceDocuments = $invoice->getInvoiceDocuments();

        foreach ($invoiceDocuments as $invoiceDocument) {
            if ($invoiceDocument->getGoodId() == $goodsWithSameName[0]->getId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws VetmanagerApiGatewayResponseException
     * @throws VetmanagerApiGatewayException
     */
    private function checkQuantityGoodsInInvoice(?AbstractInvoice $invoice, string $goodTitleToFind, float $correctQuantity): bool
    {
        if (is_null($invoice)) {
            return false;
        }

        $goodsWithSameName = $this->apiGateway->getGood()->getByQueryBuilder(
            (new Builder())
                ->where('title', $goodTitleToFind),
            1
        );

        if (empty($goodsWithSameName)) {
            return false;
        }

        $goodIdToFind = $goodsWithSameName[0]->getId();
        $invoiceDocuments = $invoice->getInvoiceDocuments();

        foreach ($invoiceDocuments as $invoiceDocument) {
            if ($invoiceDocument->getGoodId() == $goodIdToFind) {
                return ($invoiceDocument->getQuantity() == $correctQuantity);
            }
        }

        return false;
    }

    /**
     * @throws VetmanagerApiGatewayResponseException
     */
    private function checkInitialPaymentTypeForInvoice(?AbstractInvoice $invoice): bool
    {
        if (is_null($invoice)) {
            return false;
        }

        if ($invoice->getStatusAsString() == "exec") {
            return true;
        }

        return false;
    }

    /**
     * @throws VetmanagerApiGatewayResponseException
     */
    private function checkBalanceWhenPayingInvoice(?AbstractInvoice $invoice): bool
    {
        if (is_null($invoice)) {
            return false;
        }

        if ($invoice->getClient()->getBalance() > 0) { // Баланс положительный
            return true;
        }

        return false;
    }

    /*Coupon application*/

    private function checkInitialCouponApplicationForInvoice(?AbstractInvoice $invoice): bool
    {
        if (is_null($invoice)) {
            return false;
        }

        $invoiceDocuments = $invoice->getInvoiceDocuments();

        if ($invoiceDocuments[0]->getDiscountCause() == "Скидка: 10%, Купон: Я профессионал") {
            return true;
        }

        return false;
    }

    /*Repeat Appointment*/

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function checkRepeatAppointmentToTheClinic(?AbstractClient $client, ?AbstractPet $pet): bool
    {
        if (is_null($client) || is_null($pet)) {
            return false;
        }

        $petAdmissions = $this->apiGateway->getAdmission()->getByQueryBuilder(
            (new Builder())
                ->where('client_id', (string)$client->getId())
                ->where('patient_id', (string)$pet->getId()),
            10
        );

        foreach ($petAdmissions as $admission) {
            $statusValue = $admission->getStatusAsEnum();
            $repeatAdmissionsTypeId = $admission->getTypeId();
            $currentAdmissionsDate = $admission->getCreateDateAsDateTime()->setTime(0, 0, 0);//дата без учета времени
            $repeatAdmissionsDate = $admission->getDateAsDateTime()->setTime(0, 0, 0);
            $repeatAdmissionsTime = $admission->getDateAsDateTime()->format('H:i:s.u'); //2024-04-11 14:00:00.000000
            $interval = date_diff($currentAdmissionsDate, $repeatAdmissionsDate);
            $daysDifference =$interval->format('%a');

            //TypeId == 4 тип обращения в календаре Повторный прием, $daysDifference == 7 через неделю, "14:00:00.000000" время повторного приема
            if (in_array($statusValue, [StatusEnum::Save, StatusEnum::NotApproved]) && $repeatAdmissionsTypeId == 4 && $daysDifference == 7 && $repeatAdmissionsTime == "14:00:00.000000") {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws VetmanagerApiGatewayResponseException
     * @throws VetmanagerApiGatewayException
     */
    private function getAdmissionTypeIdForInitial(): int
    {
        $comboManualNameId = $this->apiGateway->getComboManualName()->getIdByNameAsEnum(NameEnum::AdmissionType);
        $typeAdmissions = $this->apiGateway->getComboManualItem()->getByQueryBuilder(
            (new Builder())
                ->where('title', "Первичный")
                ->where('combo_manual_id', (string)$comboManualNameId)
        );
        return (int)$typeAdmissions[0]->getValue();
    }
}