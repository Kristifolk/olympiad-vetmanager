<?php

namespace App\Services\Data;

use JsonException;
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

    /**
     * @throws JsonException
     */
    public function getDataAllUsers(): array
    {
        $data = $this->predis->get('user_data');
        $d = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        return $d;
    }

    /**
     * @throws JsonException
     */
    public function getDataForUserId(int $userId): mixed
    {
        $data = $this->predis->hget('user:' . $userId, 'login');
        return $data;//json_decode($data, true, 512, JSON_THROW_ON_ERROR);
    }

    public function putNewDataFileForTask(int $userId, string $userData, $value): void
    {
        $this->predis->hset('user:' . $userId, $userData, $value);
    }

    public function getDataFileForTaskByUser(int $userId, string $userData): ?string
    {
        return $this->predis->hget('user:' . $userId, $userData);
    }

    public function putKeyForData(string $key, string $data)
    {

    }
}