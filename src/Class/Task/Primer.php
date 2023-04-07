<?php

namespace App\Class\Task;

use App\Class\Task\DAO\ComboManualItem;
use App\Class\Task\DAO\Client;
use App\Class\Task\ApiGateway;
use Otis22\VetmanagerRestApi\Query\Builder;
use VetmanagerApiGateway\DTO\Client;
use VetmanagerApiGateway\DTO\DAO\Client;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;

class Primer
{

    public function primer()
    {
        $apiGateway = ApiGateway::fromDomainAndApiKey(
            'devmel',
            'secretKey',
            true,
        );

        $client = Client::fromRequestGetById($apiGateway, 1);
        $clientPets = $client->petsAlive[0]->type->self->;
        $allClientMedcards = $client->medcards;

//        $comboManualItems = ComboManualItem::fromRequestByQueryBuilder(
//            $apiGateway,
//            (new Builder())
//                ->where('value', '7')
//                ->where('combo_manual_id', '1')
//                ->top(1)
//        );

        $comboManualItemTitle = $crmComboManualItems[0]->title;
    }

    private function checkVaccineForPets(string $nameVaccine): bool
    {
        // $vaccineAsComboManualItem = ComboManualItem::getByVaccineTypeId($this->apiGateway, $pets[0]->colorId);

        return false;
    }

    /**
     * @throws VetmanagerApiGatewayRequestException
     */
    private function checkAdmissionTypeForPets(): bool
    {
        $admissionTypeAsComboManualItem = \VetmanagerApiGateway\DTO\DAO\ComboManualItem::getByAdmissionTypeId($this->apiGateway, $admission[0]->id);

        return false;
    }

    private function checkTypeDiagnosisForPets(): bool
    {
        //$admissionAsComboManualItem = ComboManualItem::getOneByValueAndComboManualName($this->apiGateway, $pets[0]->colorId);

        return false;
    }

    /**
     * @throws VetmanagerApiGatewayRequestException
     */
    private function checkInitialAdmission(string $typeAdmission)
    {
        if (!isset($this->idMedicalCard)) {
            return false;
        }
        //create_date
        //$medicalCard = Medcard::fromRequestGetById($this->apiGateway, $this->idMedicalCard);
        //$admission = Admi

        $admissionTypeAsComboManualItem = ComboManualItem::getByAdmissionTypeId($this->apiGateway, $admission[0]->id);

        return true;
    }
}