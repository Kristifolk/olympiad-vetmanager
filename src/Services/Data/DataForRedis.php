<?php

namespace App\Services\Data;


use Exception;

class DataForRedis
{
    private $predis;

    public function __construct()
    {
        $this->predis = new Predis\Client(
            [
                'host' => API_DOMAIN . '.vetmanager2.ru',
                'scheme' => 'tls',
                'alias' => 'primary',
            ]
        );
    }

    public function getDataAllUsers()
    {
        return $this->predis->get('user_data');
    }

    /**
     * @throws Exception
     */
    private function getAvailableUserId(): int
    {
        $availableUsersId = $this->predis->lpop('available_users_data');

        if (!isset($availableUsersData["id"])) {
            throw new Exception('There is no key "id"');
        }

        return $availableUsersId;
    }


    /**
     * @throws Exception
     */
    public function getIdAndLoginAndPasswordOfParticipant(array $participantData): array
    {
        $idUser = $this->getAvailableUserId();

        $arrayLoginAndPassword = $this->predis->get('user_login_and_password');
        $dataUser = $arrayLoginAndPassword[(string)$idUser];

        $this->predis->lpush($idUser, [$dataUser, $participantData]);
        $dataUser['userId'] = $idUser;
        return $dataUser;
    }

    private function putDefaultDataFileForTaskUser(int $userId, array $userLoginAndPassword, array $participantData): void
    {
        $defaultTaskData = $this->predis->get('default_data');
        $generateData = $this->generateDataForTask();

        foreach ($generateData as $key => $value) {
            $defaultTaskData[$key]["meaning"] = $value;
        }

        $this->predis->lpush('user_data', [(string)$userId => [$participantData, $userLoginAndPassword, $defaultTaskData, $generateData]]);
    }

    public function putNewDataFileForTask(array $taskData, array $loginAndPassword, array $practicianData, int $userId): void
    {
        $userTasksData = $this->predis->get('user_data');
        $userTasksData[$userId] = [$practicianData, $loginAndPassword, $taskData];
        $this->predis->humset($userTasksData);
    }

    public function getDataForUserId(int $userId): mixed
    {
        return $this->predis->get('user_data', (string)$userId);
    }

    private function generateDataForTask(): array
    {
        return [
            "add_client" => $_SESSION['FullNameClient'],
            "alias" => $_SESSION['AnimalName'],
            "gender" => $_SESSION['AnimalGender'],
            "dateOfBirth" => $_SESSION['DateOfBirth'],
            "breed" => $_SESSION['Breed']['title'],
            "color" => $_SESSION['AnimalColor']
        ];
    }
}