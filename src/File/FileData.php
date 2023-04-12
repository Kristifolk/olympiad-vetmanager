<?php

namespace App\File;


class FileData
{
    public int $countUsers = 0;

    /**
     * @throws \JsonException
     */
    public function getDataInToFile(string $fileName): mixed
    {
        $ourData = file_get_contents($fileName);

        if ($ourData) {
            // Преобразуем в объект
            $object = json_decode($ourData, true, 512, JSON_THROW_ON_ERROR);
            // выводим объект
            return $object;
        }
        return [];
    }

    private function getIdUserToParticipant(): int
    {
        $arrayDataUserAvailable = $this->getDataInToFile(USER_AVAILABLE_PATH);

        if (count($arrayDataUserAvailable["id"]) == 0) {
            //return "Логины всех пользователей заняты";
        }

        $idUser = $arrayDataUserAvailable["id"][$this->countUsers];

        if (empty($idUser)) {
            //return "Пользователь не смог получить свой логин";
        }

        array_shift($arrayDataUserAvailable["id"]);
        $this->putFileOverwrite($arrayDataUserAvailable);

        return $idUser;
    }

    public function getLoginAndPasswordToParticipant(): array
    {
        $idUser = $this->getIdUserToParticipant();
        $arrayLoginAndPassword = $this->getDataInToFile(USER_DATA_PATH);
        $dataUser = $arrayLoginAndPassword["$idUser"];
        $this->putDefaultDataFileForTaskUser(["$idUser" => $dataUser]);
        $dataUser[] = $idUser;
        return $dataUser;
    }

    private function putFileOverwrite(array $userData): void
    {
        file_put_contents(USER_AVAILABLE_PATH, json_encode($userData, true));
    }

    /**
     * @throws \JsonException
     */
    private function putDefaultDataFileForTaskUser(array $userData): void
    {
        $getData = $this->getDataInToFile(USER_TASKS_PATH);

        $putData = substr(json_encode($userData, true), 1);
        $putData = substr($putData, 0, -2);
        $taskData = substr(json_encode($this->getDataInToFile(TASK_DEFAULT_DATA), true), 1);

        $putData = $putData . "," . $taskData . "}";

        if (count($getData) !== 0) {
            $getData = substr(json_encode($getData, true), 0, -1);
            $putData = "," . $putData;
        } else {
            $getData = "{";
        }

        $fullData = $getData . $putData;
        file_put_contents(USER_TASKS_PATH, $fullData);
    }

    public function putNewDataFileForTask(array $newData, array $deleteDataUser, int $userId): void
    {
        $getData = $this->getDataInToFile(USER_TASKS_PATH);
        unset($getData[$userId]);
        $getData[$userId] = [$deleteDataUser, $newData];
        file_put_contents(USER_TASKS_PATH, json_encode($getData, true));
    }

    /**
     * @throws \JsonException
     */
    public function getDataForUserId(int $userId): mixed
    {
        $arrayDataAllUsers = $this->getDataInToFile(USER_TASKS_PATH);
        return $arrayDataAllUsers[$userId];
    }
}