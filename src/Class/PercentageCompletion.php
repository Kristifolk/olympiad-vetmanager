<?php declare(strict_types=1);

namespace App\Class;

use Otis22\VetmanagerRestApi\Query\Builder;
use VetmanagerApiGateway\ApiGateway;
use VetmanagerApiGateway\DTO\DAO\Client;
use VetmanagerApiGateway\DTO\DAO\ComboManualItem;
use VetmanagerApiGateway\DTO\DAO\Invoice;
use VetmanagerApiGateway\DTO\DAO\MedicalCard;
use VetmanagerApiGateway\DTO\DAO\MedicalCardsByClient;
use VetmanagerApiGateway\DTO\DAO\Pet;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;

class PercentageCompletion
{
    private ApiGateway $apiGateway;

    private int $idPet;
    private int $idClient;
    private int $idMedicalCard;

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
        $this->calculateResults(
            [
                $this->checkAddingClientToTheProgram(
                    $_SESSION['NameClient'],
                    $_SESSION['PatronymicClient'],
                    $_SESSION['SurnameClient']
                ),

                /*ADD PET*/

                $this->checkAddingPetToTheProgram(
                    $_SESSION['AnimalName'],
                    $_SESSION['NameClient'],
                    $_SESSION['PatronymicClient'],
                    $_SESSION['SurnameClient'],
                ),
                $this->checkTypePetToTheProgram("dog"),
                $this->checkGenderPetToTheProgram("124585"),
                $this->checkDateOfBirthPetToTheProgram("124585"),
                $this->checkBreedPetToTheProgram("корело-финская лайка"),
                $this->checkColorPetToTheProgram($_SESSION['AnimalColor']),

                /*ADD MEDICARE*/


                $this->getIdAddingMedicalCardToTheProgram(),
                $this->checkPurposeAppointment("Первичный приём"),
                $this->checkTextTemplate("Абсцесс"),
                //$this->checkTextTemplate("Больно где-то"),
                $this->checkResultApointment("Вторичный приём"),
                $this->checkAnimalDiagnosis((string)$_SESSION['Diagnose']),
                $this->checkTypeAnimalDiagnosis((string)$_SESSION['Diagnose']),

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

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function checkAddingClientToTheProgram(
        string $firstName,
        string $middleName,
        string $lastName,
    ): bool
    {
        $clients = Client::getByPagedQuery(
            $this->apiGateway,
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


    /*ADD PET*/
    private function checkAddingPetToTheProgram(
        string $aliasPet,
        string $firstName,
        string $middleName,
        string $lastName,
    ): bool
    {
        $pets = Pet::getByPagedQuery(
            $this->apiGateway,
            (new Builder())
                ->where('alias', $aliasPet)
                ->top(1)
        );

        if (count($pets) != 1 ||
            $pets[0]->client->firstName != $firstName ||
            $pets[0]->client->middleName != $middleName ||
            $pets[0]->client->lastName != $lastName) {
            return false;
        }

        $this->idPet = $pets[0]->id;
        return true;
    }

    private function checkTypePetToTheProgram(string $typePet): bool
    {
        if (!isset($this->idPet)) {
            return false;
        }

        $pets = Pet::getById($this->apiGateway, $this->idPet);
        $pet = $pets[0];

        if ($pet->type->type == $typePet) {
            return true;
        }

        return false;
    }

    private function checkGenderPetToTheProgram(string $gender): bool
    {
        if (!isset($this->idPet)) {
            return false;
        }

        $pets = Pet::getById($this->apiGateway, $this->idPet);
        $pet = $pets[0];

        if ($pet->sex == $gender) {
            return true;
        }

        return false;
    }

    private function checkDateOfBirthPetToTheProgram(
        string $dateOfBirth,
    ): bool
    {
        if (!isset($this->idPet)) {
            return false;
        }

        $pets = Pet::getById($this->apiGateway, $this->idPet);
        $pet = $pets[0];

        if ($pet["birthday"] != $dateOfBirth) {
            return false;
        }

        return true;
    }

    private function checkBreedPetToTheProgram(
        string $breedPet,
    ): bool
    {
        if (!isset($this->idPet)) {
            return false;
        }

        $pets = Pet::getById($this->apiGateway, $this->idPet);

        if ($pets->breed->title != $breedPet) {
            return false;
        }

        return true;
    }

    private function checkColorPetToTheProgram(
        string $animalColor
    ): bool
    {
        if (!isset($this->idPet)) {
            return false;
        }

        $pets = Pet::getById($this->apiGateway, $this->idPet);
        $colorAsComboManualItem = ComboManualItem::getByPetColorId($this->apiGateway, $pets->colorId);

        if ($animalColor != $colorAsComboManualItem->title) {
            return false;
        }

        return true;
    }


    /*ADD MEDICARE*/

    public function getIdAddingMedicalCardToTheProgram(): bool
    {
        if (!isset($this->idClient) || !isset($this->idPet)) {
            return false;
        }

        $medicare = MedicalCardsByClient::getByClientId($this->apiGateway, $this->idClient);
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

    private function checkPurposeAppointment(): bool
    {
        return false;
    }

    private function checkTextTemplate(string $complaintByTask): bool
    {
        if (!isset($this->idMedicalCard)) {
            return false;
        }

        $medicalCards = MedicalCard::getById($this->apiGateway, $this->idMedicalCard);
        $complaint = $medicalCards->description;

        if (!empty($complaint) && $complaint == $complaintByTask) {
            return true;
        }

        return false;
    }

    private function checkResultApointment()
    {
        return false;
    }

    private function checkAnimalDiagnosis(string $nameDiagnoseForPet): bool
    {
        if (!isset($this->idMedicalCard)) {
            return false;
        }

        $medicalCards = MedicalCard::getById($this->apiGateway, $this->idMedicalCard);
        $textMedicalCardDiagnose = $medicalCards->diagnoseText;

        $arrayTextMedicalCardDiagnose = explode(';<br/>', $textMedicalCardDiagnose);

        if (!in_array($nameDiagnoseForPet, $arrayTextMedicalCardDiagnose)) {
            return false;
        }

        return true;
    }

    private function checkTypeAnimalDiagnosis(string $diagnoseTypeForPet): bool
    {
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
        if (!isset($this->idClient) || !isset($this->idPet)) {
            return false;
        }

        $invoices = Invoice::getByPagedQuery($this->apiGateway,
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