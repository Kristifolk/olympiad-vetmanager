<?php

namespace App\Services\Data;

use Exception;
use JsonException;

class DataForJonFile
{
    /**
     * @throws JsonException
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
     * @throws JsonException
     * @throws Exception
     */
    public function getAvailableUserId(): int
    {
        $availableUsersData = $this->getDataFromJsonFile(USER_AVAILABLE_PATH);

        if (!isset($availableUsersData["id"])) {
            throw new Exception('There is no key "id" in json-file: ' . USER_AVAILABLE_PATH);
        }

        $availableUsersIds = $availableUsersData["id"];

        if (empty($availableUsersIds)) {
            throw new Exception('No more available users in file: ' . USER_AVAILABLE_PATH);
        }

        $idUser = array_shift($availableUsersData["id"]);
        file_put_contents(USER_AVAILABLE_PATH, json_encode($availableUsersData, JSON_UNESCAPED_UNICODE));
        return $idUser;
    }

    /**
     * @throws JsonException
     */
    public function defaultDataUser(array $fullNameParticipant): array
    {
        $idUser = $this->getAvailableUserId();

        $arrayLoginAndPassword = $this->getDataFromJsonFile(USER_ACCOUNT_PATH);
        $loginAndPassword = $arrayLoginAndPassword[(string)$idUser];

        return $this->putDefaultDataTaskUser($idUser, $loginAndPassword, $fullNameParticipant);
    }

    /**
     * @throws JsonException
     */
    private function putDefaultDataTaskUser(int $userId, array $loginAndPassword, array $fullNameParticipant): array
    {
        $defaultTaskData = $this->getDataFromJsonFile(TASK_TEMPLATE_DATA);
        $generateData = $this->generateDataForTask();

        foreach ($generateData as $key => $value) {
            $defaultTaskData[$key]["meaning"] = $value;
        }

        $arrayToInsert = [(string)$userId => [$fullNameParticipant, $loginAndPassword, $defaultTaskData, $generateData]];

        return $arrayToInsert;
        //$existingUsersWithTasks = $this->getDataFromJsonFile(USER_DATA_PATH);

        //$existingUsersWithTasks[$userId] = $arrayToInsert[$userId];
        //file_put_contents(USER_DATA_PATH, json_encode($existingUsersWithTasks, JSON_UNESCAPED_UNICODE));
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

///**
// * @throws JsonException
// */
//public function putNewDataFileForTask(array $taskData, array $loginAndPassword, array $practicianData, int $userId): void
//{
//    $userTasksData = $this->getDataFromJsonFile(USER_DATA_PATH);
//    $userTasksData[$userId] = [$practicianData, $loginAndPassword, $taskData];
//    file_put_contents(USER_DATA_PATH, json_encode($userTasksData, JSON_UNESCAPED_UNICODE));
//}
//
///**
// * @throws JsonException
// */
//public function getDataForUserId(int $userId): mixed
//{
//    $arrayDataAllUsers = $this->getDataFromJsonFile(USER_DATA_PATH);
//    return $arrayDataAllUsers[$userId];
//}