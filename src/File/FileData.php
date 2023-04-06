<?php

namespace App\File;

class FileData
{
    private string $userTxt = "userCollection.json";

    public function getDataInToFile(): array
    {
        $ourData = file_get_contents("userCollection.json");
        if ($ourData) {
            // Преобразуем в объект
            $object = json_decode($ourData, 1);
            // выводим объект
            return $object;
        }
        return [];
    }

    public function putDataInToFile(string $userLogin, string $userPassword): void
    {
        $jsonArray[] = ["login" => $userLogin, "password" => $userPassword];
        file_put_contents('userCollection.json', json_encode($jsonArray, JSON_FORCE_OBJECT));
    }

    public function checkLoginUserInToFile(string $userLogin): bool
    {
        $arrayData = $this->getDataInToFile();

        if (count($arrayData) == 0) {
            return false;
        }

        for ($i = 0; $i < count($arrayData); $i++) {
            if ($arrayData["login"] == $userLogin){
                return true;
            }
        }

        return false;
    }
}