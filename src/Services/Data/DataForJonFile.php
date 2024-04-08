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
        file_put_contents(USER_AVAILABLE_PATH, json_encode($availableUsersData, JSON_UNESCAPED_UNICODE));//перезапись файла availableUsers.json после того, как извлекли очередной id
        return $idUser;
    }


    /**
     * @throws JsonException
     */
    public function getLoginAndPasswordAndTemplateForUserId(int $userId): array
    {
        // Парсит файл и возвращает массив со всеми логинами и паролями
        $arrayLoginAndPassword = $this->getDataFromJsonFile(USER_ACCOUNT_PATH); // [..."5" => ["login" => "admin4", "password" => "EmXMVswUg2"], ...]
        return $arrayLoginAndPassword[(string)$userId]; // ["login" => "admin4", "password" => "EmXMVswUg2"]
    }

    /** @throws JsonException */
    public function getTemplateTask(): array
    {
        return $this->getDataFromJsonFile(TASK_TEMPLATE_DATA);
    }
}