<?php declare(strict_types=1);

namespace App\Class\Task;


use Otis22\VetmanagerRestApi\Query\Builder;
use VetmanagerApiGateway\ApiGateway;
use VetmanagerApiGateway\DTO\DAO\Client;
use VetmanagerApiGateway\DTO\DAO\ComboManualItem;
use VetmanagerApiGateway\DTO\DAO\Invoice;
use VetmanagerApiGateway\DTO\DAO\Medcard;
use VetmanagerApiGateway\DTO\DAO\MedicalCard;
use VetmanagerApiGateway\DTO\DAO\MedicalCardsByClient;
use VetmanagerApiGateway\DTO\DAO\Pet;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;


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
    public function getIdClientToTheProgram(
        string $firstName,
        string $middleName,
        string $lastName,
    ): bool
    {
        $clients = Client::getByPagedQuery(
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
        string $firstName,
        string $middleName,
        string $lastName,
    ): bool
    {
        $pets = Pet::getByPagedQuery(
            $this->getApiGateway(),
            (new Builder())
                ->where('alias', $aliasPet)
                ->top(1)
        );

        if (count($pets) != 1 ||
            $pets[0]->client->firstName != $firstName ||
            $pets[0]->client->middleName != $middleName ||
            $pets[0]->client->lastName != $lastName){
            return false;
        }

        $this->idPet = $pets[0]->id;
        return true;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function checkColorPetToTheProgram(
        string $animalColor
    ): bool
    {
        if (!isset($this->idPet)) {
            return false;
        }

        $pets = Pet::getById($this->getApiGateway(), $this->idPet);
        $colorAsComboManualItem = ComboManualItem::getByPetColorId($this->getApiGateway(), $pets->colorId);

        if ($animalColor != $colorAsComboManualItem->title) {
            return false;
        }

        return true;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function checkDateOfBirthPetToTheProgram(
        string $dateOfBirth,
    ): bool
    {
        if (!isset($this->idPet)) {
            return false;
        }

        $pets = Pet::getById($this->getApiGateway(), $this->idPet);
        $pet = $pets[0];

        if ($pet["birthday"] != $dateOfBirth) {
            return false;
        }

        return true;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function checkBreedPetToTheProgram(
        string $breedPet,
    ): bool
    {
        if (!isset($this->idPet)) {
            return false;
        }

        $pets = Pet::getById($this->getApiGateway(), $this->idPet);

        if ($pets->breed->title != $breedPet) {
            return false;
        }

        return true;
    }
    /**
     * @throws VetmanagerApiGatewayException
     */
    public function checkAddingMedicalCardToTheProgram(): bool
    {
        if (!isset($this->idClient) || !isset($this->idPet)) {
            return false;
        }

        $medicare = MedicalCardsByClient::getByClientId($this->getApiGateway(), $this->idClient);
        $medicalCardsByClient = $medicare['medicalcards'];

        if (count($medicalCardsByClient) >= 1)
            /** @var array $medicalCardsByClient */
            for ($i = 0; $i < count($medicalCardsByClient); $i++) {
                $idPets = $medicalCardsByClient[$i]['pet_id'];

                if ($idPets == $this->idPet) {
                    $this->idMedicalCard = (int)$medicalCardsByClient[$i]["medical_card_id"];
                    return true;
                }

            }

        return false;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function checkNoteTheComplaint(string $complaintByTask): bool
    {
        if (!isset($this->idMedicalCard)) {
            return false;
        }

        $medicalCards = MedicalCard::getById($this->getApiGateway(), $this->idMedicalCard);
        $complaint = $medicalCards->description;

        if (!empty($complaint) && $complaint == $complaintByTask) {
            return true;
        }

        return false;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function checkAnimalDiagnosis(string $nameDiagnoseForPet): bool
    {
        if (!isset($this->idMedicalCard)) {
            return false;
        }

        $medicalCards = MedicalCard::getById($this->getApiGateway(), $this->idMedicalCard);
        $textMedicalCardDiagnose = $medicalCards->diagnoseText;

        $arrayTextMedicalCardDiagnose = explode(';<br/>', $textMedicalCardDiagnose);

        if (!in_array($nameDiagnoseForPet, $arrayTextMedicalCardDiagnose)) {
            return false;
        }

        return true;
    }

    /**
     * @throws VetmanagerApiGatewayRequestException
     * @throws VetmanagerApiGatewayException
     */
    public function checkVaccineForPets(string $nameVaccine): bool
    {
        $vaccineAsComboManualItem = ComboManualItem::getByVaccineTypeId($this->getApiGateway(), $pets[0]->colorId);

        return false;
    }

    /**
     * @throws VetmanagerApiGatewayRequestException
     * @throws VetmanagerApiGatewayException
     */
    public function checkAdmissionTypeForPets(): bool
    {
        $admissionTypeAsComboManualItem = ComboManualItem::getByAdmissionTypeId($this->getApiGateway(), $admission[0]->id);

        return false;
    }
    public function checkTypeDiagnosisForPets(): bool
    {
        //$admissionAsComboManualItem = ComboManualItem::getOneByValueAndComboManualName($this->getApiGateway(), $pets[0]->colorId);

        return false;
    }

    /**
     * @throws VetmanagerApiGatewayRequestException
     */
    public function checkInitialAdmission(string $typeAdmission)
    {
        if (!isset($this->idMedicalCard)) {
            return false;
        }
        //create_date
        //$medicalCard = Medcard::fromRequestGetById($this->getApiGateway(), $this->idMedicalCard);
        //$admission = Admi

        $admissionTypeAsComboManualItem = ComboManualItem::getByAdmissionTypeId($this->getApiGateway(), $admission[0]->id);

        return true;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function checkCreateInvoiceUsingCoupon(): bool
    {
        if (!isset($this->idClient) || !isset($this->idPet)) {
            return false;
        }

        $invoices = Invoice::getByPagedQuery($this->getApiGateway(),
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
//        $admissions = Client::fromRequestGetByQueryBuilder(
//            $this->getApiGateway(),
//            (new Builder ())
//                ->where('client_id', $idClient)
//                ->top(1)
//        );
//
//        return $this->activateArrayResultStatus($admissions);
//    }


}