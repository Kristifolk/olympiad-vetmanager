<?php

namespace App\Services;

use App\Controllers\AuthorizationController;

class Data
{
    /**
     * @throws \JsonException
     */
    public function getDataFromJsonFile(string $filePath): array
    {
        $fileContents = file_get_contents($filePath);

        if (!$fileContents) {
            return [];
        }

        $decodedJsonAsArray = json_decode($fileContents, true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($decodedJsonAsArray)) {
            return [];
        }

        return $decodedJsonAsArray;
    }

    /**
     * @throws \JsonException
     * @throws \Exception
     */
    private function getAvailableUserId(): int
    {
        $availableUsersData = $this->getDataFromJsonFile(USER_AVAILABLE_PATH);

        if (!isset($availableUsersData["id"])) {
            throw new \Exception('There is no key "id" in json-file: ' . USER_AVAILABLE_PATH);
        }

        $availableUsersIds = $availableUsersData["id"];

        if (empty($availableUsersIds)) {
            throw new \Exception('No more available users in file: ' . USER_AVAILABLE_PATH);
        }

        $idUser = array_shift($availableUsersData["id"]);
        file_put_contents(USER_AVAILABLE_PATH, json_encode($availableUsersData, JSON_UNESCAPED_UNICODE));
        return $idUser;
    }

    /**
     * @throws \JsonException
     */
    public function getIdAndLoginAndPasswordOfParticipant(array $participantData): array
    {

        $idUser = $this->getAvailableUserId();

        $arrayLoginAndPassword = $this->getDataFromJsonFile(USER_DATA_PATH);
        $dataUser = $arrayLoginAndPassword[(string)$idUser];
        $this->putDefaultDataFileForTaskUser($idUser, $dataUser, $participantData);
        $dataUser['userId'] = $idUser;
        return $dataUser;
    }

    /**
     * @throws \JsonException
     */
    private function putDefaultDataFileForTaskUser(int $userId, array $userLoginAndPassword, array $participantData): void
    {
        $defaultTaskData = $this->getDataFromJsonFile(TASK_DEFAULT_DATA);
        $arrayToInsert = [(string)$userId => [$participantData, $userLoginAndPassword, $defaultTaskData]];

        $existingUsersWithTasks = $this->getDataFromJsonFile(USER_TASKS_PATH);

        $existingUsersWithTasks[$userId] = $arrayToInsert[$userId];
        file_put_contents(USER_TASKS_PATH, json_encode($existingUsersWithTasks, JSON_UNESCAPED_UNICODE));
    }

    /**
     * @throws \JsonException
     */
    public function putNewDataFileForTask(array $taskData, array $loginAndPassword, array $practicianData, int $userId): void
    {
        $userTasksData = $this->getDataFromJsonFile(USER_TASKS_PATH);
        $userTasksData[$userId] = [$practicianData, $loginAndPassword, $taskData];
        file_put_contents(USER_TASKS_PATH, json_encode($userTasksData, JSON_UNESCAPED_UNICODE));
    }

    /**
     * @throws \JsonException
     */
    public function getDataForUserId(int $userId): mixed
    {
        $arrayDataAllUsers = $this->getDataFromJsonFile(USER_TASKS_PATH);
        return $arrayDataAllUsers[$userId];
    }
}