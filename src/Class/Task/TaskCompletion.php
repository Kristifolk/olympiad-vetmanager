<?php

namespace App\Class\Task;
use VetmanagerApiGateway\ApiGateway;
use VetmanagerApiGateway\DAO\Client;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseEmptyException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseException;

session_start();

class TaskCompletion
{
    public function __construct(
        public int $idTask,
    )
    {
    }

    /**
     * @throws VetmanagerApiGatewayRequestException
     */
    public function getApiGateway(): ApiGateway
    {
        return ApiGateway::fromDomainAndApiKey(
            'devmel',
            '31af0669fd1bcd6d145410795a6ef4f7',
            true,
        );
    }

    /**
     * @throws VetmanagerApiGatewayException
     * @throws VetmanagerApiGatewayRequestException
     * @throws VetmanagerApiGatewayResponseException
     * @throws VetmanagerApiGatewayResponseEmptyException
     */
    public function checkInitialAppointment(): ?\VetmanagerApiGateway\DTO\PetType
    {
        $client = Client::fromRequestGetById($this->getApiGateway(), 1);
        return $client->;
    }

    public function checkAddingClientToTheProgram()
    {
    }

    public function checkAddingPetToTheProgram()
    {
    }

    public function checkAddingMedicalCardToTheProgram()
    {
    }

    public function checkNoteTheComplaint()
    {
    }

    public function checkAnimalDiagnosis()
    {
    }

    public function checkCreateInvoiceUsingCoupon()
    {
    }

    public function checkRepeatAppointmentToTheClinic()
    {
    }
}