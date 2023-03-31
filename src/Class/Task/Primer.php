<?php

namespace App\Class\Task;

use VetmanagerApiGateway\DAO\ComboManualItem;
use VetmanagerApiGateway\DAO\Client;
use VetmanagerApiGateway\ApiGateway;
use Otis22\VetmanagerRestApi\Query\Builder;

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


}