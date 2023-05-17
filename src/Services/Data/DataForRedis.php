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

    public function getDataAllUsers(): array
    {
        $keys = $this->predis->keys('*');
        $data = [];

        foreach ($keys as $key) {
            $data[] = $this->predis->hgetall("$key");
        }

        return $data;
    }

    public function getDataFileForTaskByUser(int $userId, string $userData): ?string
    {
        return $this->predis->hget('user:' . $userId, $userData);
    }

    public function getDataFileForTaskByArray(int $userId): array
    {
        return $this->predis->hgetall('user:' . $userId);
    }

    public function putNewDataFileForTask(int $userId, string $userData, $value): void
    {
        $this->predis->hset('user:' . $userId, $userData, $value);
    }

    /**  @throws JsonException */
    public function putNewDataFileForTaskArray(int $userId, array $userData): void
    {
        foreach ($userData as $key => $value) {
            $this->putNewDataFileForTask($userId, $key, $value);
        }
    }

    public function deleteKeyUser(int $userId): void
    {
        $this->predis->del('user:' . $userId);
    }
}