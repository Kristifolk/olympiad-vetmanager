<?php

namespace App\Services\Data;

use Predis\Client;

class DataForRedis
{
    private $predis;

    public function __construct()
    {
        $this->predis = new Client(
            [
                'scheme' => 'tcp',
                "host" => HOST_REDIS,
                "port" => PORT_REDIS
            ]
        );
    }

    public function getDataAllUsers()
    {
        return $this->predis->get('user_data');
    }

    public function getDataForUserId(int $userId): mixed
    {
        $users = $this->predis->get('user_data');
        return $users[(string)$userId];
    }

    public function putNewDataFileForTask(array $userTasksData): void
    {
        $this->predis->humset('user_data', $userTasksData);
    }
}